<?php
/**
 * Phase 8: comments (decision 32 — comments are active).
 *
 * The static reference has no comments section, so this is designed
 * in the theme's own design language: rounded white cards, emerald
 * accents, RTL, HTML5 form. Jalali dates via erfanpro_jalali_date().
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}

$commenter = wp_get_current_commenter();

if ( ! function_exists( 'erfanpro_comment_callback' ) ) {
	/**
	 * Render one comment as a rounded card.
	 *
	 * @param WP_Comment $comment Comment object.
	 * @param array      $args    wp_list_comments args.
	 * @param int        $depth   Depth.
	 */
	function erfanpro_comment_callback( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		$tag                = ( 'div' === $args['style'] ) ? 'div' : 'li';
		$date               = erfanpro_jalali_date( (int) strtotime( $comment->comment_date ) );
		?>
		<<?php echo esc_html( $tag ); ?> <?php comment_class( 'rounded-3xl border border-emerald-100 bg-white p-6 shadow-xs' ); ?> id="comment-<?php comment_ID(); ?>">
			<div class="flex items-start gap-4">
				<div class="shrink-0 overflow-hidden rounded-full border border-emerald-100">
					<?php echo get_avatar( $comment, 52, '', '', array( 'class' => 'h-[52px] w-[52px]' ) ); ?>
				</div>
				<div class="min-w-0 flex-1">
					<div class="flex flex-wrap items-center gap-x-3 gap-y-1">
						<span class="text-[14px] font-extrabold text-slate-900"><?php echo get_comment_author_link( $comment ); ?></span>
						<span class="text-[11.5px] font-semibold text-slate-500"><?php echo esc_html( $date ); ?></span>
					</div>
					<div class="erfanpro-comment-text mt-3 text-[13.5px] leading-8 text-slate-700">
						<?php comment_text( $comment ); ?>
					</div>
					<div class="mt-3">
						<?php
						comment_reply_link(
							array_merge(
								$args,
								array(
									'depth'      => $depth,
									'max_depth'  => $args['max_depth'],
									'reply_text' => __( 'پاسخ', 'erfanpro' ),
									'before'     => '',
									'after'      => '',
								)
							),
							$comment->comment_ID,
							$comment->comment_post_ID
						);
						?>
					</div>
				</div>
			</div>
		</<?php echo esc_html( $tag ); ?>>
		<?php
	}
}

