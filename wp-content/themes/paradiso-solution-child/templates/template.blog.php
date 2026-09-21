<?php
/*
 * Template Name: Blog template
 * Description: Template file for the Blog Page Only
 */
get_header(); ?>
<!-- ============================= Banner Section ===================== -->
<?php get_template_part( 'template-parts/header-banner');  ?>
<!-- ============================= End: Banner Section ===================== -->

<!-- Sidebar Page Container -->
<div class="sidebar-page-container">
    <div class="auto-container">
        <div class="row clearfix">

            <!-- =========================
                 Content Side (Blog Loop)
            ========================== -->
            <div class="content-side col-lg-8 col-md-12 col-sm-12">
                <div class="blog-classic">
                    <?php
                    $paged = max(1, get_query_var('paged'), get_query_var('page'));

                    $blog_query = new WP_Query(array(
                        'post_type'           => 'post',
                        'post_status'         => 'publish',
                        'posts_per_page'      => 4,
                        'paged'               => $paged,
                        'ignore_sticky_posts' => true,
                    ));

                    if ($blog_query->have_posts()) :
                        while ($blog_query->have_posts()) : $blog_query->the_post();
                            $post_id    = get_the_ID();
                            $post_title = get_the_title();
                            $post_link  = get_permalink();
                            $thumbnail  = has_post_thumbnail($post_id) 
                                ? get_the_post_thumbnail($post_id, 'large', array('alt' => esc_attr($post_title))) 
                                : '<img src="' . esc_url(get_stylesheet_directory_uri() . '/assets/images/no-image.png') . '" alt="image' . esc_attr($post_title) . '">';
                    ?>
                        <!-- News Block Five -->
                        <article class="news-block-five">
                            <div class="inner-box wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                                
                                <!-- Image -->
                                <div class="image">
                                    <a href="<?php echo esc_url($post_link); ?>">
                                        <?php echo $thumbnail; ?>
                                    </a>
                                </div>

                                <!-- Lower Content -->
                                <div class="lower-content">
                                    <!-- Post Date -->
                                    <div class="post-date">
                                        <?php echo esc_html(get_the_date('d')); ?>
                                        <span><?php echo esc_html(strtoupper(get_the_date('M'))); ?></span>
                                    </div>

                                    <!-- Post Title -->
                                    <h4>
                                        <a href="<?php echo esc_url($post_link); ?>">
                                            <?php echo esc_html($post_title); ?>
                                        </a>
                                    </h4>

                                    <!-- Post Excerpt -->
                                    <div class="text">
                                        <?php echo esc_html(wp_trim_words(get_the_excerpt(), 28, '...')); ?>
                                    </div>

                                    <!-- Lower Box -->
                                    <div class="lower-box clearfix">
                                        <div class="pull-left">
                                            <ul class="post-meta">
                                                <li>
                                                    <span class="icon flaticon-user"></span>
                                                    <?php echo esc_html(get_the_author()); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="pull-right">
                                            <a class="read-more" href="<?php echo esc_url($post_link); ?>">
                                                <span class="arrow flaticon-long-arrow-pointing-to-the-right"></span>
                                                Read More
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </article>
                        <!-- End News Block Five -->
                    <?php 
                        endwhile;

                        // Call your custom pagination function
                        pagination($blog_query->max_num_pages);

                        wp_reset_postdata();
                    else : 
                    ?>
                        <!-- No Posts -->
                        <div class="no-posts">
                            <h4>No Posts Found</h4>
                            <p>There are currently no blog posts available.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- End Content Side -->

            <!-- =========================
                 Sidebar Side
            ========================== -->
            <div class="sidebar-side left-sidebar col-lg-4 col-md-12 col-sm-12">
                <aside class="sidebar sticky-top">
                    <div class="sidebar-inner">

                        <!-- Search Widget -->
                        <div class="sidebar-widget search-box">
                            <div class="sidebar-title">
                                <h5>Search :-</h5>
                            </div>
                            <form method="get" action="<?php echo esc_url(home_url('/')); ?>">
                                <div class="form-group">
                                    <input type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Search....." required>
                                    <button type="submit"><span class="icon fa fa-search"></span></button>
                                </div>
                            </form>
                        </div>

                        <!-- Categories Widget -->
                        <div class="sidebar-widget categories-widget">
                            <div class="sidebar-title">
                                <h5>Categories :-</h5>
                            </div>
                            <div class="widget-content">
                                <ul class="blog-cat">
                                    <?php
                                    $categories = get_categories(array('orderby' => 'name', 'order' => 'ASC', 'hide_empty' => true));
                                    if ($categories) :
                                        foreach ($categories as $category) :
                                    ?>
                                        <li>
                                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                                                <?php echo esc_html($category->name); ?>
                                                <span>(<?php echo esc_html($category->count); ?>)</span>
                                            </a>
                                        </li>
                                    <?php 
                                        endforeach;
                                    else :
                                    ?>
                                        <li><span>No categories found.</span></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Recent Posts Widget -->
                        <div class="sidebar-widget popular-posts">
                            <div class="sidebar-title">
                                <h5>Recent Post :-</h5>
                            </div>
                            <div class="widget-content">
                                <?php
                                $recent_posts = new WP_Query(array(
                                    'post_type'           => 'post',
                                    'post_status'         => 'publish',
                                    'posts_per_page'      => 3,
                                    'ignore_sticky_posts' => true,
                                ));

                                if ($recent_posts->have_posts()) :
                                    while ($recent_posts->have_posts()) : $recent_posts->the_post();
                                        $recent_link  = get_permalink();
                                        $recent_title = get_the_title();
                                        $recent_thumb = has_post_thumbnail() 
                                            ? get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('alt' => esc_attr($recent_title))) 
                                            : '<img src="' . esc_url(get_stylesheet_directory_uri() . '/assets/images/no-image.png') . '" alt="' . esc_attr($recent_title) . '">';
                                ?>
                                    <article class="post">
                                        <figure class="post-thumb">
                                            <a href="<?php echo esc_url($recent_link); ?>">
                                                <?php echo $recent_thumb; ?>
                                            </a>
                                            <a href="<?php echo esc_url($recent_link); ?>" class="overlay-box">
                                                <span class="icon fa fa-link"></span>
                                            </a>
                                        </figure>
                                        <div class="text">
                                            <a href="<?php echo esc_url($recent_link); ?>">
                                                <?php echo esc_html($recent_title); ?>
                                            </a>
                                        </div>
                                        <div class="post-info">
                                            <?php echo esc_html(get_the_date('F d, Y')); ?>
                                        </div>
                                    </article>
                                <?php
                                    endwhile;
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </div>
                        </div>

                        <!-- Tags Widget -->
                        <div class="sidebar-widget popular-tags">
                            <div class="sidebar-title">
                                <h5>Tag :-</h5>
                            </div>
                            <div class="widget-content">
                                <?php
                                $tags = get_tags(array('orderby' => 'name', 'order' => 'ASC', 'hide_empty' => true));
                                if ($tags) :
                                    foreach ($tags as $tag) :
                                ?>
                                    <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>">
                                        <?php echo esc_html($tag->name); ?>
                                    </a>
                                <?php
                                    endforeach;
                                else :
                                ?>
                                    <span>No tags found.</span>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </aside>
            </div>
            <!-- End Sidebar Side -->

        </div>
    </div>
</div>

<!-- End Sidebar Page Container -->
<?php get_footer(); ?>
