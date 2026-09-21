<?php
/*
 * Template Name: Solution template
 * Description: Template file for the Solution Page Only
 */
 
get_header(); ?>
<!-- ============================= Banner Section ===================== -->
<?php get_template_part( 'template-parts/header-banner');  ?>
<!-- ============================= End: Banner Section ===================== -->

<section class="blog-page-section solutions-page-section">
    <div class="auto-container">
        <div class="row clearfix">
            
            <?php
            // Fetch ALL solution posts without pagination
            $args = array(
                'post_type'      => 'solution',
                'posts_per_page' => -1, // Display show all
                'post_status'    => 'publish',
                'orderby'        => 'menu_order date',
                'order'          => 'ASC',
            );

            $solutions_query = new WP_Query( $args );

            $anim_classes = array( 'fadeInLeft', 'fadeInUp', 'fadeInRight' );
            $index = 0;

            if ( $solutions_query->have_posts() ) :
                while ( $solutions_query->have_posts() ) : $solutions_query->the_post();
                    
                    $anim_class = $anim_classes[ $index % 3 ];
                    $index++;

                    // ACF Fields
                    $cta_text = get_field( 'solution_cta_text' );
                    if ( empty( $cta_text ) ) {
                        $cta_text = __( 'Read More', 'paradiso' );
                    }

                    $cta_url = get_field( 'solution_cta_url' );
                    if ( empty( $cta_url ) ) {
                        $cta_url = get_permalink();
                    }
                    ?>

                    <!-- Solution Block -->
                    <div class="news-block solution-block col-lg-4 col-md-6 col-sm-12">
                        <div class="inner-box wow <?php echo esc_attr( $anim_class ); ?>" data-wow-delay="0ms" data-wow-duration="1500ms">
                            
                            <!-- Image / Banner -->
                            <div class="image">
                                <a href="<?php echo esc_url( $cta_url ); ?>">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'medium_large', array( 'alt' => get_the_title() ) ); ?>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/no-image.png' ); ?>" alt="<?php the_title_attribute(); ?>">
                                    <?php endif; ?>
                                </a>
                            </div>

                            <!-- Lower Content -->
                            <div class="lower-content">
                                
                                <!-- Solution Title -->
                                <h4><a href="<?php echo esc_url( $cta_url ); ?>"><?php the_title(); ?></a></h4>

                                <!-- Description -->
                                <div class="text">
                                    <?php 
                                    if ( has_excerpt() ) {
                                        echo wp_kses_post( get_the_excerpt() );
                                    } else {
                                        echo wp_kses_post( wp_trim_words( get_the_content(), 18, '...' ) );
                                    }
                                    ?>
                                </div>

                                <!-- ACF Feature List (Repeater Field) -->
                                <?php if ( have_rows( 'solution_features' ) ) : ?>
                                    <div class="solution-features-box">
                                        <strong class="features-label"><?php esc_html_e( 'Features:', 'paradiso' ); ?></strong>
                                        <ul class="solution-features-list">
                                            <?php while ( have_rows( 'solution_features' ) ) : the_row(); 
                                                $feature_item = get_sub_field( 'feature_item' );
                                                if ( empty( $feature_item ) ) {
                                                    $feature_item = get_sub_field( 'feature_name' );
                                                }
                                                if ( ! empty( $feature_item ) ) :
                                                ?>
                                                    <li><span class="check-icon flaticon-check-mark"></span> <?php echo esc_html( $feature_item ); ?></li>
                                                <?php endif; ?>
                                            <?php endwhile; ?>
                                        </ul>
                                    </div>
                                <?php elseif ( get_field( 'solution_features_text' ) ) : ?>
                                    <!-- Fallback if using ACF Textarea field -->
                                    <div class="solution-features-box">
                                        <strong class="features-label"><?php esc_html_e( 'Features:', 'paradiso' ); ?></strong>
                                        <ul class="solution-features-list">
                                            <?php 
                                            $lines = explode( "\n", get_field( 'solution_features_text' ) );
                                            foreach ( $lines as $line ) {
                                                $line_text = trim( $line );
                                                if ( ! empty( $line_text ) ) {
                                                    echo '<li><span class="check-icon flaticon-check-mark"></span> ' . esc_html( $line_text ) . '</li>';
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <!-- CTA Button -->
                                <a class="read-more cta-btn" href="<?php echo esc_url( $cta_url ); ?>">
                                    <?php echo esc_html( $cta_text ); ?>
                                    <span class="arrow flaticon-long-arrow-pointing-to-the-right"></span>
                                </a>

                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-12 text-center">
                    <p><?php esc_html_e( 'No solutions found.', 'paradiso' ); ?></p>
                </div>
            <?php endif; ?>

        </div>

        <?php wp_reset_postdata(); ?>

    </div>
</section>
<!-- End Solutions Section -->

<?php get_footer(); ?>
