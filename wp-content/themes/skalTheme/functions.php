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

// ============================================
// AJAX ADD TO CART HANDLER
// ============================================
function skal_ajax_add_to_cart() {
    // Verify nonce for security
    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( array( 'message' => 'Product ID is required' ) );
    }
    
    $product_id = absint( $_POST['product_id'] );
    $quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
    
    if ( $quantity < 1 ) {
        $quantity = 1;
    }
    
    // Add product to cart
    $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
    
    if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity ) ) {
        do_action( 'woocommerce_ajax_added_to_cart', $product_id );
        
        wp_send_json_success( array(
            'message' => 'Product added to cart',
            'cart_hash' => WC()->cart->get_cart_hash(),
        ) );
    } else {
        wp_send_json_error( array( 'message' => 'Failed to add product to cart' ) );
    }
}
add_action( 'wp_ajax_woocommerce_ajax_add_to_cart', 'skal_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'skal_ajax_add_to_cart' );

// ============================================
// CUSTOM ORDER PROCESSING WITH CUSTOMER DATA
// ============================================
function skal_process_custom_order() {
    // Verify nonce for security
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'woocommerce-cart' ) ) {
        wp_send_json_error( array( 'message' => 'Security check failed' ) );
    }

    // Check if customer data is provided
    if ( ! isset( $_POST['customer_data'] ) ) {
        wp_send_json_error( array( 'message' => 'Customer data is required' ) );
    }

    // Decode customer data
    $customer_data = json_decode( stripslashes( $_POST['customer_data'] ), true );
    
    if ( ! $customer_data ) {
        wp_send_json_error( array( 'message' => 'Invalid customer data' ) );
    }

    // Validate required fields
    $required_fields = array( 'nombre', 'apellido', 'celular', 'zona', 'direccion' );
    foreach ( $required_fields as $field ) {
        if ( empty( $customer_data[ $field ] ) ) {
            wp_send_json_error( array( 'message' => 'Todos los campos son requeridos' ) );
        }
    }

    // Check if cart is empty
    if ( WC()->cart->is_empty() ) {
        wp_send_json_error( array( 'message' => 'El carrito está vacío' ) );
    }

    try {
        // Search for existing customer by phone number
        $customer_id = 0;
        $celular = sanitize_text_field( $customer_data['celular'] );
        
        // Search in user meta for existing customer with this phone
        $existing_users = get_users( array(
            'meta_key' => 'billing_phone',
            'meta_value' => $celular,
            'number' => 1
        ) );
        
        if ( ! empty( $existing_users ) ) {
            // Customer exists, use their ID
            $customer_id = $existing_users[0]->ID;
            $customer = new WC_Customer( $customer_id );
            
            // Update customer information with latest data
            $customer->set_first_name( sanitize_text_field( $customer_data['nombre'] ) );
            $customer->set_last_name( sanitize_text_field( $customer_data['apellido'] ) );
            $customer->set_billing_phone( $celular );
            $customer->set_billing_address_1( sanitize_textarea_field( $customer_data['direccion'] ) );
            $customer->set_billing_city( 'Bogotá' );
            $customer->set_billing_state( sanitize_text_field( $customer_data['zona'] ) );
            $customer->set_billing_country( 'CO' );
            $customer->save();
        } else {
            // Create new customer
            $customer = new WC_Customer();
            $customer->set_first_name( sanitize_text_field( $customer_data['nombre'] ) );
            $customer->set_last_name( sanitize_text_field( $customer_data['apellido'] ) );
            $customer->set_billing_phone( $celular );
            $customer->set_billing_address_1( sanitize_textarea_field( $customer_data['direccion'] ) );
            $customer->set_billing_city( 'Bogotá' );
            $customer->set_billing_state( sanitize_text_field( $customer_data['zona'] ) );
            $customer->set_billing_country( 'CO' );
            
            // Generate email based on phone number (required by WooCommerce)
            $customer->set_email( $celular . '@guest.local' );
            
            // Generate username based on phone
            $customer->set_username( 'guest_' . $celular );
            
            // Save customer
            $customer_id = $customer->save();
        }
        
        // Create the order and associate with customer
        $order = wc_create_order( array( 'customer_id' => $customer_id ) );

        // Add cart items to order
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            $product = wc_get_product( $product_id );
            
            if ( $product ) {
                $order->add_product( $product, $quantity );
            }
        }

        // Set billing information
        $order->set_billing_first_name( sanitize_text_field( $customer_data['nombre'] ) );
        $order->set_billing_last_name( sanitize_text_field( $customer_data['apellido'] ) );
        $order->set_billing_phone( $celular );
        $order->set_billing_address_1( sanitize_textarea_field( $customer_data['direccion'] ) );
        $order->set_billing_city( 'Bogotá' );
        $order->set_billing_state( sanitize_text_field( $customer_data['zona'] ) );
        $order->set_billing_country( 'CO' );
        $order->set_billing_email( $celular . '@guest.local' );

        // Set shipping information (same as billing)
        $order->set_shipping_first_name( sanitize_text_field( $customer_data['nombre'] ) );
        $order->set_shipping_last_name( sanitize_text_field( $customer_data['apellido'] ) );
        $order->set_shipping_address_1( sanitize_textarea_field( $customer_data['direccion'] ) );
        $order->set_shipping_city( 'Bogotá' );
        $order->set_shipping_state( sanitize_text_field( $customer_data['zona'] ) );
        $order->set_shipping_country( 'CO' );

        // Add custom meta data for zona
        $order->update_meta_data( '_zona_bogota', sanitize_text_field( $customer_data['zona'] ) );
        $order->update_meta_data( '_celular_cliente', sanitize_text_field( $customer_data['celular'] ) );
        
        // Add tratamiento de datos if provided
        if ( ! empty( $customer_data['tratamiento_datos'] ) ) {
            $order->update_meta_data( '_tratamiento_datos_aceptado', 'Si' );
            $order->update_meta_data( '_tratamiento_datos_fecha', current_time( 'mysql' ) );
        }
        
        // Set Origin to "Web"
        $order->update_meta_data( 'Origin', 'Web' );

        // Calculate totals
        $order->calculate_totals();

        // Set order status to pending payment
        $order->set_status( 'pending', 'Orden creada desde la web - Pendiente de pago' );

        // Save the order
        $order->save();
        
        // Register order with WooCommerce Analytics
        if ( function_exists( 'wc_admin_record_order' ) ) {
            wc_admin_record_order( $order->get_id() );
        }
        
        // Trigger order created action for Analytics
        do_action( 'woocommerce_new_order', $order->get_id() );

        // Add order note with customer details
        $tratamiento_texto = ! empty( $customer_data['tratamiento_datos'] ) ? 'Sí - ' . current_time( 'Y-m-d H:i:s' ) : 'No aceptado';
        $order_note = sprintf(
            'Información de entrega:<br>Nombre: %s %s<br>Celular: %s<br>Zona: %s<br>Dirección: %s<br>Tratamiento de datos: %s',
            $customer_data['nombre'],
            $customer_data['apellido'],
            $customer_data['celular'],
            $customer_data['zona'],
            $customer_data['direccion'],
            $tratamiento_texto
        );
        $order->add_order_note( $order_note );

        // Empty the cart
        WC()->cart->empty_cart();

        // Send success response
        wp_send_json_success( array(
            'message' => 'Orden creada exitosamente',
            'order_id' => $order->get_id(),
            'redirect_url' => home_url( '/gracias/?order_id=' . $order->get_id() )
        ) );

    } catch ( Exception $e ) {
        wp_send_json_error( array( 'message' => 'Error al crear la orden: ' . $e->getMessage() ) );
    }
}
add_action( 'wp_ajax_process_custom_order', 'skal_process_custom_order' );
add_action( 'wp_ajax_nopriv_process_custom_order', 'skal_process_custom_order' );

