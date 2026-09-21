
<?php
/*
 * Template Name: Home template
 * Description: Template file for the Home Page Only
 */

get_header();

$paradiso_theme_uri = get_stylesheet_directory_uri();

/* Common Option Fields */
$paradiso_request_demo_button = get_field( 'request_demo', 'option' );
$paradiso_contact_button      = get_field( 'contact_us', 'option' );
$paradiso_schedule_button     = get_field( 'schedule_a_consultation', 'option' );

$paradiso_show_banner = get_field( 'show_home_page_banner' );

if ( $paradiso_show_banner ) :
	$paradiso_home_slides = get_field( 'manage_home_page_slider' );

	if ( ! empty( $paradiso_home_slides ) && is_array( $paradiso_home_slides ) ) :
		?>
<section class="banner-section">
	<div class="main-slider-carousel owl-carousel owl-theme">

		<?php
		foreach ( $paradiso_home_slides as $paradiso_slide_index => $paradiso_slide ) :
			$paradiso_background_image = $paradiso_slide['home_page_banner_image'] ?? '';
			$paradiso_title            = $paradiso_slide['company_tagline'] ?? '';
			$paradiso_description      = $paradiso_slide['brief_introduction'] ?? '';
			$paradiso_is_first_slide   = ( 0 === $paradiso_slide_index );

			$paradiso_demo_target    = ! empty( $paradiso_request_demo_button['target'] ) ? $paradiso_request_demo_button['target'] : '_self';
			$paradiso_contact_target = ! empty( $paradiso_contact_button['target'] ) ? $paradiso_contact_button['target'] : '_self';
			?>

			<div class="slide">

				<?php
				// Output Banner Image cleanly using native WordPress attachment handling.
				if ( ! empty( $paradiso_background_image ) ) {
					$paradiso_image_id = 0;

					if ( is_array( $paradiso_background_image ) && ! empty( $paradiso_background_image['ID'] ) ) {
						$paradiso_image_id = absint( $paradiso_background_image['ID'] );
					} elseif ( is_numeric( $paradiso_background_image ) ) {
						$paradiso_image_id = absint( $paradiso_background_image );
					}

					if ( $paradiso_image_id ) {
						// Attributes for LCP Optimization on Slide 1 vs Lazy Loading on Slide 2+
						$image_attributes = array(
							'class' => 'banner-bg-image',
							'sizes' => '(max-width: 767px) 100vw, 100vw',
						);

						if ( $paradiso_is_first_slide ) {
							$image_attributes['loading']       = 'eager';
							$image_attributes['fetchpriority'] = 'high';
							$image_attributes['decoding']      = 'sync';
						} else {
							$image_attributes['loading']  = 'lazy';
							$image_attributes['decoding'] = 'async';
						}

						echo wp_get_attachment_image( $paradiso_image_id, 'full', false, $image_attributes );

					} elseif ( is_string( $paradiso_background_image ) ) {
						// Fallback for raw image URL strings
						?>
						<img
							class="banner-bg-image"
							src="<?php echo esc_url( $paradiso_background_image ); ?>"
							alt="<?php echo esc_attr( $paradiso_title ); ?>"
							width="1920"
							height="800"
							<?php if ( $paradiso_is_first_slide ) : ?>
								loading="eager" fetchpriority="high" decoding="sync"
							<?php else : ?>
								loading="lazy" decoding="async"
							<?php endif; ?>
						>
						<?php
					}
				}
				?>

				<!-- Pattern Layers -->
				<div class="patern-layer-one"></div>
				<div class="patern-layer-two"></div>

				<div class="auto-container">
					<div class="content-column">
						<div class="inner-column">

							<!-- Pattern Three -->
							<div class="patern-layer-three"></div>

							<?php if ( $paradiso_title ) : ?>
								<h1><?php echo esc_html( $paradiso_title ); ?></h1>
							<?php endif; ?>

							<?php if ( $paradiso_description ) : ?>
								<div class="text">
									<?php echo wp_kses_post( $paradiso_description ); ?>
								</div>
							<?php endif; ?>

							<div class="button-wrapper">
								<?php if ( ! empty( $paradiso_request_demo_button['url'] ) ) : ?>
									<div class="btns-box">
										<a
											href="<?php echo esc_url( $paradiso_request_demo_button['url'] ); ?>"
											target="<?php echo esc_attr( $paradiso_demo_target ); ?>"
											<?php if ( '_blank' === $paradiso_demo_target ) : ?>
												rel="noopener noreferrer"
											<?php endif; ?>
											class="theme-btn btn-style-one"
										>
											<span class="txt">
												<?php echo esc_html( $paradiso_request_demo_button['title'] ); ?>
											</span>
										</a>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $paradiso_contact_button['url'] ) ) : ?>
									<div class="btns-box">
										<a
											href="<?php echo esc_url( $paradiso_contact_button['url'] ); ?>"
											target="<?php echo esc_attr( $paradiso_contact_target ); ?>"
											<?php if ( '_blank' === $paradiso_contact_target ) : ?>
												rel="noopener noreferrer"
											<?php endif; ?>
											class="theme-btn btn-style-one"
										>
											<span class="txt">
												<?php echo esc_html( $paradiso_contact_button['title'] ); ?>
											</span>
										</a>
									</div>
								<?php endif; ?>
							</div>

						</div>
					</div>
				</div>
			</div>

		<?php endforeach; ?>

	</div>
