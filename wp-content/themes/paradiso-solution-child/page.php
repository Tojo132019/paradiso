<?php
/**
 * Default Page Template
 *
 * File:
 * /wp-content/themes/your-child-theme/page.php
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<!-- ============================= Banner Section ===================== -->
<?php get_template_part( 'template-parts/header-banner');  ?>
<!-- ============================= End: Banner Section ===================== -->
<div class="sidebar-page-container">

    <div class="auto-container">

        <div class="row clearfix">

            <!-- =========================
                 MAIN CONTENT
            ========================== -->

            <div class="content-side col-lg-12 col-md-12 col-sm-12">

                <div class="page-content">

                    <?php
                    while ( have_posts() ) :
                        the_post();
                    ?>

                       


                        <!-- Page Content -->
                        <div class="page-inner-content">

                            <?php
                            the_content();
                            ?>

                        </div>


                        <?php
                        /*
                         * If the page has multiple pages,
                         * display pagination.
                         */
                        wp_link_pages(
                            array(
                                'before' => '<div class="page-links">',
                                'after'  => '</div>',
                            )
                        );
                        ?>


                    <?php endwhile; ?>

                </div>

            </div>



        </div>

    </div>

</div>


<?php
get_footer();
?>