// ============================================
// WOOCOMMERCE ANALYTICS SETUP (OPCIONAL)
// ============================================

/**
 * Sincroniza órdenes existentes con WooCommerce Analytics
 * Solo necesario ejecutar UNA VEZ si tienes órdenes antiguas
 */
function skal_setup_woocommerce_analytics() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    if ( ! function_exists( 'wc_admin_get_feature_config' ) ) {
        return;
    }
    
    // Crear tablas de Analytics si no existen
    $install_class = '\Automattic\WooCommerce\Admin\Install';
    if ( class_exists( $install_class ) ) {
        call_user_func( array( $install_class, 'create_tables' ) );
    }
    
    // Sincronizar órdenes existentes
    $orders = wc_get_orders( array(
        'limit' => -1,
        'status' => array( 'pending', 'processing', 'completed', 'on-hold' )
    ) );
    
    foreach ( $orders as $order ) {
        if ( function_exists( 'wc_admin_record_order' ) ) {
            wc_admin_record_order( $order->get_id() );
        }
    }
    
    // Mostrar mensaje de éxito
    $order_count = count( $orders );
    add_action( 'admin_notices', function() use ( $order_count ) {
        echo '<div class="notice notice-success is-dismissible"><p>Analytics sincronizado: ' . $order_count . ' órdenes procesadas.</p></div>';
    });
}

