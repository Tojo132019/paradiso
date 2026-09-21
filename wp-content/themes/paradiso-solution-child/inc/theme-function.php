<?php
global $templates;
/*========== Banner Heading ===============*/
function header_banner_heading() {
    $title = '';
    if ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_404() ) {
        $title = __( 'Error 404', 'textdomain' );
    } elseif ( is_search() ) {
        $title = __( 'Search', 'textdomain' ) . ': ' . get_search_query();
    } elseif ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_archive() ) {
        $title = get_the_archive_title();
    } else {
        if ( function_exists( 'get_field' ) && get_field( 'banner_title' ) ) {
            $title = get_field( 'banner_title' );
        } else {
            $title = get_the_title();
        }
    }
    echo '<h1>' . esc_html( $title ) . '</h1>';
}
/*========= Manage Page Banner Image ======*/
if ( ! function_exists( 'get_page_banner' ) ) {

    function get_page_banner( $post_ID = '' ) {

        // Default banner from Theme Options
        $default_banner = get_field( 'default_banner_image', 'option' );

        // No post ID - return default banner
        if ( empty( $post_ID ) ) {
            return $default_banner;
        }

        // Check current page/post banner
        $banner_image = get_field( 'banner_image', $post_ID );

        if ( $banner_image ) {
            return $banner_image;
        }

        // Check parent page banner
        $parent_page = wp_get_post_parent_id( $post_ID );

        if ( $parent_page > 0 ) {
            return get_page_banner( $parent_page );
        }

        // Fallback to default banner
        return $default_banner;
    }
}

/* ================= bread crumbs =======================*/

if ( ! function_exists( 'simple_breadcrumb' ) ) {

    function simple_breadcrumb() {

        global $post;

        $separator = "<span class='spt'> » </span>";

        // Do not show breadcrumb on front page
        if ( is_front_page() ) {

            bloginfo( 'name' );

            return;
        }

        // Home
        echo '<a href="' . esc_url( home_url( '/' ) ) . '">';
        echo esc_html__( 'Home', 'textdomain' );
        echo '</a>' . $separator;


        /*
         * Search Page
         */
        if ( is_search() ) {

            echo '<strong>';
            echo esc_html__( 'Search', 'textdomain' );
            echo '</strong>';

            return;
        }


        /*
         * 404 Page
         */
        if ( is_404() ) {

            echo '<strong>';
            echo esc_html__( '404', 'textdomain' );
            echo '</strong>';

            return;
        }


        /*
         * Category Archive
         */
        if ( is_category() ) {

            echo '<strong class="inherit-color">';
            single_cat_title();
            echo '</strong>';

            return;
        }


        /*
         * Single Post
         */
        if ( is_single() ) {

            // Post category
            $categories = get_the_category();

            if ( ! empty( $categories ) ) {

                $category = $categories[0];

                echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">';
                echo esc_html( $category->name );
                echo '</a>' . $separator;
            }

            // Post title
            echo '<strong>';
            echo esc_html( get_the_title() );
            echo '</strong>';

            return;
        }


        /*
         * Child Page
         */
        if ( is_page() && ! empty( $post->post_parent ) ) {

            $ancestors = get_post_ancestors( $post->ID );

            // Display ancestors from parent to child
            $ancestors = array_reverse( $ancestors );

            foreach ( $ancestors as $ancestor_id ) {

                echo '<a href="' . esc_url( get_permalink( $ancestor_id ) ) . '">';
                echo esc_html( get_the_title( $ancestor_id ) );
                echo '</a>' . $separator;
            }

            // Current page
            echo '<strong>';
            echo esc_html( get_the_title() );
            echo '</strong>';

            return;
        }


        /*
         * Normal Page
         */
        if ( is_page() ) {

            echo '<strong>';
            echo esc_html( get_the_title() );
            echo '</strong>';

            return;
        }


        /*
         * Other Archives
         */
        if ( is_archive() ) {

            echo '<strong>';
            echo esc_html( get_the_archive_title() );
            echo '</strong>';

            return;
        }


        /*
         * Default
         */
        echo '<strong>';
        echo esc_html( get_the_title() );
        echo '</strong>';
    }
}

