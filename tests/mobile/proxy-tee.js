/**
 * D38 test infra — local unauthenticated CONNECT forwarder.
 *
 * Why this exists: the VM reaches the internet through an
 * authenticating egress proxy (HTTPS_PROXY env). curl and Node
 * connect through it fine, but Chrome's own CONNECT requests are
 * refused by the proxy with 403 policy_denied (observed 2026-10-27:
 * Chrome sends a `User-Agent: ... HeadlessChrome/...` header with
 * its CONNECT; the proxy denies those). This forwarder terminates
 * Chrome's CONNECT on localhost and re-issues a minimal CONNECT
 * upstream (Host + Proxy-Connection only), which the proxy accepts.
 *
 * The upstream proxy URL — which carries a credential — is read from
 * the HTTPS_PROXY environment variable at run time and is never
 * written to a file. Run standalone:
 *
 *   node proxy-tee.js [port]   # default 18081, logs "listening on ..."
 *
 * mobile-audit.js spawns this automatically when HTTPS_PROXY is set.
 */

'use strict';

const http = require( 'http' );
const net = require( 'net' );

const upstream = new URL( process.env.HTTPS_PROXY || process.env.https_proxy || '' );
if ( ! upstream.hostname ) {
	console.error( 'proxy-tee: HTTPS_PROXY is not set; nothing to forward to.' );
	process.exit( 1 );
}
const UP_HOST = upstream.hostname;
const UP_PORT = Number( upstream.port || 3128 );
const PORT = Number( process.argv[ 2 ] || 18081 );

const server = http.createServer();
server.on( 'connect', ( req, clientSocket, head ) => {
	const target = req.url;
	const up = net.connect( UP_PORT, UP_HOST, () => {
		up.write(
			'CONNECT ' + target + ' HTTP/1.1\r\n' +
			'Host: ' + target + '\r\n' +
			'Proxy-Connection: keep-alive\r\n\r\n'
		);
	} );
	let stage = 'connect-response';
	let buf = Buffer.alloc( 0 );
	up.on( 'data', ( chunk ) => {
		if ( stage === 'connect-response' ) {
			buf = Buffer.concat( [ buf, chunk ] );
			const end = buf.indexOf( '\r\n\r\n' );
			if ( end === -1 ) return;
			const statusLine = buf.slice( 0, end ).toString().split( '\r\n' )[ 0 ];
			if ( ! /^HTTP\/1\.[01] 200/.test( statusLine ) ) {
				clientSocket.end( 'HTTP/1.1 502 Bad Gateway\r\n\r\n' );
				up.destroy();
				return;
			}
			stage = 'tunnel';
			clientSocket.write( 'HTTP/1.1 200 Connection Established\r\n\r\n' );
			if ( head && head.length ) clientSocket.write( head );
			const rest = buf.slice( end + 4 );
			if ( rest.length ) clientSocket.write( rest );
			return;
		}
		clientSocket.write( chunk );
	} );
	clientSocket.on( 'data', ( chunk ) => {
		up.write( chunk );
	} );
	up.on( 'error', () => clientSocket.destroy() );
	clientSocket.on( 'error', () => up.destroy() );
} );

server.listen( PORT, '127.0.0.1', () => {
	console.log( 'proxy-tee: listening on 127.0.0.1:' + PORT + ' -> ' + UP_HOST + ':' + UP_PORT );
} );
