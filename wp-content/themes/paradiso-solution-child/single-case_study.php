<?php
get_header();
?>

<div class="sidebar-page-container">
    <div class="auto-container">
        <div class="row clearfix">
            
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    $post_id     = get_the_ID();
                    $title       = get_the_title();
                    
                    // Fetch Custom Fields
                    $client_name       = function_exists('get_field') ? get_field('client_name') : get_post_meta($post_id, 'client_name', true);
                    $industry          = function_exists('get_field') ? get_field('industry') : get_post_meta($post_id, 'industry', true);
                    $challenge         = function_exists('get_field') ? get_field('challenge') : get_post_meta($post_id, 'challenge', true);
                    $solution_provided = function_exists('get_field') ? get_field('solution_provided') : get_post_meta($post_id, 'solution_provided', true);
                    $results           = function_exists('get_field') ? get_field('results') : get_post_meta($post_id, 'results', true);
                    $client_logo       = function_exists('get_field') ? get_field('client_logo') : get_post_meta($post_id, 'client_logo', true);
            ?>
                <!-- Content Side -->
                <div class="content-side col-lg-8 col-md-12 col-sm-12">
                    <div class="news-detail">
                        <div class="inner-box">
                            
                            <!-- Project Title -->
                            <div class="upper-box">
                                <h3><?php echo esc_html($title); ?></h3>
                                <ul class="post-meta">
                                    <?php if ($client_name) : ?>
                                        <li><span class="icon fa fa-building"></span> Client: <?php echo esc_html($client_name); ?></li>
                                    <?php endif; ?>
                                    <?php if ($industry) : ?>
                                        <li><span class="icon fa fa-briefcase"></span> Industry: <?php echo esc_html($industry); ?></li>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <!-- Featured Image -->
                            <div class="image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large'); ?>
                                <?php endif; ?>
                            </div>
                                
                            <div class="lower-content">
                                <!-- Main Content / Overview -->
                                <?php the_content(); ?>

                                <!-- Challenge Section -->
                                <?php if (!empty($challenge)) : ?>
                                    <div class="case-section mb-4">
                                        <h4>The Challenge</h4>
                                        <p><?php echo wp_kses_post($challenge); ?></p>
                                    </div>
                                <?php endif; ?>

                                <!-- Solution Provided Section -->
                                <?php if (!empty($solution_provided)) : ?>
                                    <div class="case-section mb-4">
                                        <h4>Solution Provided</h4>
                                        <p><?php echo wp_kses_post($solution_provided); ?></p>
                                    </div>
                                <?php endif; ?>

                                <!-- Results Section -->
                                <?php if (!empty($results)) : ?>
                                    <blockquote class="results-box">
                                        <div class="">
                                            <h4>Project Results</h4>
                                            <p><?php echo wp_kses_post($results); ?></p>
                                        </div>
                                    </blockquote>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Side (Client Info Box) -->
                <div class="sidebar-side left-sidebar col-lg-4 col-md-12 col-sm-12">
                    <aside class="sidebar sticky-top">
                        <div class="sidebar-inner">
                            
                            <!-- Client Info Box -->
                            <div class="sidebar-widget client-info-widget p-4" style="background:#f9f9f9; border-radius:8px;">
                                <div class="sidebar-title">
                                    <h5>Project Snapshot</h5>
                                </div>
                                <div class="widget-content">
                                    <?php if ($client_logo) : 
                                        $logo_url = is_array($client_logo) ? $client_logo['url'] : $client_logo;
                                    ?>
                                        <div class="client-logo mb-3 text-center">
                                            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($client_name); ?>" style="max-width: 150px;">
                                        </div>
                                    <?php endif; ?>

                                    <ul class="list-style-one company-details">
                                        <?php if ($client_name) : ?>
                                            <li><strong>Client:</strong> <?php echo esc_html($client_name); ?></li>
                                        <?php endif; ?>
                                        <?php if ($industry) : ?>
                                            <li><strong>Industry:</strong> <?php echo esc_html($industry); ?></li>
                                        <?php endif; ?>
                                        <li><strong>Date:</strong> <?php echo esc_html(get_the_date('F Y')); ?></li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </aside>
                </div>
            <?php
                endwhile;
            endif;
            ?>
        </div>
    </div>
</div>

<?php
get_footer();
?>