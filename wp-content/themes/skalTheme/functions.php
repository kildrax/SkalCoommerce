<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function myshop_setup() {
    load_theme_textdomain( 'myshop-tailwind', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption' ) );
    add_theme_support( 'custom-logo' );

    // Soporte WooCommerce
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'myshop-tailwind' ),
    ) );
}
add_action( 'after_setup_theme', 'myshop_setup' );

function myshop_enqueue_assets() {
    $css_file = get_template_directory() . '/assets/css/tailwind.css';
    if ( file_exists( $css_file ) ) {
        wp_enqueue_style( 'myshop-tailwind', get_template_directory_uri() . '/assets/css/tailwind.css', array(), filemtime( $css_file ) );
    }

    // theme style (optional)
    wp_enqueue_style( 'myshop-style', get_stylesheet_uri(), array('myshop-tailwind'), wp_get_theme()->get('Version') );

    // main JS
    $js_file = get_template_directory() . '/assets/js/main.js';
    if ( file_exists( $js_file ) ) {
        wp_enqueue_script( 'myshop-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), filemtime( $js_file ), true );
    }
}
add_action( 'wp_enqueue_scripts', 'myshop_enqueue_assets' );

// Enqueue Tailwind also for Gutenberg editor
add_action( 'enqueue_block_editor_assets', function(){
    $css_file = get_template_directory() . '/assets/css/tailwind.css';
    if ( file_exists( $css_file ) ) {
        wp_enqueue_style( 'myshop-editor', get_template_directory_uri() . '/assets/css/tailwind.css', array(), filemtime( $css_file ) );
    }
});

function myshop_remove_wc_wrapper() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
}
add_action( 'after_setup_theme', 'myshop_remove_wc_wrapper' );

function myshop_wc_wrapper_start() {
    echo '<main id="main" class="container mx-auto px-4 py-8">';
}

function myshop_wc_wrapper_end() {
    echo '</main>';
}

add_action( 'woocommerce_before_main_content', 'myshop_wc_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'myshop_wc_wrapper_end', 10 );
