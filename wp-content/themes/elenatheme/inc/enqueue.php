<?php
add_action('wp_enqueue_scripts', 'em_scripts');
function em_scripts()
{
    $ver = '1.1.2';
    /**
     * Регистрация стилей
     */
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Arvo:ital,wght@0,400;1,700&family=Assistant&family=Roboto:wght@400;700&display=swap', array(), null);
    wp_enqueue_style('em-style', get_template_directory_uri() . '/assets/css/style.css', array(), $ver);
    wp_enqueue_style('em-landing', get_template_directory_uri() . '/assets/css/landing.css', array(), $ver);
    wp_enqueue_style('em-landing-select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), $ver);

    /**
     * Регистрация скриптов
     */
    if (is_page_template('template/template-landing.php')) {
        wp_enqueue_script('em-landing-jquery', 'https://code.jquery.com/jquery-3.6.3.min.js', array(), $ver, true);
        wp_enqueue_script('em-landing-select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array(), $ver, true);
        wp_enqueue_script('em-landing', get_template_directory_uri() . '/assets/js/landing.js', array(), $ver, true);
        wp_enqueue_script('em-landing-form', get_template_directory_uri() . '/assets/js/landing-form.js', array(), $ver, true);
    }
}
