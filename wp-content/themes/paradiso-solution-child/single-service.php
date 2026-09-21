<?php
/**
 * Template Name: Single Service Detail
 * Template Post Type: service
 *
 * The template for displaying single Service details.
 *
 * @package Paradiso
 */

get_header();

while ( have_posts() ) :
	the_post();
	$current_service_id = get_the_ID();
	$service_icon       = get_post_meta( $current_service_id, 'service_icon', true );
	if ( function_exists( 'get_field' ) && get_field( 'choose_service_icon' ) ) {
		$service_icon = get_field( 'choose_service_icon' );
	}

	// Fetch Service Category Terms for this service
	$terms = get_the_terms( $current_service_id, 'service_category' );
	?>

	<!-- ============================= Banner Section ===================== -->
	<?php get_template_part( 'template-parts/header-banner' ); ?>
	<!-- ============================= End: Banner Section ===================== -->

	<!-- Sidebar Page Container -->
	<div class="sidebar-page-container">
		<div class="auto-container">
			<div class="row clearfix">

				<!-- Content Side (Main Service Details) -->
				<div class="content-side col-lg-8 col-md-12 col-sm-12">
					<div class="service-detail">

						<!-- Featured Image -->
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="inner-box">
								<div class="image">
									<?php the_post_thumbnail( 'full', array( 'alt' => get_the_title() ) ); ?>
								</div>
							</div>
						<?php endif; ?>

						<!-- Service Title & Main Content -->
						<div class="lower-content">

							<!-- Service Category Display Above Title -->
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
						</div>

					</div>
				</div>

				<!-- Sidebar Side -->
				<div class="sidebar-side col-lg-4 col-md-12 col-sm-12">
					<aside class="sidebar default-sidebar">

						

						<!-- Service Categories Widget -->
						<div class="sidebar-widget categories-blog">
							<div class="sidebar-title">
								<h4><?php esc_html_e( 'Categories', 'paradiso' ); ?></h4>
							</div>
							<ul class="blog-cat">
								<?php
								$categories = get_terms( array(
									'taxonomy'   => 'service_category',
									'hide_empty' => true,
								) );

								if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
									foreach ( $categories as $category ) :
										// Highlight category if current service belongs to it
										$is_active = has_term( $category->term_id, 'service_category', $current_service_id ) ? ' class="active"' : '';
										?>
										<li<?php echo $is_active; ?>>
											<a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
												<?php echo esc_html( $category->name ); ?> <span>(<?php echo esc_html( $category->count ); ?>)</span>
											</a>
										</li>
										<?php
									endforeach;
								endif;
								?>
							</ul>
						</div>

						<!-- Contact / Need Help Widget -->
						<div class="sidebar-widget need-help-widget">
							<div class="widget-inner">
								<?php
								$request_demo_button = get_field('request_demo', 'option');
								if($request_demo_button): 
								$link_target_demo = $request_demo_button['target'] ? $request_demo_button['target'] : '_self';
								?>
								<div class="btns-box">
									<a href="<?=$request_demo_button['url'];?>" target="<?=$link_target_demo?>" class="theme-btn btn-style-three"><span class="txt"><?=$request_demo_button['title'];?></span></a>
								</div>
								<?php endif; ?>
							</div>
						</div>

						

					</aside>
				</div>

			</div>
		</div>
	</div>

	<?php
endwhile;

get_footer();