//============================ Pagination ==========================================

if ( ! function_exists( 'pagination' ) ) {

    function pagination( $pages = '', $range = 2 ) {

        global $paged, $wp_query;

        // Get current page
        $paged = max( 1, (int) $paged );

        // Get total pages
        if ( empty( $pages ) ) {

            $pages = (int) $wp_query->max_num_pages;

            if ( $pages < 1 ) {
                $pages = 1;
            }
        }

        // No pagination required
        if ( $pages <= 1 ) {
            return;
        }

        // Pagination URLs
        $prev_url = get_pagenum_link( max( 1, $paged - 1 ) );
        $next_url = get_pagenum_link( min( $pages, $paged + 1 ) );

        // Previous button
        $prev_html = '« Prev <span><img src="' .
            esc_url( get_stylesheet_directory_uri() . '/assets/images/back.svg' ) .
            '" alt=""></span>';

        // Next button
        $next_html = '<span><img src="' .
            esc_url( get_stylesheet_directory_uri() . '/assets/images/Next.svg' ) .
            '" alt=""></span> Next »';

        echo '<div class="pagination">';

        /*
         * Previous Button
         */
        if ( $paged > 1 ) {

            echo '<a href="' . esc_url( $prev_url ) . '">';
            echo $prev_html;
            echo '</a>';

        } else {

            echo '<div class="no-element">';
            echo $prev_html;
            echo '</div>';
        }


        /*
         * Page Numbers
         */
        echo '<div class="number-area">';
        echo '<ul>';

        // First page
        if ( ( $paged - $range - 1 ) > 0 ) {

            echo '<li>';
            echo '<a href="' . esc_url( get_pagenum_link( 1 ) ) . '">1</a>';
            echo '</li>';
        }

        // First ellipsis
        if ( ( $paged - $range - 1 ) > 1 ) {

            echo '<li class="dot-dot">...</li>';
        }


        // Pages around current page
        $start = max( 1, $paged - $range );
        $end   = min( $pages, $paged + $range );

        for ( $i = $start; $i <= $end; $i++ ) {

            if ( $paged === $i ) {

                echo '<li id="page-' . esc_attr( $i ) . '" class="active">';
                echo esc_html( $i );
                echo '</li>';

            } else {

                echo '<li>';
                echo '<a href="' . esc_url( get_pagenum_link( $i ) ) . '">';
                echo esc_html( $i );
                echo '</a>';
                echo '</li>';
            }
        }


        // Last ellipsis
        if ( ( $pages - $end ) > 1 ) {

            echo '<li class="dot-dot">...</li>';
        }


        // Last page
        if ( $end < $pages ) {

            if ( $paged === $pages ) {

                echo '<li id="page-' . esc_attr( $pages ) . '" class="active">';
                echo esc_html( $pages );
                echo '</li>';

            } else {

                echo '<li>';
                echo '<a href="' . esc_url( get_pagenum_link( $pages ) ) . '">';
                echo esc_html( $pages );
                echo '</a>';
                echo '</li>';
            }
        }

        echo '</ul>';
        echo '</div>';


        /*
         * Next Button
         */
        if ( $paged < $pages ) {

            echo '<a href="' . esc_url( $next_url ) . '">';
            echo $next_html;
            echo '</a>';

        } else {

            echo '<div class="no-element">';
            echo $next_html;
            echo '</div>';
        }

        echo '</div>';

        echo '<div class="clearfix"></div>';
    }
}

function include_all_post_types_in_search( $query ) {
    if ( $query->is_search && ! is_admin() && $query->is_main_query() ) {
        // Include blog posts, pages, and custom post types in search results
        $query->set( 'post_type', array( 'post', 'page', 'case_studies', 'service' ) ); 
    }
    return $query;
}
add_filter( 'pre_get_posts', 'include_all_post_types_in_search' );
