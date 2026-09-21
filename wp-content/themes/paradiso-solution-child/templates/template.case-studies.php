<?php
/*
 * Template Name: Case Studies template
 * Description: Template file for the Case Studies Page Only
 */
get_header(); ?>
<!-- ============================= Banner Section ===================== -->
<?php get_template_part( 'template-parts/header-banner');  ?>
<!-- ============================= End: Banner Section ===================== -->
<section class="blog-page-section">
    <div class="auto-container">
        <div class="row clearfix">

            <?php
            $paged = max(1, get_query_var('paged'), get_query_var('page'));

            $case_query = new WP_Query(array(
                'post_type'      => 'case_study',
                'post_status'    => 'publish',
                'posts_per_page' => 6,
                'paged'          => $paged,
            ));

            if ($case_query->have_posts()) :
                while ($case_query->have_posts()) : $case_query->the_post();
                    $post_id     = get_the_ID();
                    $title       = get_the_title();
                    $link        = get_permalink();
                    
                    // Custom Fields (Works with ACF or get_post_meta)
                    $client_name = function_exists('get_field') ? get_field('client_name') : get_post_meta($post_id, 'client_name', true);
                    $industry    = function_exists('get_field') ? get_field('industry') : get_post_meta($post_id, 'industry', true);
                    $client_logo = function_exists('get_field') ? get_field('client_logo') : get_post_meta($post_id, 'client_logo', true);

                    // Safely extract Client Logo URL (handles Array, ID, or String)
                    $client_logo_url = '';
                    if (!empty($client_logo)) {
                        if (is_array($client_logo) && isset($client_logo['url'])) {
                            $client_logo_url = $client_logo['url'];
                        } elseif (is_numeric($client_logo)) {
                            $client_logo_url = wp_get_attachment_image_url($client_logo, 'medium');
                        } elseif (is_string($client_logo)) {
                            $client_logo_url = $client_logo;
                        }
                    }

                    // Featured Image
                    $thumbnail = has_post_thumbnail($post_id) 
                        ? get_the_post_thumbnail($post_id, 'large', array('alt' => esc_attr($title))) 
                        : '<img src="' . esc_url(get_stylesheet_directory_uri() . '/assets/images/no-image.png') . '" alt="' . esc_attr($title) . '">';
            ?>
                <!-- News / Case Study Block -->
                <div class="news-block col-lg-4 col-md-6 col-sm-12">
                    <div class="inner-box wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                        
                        <!-- Featured Image & Client Logo Badge -->
                        <div class="image">
                            <a href="<?php echo esc_url($link); ?>">
                                <?php echo $thumbnail; ?>
                            </a>

                           
                        </div>

                        <!-- Lower Content -->
                        <div class="lower-content">
                            <!-- Post Date -->
                            

                            <!-- Client & Industry Meta -->
                            <ul class="post-meta">
                                <?php if (!empty($client_name)) : ?>
                                    <li><span class="icon fa fa-building"></span> <?php echo esc_html($client_name); ?></li>
                                <?php endif; ?>

                                <?php if (!empty($industry)) : ?>
                                    <li><span class="icon fa fa-briefcase"></span> <?php echo esc_html($industry); ?></li>
                                <?php endif; ?>
                            </ul>

                            <!-- Project Title -->
                            <h4>
                                <a href="<?php echo esc_url($link); ?>">
                                    <?php echo esc_html($title); ?>
                                </a>
                            </h4>

                            <!-- Excerpt -->
                            <div class="text">
                                <?php echo esc_html(wp_trim_words(get_the_excerpt(), 18, '...')); ?>
                            </div>

                            <!-- Read More Link -->
                            <a class="read-more" href="<?php echo esc_url($link); ?>">
                                Read More <span class="arrow flaticon-long-arrow-pointing-to-the-right"></span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php
                endwhile;

                // Custom Pagination Function
                pagination($case_query->max_num_pages);

                wp_reset_postdata();
            else :
            ?>
                <div class="no-posts col-12 text-center">
                    <h4>No Case Studies Found</h4>
                    <p>There are currently no case studies available.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php get_footer(); ?>
