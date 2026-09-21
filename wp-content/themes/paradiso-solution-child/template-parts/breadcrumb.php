<?php
global $templates;
$post_id = get_the_ID();
$category_type = get_query_var( 'taxonomy' );
$category_slug = get_query_var( 'term' );
?>
<div class="page-breadcrumb">
    <?php
    /*
     * Blog Post / Category
     */
    if ( is_category() || is_single() ) {

        $taxonomy_slug = 'category';

        // Category archive
        if ( is_category() ) {

            $current_category = get_queried_object();

            if ( $current_category && ! empty( $current_category->term_id ) ) {

                echo '<a href="' . esc_url( home_url( '/' ) ) . '">';
                echo esc_html__( 'Home', 'textdomain' );
                echo '</a>';

                echo '<span class="spt"> » </span>';

                // Parent categories
                $ancestors = get_ancestors(
                    $current_category->term_id,
                    $taxonomy_slug
                );

                $ancestors = array_reverse( $ancestors );

                foreach ( $ancestors as $ancestor_id ) {

                    echo '<a href="' . esc_url( get_category_link( $ancestor_id ) ) . '">';
                    echo esc_html( get_cat_name( $ancestor_id ) );
                    echo '</a>';

                    echo '<span class="spt"> » </span>';
                }

                // Current category
                echo '<strong class="inherit-color">';
                echo esc_html( $current_category->name );
                echo '</strong>';
            }
        }

        // Single blog post
        elseif ( is_single() && get_post_type() === 'post' ) {

            echo '<a href="' . esc_url( home_url( '/' ) ) . '">';
            echo esc_html__( 'Home', 'textdomain' );
            echo '</a>';

            echo '<span class="spt"> » </span>';

            // Post category
            $categories = get_the_category();

            if ( ! empty( $categories ) ) {

                $category = $categories[0];

                echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">';
                echo esc_html( $category->name );
                echo '</a>';

                echo '<span class="spt"> » </span>';
            }

            // Current post
            echo '<strong>';
            echo esc_html( get_the_title() );
            echo '</strong>';
        }

    }

    /*
     * Everything Else
     */
    else {

        if ( function_exists( 'simple_breadcrumb' ) ) {
            simple_breadcrumb();
        }
    }

    ?>

</div>

