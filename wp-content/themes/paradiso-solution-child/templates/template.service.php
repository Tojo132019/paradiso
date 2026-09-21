<?php
/*
 * Template Name: Service template
 * Description: Template file for the Service Page Only
 */
 
get_header(); ?>
<!-- ============================= Banner Section ===================== -->
<?php get_template_part( 'template-parts/header-banner');  ?>
<!-- ============================= End: Banner Section ===================== -->
<!-- Services Page Section -->
<section class="services-page-section">
	<div class="auto-container">
		<div class="row clearfix">
			<?php
			// 1. Query 'service' custom post type
			$args = array(
				'post_type'      => 'service',
				'posts_per_page' => -1, 
				'post_status'    => 'publish',
				'orderby'        => 'menu_order date',
				'order'          => 'ASC',
			);

			$service_query = new WP_Query( $args );

			if ( $service_query->have_posts() ) :
				$count = 0; // Counter to track alternating style-two layout

				while ( $service_query->have_posts() ) :
					$service_query->the_post();
					$count++;

					$is_style_two = ( $count % 2 === 0 );
					$extra_class  = $is_style_two ? ' style-two' : '';

					// Get Service Icon (ACF field 'choose_service_icon' with fallbacks)
					$icon_class = get_field( 'choose_service_icon' );
					if ( empty( $icon_class ) ) {
						$icon_class = get_post_meta( get_the_ID(), 'service_icon', true );
					}
					if ( empty( $icon_class ) ) {
						$icon_class = 'flaticon-coding-1'; // Default fallback icon
					}

					// Get Featured Image URL with default fallback
					$image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
					if ( empty( $image_url ) ) {
						$image_url = get_stylesheet_directory_uri() . '/assets/images/no-image.png';
					}

					// Get Excerpt / Short Text
					$description = get_the_excerpt();
					if ( empty( $description ) ) {
						$description = wp_trim_words( get_the_content(), 20, '...' );
					}

					// Get Service Categories from 'service_category' taxonomy
					$terms = get_the_terms( get_the_ID(), 'service_category' );
					?>

					<!-- News Block Three -->
					<div class="news-block-three<?php echo esc_attr( $extra_class ); ?> col-lg-4 col-md-6 col-sm-12">
						<div class="inner-box">

							<?php if ( ! $is_style_two ) : ?>
								<!-- Image Position for Standard Layout -->
								<div class="image">
									<a href="<?php the_permalink(); ?>">
										<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title_attribute(); ?>">
									</a>
								</div>
							<?php endif; ?>

							<div class="lower-content">
								<div class="content">
									<div class="icon-box">
										<span class="icon"><?=get_field('choose_service_icon'); ?></span>
									</div>

									<!-- Service Category Badge/Link -->
									<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
										<div class="category">
											<?php
											$cat_links = array();
											foreach ( $terms as $term ) {
												$cat_links[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
											}
											echo implode( ', ', $cat_links );
											?>
										</div>
									<?php endif; ?>

									<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
									<div class="text"><?php echo esc_html( $description ); ?></div>
									<a class="read-more" href="<?php the_permalink(); ?>">
										<?php esc_html_e( 'Read More', 'paradiso' ); ?>
										<span class="arrow flaticon-long-arrow-pointing-to-the-right"></span>
									</a>
								</div>
							</div>

							<?php if ( $is_style_two ) : ?>
								<!-- Image Position for Style Two Layout (Reversed) -->
								<div class="image">
									<a href="<?php the_permalink(); ?>">
										<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title_attribute(); ?>">
									</a>
								</div>
							<?php endif; ?>

						</div>
					</div>

				<?php
				endwhile;
				wp_reset_postdata(); // Reset global post data after loop
			else :
				?>
				<div class="col-12">
					<p><?php esc_html_e( 'No services found.', 'paradiso' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
<!-- End Services Page Section -->
<?php get_footer(); ?>
