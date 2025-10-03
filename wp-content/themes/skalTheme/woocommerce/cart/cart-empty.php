<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
do_action( 'woocommerce_cart_is_empty' );

if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
		<div class="container mx-auto p-4 py-12 max-w-4xl">
			<div class="text-center py-12">
				<svg class="mx-auto h-24 w-24 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
				</svg>
				<h2 class="mt-6 text-2xl font-semibold text-stone-900">Tu carrito está vacío</h2>
				<p class="mt-2 text-stone-600">Agrega algunos productos para comenzar tu compra.</p>
				<a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="mt-6 inline-block px-6 py-3 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition-colors">
					Ir a la tienda
				</a>
			</div>
		</div>
<?php endif; ?>
