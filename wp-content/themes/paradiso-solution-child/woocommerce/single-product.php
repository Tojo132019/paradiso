<?php
/**
 * Custom WooCommerce Single Product Template
 *
 * @package Paradiso_Solution_Child
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

while ( have_posts() ) :
    the_post();

    global $product;

    if ( ! $product || ! $product->is_visible() ) {
        continue;
    }

    $featured_image_id = $product->get_image_id();
    $gallery_image_ids = $product->get_gallery_image_ids();

    // Combine featured image & gallery thumbnails into a single list
    $all_image_ids = array();
    if ( $featured_image_id ) {
        $all_image_ids[] = $featured_image_id;
    }
    if ( ! empty( $gallery_image_ids ) ) {
        $all_image_ids = array_merge( $all_image_ids, $gallery_image_ids );
    }
    $all_image_ids = array_unique( $all_image_ids );
?>

<main class="custom-product-page">

    <div class="auto-container custom-product-container">

        <!-- Breadcrumb -->
        <div class="custom-product-breadcrumb">
            <?php woocommerce_breadcrumb(); ?>
        </div>

        <div class="custom-product-layout row clearfix">

            <!-- =========================
                 LEFT: PRODUCT GALLERY
                 ========================= -->
            <div class="custom-product-gallery col-lg-6 col-md-12 col-sm-12">

                <!-- Main Featured Image -->
                <div class="custom-product-main-image">
                    <?php
                    if ( $featured_image_id ) {
                        echo wp_get_attachment_image(
                            $featured_image_id,
                            'woocommerce_single',
                            false,
                            array(
                                'class' => 'custom-main-product-image',
                                'id'    => 'main-product-img',
                            )
                        );
                    } else {
                        echo wc_placeholder_img(
                            'woocommerce_single',
                            array(
                                'class' => 'custom-main-product-image',
                                'id'    => 'main-product-img',
                            )
                        );
                    }
                    ?>
                </div>

                <!-- Gallery Thumbnails -->
                <?php if ( count( $all_image_ids ) > 1 ) : ?>
                    <div class="custom-product-thumbnails">
                        <?php foreach ( $all_image_ids as $index => $attachment_id ) : 
                            $full_src = wp_get_attachment_image_url( $attachment_id, 'woocommerce_single' );
                        ?>
                            <div class="custom-product-thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" data-full-src="<?php echo esc_url( $full_src ); ?>">
                                <?php
                                echo wp_get_attachment_image(
                                    $attachment_id,
                                    'woocommerce_thumbnail',
                                    false,
                                    array( 'alt' => $product->get_name() )
                                );
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>


            <!-- =========================
                 RIGHT: PRODUCT DETAILS
                 ========================= -->
            <div class="custom-product-details col-lg-6 col-md-12 col-sm-12">

                <!-- Product Title -->
                <h1 class="custom-product-title">
                    <?php the_title(); ?>
                </h1>

                <!-- Rating -->
                <?php if ( $product->get_review_count() > 0 ) : ?>
                    <div class="custom-product-rating">
                        <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                        <span class="review-count">
                            (<?php printf( esc_html( _n( '%s review', '%s reviews', $product->get_review_count(), 'paradiso' ) ), esc_html( $product->get_review_count() ) ); ?>)
                        </span>
                    </div>
                <?php endif; ?>

                <!-- Price -->
                <div class="custom-product-price">
                    <?php echo $product->get_price_html(); ?>
                </div>

                <!-- Short Description -->
                <?php if ( $product->get_short_description() ) : ?>
                    <div class="custom-product-short-description">
                        <?php echo wpautop( wp_kses_post( $product->get_short_description() ) ); ?>
                    </div>
                <?php endif; ?>

                <!-- Add To Cart Form (Simple & Variable Products) -->
                <div class="custom-add-to-cart">
                    <?php
                    /**
                     * Handles: Simple/Variable products, Variations dropdowns, Quantity & Add to Cart button
                     */
                    do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' );
                    ?>
                </div>

                <!-- Product Meta (SKU, Categories, Tags) -->
                <div class="custom-product-meta">

                    <?php if ( $product->get_sku() ) : ?>
                        <div class="meta-item">
                            <span class="meta-label">SKU:</span>
                            <span class="meta-value"><?php echo esc_html( $product->get_sku() ); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="meta-item">
                        <span class="meta-label">Category:</span>
                        <span class="meta-value"><?php echo wc_get_product_category_list( $product->get_id() ); ?></span>
                    </div>

                    <?php if ( $product->get_tag_ids() ) : ?>
                        <div class="meta-item">
                            <span class="meta-label">Tags:</span>
                            <span class="meta-value"><?php echo wc_get_product_tag_list( $product->get_id() ); ?></span>
                        </div>
                    <?php endif; ?>

                </div>

            </div>

        </div>


        <!-- =========================
             PRODUCT DESCRIPTION TABS
             ========================= -->
        <section class="custom-product-description">
            <div class="custom-product-tabs">
                <?php woocommerce_output_product_data_tabs(); ?>
            </div>
        </section>


        <!-- =========================
             RELATED PRODUCTS
             ========================= -->
        <section class="custom-related-products">
            <?php woocommerce_output_related_products(); ?>
        </section>

    </div>

</main>

<!-- Vanilla JS for Interactive Thumbnail Image Switcher -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mainImg = document.getElementById('main-product-img');
    const thumbnails = document.querySelectorAll('.custom-product-thumbnail');
    
    if (mainImg && thumbnails.length > 0) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                const newSrc = this.getAttribute('data-full-src');
                if (newSrc) {
                    mainImg.src = newSrc;
                    if (mainImg.srcset) {
                        mainImg.srcset = '';
                    }
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
    }
});
</script>

<?php

endwhile;

get_footer( 'shop' );