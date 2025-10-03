<?php
/**
 * WooCommerce Template
 * 
 * This template is used for all WooCommerce pages
 * (Shop, Cart, Checkout, My Account, etc.)
 */

get_header(); ?>

<main id="primary" class="site-main bg-gradient-to-br from-stone-50/30 to-amber-50/40">
    <?php 
    // Check what type of WooCommerce page this is
    if (is_cart()) {
        echo '<!-- This is the cart page -->';
        echo '<!-- Cart items: ' . WC()->cart->get_cart_contents_count() . ' -->';
        
        // Check if the page is using blocks or shortcode
        $cart_page_id = wc_get_page_id('cart');
        $page_content = get_post_field('post_content', $cart_page_id);
        
        echo '<!-- Page content preview: ' . substr(strip_tags($page_content), 0, 100) . '... -->';
        
        // If page has blocks, output the page content
        if (has_blocks($page_content)) {
            echo '<!-- Using WooCommerce Blocks -->';
            while (have_posts()) {
                the_post();
                the_content();
            }
        } else {
            // Otherwise use our custom template
            echo '<!-- Using custom cart template -->';
            $template_path = get_template_directory() . '/woocommerce/cart/cart.php';
            if (file_exists($template_path)) {
                include($template_path);
            } else {
                wc_get_template('cart/cart.php');
            }
        }
        
    } elseif (is_checkout()) {
        woocommerce_content();
    } elseif (is_account_page()) {
        woocommerce_content();
    } else {
        // For shop and other pages
        woocommerce_content();
    }
    ?>
</main>

<?php get_footer(); ?>