$comment_count = get_comments_number();
?>
<section class="border-t border-emerald-100 bg-[#f8faf9] py-14 lg:py-16" id="comments">
	<div class="mx-auto max-w-4xl px-5 lg:px-8">
		<?php if ( have_comments() ) : ?>
		<div<?php erfanpro_reveal( 0.0, 'inview', 'opacity: 0; filter: blur(6px); transform: translateY(42px);', 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
			<h3 class="mb-8 flex items-center gap-3 text-2xl font-extrabold text-slate-900">
				<span class="h-7 w-1.5 rounded-full bg-gradient-to-b from-emerald-500 to-emerald-700"></span>
				<?php echo esc_html( sprintf( __( 'دیدگاه‌ها (%s)', 'erfanpro' ), erfanpro_fa_digits( $comment_count ) ) ); ?>
			</h3>
		</div>
		<ol class="comment-list grid gap-5">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 52,
					'callback'    => 'erfanpro_comment_callback',
				)
			);
			?>
		</ol>
		<?php
		the_comments_navigation(
			array(
				'prev_text' => __( 'دیدگاه‌های قدیمی‌تر', 'erfanpro' ),
				'next_text' => __( 'دیدگاه‌های جدیدتر', 'erfanpro' ),
			)
		);
		?>
		<?php endif; ?>

		<?php if ( comments_open() ) : ?>
		<div class="mt-10 rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-xs sm:p-8"<?php erfanpro_reveal( 0.07, 'inview', 'opacity: 0; filter: blur(6px); transform: translateY(42px);', 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
			<?php
			$input_class = 'w-full rounded-2xl border border-slate-200 bg-white px-5 py-3.5 text-[14px] font-medium text-slate-800 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100';
			comment_form(
				array(
					'title_reply'         => __( 'دیدگاه شما', 'erfanpro' ),
					'title_reply_before'  => '<h3 id="reply-title" class="comment-reply-title mb-2 flex items-center gap-3 text-xl font-extrabold text-slate-900"><span class="h-6 w-1.5 rounded-full bg-gradient-to-b from-emerald-500 to-emerald-700"></span>',
					'title_reply_after'   => '</h3>',
					'comment_notes_before' => '<p class="mb-6 text-[13px] leading-7 text-slate-500">' . esc_html__( 'دیدگاه شما پس از تأیید نمایش داده می‌شود.', 'erfanpro' ) . '</p>',
					'label_submit'        => __( 'ثبت دیدگاه', 'erfanpro' ),
					'submit_button'       => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">' . esc_html__( 'ثبت دیدگاه', 'erfanpro' ) . '</button>',
					'submit_field'        => '<p class="form-submit mt-6">%1$s %2$s</p>',
					'class_submit'        => 'rounded-full bg-emerald-600 px-8 py-3.5 text-[14px] font-extrabold text-white shadow-lg shadow-emerald-600/25 transition-all hover:bg-emerald-700 cursor-pointer',
					'class_form'          => 'comment-form grid gap-5',
					'fields'              => array(
						'author' => sprintf(
							'<p class="comment-form-author"><label for="author" class="mb-2 block text-[13px] font-bold text-slate-700">%s <span class="text-rose-500">*</span></label><input id="author" name="author" type="text" value="%s" size="30" maxlength="245" autocomplete="name" required class="%s" placeholder="%s"></p>',
							esc_html__( 'نام', 'erfanpro' ),
							esc_attr( $commenter['comment_author'] ?? '' ),
							esc_attr( $input_class ),
							esc_attr__( 'نام شما', 'erfanpro' )
						),
						'email'  => sprintf(
							'<p class="comment-form-email"><label for="email" class="mb-2 block text-[13px] font-bold text-slate-700">%s <span class="text-rose-500">*</span></label><input id="email" name="email" type="email" value="%s" size="30" maxlength="100" autocomplete="email" required class="%s" placeholder="%s" dir="ltr" style="text-align:right"></p>',
							esc_html__( 'ایمیل', 'erfanpro' ),
							esc_attr( $commenter['comment_author_email'] ?? '' ),
							esc_attr( $input_class ),
							esc_attr__( 'example@mail.com', 'erfanpro' )
						),
						'url'    => sprintf(
							'<p class="comment-form-url"><label for="url" class="mb-2 block text-[13px] font-bold text-slate-700">%s</label><input id="url" name="url" type="url" value="%s" size="30" maxlength="200" autocomplete="url" class="%s" placeholder="https://" dir="ltr" style="text-align:right"></p>',
							esc_html__( 'وب‌سایت', 'erfanpro' ),
							esc_attr( $commenter['comment_author_url'] ?? '' ),
							esc_attr( $input_class )
						),
					),
					'comment_field'       => sprintf(
						'<p class="comment-form-comment"><label for="comment" class="mb-2 block text-[13px] font-bold text-slate-700">%s <span class="text-rose-500">*</span></label><textarea id="comment" name="comment" cols="45" rows="5" maxlength="65525" required class="%s" placeholder="%s"></textarea></p>',
						esc_html__( 'دیدگاه', 'erfanpro' ),
						esc_attr( $input_class ),
						esc_attr__( 'دیدگاه خود را بنویسید…', 'erfanpro' )
					),
				)
			);
			?>
		</div>
		<?php elseif ( $comment_count > 0 ) : ?>
		<p class="mt-10 rounded-3xl border border-emerald-100 bg-white p-6 text-center text-[13.5px] font-bold text-slate-600"><?php esc_html_e( 'ثبت دیدگاه برای این مقاله بسته شده است.', 'erfanpro' ); ?></p>
		<?php endif; ?>
	</div>
</section>
