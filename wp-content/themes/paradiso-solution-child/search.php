<?php
get_header();
?>
<!-- ============================= Banner Section ===================== -->
<?php get_template_part( 'template-parts/header-banner');  ?>
<!-- ============================= End: Banner Section ===================== -->
<div class="sidebar-page-container">
    <div class="auto-container">

        <!-- Search Header -->
        <div class="category-header mb-4">
            <h2 class="title">
                Search Results for: <span>"<?php echo esc_html(get_search_query()); ?>"</span>
            </h2>
        </div>

        <div class="row clearfix">

            <!-- =========================
                 Search Results List
            ========================== -->
            <div class="content-side col-lg-8 col-md-12 col-sm-12">
                <div class="blog-classic">
                    <?php if (have_posts()) : ?>
                        <?php while (have_posts()) : the_post();
                            $post_id    = get_the_ID();
                            $post_title = get_the_title();
                            $post_link  = get_permalink();
                            $thumbnail  = has_post_thumbnail($post_id) 
                                ? get_the_post_thumbnail($post_id, 'large', array('alt' => esc_attr($post_title))) 
                                : '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/news-17.jpg') . '" alt="' . esc_attr($post_title) . '">';
                        ?>
                            <!-- Search Result Item -->
                            <article class="news-block-five">
                                <div class="inner-box wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                                    <div class="image">
                                        <a href="<?php echo esc_url($post_link); ?>">
                                            <?php echo $thumbnail; ?>
                                        </a>
                                    </div>
                                    <div class="lower-content">
                                        <div class="post-date">
                                            <?php echo esc_html(get_the_date('d')); ?>
                                            <span><?php echo esc_html(strtoupper(get_the_date('M'))); ?></span>
                                        </div>
                                        <h4>
                                            <a href="<?php echo esc_url($post_link); ?>">
                                                <?php echo esc_html($post_title); ?>
                                            </a>
                                        </h4>
                                        <div class="text">
                                            <?php echo esc_html(wp_trim_words(get_the_excerpt(), 28, '...')); ?>
                                        </div>
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
                        <?php endwhile; ?>

                        <!-- Pagination -->
                        <?php pagination(); ?>

                    <?php else : ?>
                        <!-- No Results Found Message -->
                        <div class="no-posts">
                            <h4>No Results Found</h4>
                            <p>Sorry, no posts matched your search <strong>"<?php echo esc_html(get_search_query()); ?>"</strong>. Please try searching with different keywords.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- End Content Side -->

            <!-- Sidebar -->
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
                                    endif; 
                                    ?>
                                </ul>
                            </div>
                        </div>

                    </div>
                </aside>
            </div>
            <!-- End Sidebar -->

        </div>
    </div>
</div>

<?php
get_footer();
?>