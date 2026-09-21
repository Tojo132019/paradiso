<?php
get_header(); // Includes your header template
?>
<!-- ============================= Banner Section ===================== -->
<?php get_template_part( 'template-parts/header-banner');  ?>
<!-- ============================= End: Banner Section ===================== -->
<div class="sidebar-page-container">
    <div class="auto-container">
        <div class="row clearfix">
            
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    $post_id    = get_the_ID();
                    $post_title = get_the_title();
                    $post_link  = get_permalink();
            ?>
                <!-- Content Side -->
                <div class="content-side col-lg-8 col-md-12 col-sm-12">
                    <div class="news-detail">
                        <div class="inner-box">
                            
                            <!-- Upper Box -->
                            <div class="upper-box">
                                <h3><?php echo esc_html($post_title); ?></h3>
                                <ul class="post-meta">
                                    <li>
                                        <span class="icon flaticon-comment"></span>
                                        <?php comments_number('0 comments', '1 comment', '% comments'); ?>
                                    </li>
                                    <li>
                                        <span class="icon flaticon-user"></span>
                                        <?php echo esc_html(get_the_author()); ?>
                                    </li>
                                </ul>
                            </div>

                            <!-- Featured Image -->
                            <div class="image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large'); ?>
                                <?php else : ?>
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-17.jpg'); ?>" alt="<?php echo esc_attr($post_title); ?>">
                                <?php endif; ?>
                                <div class="post-date">
                                    <?php echo esc_html(get_the_date('d')); ?> 
                                    <span><?php echo esc_html(strtoupper(get_the_date('M'))); ?></span>
                                </div>
                            </div>
                                
                            <!-- Lower Content (Post Body) -->
                            <div class="lower-content">
                                <?php the_content(); ?>
                                
                                <!-- Post Share Options & Tags -->
                                <div class="post-share-options">
                                    <div class="post-share-inner clearfix">
                                        
                                        <!-- Post Tags -->
                                        <div class="pull-left tags">
                                            <?php
                                            $tags = get_the_tags();
                                            if ($tags) :
                                                foreach ($tags as $tag) :
                                            ?>
                                                    <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>">
                                                        <?php echo esc_html($tag->name); ?>
                                                    </a>
                                            <?php
                                                endforeach;
                                            else :
                                                echo '<span>No tags</span>';
                                            endif;
                                            ?>
                                        </div>

                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <!-- WordPress Comments Area -->
                       

                    </div>
                </div>
            <?php
                endwhile;
            endif;
            ?>

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
                                                <span>(<?php echo esc_html(sprintf('%02d', $category->count)); ?>)</span>
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
                                    'post__not_in'        => array(get_the_ID()), // Exclude current viewing post
                                    'ignore_sticky_posts' => true,
                                ));

                                if ($recent_posts->have_posts()) :
                                    while ($recent_posts->have_posts()) : $recent_posts->the_post();
                                        $recent_link  = get_permalink();
                                        $recent_title = get_the_title();
                                        $recent_thumb = has_post_thumbnail() 
                                            ? get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('alt' => esc_attr($recent_title))) 
                                            : '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/post-thumb-5.jpg') . '" alt="' . esc_attr($recent_title) . '">';
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
                                        <div class="post-info"><?php echo esc_html(get_the_date('F d, Y')); ?></div>
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
                                <h5>Tag Cloud :-</h5>
                            </div>
                            <div class="widget-content">
                                <?php
                                $all_tags = get_tags(array('orderby' => 'name', 'order' => 'ASC', 'hide_empty' => true));
                                if ($all_tags) :
                                    foreach ($all_tags as $tag) :
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

<?php
get_footer(); // Includes your footer template
?>