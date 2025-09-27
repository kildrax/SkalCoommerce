<?php
/**
 * Template for Cart Page
 */

get_header(); ?>

<script>
// Add body class for cart page detection
document.body.classList.add('woocommerce-cart');
</script>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center text-stone-900">Carrito de Compras</h1>
        
        <?php
        // Check if WooCommerce is active and cart page content
        if (class_exists('WooCommerce')) {
            echo do_shortcode('[woocommerce_cart]');
        } else {
            echo '<p>WooCommerce no está activo.</p>';
        }
        ?>
    </div>
</main>

<?php get_footer(); ?>
