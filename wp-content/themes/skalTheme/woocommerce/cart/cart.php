<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');
?>

<div class="custom-cart-container">
	<?php if (WC()->cart->is_empty()) : ?>
		<!-- Empty cart message -->
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
	<?php else : ?>
		<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
			<?php do_action('woocommerce_before_cart_table'); ?>
			<section class="">
				<div class="container mx-auto p-4 py-12 max-w-4xl">

					<?php
					// Loop through cart items
					foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
						$_product   = $cart_item['data'];
						$product_id = $cart_item['product_id'];

						if ($_product && $_product->exists() && $cart_item['quantity'] > 0) {
							$product_permalink = $_product->get_permalink($cart_item);
					?>

							<!-- Product Card -->
							<div class="flex flex-col items-end md:flex-row md:items-center md:justify-between rounded-xl border border-stone-200 bg-stone-50/30 p-6 mb-6">
								<button type="button" class="remove-from-cart cursor-pointer mb-2.5 md:mb-0" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
									<svg class="w-6 h-6 md:w-8 md:h-8 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
										<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
									</svg>
								</button>
								<div class="flex flex-col md:flex-row items-center space-x-4 md:w-[500px]">
									<div class="w-full h-64 md:w-[150px] md:h-auto rounded-lg overflow-hidden flex-shrink-0 m-0 md:mr-4">
										<?php
										$thumbnail = $_product->get_image('woocommerce_thumbnail');
										echo $thumbnail;
										?>
									</div>
									<div class="flex-1 mt-2 mb-4 md:my-0 md:mr-2">
										<h3 class="text-2xl text-stone-900 leading-none">
											<?php echo esc_html($_product->get_name()); ?>
										</h3>
										<p class="text-stone-600 text-md md:my-2">
											<?php echo wp_kses_post($_product->get_short_description()); ?>
										</p>
										<span class="text-lg text-teal-700">
											<?php echo WC()->cart->get_product_price($_product); ?>
										</span>
									</div>
								</div>
								<div class="flex items-center justify-end space-x-3 mb-2 md:mb-0">
									<button type="button" class="qty-decrease inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 border bg-white hover:bg-stone-100 h-8 rounded-md gap-1.5 mr-1 px-3 border-stone-300 text-stone-600 hover:text-stone-800 hover:border-stone-400" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus h-4 w-4" aria-hidden="true">
											<path d="M5 12h14"></path>
										</svg>
									</button>
									<?php
									$max_qty = $_product->get_max_purchase_quantity();
									if ($max_qty < 0) {
										$max_qty = 9999; // No limit
									}
									?>
									<input
										type="number"
										name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]"
										value="<?php echo esc_attr($cart_item['quantity']); ?>"
										min="0"
										max="<?php echo esc_attr($max_qty); ?>"
										step="1"
										class="qty-input w-12 text-center text-stone-800 border border-stone-300 rounded-md h-8 mr-1"
										data-cart-key="<?php echo esc_attr($cart_item_key); ?>" />
									<button type="button" class="qty-increase inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 border bg-white hover:bg-stone-100 h-8 rounded-md gap-1.5 px-3 border-stone-300 text-stone-600 hover:text-stone-800 hover:border-stone-400" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4" aria-hidden="true">
											<path d="M5 12h14"></path>
											<path d="M12 5v14"></path>
										</svg>
									</button>
								</div>
								<div class="flex flex-col items-end justify-end">
									<p class="text-stone-600 text-lg">Subtotal</p>
									<span class="text-lg text-stone-900">
										<?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']); ?>
									</span>
								</div>
							</div>

					<?php
						}
					}
					?>

					<!-- Update Cart Button -->
					<div class="flex justify-end mb-4">
						<button type="submit" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>" class="hidden update-cart-btn px-6 py-2 bg-stone-600 text-white rounded-md hover:bg-stone-700 transition-all">
							<?php esc_html_e('Update cart', 'woocommerce'); ?>
						</button>

						<?php do_action('woocommerce_cart_actions'); ?>

						<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
					</div>
					<?php do_action('woocommerce_after_cart_table'); ?>

					<!-- Total amount and place order card -->
					<div class="flex flex-col gap-6 rounded-xl border border-teal-200 bg-teal-50">
						<div class="p-6">
							<div class="flex items-center justify-between text-xl">
								<span class="text-stone-900">Total:</span>
								<span class="text-teal-700"><?php echo WC()->cart->get_cart_total(); ?></span>
							</div>
							<div class="h-px w-full bg-stone-200 my-4"></div>
							<button class="w-full h-10 rounded-md px-6 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white font-medium transition-all">
								Colocar Orden
							</button>
						</div>
					</div>
				</div>
			</section>
		</form>
	<?php endif; ?>
</div>

<?php do_action('woocommerce_after_cart'); ?>