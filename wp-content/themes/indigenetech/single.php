<?php
/**
 * Single Post Template
 *
 * @package appzend
 */

get_header(); ?>

<div class="container">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <div class="single-wrapper">

        <!-- LEFT 70% -->
        <div class="single-content">

            <!-- Featured Image -->
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="featured-image">
                    <?php the_post_thumbnail('full'); ?>
                </div>
            <?php endif; ?>

            <!-- Title -->
            <h1><?php the_title(); ?></h1>

            <!-- Published Date -->
            <span class="post-date">
                <?php echo get_the_date('F j, Y'); ?>
            </span>

            <!-- Content -->
            <div class="post-content">
                <?php the_content(); ?>
            </div>

        </div>

        <!-- RIGHT 30% -->
        <div class="single-sidebar">
            <div class="sticky-form">
                <h4>Start Your Digital Journey with IndigeneTech.</h4>
                <p>Fill your details and our specialists will get in touch within 24 hours</p>

                <?php echo do_shortcode('[hubspot_vertical_form]'); ?>
            </div>
        </div>

    </div>

<?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>