// Descomenta la siguiente línea, visita el admin UNA VEZ, luego vuelve a comentarla
//add_action( 'admin_init', 'skal_setup_woocommerce_analytics' );

// ============================================
// AJAX UPDATE CART QUANTITY
// ============================================
function skal_update_cart_quantity_ajax() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'woocommerce-cart' ) ) {
        wp_send_json_error( array( 'message' => 'Security check failed' ) );
    }

    if ( ! isset( $_POST['cart_key'] ) || ! isset( $_POST['quantity'] ) ) {
        wp_send_json_error( array( 'message' => 'Missing required parameters' ) );
    }

    $cart_key = sanitize_text_field( $_POST['cart_key'] );
    $quantity = intval( $_POST['quantity'] );

    // Get cart
    $cart = WC()->cart->get_cart();

    if ( ! isset( $cart[ $cart_key ] ) ) {
        wp_send_json_error( array( 'message' => 'Item not found in cart' ) );
    }

    $old_quantity = $cart[ $cart_key ]['quantity'];

    // Update quantity
    if ( $quantity > 0 ) {
        WC()->cart->set_quantity( $cart_key, $quantity, true );
    } else {
        // Remove item if quantity is 0
        WC()->cart->remove_cart_item( $cart_key );
    }

    // Calculate totals
    WC()->cart->calculate_totals();

    // Get updated values
    $product = wc_get_product( $cart[ $cart_key ]['product_id'] );
    $subtotal = '';
    
    if ( $quantity > 0 && $product ) {
        $subtotal = WC()->cart->get_product_subtotal( $product, $quantity );
    }

    $cart_total = WC()->cart->get_cart_total();
    $cart_count = WC()->cart->get_cart_contents_count();

    wp_send_json_success( array(
        'message' => 'Cart updated successfully',
        'subtotal' => $subtotal,
        'cart_total' => $cart_total,
        'cart_count' => $cart_count,
        'old_quantity' => $old_quantity
    ) );
}
add_action( 'wp_ajax_update_cart_quantity', 'skal_update_cart_quantity_ajax' );
add_action( 'wp_ajax_nopriv_update_cart_quantity', 'skal_update_cart_quantity_ajax' );

// ============================================
// ADD ZONA COLUMN TO ORDERS LIST
// ============================================

// Add custom column to orders list (legacy and HPOS)
add_filter( 'manage_edit-shop_order_columns', 'skal_add_zona_column_to_orders', 20 );
add_filter( 'manage_woocommerce_page_wc-orders_columns', 'skal_add_zona_column_to_orders', 20 );
function skal_add_zona_column_to_orders( $columns ) {
    $new_columns = array();
    
    foreach ( $columns as $column_name => $column_info ) {
        $new_columns[ $column_name ] = $column_info;
        
        // Add Zona column after Order column
        if ( 'order_number' === $column_name ) {
            $new_columns['zona_bogota'] = 'Zona';
        }
    }
    
    return $new_columns;
}

// Populate the Zona column (legacy posts)
add_action( 'manage_shop_order_posts_custom_column', 'skal_populate_zona_column', 10, 2 );
function skal_populate_zona_column( $column, $post_id ) {
    if ( 'zona_bogota' === $column ) {
        $order = wc_get_order( $post_id );
        if ( $order ) {
            $zona = $order->get_meta( '_zona_bogota' );
            if ( $zona ) {
                echo '<span class="zona-badge">' . esc_html( $zona ) . '</span>';
            } else {
                echo '<span style="color: #999;">—</span>';
            }
        }
    }
}

