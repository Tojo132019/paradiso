<?php
/*
 * Template Name: About Us template
 * Description: Template file for the About Us Page Only
 */
 
get_header(); ?>
<!-- ============================= Banner Section ===================== -->
<?php get_template_part( 'template-parts/header-banner');  ?>
<!-- ============================= End: Banner Section ===================== -->
<!-- About Section -->
<section class="about-section">
	<div class="auto-container">
		<!-- Sec Title -->
		<?php if(get_field('why_choose_us_title')): ?>
		<div class="sec-title">
			<div class="title"><?=get_field('why_choose_us_title'); ?></div>
		</div>
		<?php endif; ?>
		<div class="row clearfix">
			<!-- Content Column -->
			<div class="content-column col-lg-6 col-md-12 col-sm-12">
				<div class="inner-column">
					<?php if(get_field('why_choose_us_content')): ?>
						<div class="text"><?=get_field('why_choose_us_content'); ?></div>
					<?php endif; ?>
					<?php if( have_rows('manage_why_choose_us_features') ): ?>
					<div class="blocks-outer">
						<!-- Feature Block -->
						<?php while( have_rows('manage_why_choose_us_features') ): the_row(); ?>
						<div class="feature-block">
							<div class="inner-box">
								<div class="icon"><?=get_sub_field('feature_icon'); ?></div>
								<h6><?=get_sub_field('feature_title'); ?></h6>
								<div class="feature-text"><?=get_sub_field('feature_content'); ?></div>
							</div>
						</div>
						<?php endwhile; ?>
						<!-- Feature Block -->
					</div>
					<?php endif; ?>
				</div>
			</div>
			
			<!-- Images Column -->
			<?php if(get_field('why_choose_us_image')): ?>
			<div class="images-column col-lg-6 col-md-12 col-sm-12">
				<div class="image-wrapper">
					<img src="<?=get_field('why_choose_us_image'); ?>"  border="0" alt="about Image">
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>
<!-- Mission & Vision Section -->
<section class="mission-vision-section">
    <div class="auto-container">
        <div class="row clearfix">
            <!-- Left Content -->
            <div class="content-column col-lg-6 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="sec-title">
                        <h2>
                            <?php echo esc_html(get_field('mission_&_vision_title')); ?>
                        </h2>
                    </div>
                    <!-- Mission -->
                    <div class="mission-box">
                        <div class="icon">
                            <span class="flaticon-target"></span>
                        </div>
                        <div class="content">
                            <h4>
                                <?php echo esc_html(get_field('our_mission_title')); ?>
                            </h4>
                            <?php
                            $mission_content = get_field('our_mission_content');

                            if ($mission_content) {
                                echo wp_kses_post($mission_content);
                            }
                            ?>
                        </div>
                    </div>
                    <!-- Vision -->
                    <div class="mission-box">
                        <div class="icon">
                            <span class="flaticon-eye"></span>
                        </div>
                        <div class="content">
                            <h4>
                                <?php echo esc_html(get_field('our_vision_title')); ?>
                            </h4>
                            <?php
                            $vision_content = get_field('our_vision_content');

                            if ($vision_content) {
                                echo wp_kses_post($vision_content);
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right Image -->
            <div class="image-column col-lg-6 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="image">
                        <?php
                        $mission_image = get_field('upload_mission_vision_image');
                        if ($mission_image) :
                        ?>

                            <img
                                src="<?php echo esc_url($mission_image); ?>"
                                alt="<?php echo esc_attr(get_field('mission_&_vision_title')); ?>"
                            >
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Mission & Vision Section -->
<!-- Company Journey Section -->
<section class="company-journey-section">
    <div class="auto-container">
        <!-- Section Title -->
        <div class="sec-title centered">
            <h2>
                <?php echo esc_html(get_field('company_journey_title')); ?>
            </h2>
            <?php
            $journey_content = get_field('company_journey_content');
            if ($journey_content) :
            ?>
                <div class="text">
                    <?php echo wp_kses_post($journey_content); ?>
                </div>
            <?php endif; ?>
        </div>
        <!-- Timeline -->
        <?php if (have_rows('manage_company_journey')) : ?>
            <div class="journey-timeline">
                <?php while (have_rows('manage_company_journey')) : the_row(); ?>
                    <div class="journey-item">
                        <!-- Year -->
                        <div class="journey-year">
                            <?php
                            echo esc_html(
                                get_sub_field('company_journey_year')
                            );
                            ?>
                        </div>
                        <!-- Content -->
                        <div class="journey-content">
                            <h4>
                                <?php
                                echo esc_html(
                                    get_sub_field('company_journey_heading')
                                );
                                ?>
                            </h4>
                            <p>
                                <?php
                                echo esc_html(
                                    get_sub_field('company_journey_short_content')
                                );
                                ?>
                            </p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<!-- End Company Journey Section -->
<!-- Team Page Section -->
<section class="team-page-section">
    <div class="auto-container">
        <div class="row clearfix">
            <?php if (have_rows('manage_team')) : ?>

                <?php while (have_rows('manage_team')) : the_row(); ?>
                    <?php
                    $team_image = get_sub_field('team_member_image');
                    ?>
                    <!-- Team Block -->
                    <div class="team-block col-lg-3 col-md-6 col-sm-12">
                        <div class="inner-box">
                            <?php if ($team_image) : ?>
                                <div class="image">
                                    <img
                                        src="<?php echo esc_url($team_image); ?>"
                                        alt="<?php echo esc_attr(get_sub_field('team_member_name')); ?>"
                                    >
                                </div>
                            <?php endif; ?>
                            <div class="lower-box">
                                <div class="content">
                                    <h5>
                                        <?php
                                        echo esc_html(
                                            get_sub_field('team_member_name')
                                        );
                                        ?>
                                    </h5>
                                    <div class="designation">
                                        <?php
                                        echo esc_html(
                                            get_sub_field('team_member_designation')
                                        );
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- End Team Page Section -->
<!-- Counter Section -->
<section class="counter-section">
    <div class="auto-container">
        <div class="inner-container">
            <div class="fact-counter">
                <div class="row clearfix">
                    <?php if (have_rows('manage_counter')) : ?>
                        <?php while (have_rows('manage_counter')) : the_row(); ?>
                            <div class="column counter-column col-lg-3 col-md-6 col-sm-12">
                                <div
                                    class="inner wow fadeInLeft"
                                    data-wow-delay="0ms"
                                    data-wow-duration="1500ms"
                                >
                                    <div class="content">
                                        <div class="count-outer count-box">
                                            <span
                                                class="count-text"
                                                data-speed="3000"
                                                data-stop="<?php echo esc_attr(get_sub_field('counter_number')); ?>"
                                            >
                                                0
                                            </span>
                                            +
                                        </div>
                                        <h4 class="counter-title">
                                            <?php
                                            echo esc_html(
                                                get_sub_field('counter_title')
                                            );
                                            ?>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Counter Section -->
<?php get_footer(); ?>