</section>
		<?php
	endif;
endif;
?>
<!-- End Banner Section -->

<!-- About Section -->
<?php
$paradiso_show_about = get_field( 'show_about_us_section' );

if ( $paradiso_show_about ) :

	$paradiso_about_title   = get_field( 'why_choose_us_title' );
	$paradiso_about_content = get_field( 'why_choose_us_content' );
	$paradiso_about_image   = get_field( 'why_choose_us_image' );
?>
<section class="about-section">
	<div class="auto-container">

		<!-- Sec Title -->
		<?php if ( $paradiso_about_title ) : ?>
			<div class="sec-title">
				<div class="title">
					<?php echo wp_kses_post( $paradiso_about_title ); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="row clearfix">

			<!-- Content Column -->
			<div class="content-column col-lg-6 col-md-12 col-sm-12">
				<div class="inner-column">

					<?php if ( $paradiso_about_content ) : ?>
						<div class="text">
							<?php echo wp_kses_post( $paradiso_about_content ); ?>
						</div>
					<?php endif; ?>

					<?php if ( have_rows( 'manage_why_choose_us_features' ) ) : ?>

						<div class="blocks-outer">

							<!-- Feature Block -->
							<?php while ( have_rows( 'manage_why_choose_us_features' ) ) : the_row(); ?>

								<?php
								$paradiso_feature_icon    = get_sub_field( 'feature_icon' );
								$paradiso_feature_title   = get_sub_field( 'feature_title' );
								$paradiso_feature_content = get_sub_field( 'feature_content' );
								?>

								<div class="feature-block">
									<div class="inner-box">

										<?php if ( $paradiso_feature_icon ) : ?>
											<div class="icon">
												<?php echo wp_kses_post( $paradiso_feature_icon ); ?>
											</div>
										<?php endif; ?>

										<?php if ( $paradiso_feature_title ) : ?>
											<h6>
												<?php echo esc_html( $paradiso_feature_title ); ?>
											</h6>
										<?php endif; ?>

										<?php if ( $paradiso_feature_content ) : ?>
											<div class="feature-text">
												<?php echo wp_kses_post( $paradiso_feature_content ); ?>
											</div>
										<?php endif; ?>

									</div>
								</div>

							<?php endwhile; ?>

							<!-- Feature Block -->

						</div>

					<?php endif; ?>

				</div>
			</div>

			<!-- Images Column -->
			<?php if ( $paradiso_about_image ) : ?>

				<?php
				$paradiso_about_image_url = '';

				if ( is_array( $paradiso_about_image ) ) {
					$paradiso_about_image_url = $paradiso_about_image['sizes']['large']
						?? $paradiso_about_image['url']
						?? '';
				} elseif ( is_numeric( $paradiso_about_image ) ) {
					$paradiso_about_image_url = wp_get_attachment_image_url(
						absint( $paradiso_about_image ),
						'large'
					);
				} else {
					$paradiso_about_image_url = $paradiso_about_image;
				}
				?>

				<?php if ( $paradiso_about_image_url ) : ?>

					<div class="images-column col-lg-6 col-md-12 col-sm-12">
						<div class="image-wrapper">
							<img
								src="<?php echo esc_url( $paradiso_about_image_url ); ?>"
								border="0"
								alt="about Image"
								loading="lazy"
							>
						</div>
					</div>

				<?php endif; ?>

			<?php endif; ?>

		</div>
	</div>
