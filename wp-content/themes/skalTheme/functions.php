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
        'footer' => __( 'Footer Menu', 'myshop-tailwind' ),
    ) );
}
add_action( 'after_setup_theme', 'myshop_setup' );

// Enqueue scripts and styles
function myshop_enqueue_scripts() {
    // Enqueue Tailwind CSS
    $css_file = get_template_directory() . '/assets/css/tailwind.css';
    if ( file_exists( $css_file ) ) {
        wp_enqueue_style( 'myshop-tailwind', get_template_directory_uri() . '/assets/css/tailwind.css', array(), filemtime( $css_file ) );
    }
    
    // Enqueue main JavaScript
    wp_enqueue_script( 'myshop-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true );
    
    // Enqueue carousel JavaScript
    wp_enqueue_script( 'myshop-carousel', get_template_directory_uri() . '/assets/js/carousel.js', array(), '1.0.0', true );
    
    // Enqueue cart JavaScript
    wp_enqueue_script( 'myshop-cart', get_template_directory_uri() . '/assets/js/cart.js', array('jquery'), '1.0.0', true );
    
    // Enqueue WooCommerce cart fragments for dynamic cart updates
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_script( 'wc-cart-fragments' );
    }
    
    // Localize script for AJAX - for both main.js and cart.js
    wp_localize_script( 'myshop-main', 'wc_add_to_cart_params', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'wc_add_to_cart_nonce' )
    ));
    
    wp_localize_script( 'myshop-cart', 'wc_add_to_cart_params', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'wc_add_to_cart_nonce' )
    ));
    
    // Also localize for cart updates
    wp_localize_script( 'myshop-main', 'skal_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'skal_ajax_nonce' )
    ));
    
    wp_localize_script( 'myshop-cart', 'skal_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'skal_ajax_nonce' )
    ));
}
add_action( 'wp_enqueue_scripts', 'myshop_enqueue_scripts' );

// Disable WooCommerce default styles to prevent conflicts with Tailwind
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Enable AJAX add to cart on shop and archive pages
add_filter( 'woocommerce_loop_add_to_cart_link', 'skal_ajax_add_to_cart_script', 10, 2 );
function skal_ajax_add_to_cart_script( $html, $product ) {
    // Add AJAX add to cart class
    $html = str_replace( 'add_to_cart_button', 'add_to_cart_button ajax_add_to_cart', $html );
    return $html;
}

// Add WooCommerce support
function myshop_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'myshop_add_woocommerce_support' );

// AJAX handler for adding to cart
function ajax_add_to_cart() {
    // Verify nonce for security
    if ( ! wp_verify_nonce( $_POST['nonce'], 'wc_add_to_cart_nonce' ) ) {
        wp_send_json_error( array( 'message' => 'Security check failed' ) );
    }

    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( array( 'message' => 'Product ID missing' ) );
    }

    $product_id = absint( $_POST['product_id'] );
    $quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;

    // Check if product exists
    $product = wc_get_product( $product_id );
    if ( ! $product ) {
        wp_send_json_error( array( 'message' => 'Product not found' ) );
    }

    $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

    if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity ) ) {
        wp_send_json_success( array(
            'message' => 'Product added to cart successfully',
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'product_name' => $product->get_name()
        ));
    } else {
        wp_send_json_error( array(
            'message' => 'Failed to add product to cart'
        ));
    }
}
add_action( 'wp_ajax_woocommerce_add_to_cart', 'ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_woocommerce_add_to_cart', 'ajax_add_to_cart' );

// AJAX handler for getting cart count
function ajax_get_cart_count() {
    wp_send_json_success( array(
        'count' => WC()->cart->get_cart_contents_count()
    ));
}
add_action( 'wp_ajax_get_cart_count', 'ajax_get_cart_count' );
add_action( 'wp_ajax_nopriv_get_cart_count', 'ajax_get_cart_count' );

// Add cart fragments for dynamic cart updates (WooCommerce way)
function skal_add_to_cart_fragments( $fragments ) {
    $cart_count = WC()->cart->get_cart_contents_count();
    
    if ( $cart_count > 0 ) {
        $fragments['.cart-count'] = '<span class="cart-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">' . $cart_count . '</span>';
    } else {
        $fragments['.cart-count'] = '';
    }
    
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'skal_add_to_cart_fragments' );

// Update cart count after cart actions
add_action( 'woocommerce_cart_item_removed', 'refresh_cart_count' );
add_action( 'woocommerce_cart_item_restored', 'refresh_cart_count' );
add_action( 'woocommerce_after_cart_item_quantity_update', 'refresh_cart_count' );

function refresh_cart_count() {
    // This function will trigger when cart is updated
    // The AJAX call will get the fresh count
}


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

// Add custom admin menu
function skal_add_admin_menu() {
    add_menu_page(
        'Administration',           // Page title
        'Administration',           // Menu title
        'manage_options',           // Capability required
        'skal-administration',      // Menu slug
        'skal_administration_page', // Callback function
        'dashicons-admin-generic',  // Icon (you can change this)
        3                          // Position (3 = below Dashboard)
    );
}
add_action( 'admin_menu', 'skal_add_admin_menu' );

// Callback function for the admin page
function skal_administration_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <div class="skal-admin-content">
            <p>Welcome to the Skal Administration panel.</p>
            <!-- Add your custom admin content here -->
        </div>
    </div>
    <?php
}

/**
 * Translate WooCommerce messages to Spanish
 */
function skal_translate_woocommerce_messages( $translated, $text, $domain ) {
    if ( $domain === 'woocommerce' ) {
        $translations = array(
            'removed.' => 'eliminado.',
            'Undo?' => 'Deshacer?',
            'Update cart' => 'Actualizar carrito',
            'Apply coupon' => 'Aplicar cupón',
            'Coupon code' => 'Código de cupón',
            'Coupon:' => 'Cupón:',
            'Remove this item' => 'Eliminar este artículo',
            'Product' => 'Producto',
            'Price' => 'Precio',
            'Quantity' => 'Cantidad',
            'Subtotal' => 'Subtotal',
            'Total' => 'Total',
        );
        
        if ( isset( $translations[ $text ] ) ) {
            return $translations[ $text ];
        }
    }
    
    return $translated;
}
add_filter( 'gettext', 'skal_translate_woocommerce_messages', 20, 3 );

/**
 * Force WooCommerce template for cart page
 */
function skal_force_woocommerce_template( $template ) {
    if ( is_page() ) {
        $page_id = get_queried_object_id();
        $cart_page_id = wc_get_page_id( 'cart' );
        $checkout_page_id = wc_get_page_id( 'checkout' );
        
        // If this is the cart page, use woocommerce.php
        if ( $page_id == $cart_page_id ) {
            $woo_template = locate_template( 'woocommerce.php' );
            if ( $woo_template ) {
                return $woo_template;
            }
        }
        
        // If this is the checkout page, use woocommerce.php
        if ( $page_id == $checkout_page_id ) {
            $woo_template = locate_template( 'woocommerce.php' );
            if ( $woo_template ) {
                return $woo_template;
            }
        }
    }
    
    return $template;
}
add_filter( 'template_include', 'skal_force_woocommerce_template', 99 );
