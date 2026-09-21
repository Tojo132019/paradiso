<?php
/**
 * Template Name: Single Solution Detail
 * Template Post Type: solution
 *
 * The template for displaying single Solution details.
 *
 * @package Paradiso
 */

get_header();

while ( have_posts() ) :
	the_post();
	$current_solution_id = get_the_ID();

	// Fetch Solution Category Terms for this solution
	$terms = get_the_terms( $current_solution_id, 'solution_category' );
	?>

	<!-- ============================= Banner Section ===================== -->
	<?php get_template_part( 'template-parts/header-banner' ); ?>
	<!-- ============================= End: Banner Section ===================== -->

	<!-- Sidebar Page Container -->
	<div class="sidebar-page-container">
		<div class="auto-container">
			<div class="row clearfix">

				<!-- Content Side (Main Solution Details) -->
				<div class="content-side col-lg-12 col-md-12 col-sm-12">
					<div class="service-detail solution-detail">

						<!-- Featured Image -->
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="inner-box">
								<div class="image">
									<?php the_post_thumbnail( 'full', array( 'alt' => get_the_title() ) ); ?>
								</div>
							</div>
						<?php endif; ?>

						<!-- Solution Title & Main Content -->
						<div class="lower-content">

							<!-- Solution Category Display Above Title -->
							<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
								<div class="category" style="margin-top:20px;"> 
									<?php
									$cat_links = array();
									foreach ( $terms as $term ) {
										$cat_links[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
									}
									echo implode( ', ', $cat_links );
									?>
								</div>
							<?php endif; ?>

							<h2><?php the_title(); ?></h2>
							<div class="text">
								<?php the_content(); ?>
							</div>

							<!-- ACF Feature List -->
							<?php if ( have_rows( 'solution_features' ) ) : ?>
								<div class="solution-features-box" style="margin-top: 30px;">
									<h4 style="margin-bottom: 15px;"><?php esc_html_e( 'Features', 'paradiso' ); ?></h4>
									<ul class="solution-features-list" style="list-style:none; padding:0;">
										<?php while ( have_rows( 'solution_features' ) ) : the_row(); 
											$feature_item = get_sub_field( 'feature_item' );
											if ( empty( $feature_item ) ) {
												$feature_item = get_sub_field( 'feature_name' );
											}
											if ( ! empty( $feature_item ) ) :
											?>
												<li style="margin-bottom: 8px;"><span class="check-icon flaticon-check-mark" style="color:#28a745; margin-right:8px;"></span> <?php echo esc_html( $feature_item ); ?></li>
											<?php endif; ?>
										<?php endwhile; ?>
									</ul>
								</div>
							<?php endif; ?>

							<!-- ACF CTA Button -->
							<?php
							$cta_text = get_field( 'solution_cta_text' );
							$cta_url  = get_field( 'solution_cta_url' );
							if ( $cta_text || $cta_url ) :
								$cta_text = $cta_text ? $cta_text : __( 'Read More', 'paradiso' );
								$cta_url  = $cta_url ? $cta_url : '#';
								?>
								<div class="btns-box" style="margin-top: 30px;">
									<a href="<?php echo esc_url( $cta_url ); ?>" class="theme-btn btn-style-one"><span class="txt"><?php echo esc_html( $cta_text ); ?></span></a>
								</div>
							<?php endif; ?>

						</div>

					</div>
				</div>

				

			</div>
		</div>
	</div>

	<?php
endwhile;

get_footer();