</section>
<?php endif; ?>
<!-- End About Section -->


<!-- Services Section -->
<?php
$paradiso_show_solution = get_field( 'show_solution' );

if ( $paradiso_show_solution ) :

	$paradiso_solution_title = get_field( 'solution_title' );
	$paradiso_solutions      = get_field( 'choose_solution' );
?>
<section class="services-section margin-top">
	<div class="pattern-layer"></div>

	<div class="auto-container">

		<!-- Sec Title -->
		<?php if ( $paradiso_solution_title ) : ?>
			<div class="sec-title light centered">
				<div class="title">
					<?php echo wp_kses_post( $paradiso_solution_title ); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="row service-row clearfix">

			<?php if ( $paradiso_solutions ) : ?>

				<?php foreach ( $paradiso_solutions as $paradiso_solution ) : ?>

					<?php
					$paradiso_solution_id = is_object( $paradiso_solution )
						? absint( $paradiso_solution->ID )
						: absint( $paradiso_solution );

					if ( ! $paradiso_solution_id ) {
						continue;
					}

					$paradiso_solution_title = get_the_title( $paradiso_solution_id );
					$paradiso_solution_description = get_the_excerpt( $paradiso_solution_id );
					$paradiso_solution_link = get_permalink( $paradiso_solution_id );
					$paradiso_solution_icon = get_field(
						'choose_solution_icon',
						$paradiso_solution_id
					);
					?>

					<!-- Service Block -->
					<div class="service-block col-lg-4 col-md-6 col-sm-12">
						<div class="inner-box">

							<div class="icon-box">
								<span class="icon">
									<?php echo wp_kses_post( $paradiso_solution_icon ); ?>
								</span>
							</div>

							<h5>
								<a href="<?php echo esc_url( $paradiso_solution_link ); ?>">
									<?php echo esc_html( $paradiso_solution_title ); ?>
								</a>
							</h5>

							<div class="text">
								<?php echo esc_html( $paradiso_solution_description ); ?>
							</div>

							<a
								href="<?php echo esc_url( $paradiso_solution_link ); ?>"
								class="arrow flaticon-long-arrow-pointing-to-the-right">
							</a>

						</div>
					</div>

				<?php endforeach; ?>

			<?php endif; ?>

		</div>
	</div>
</section>
<?php endif; ?>
<!-- End Services Section -->


<!-- Services Section Two -->
<?php
$paradiso_show_service = get_field( 'show_service' );

if ( $paradiso_show_service ) :

	$paradiso_service_title = get_field( 'service_title' );
	$paradiso_services      = get_field( 'choose_service' );
?>
<section class="services-section-two margin-top">

	<div class="auto-container">

		<div class="upper-box">

			<!-- Sec Title -->
			<?php if ( $paradiso_service_title ) : ?>
				<div class="sec-title light centered">
					<div class="title">
						<?php echo wp_kses_post( $paradiso_service_title ); ?>
					</div>
				</div>
			<?php endif; ?>

		</div>

		<div class="inner-container">
			<div class="row service-row clearfix">

				<?php if ( $paradiso_services ) : ?>

					<?php foreach ( $paradiso_services as $paradiso_service ) : ?>

						<?php
						$paradiso_service_id = is_object( $paradiso_service )
							? absint( $paradiso_service->ID )
							: absint( $paradiso_service );

						if ( ! $paradiso_service_id ) {
							continue;
						}

						$paradiso_service_title = get_the_title( $paradiso_service_id );
						$paradiso_service_link  = get_permalink( $paradiso_service_id );

						$paradiso_service_description = get_field(
							'service_short_description',
							$paradiso_service_id
						);

						if ( ! $paradiso_service_description ) {
							$paradiso_service_description = get_the_excerpt(
								$paradiso_service_id
							);
						}

						$paradiso_service_icon = get_field(
							'choose_service_icon',
							$paradiso_service_id
						);
						?>

						<!-- Service Block Two -->
						<div class="service-block-two col-lg-4 col-md-6 col-sm-12">
							<div class="inner-box">

								<div class="shape-one"></div>
								<div class="shape-two"></div>

								<div class="icon-box">
									<span class="icon">
										<?php echo wp_kses_post( $paradiso_service_icon ); ?>
									</span>
								</div>

								<h5>
									<a href="<?php echo esc_url( $paradiso_service_link ); ?>">
										<?php echo esc_html( $paradiso_service_title ); ?>
									</a>
								</h5>

								<div class="text">
									<?php echo esc_html( $paradiso_service_description ); ?>
								</div>

							</div>
						</div>

					<?php endforeach; ?>

				<?php endif; ?>

			</div>
		</div>

	</div>
