<?php
/**
 * Template Name: Thanks
 */
?>

<?php get_header(); ?>

<header class="thanks">
    <div class="container">
        <div class="thanks__wrapper">
            <h1><?php the_field('title'); ?></h1>

            <div class="thanks__desc"><?php the_field('subtitle'); ?></div>

            <div class="thanks__gray">
                <?php the_field('text'); ?>
            </div>
            <div class="thanks__links">
                <?php
                if (have_rows('cards')) {
                    while (have_rows('cards')) {
                        the_row();
                        $img = get_sub_field('image');
                        ?>
                        <a class="thanks__links__item" href="<?php the_sub_field('link'); ?>">
                            <p><?php the_sub_field('title'); ?></p>
                            <img src="<?= $img['url']; ?>" alt="<?= $img['alt']; ?>">
                        </a>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="thanks__desc thanks__desc_bottom">
                <?php the_field('text_after_cards_1'); ?>
            </div>
            <div class="thanks__desc thanks__desc_margin_bottom">
                <?php the_field('text_after_cards_2'); ?>
            </div>
            <p class="thanks__logo">
                <?php
                $logo = get_field('logo');
                ?>
                <img src="<?= $logo['url']; ?>" alt="<?= $logo['alt']; ?>">
            </p>
        </div>
    </div>
</header>

<?php get_footer(); ?>
