<?php
/**
 * Custom WooCommerce Shop / Product Archive
 *
 * @package Paradiso_Solution_Child
 */

defined( 'ABSPATH' ) || exit;

get_header();

// WooCommerce loop values.
$current_page = max( 1, absint( get_query_var( 'paged' ) ) );
$total_pages  = max( 1, absint( wc_get_loop_prop( 'total_pages' ) ) );
?>

<div class="sidebar-page-container custom-woocommerce-shop">
    <div class="auto-container">
        <div class="row clearfix">

            <!-- Content Side -->
            <div class="content-side col-lg-8 col-md-12 col-sm-12">
                <div class="shop-section">

                    <!-- Sort By -->
                    <div class="items-sorting">
                        <div class="row clearfix">

                            <div class="results-column col-md-6 col-sm-6 col-xs-12">
                                <h6>
                                    <?php woocommerce_result_count(); ?>
                                </h6>
                            </div>

                            <div class="select-column pull-right col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <?php woocommerce_catalog_ordering(); ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <?php if ( woocommerce_product_loop() ) : ?>

                        <div class="our-shops">
                            <div class="row clearfix">

                                <?php while ( have_posts() ) : the_post(); ?>

                                    <?php
                                    global $product;

                                    if ( ! $product || ! $product->is_visible() ) {
                                        continue;
                                    }

                                    $product_id  = $product->get_id();
                                    $product_url = get_permalink( $product_id );
                                    $image_id    = $product->get_image_id();
                                    ?>

                                    <!-- Shop Item -->
                                    <div <?php wc_product_class( 'single-product-item col-lg-6 col-md-6 col-sm-12 text-center', $product ); ?>>

                                        <!-- Product Image (Clickable Link to Details Page) -->
                                        <div class="img-holder">
                                            <a href="<?php echo esc_url( $product_url ); ?>" class="product-img-link" title="<?php echo esc_attr( $product->get_name() ); ?>">
                                                <?php
                                                if ( $image_id ) {
                                                    echo wp_get_attachment_image(
                                                        $image_id,
                                                        'woocommerce_thumbnail',
                                                        false,
                                                        array(
                                                            'alt'   => $product->get_name(),
                                                            'class' => 'product-image',
                                                        )
                                                    );
                                                } else {
                                                    echo wc_placeholder_img(
                                                        'woocommerce_thumbnail',
                                                        array(
                                                            'class' => 'product-image',
                                                            'alt'   => $product->get_name(),
                                                        )
                                                    );
                                                }
                                                ?>
                                            </a>
                                        </div>

                                        <!-- Product Title / Price / Simple Add to Cart Button -->
                                        <div class="title-holder text-center">

                                            <div class="static-content">
                                                <h3 class="title text-center">
                                                    <a href="<?php echo esc_url( $product_url ); ?>">
                                                        <?php echo esc_html( $product->get_name() ); ?>
                                                    </a>
                                                </h3>

                                                <span class="price">
                                                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                                                </span>
                                            </div>

                                            <!-- Simple Add to Cart Button -->
                                            <div class="add-to-cart-btn-wrapper">
                                                <?php woocommerce_template_loop_add_to_cart(); ?>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- End Shop Item -->

                                <?php endwhile; ?>

                            </div>
                        </div>

                        <!-- Pagination -->
                        <?php if ( $total_pages > 1 ) : ?>
                            <div class="styled-pagination text-center">
                                <ul class="clearfix">

                                    <?php if ( $current_page > 1 ) : ?>
                                        <li class="prev">
                                            <a href="<?php echo esc_url( get_pagenum_link( $current_page - 1 ) ); ?>" aria-label="<?php esc_attr_e( 'Previous page', 'paradiso' ); ?>">
                                                <span class="fa fa-angle-left"></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php
                                    $pagination = paginate_links(
                                        array(
                                            'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                                            'format'    => '?paged=%#%',
                                            'current'   => $current_page,
                                            'total'     => $total_pages,
                                            'type'      => 'array',
                                            'mid_size'  => 2,
                                            'end_size'  => 1,
                                            'prev_next' => false,
                                        )
                                    );

                                    if ( ! empty( $pagination ) ) :
                                        foreach ( $pagination as $page_link ) :
                                            $is_current = false !== strpos( $page_link, 'current' );
                                            ?>
                                            <li<?php echo $is_current ? ' class="active"' : ''; ?>>
                                                <?php echo wp_kses_post( $page_link ); ?>
                                            </li>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>

                                    <?php if ( $current_page < $total_pages ) : ?>
                                        <li class="next">
                                            <a href="<?php echo esc_url( get_pagenum_link( $current_page + 1 ) ); ?>" aria-label="<?php esc_attr_e( 'Next page', 'paradiso' ); ?>">
                                                <span class="fa fa-angle-right"></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                </ul>
                            </div>
                        <?php endif; ?>

                    <?php else : ?>

                        <div class="woocommerce-info">
                            <?php esc_html_e( 'No products found.', 'woocommerce' ); ?>
                        </div>

                    <?php endif; ?>

                </div>
            </div>

            <!-- Sidebar Side -->
            <div class="sidebar-side left-sidebar col-lg-4 col-md-12 col-sm-12">
                <aside class="sidebar sticky-top">
                    <div class="sidebar-inner">

                        <!-- Categories Widget -->
                        <div class="sidebar-widget categories-widget">
                            <div class="sidebar-title">
                                <h5><?php esc_html_e( 'Categories :-', 'paradiso' ); ?></h5>
                            </div>

                            <div class="widget-content">
                                <ul class="blog-cat">
                                    <?php
                                    $categories = get_terms(
                                        array(
                                            'taxonomy'   => 'product_cat',
                                            'hide_empty' => true,
                                            'parent'     => 0,
                                            'orderby'    => 'name',
                                            'order'      => 'ASC',
                                        )
                                    );

                                    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
                                        foreach ( $categories as $category ) :
                                            $category_link = get_term_link( $category );
                                            if ( is_wp_error( $category_link ) ) {
                                                continue;
                                            }
                                            ?>
                                            <li<?php echo is_product_category( $category->term_id ) ? ' class="active"' : ''; ?>>
                                                <a href="<?php echo esc_url( $category_link ); ?>">
                                                    <?php echo esc_html( $category->name ); ?>
                                                    <span>( <?php echo esc_html( $category->count ); ?> )</span>
                                                </a>
                                            </li>
                                            <?php
                                        endforeach;
                                    else :
                                        ?>
                                        <li><?php esc_html_e( 'No categories found.', 'woocommerce' ); ?></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                    </div>
                </aside>
            </div>

        </div>
    </div>
</div>

<?php get_footer(); ?>