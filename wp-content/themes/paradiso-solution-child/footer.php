<?php
$paradiso_theme_uri  = get_stylesheet_directory_uri();
$paradiso_home_url   = home_url( '/' );
$paradiso_logo       = get_field( 'site_logo', 'option' );
$paradiso_about      = get_field( 'footer_about_company_text', 'option' );
$paradiso_address    = get_field( 'address', 'option' );
$paradiso_phone      = get_field( 'site_phone_number', 'option' );
$paradiso_email      = get_field( 'site_email_id', 'option' );
$paradiso_copyright  = get_field( 'copyright_text', 'option' );
$paradiso_phone_link = $paradiso_phone ? preg_replace( '/[^0-9+]/', '', $paradiso_phone ) : '';
?>
</main>
<footer class="main-footer">
	<div class="auto-container">
		<!-- Widgets Section -->
		<div class="widgets-section">
			<div class="row clearfix">
				<!-- Column -->
				<div class="big-column col-lg-6 col-md-12 col-sm-12">
					<div class="row clearfix">
						<!-- Footer Column -->
						<div class="footer-column col-lg-7 col-md-6 col-sm-12">
							<div class="footer-widget logo-widget">
								<?php if ( $paradiso_logo ) : ?>
									<div class="logo">
										<a href="<?php echo esc_url( $paradiso_home_url ); ?>">
											<img src="<?php echo esc_url( $paradiso_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="200" height="60" loading="lazy">
										</a>
									</div>
								<?php endif; ?>
								<?php if ( $paradiso_about ) : ?>
									<div class="text"><?php echo wp_kses_post( $paradiso_about ); ?></div>
								<?php endif; ?>
								<!-- Social Box -->
								<?php if ( have_rows( 'manage_social_media', 'option' ) ) : ?>
									<ul class="social-box">
										<?php while ( have_rows( 'manage_social_media', 'option' ) ) : the_row(); ?>
											<?php
											$paradiso_social_link = get_sub_field( 'social_media_link' );
											$paradiso_social_icon = get_sub_field( 'social_media_icon' );
											?>
											<?php if ( $paradiso_social_link ) : ?>
												<li>
													<a href="<?php echo esc_url( $paradiso_social_link ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Social Media', 'paradiso' ); ?>">
														<?php echo wp_kses_post( $paradiso_social_icon ); ?>
													</a>
												</li>
											<?php endif; ?>
										<?php endwhile; ?>
									</ul>
								<?php endif; ?>
							</div>
						</div>
						<!-- Footer Column -->
						<div class="footer-column col-lg-5 col-md-6 col-sm-12">
							<div class="footer-widget links-widget">
								<h5><?php esc_html_e( 'Quick Links', 'paradiso' ); ?></h5>
								<?php
								if ( has_nav_menu( 'footer' ) ) {
									wp_nav_menu(
										array(
											'theme_location' => 'footer',
											'container'      => false,
											'menu_class'     => 'list-link',
											'fallback_cb'    => false,
											'depth'          => 2,
										)
									);
								}
								?>
							</div>
						</div>
					</div>
				</div>
				<!-- Column -->
				<div class="big-column col-lg-6 col-md-12 col-sm-12">
					<div class="row clearfix">
						<!-- Footer Column -->
						<div class="footer-column col-lg-6 col-md-6 col-sm-12">
							<div class="footer-widget news-widget">
								<h5><?php esc_html_e( 'Recent Posts', 'paradiso' ); ?></h5>
								<div class="widget-content">
									<?php
									// Optimized Query: Disables unneeded caching features for performance.
									$paradiso_recent_posts = new WP_Query(
										array(
											'post_type'              => 'post',
											'posts_per_page'         => 2,
											'post_status'            => 'publish',
											'ignore_sticky_posts'    => true,
											'no_found_rows'          => true,
											'update_post_meta_cache' => false,
											'update_post_term_cache' => false,
										)
									);
									?>
									<?php if ( $paradiso_recent_posts->have_posts() ) : ?>
										<?php while ( $paradiso_recent_posts->have_posts() ) : $paradiso_recent_posts->the_post(); ?>
											<?php $paradiso_post_permalink = get_permalink(); ?>
											<div class="post">
												<div class="thumb">
													<a href="<?php echo esc_url( $paradiso_post_permalink ); ?>">
														<?php if ( has_post_thumbnail() ) : ?>
															<?php
															the_post_thumbnail(
																'thumbnail',
																array(
																	'alt'     => esc_attr( get_the_title() ),
																	'loading' => 'lazy',
																)
															);
															?>
														<?php else : ?>
															<img src="<?php echo esc_url( $paradiso_theme_uri . '/assets/images/post-thumb-3.jpg' ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" width="150" height="150" loading="lazy">
														<?php endif; ?>
													</a>
												</div>
												<h6>
													<a href="<?php echo esc_url( $paradiso_post_permalink ); ?>">
														<?php echo esc_html( get_the_title() ); ?>
													</a>
												</h6>
												<span class="date"><?php echo esc_html( get_the_date( 'F d, Y' ) ); ?></span>
											</div>
										<?php endwhile; ?>
										<?php wp_reset_postdata(); ?>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<!-- Footer Column -->
						<div class="footer-column col-lg-6 col-md-6 col-sm-12">
							<div class="footer-widget contact-widget">
								<h5><?php esc_html_e( 'Contact Us', 'paradiso' ); ?></h5>
								<ul>
									<?php if ( $paradiso_address ) : ?>
										<li>
											<span class="icon flaticon-placeholder-2"></span>
											<strong><?php esc_html_e( 'Address', 'paradiso' ); ?></strong>
											<?php echo esc_html( $paradiso_address ); ?>
										</li>
									<?php endif; ?>
									<?php if ( $paradiso_phone ) : ?>
										<li>
											<span class="icon flaticon-phone-call"></span>
											<strong><?php esc_html_e( 'Phone', 'paradiso' ); ?></strong>
											<a href="tel:<?php echo esc_attr( $paradiso_phone_link ); ?>">
												<?php echo esc_html( $paradiso_phone ); ?>
											</a>
										</li>
									<?php endif; ?>
									<?php if ( $paradiso_email ) : ?>
										<li>
											<span class="icon flaticon-email-1"></span>
											<strong><?php esc_html_e( 'E-Mail', 'paradiso' ); ?></strong>
											<a href="mailto:<?php echo esc_attr( $paradiso_email ); ?>">
												<?php echo esc_html( $paradiso_email ); ?>
											</a>
										</li>
									<?php endif; ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Footer Bottom -->
		<?php if ( $paradiso_copyright ) : ?>
			<div class="footer-bottom">
				<div class="auto-container">
					<div class="row clearfix">
						<div class="column col-lg-6 col-md-12 col-sm-12">
							<div class="copyright"><?php echo wp_kses_post( $paradiso_copyright ); ?></div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</footer>
</div>

<!-- Scroll to top -->
<div class="scroll-to-top scroll-to-target" data-target="html">
	<span class="fa fa-arrow-up"></span>
</div>

<?php wp_footer(); ?>
</body>
</html>