</section>
<?php endif; ?>
<!-- End Services Section Two -->


<!-- Call To Action Section -->
<?php
$paradiso_show_cta = get_field( 'show_contact_cta_section' );

if ( $paradiso_show_cta ) :

	$paradiso_cta_text = get_field( 'contact_cta_text' );
?>
<section
	class="call-to-action-section"
	style="background-image:url('<?php echo esc_url( $paradiso_theme_uri . '/assets/images/cta.png' ); ?>')"
>
	<div class="auto-container">

		<div class="row clearfix">

			<!-- Heading Column -->
			<?php if ( $paradiso_cta_text ) : ?>

				<div class="heading-column col-lg-8 col-md-12 col-sm-12">
					<div class="inner-column">
						<h2>
							<?php echo wp_kses_post( $paradiso_cta_text ); ?>
						</h2>
					</div>
				</div>

			<?php endif; ?>

			<!-- Button Column -->
			<?php if ( $paradiso_schedule_button ) : ?>

				<?php
				$paradiso_schedule_target = ! empty( $paradiso_schedule_button['target'] )
					? $paradiso_schedule_button['target']
					: '_self';
				?>

				<div class="button-column col-lg-4 col-md-12 col-sm-12">
					<div class="inner-column">

						<a
							href="<?php echo esc_url( $paradiso_schedule_button['url'] ); ?>"
							class="theme-btn btn-style-one"
							target="<?php echo esc_attr( $paradiso_schedule_target ); ?>"
							<?php if ( '_blank' === $paradiso_schedule_target ) : ?>
								rel="noopener noreferrer"
							<?php endif; ?>
						>
							<span class="txt">
								<?php echo esc_html( $paradiso_schedule_button['title'] ); ?>
							</span>
						</a>

					</div>
				</div>

			<?php endif; ?>

		</div>
	</div>
</section>
<?php endif; ?>
<!-- End Call To Action Section -->


<!-- Testimonial Section -->
<?php
$paradiso_show_testimonials = get_field( 'show_testimonials' );

if ( $paradiso_show_testimonials ) :

	$paradiso_testimonial_heading = get_field( 'testimonial_heading' );
?>
<section class="testimonial-section">

	<div class="auto-container">

		<!-- Sec Title -->
		<?php if ( $paradiso_testimonial_heading ) : ?>

			<div class="sec-title">
				<div class="clearfix">

					<div class="pull-left">
						<div class="title">
							<?php echo wp_kses_post( $paradiso_testimonial_heading ); ?>
						</div>
					</div>

				</div>
			</div>

		<?php endif; ?>

		<?php if ( have_rows( 'manage_testimonial' ) ) : ?>

			<div class="testimonial-carousel owl-carousel owl-theme">

				<!-- Testimonial Block -->
				<?php while ( have_rows( 'manage_testimonial' ) ) : the_row(); ?>

					<?php
					$paradiso_client_name = get_sub_field( 'client_name' );
					$paradiso_company_name = get_sub_field( 'company_name' );
					$paradiso_feedback = get_sub_field( 'feedback' );
					?>

					<div class="testimonial-block">
						<div class="inner-box">

							<div class="upper-box">

								<h4>
									<?php echo esc_html( $paradiso_client_name ); ?>
								</h4>

								<div class="designation">
									<?php echo esc_html( $paradiso_company_name ); ?>
								</div>

							</div>

							<div class="text">
								"<?php echo wp_kses_post( $paradiso_feedback ); ?>"
							</div>

						</div>
					</div>

				<?php endwhile; ?>

			</div>

		<?php endif; ?>

	</div>

</section>
<?php endif; ?>
<!-- End Testimonial Section -->

<?php get_footer(); ?>