// Populate the Zona column (HPOS)
add_action( 'manage_woocommerce_page_wc-orders_custom_column', 'skal_populate_zona_column_hpos', 10, 2 );
function skal_populate_zona_column_hpos( $column, $order ) {
    if ( 'zona_bogota' === $column ) {
        if ( is_numeric( $order ) ) {
            $order = wc_get_order( $order );
        }
        
        if ( $order ) {
            $zona = $order->get_meta( '_zona_bogota' );
            if ( $zona ) {
                echo '<span class="zona-badge">' . esc_html( $zona ) . '</span>';
            } else {
                echo '<span style="color: #999;">—</span>';
            }
        }
    }
}

// Make the column sortable
add_filter( 'manage_edit-shop_order_sortable_columns', 'skal_zona_column_sortable' );
function skal_zona_column_sortable( $columns ) {
    $columns['zona_bogota'] = 'zona_bogota';
    return $columns;
}

// Add filter dropdown for Zona (compatible with HPOS)
add_action( 'restrict_manage_posts', 'skal_add_zona_filter_to_orders' );
add_action( 'woocommerce_order_list_table_restrict_manage_orders', 'skal_add_zona_filter_to_orders' );
function skal_add_zona_filter_to_orders() {
    global $typenow;
    
    // Check if we're on the orders page (works for both legacy and HPOS)
    $current_screen = get_current_screen();
    if ( ( 'shop_order' === $typenow ) || ( $current_screen && 'woocommerce_page_wc-orders' === $current_screen->id ) ) {
        $zonas = array(
            'Usaquén',
            'Chapinero',
            'Santa Fe',
            'San Cristóbal',
            'Usme',
            'Tunjuelito',
            'Bosa',
            'Kennedy',
            'Fontibón',
            'Engativá',
            'Suba',
            'Barrios Unidos',
            'Teusaquillo',
            'Los Mártires',
            'Antonio Nariño',
            'Puente Aranda',
            'La Candelaria',
            'Rafael Uribe Uribe',
            'Ciudad Bolívar',
            'Sumapaz'
        );
        
        $current_zona = isset( $_GET['zona_filter'] ) ? sanitize_text_field( $_GET['zona_filter'] ) : '';
        
        echo '<select name="zona_filter" id="zona_filter" style="float: none;">';
        echo '<option value="">Todas las zonas</option>';
        
        foreach ( $zonas as $zona ) {
            printf(
                '<option value="%s"%s>%s</option>',
                esc_attr( $zona ),
                selected( $current_zona, $zona, false ),
                esc_html( $zona )
            );
        }
        
        echo '</select>';
    }
}

// Filter orders by Zona (legacy posts)
add_filter( 'parse_query', 'skal_filter_orders_by_zona' );
function skal_filter_orders_by_zona( $query ) {
    global $pagenow, $typenow;
    
    if ( 'edit.php' === $pagenow && 'shop_order' === $typenow && isset( $_GET['zona_filter'] ) && ! empty( $_GET['zona_filter'] ) ) {
        $zona = sanitize_text_field( $_GET['zona_filter'] );
        
        $meta_query = array(
            array(
                'key' => '_zona_bogota',
                'value' => $zona,
                'compare' => '='
            )
        );
        
        $query->set( 'meta_query', $meta_query );
    }
}

// Filter orders by Zona (HPOS compatible)
add_filter( 'woocommerce_order_list_table_prepare_items_query_args', 'skal_filter_orders_by_zona_hpos' );
function skal_filter_orders_by_zona_hpos( $query_args ) {
    if ( isset( $_GET['zona_filter'] ) && ! empty( $_GET['zona_filter'] ) ) {
        $zona = sanitize_text_field( $_GET['zona_filter'] );
        
        $query_args['meta_query'] = array(
            array(
                'key' => '_zona_bogota',
                'value' => $zona,
                'compare' => '='
            )
        );
    }
    
    return $query_args;
}
