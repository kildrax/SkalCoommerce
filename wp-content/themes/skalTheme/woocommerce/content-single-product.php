<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * @package SkalTheme
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();
    return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('container mx-auto px-4 py-8', $product); ?>>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        <!-- Galeria del producto -->
        <div>
            <?php
            // Get product gallery images
            $attachment_ids = $product->get_gallery_image_ids();

            // Add featured image as first image
            $featured_image_id = $product->get_image_id();
            if ($featured_image_id) {
                array_unshift($attachment_ids, $featured_image_id);
            }

            // If no images, use placeholder
            if (empty($attachment_ids)) {
                $attachment_ids = array(wc_placeholder_img_src());
            }

            $total_images = count($attachment_ids);
            ?>

            <div class="space-y-4" data-product-gallery>
                <!-- Main Image Display -->
                <div class="relative aspect-square overflow-hidden rounded-lg bg-stone-100">
                    <?php foreach ($attachment_ids as $index => $attachment_id) :
                        $image_url = wp_get_attachment_image_url($attachment_id, 'full');
                        $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                        if (!$image_alt) {
                            $image_alt = $product->get_name() . ' - Image ' . ($index + 1);
                        }
                    ?>
                        <img
                            src="<?php echo esc_url($image_url); ?>"
                            alt="<?php echo esc_attr($image_alt); ?>"
                            class="gallery-main-image w-full h-full object-cover <?php echo $index === 0 ? '' : 'hidden'; ?>"
                            data-image-index="<?php echo $index; ?>">
                    <?php endforeach; ?>

                    <?php if ($total_images > 1) : ?>
                        <!-- Previous Button -->
                        <button
                            type="button"
                            class="gallery-prev-btn inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 shrink-0 outline-none h-8 rounded-md gap-1.5 px-3 absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 backdrop-blur-sm border border-stone-300 text-stone-600 hover:text-stone-800 hover:border-stone-400"
                            aria-label="Previous image">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                                <path d="m15 18-6-6 6-6"></path>
                            </svg>
                        </button>

                        <!-- Next Button -->
                        <button
                            type="button"
                            class="gallery-next-btn inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 shrink-0 outline-none h-8 rounded-md gap-1.5 px-3 absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 backdrop-blur-sm border border-stone-300 text-stone-600 hover:text-stone-800 hover:border-stone-400"
                            aria-label="Next image">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </button>

                        <!-- Image Counter -->
                        <div class="gallery-counter absolute bottom-2 right-2 bg-black/60 text-white px-2 py-1 rounded text-sm">
                            <span class="current-image">1</span> / <span class="total-images"><?php echo $total_images; ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Thumbnail Gallery -->
                <?php if ($total_images > 1) : ?>
                    <div class="flex space-x-2 overflow-x-auto pb-2">
                        <?php foreach ($attachment_ids as $index => $attachment_id) :
                            $thumb_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
                            $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                            if (!$image_alt) {
                                $image_alt = $product->get_name() . ' thumbnail ' . ($index + 1);
                            }
                        ?>
                            <button
                                type="button"
                                class="gallery-thumb-btn flex-shrink-0 w-16 h-16 rounded-md overflow-hidden border-2 transition-all <?php echo $index === 0 ? 'border-teal-600 shadow-md' : 'border-stone-200 hover:border-stone-300'; ?>"
                                data-thumb-index="<?php echo $index; ?>"
                                aria-label="View image <?php echo $index + 1; ?>">
                                <img
                                    src="<?php echo esc_url($thumb_url); ?>"
                                    alt="<?php echo esc_attr($image_alt); ?>"
                                    class="w-full h-full object-cover">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- Informacion del producto -->
        <div class="space-y-6">
            <div>
                <?php
                // Get product categories
                $categories = get_the_terms($product->get_id(), 'product_cat');
                if ($categories && !is_wp_error($categories)) {
                    $category_names = array();
                    foreach ($categories as $category) {
                        $category_names[] = $category->name;
                    }
                    $category_list = implode(', ', $category_names);
                ?>
                    <div data-slot="badge" class="inline-flex items-center justify-center rounded-md border px-2 py-0.5 font-medium w-fit whitespace-nowrap shrink-0 [&amp;&gt;svg]:size-3 gap-1 [&amp;&gt;svg]:pointer-events-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive transition-[color,box-shadow] overflow-hidden [a&amp;]:hover:bg-accent [a&amp;]:hover:text-accent-foreground text-sm text-teal-700 border-teal-200 bg-teal-50 mb-3">
                        <?php echo esc_html($category_list); ?>
                    </div>
                <?php } ?>
                <h1 class="text-3xl text-stone-900 mb-4"><?php echo esc_html($product->get_name()); ?></h1>
                <!-- Rating -->
                <?php
                // Get product rating data
                $average_rating = $product->get_average_rating();
                $rating_count = $product->get_rating_count();
                $review_count = $product->get_review_count();
                ?>

                <div class="flex items-center space-x-1 my-2.5">
                    <!-- Star Rating Display -->
                    <div class="flex items-center space-x-1">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <svg class="w-4 h-4 <?php echo $i <= $average_rating ? 'text-yellow-300' : 'text-gray-200'; ?>" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"></path>
                            </svg>
                        <?php endfor; ?>
                    </div>

                    <!-- Rating Text -->
                    <?php if ($average_rating > 0) : ?>
                        <span class="text-sm text-gray-600 ml-2">
                            <?php echo number_format($average_rating, 1); ?>
                            (<?php echo $review_count; ?> <?php echo $review_count == 1 ? 'reseña' : 'reseñas'; ?>)
                        </span>
                    <?php else : ?>
                        <span class="text-sm text-gray-400 ml-2">Sin reseñas</span>
                    <?php endif; ?>
                </div>
                <p class="text-xl text-teal-700 mb-4"><?php echo $product->get_price_html(); ?></p>
                <p class="text-stone-600 leading-relaxed mb-6"><?php echo wp_kses_post($product->get_short_description()); ?></p>
            </div>
            <!-- Ingredientes y alergenos -->
            <div class="space-y-4">
                <?php
                // Get Ingredientes attribute
                $ingredientes = $product->get_attribute('ingredientes');
                if ($ingredientes) :
                    $ingredientes_array = array_map('trim', explode(',', $ingredientes));
                ?>
                <div>
                    <h3 class="text-lg text-stone-900 mb-2">Ingredientes</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($ingredientes_array as $ingrediente) : ?>
                            <span data-slot="badge" class="inline-flex items-center justify-center rounded-md border px-2 py-0.5 text-xs font-medium w-fit whitespace-nowrap shrink-0 gap-1 focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive transition-[color,box-shadow] overflow-hidden border-transparent bg-stone-100 text-stone-700">
                                <?php echo esc_html($ingrediente); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php
                // Get Alérgenos attribute
                $alergenos = $product->get_attribute('alergenos');
                if ($alergenos) :
                    $alergenos_array = array_map('trim', explode(',', $alergenos));
                ?>
                <div>
                    <h3 class="text-lg text-stone-900 mb-2">Alérgenos</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($alergenos_array as $alergeno) : ?>
                            <span data-slot="badge" class="inline-flex items-center justify-center rounded-md border px-2 py-0.5 text-xs font-medium w-fit whitespace-nowrap shrink-0 gap-1 focus-visible:border-ring focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive transition-[color,box-shadow] overflow-hidden border-transparent focus-visible:ring-destructive/20 dark:focus-visible:ring-destructive/40 dark:bg-destructive/60 bg-red-100 text-red-700">
                                <?php echo esc_html($alergeno); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <!-- Add to cart -->
            <div class="border-t border-stone-200 pt-6">
                <div class="flex items-center space-x-2 justify-start" data-add-to-cart-form data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                    <div class="flex items-center border border-stone-300 rounded-md">
                        <button type="button" data-qty-decrease class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg:not([class*='size-'])]:size-4 shrink-0 [&amp;_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive hover:text-accent-foreground dark:hover:bg-accent/50 rounded-md gap-1.5 has-[&gt;svg]:px-2.5 h-8 w-8 p-0 hover:bg-stone-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus h-3 w-3" aria-hidden="true">
                                <path d="M5 12h14"></path>
                            </svg>
                        </button>
                        <input type="number" data-qty-input data-slot="input" class="file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex min-w-0 rounded-md px-3 py-1 text-base bg-input-background transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive w-12 h-8 text-center border-0 focus:ring-0 text-stone-800" min="1" value="1">
                        <button type="button" data-qty-increase class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg:not([class*='size-'])]:size-4 shrink-0 [&amp;_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive hover:text-accent-foreground dark:hover:bg-accent/50 rounded-md gap-1.5 has-[&gt;svg]:px-2.5 h-8 w-8 p-0 hover:bg-stone-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-3 w-3" aria-hidden="true">
                                <path d="M5 12h14"></path>
                                <path d="M12 5v14"></path>
                            </svg>
                        </button>
                    </div>
                    <button type="button" data-add-to-cart-btn class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg:not([class*='size-'])]:size-4 shrink-0 [&amp;_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive hover:bg-primary/90 h-8 rounded-md gap-1.5 px-3 has-[&gt;svg]:px-2.5 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white">Agregar al carrito</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos que te pueden gustar -->
    <?php
    // Get related products (WooCommerce way)
    $related_ids = wc_get_related_products($product->get_id(), 3); // Get 3 related products
    
    if (!empty($related_ids)) :
    ?>
    <div class="border-t border-stone-200 pt-12">
        <h2 class="text-2xl text-stone-900 mb-8">También te pueden gustar</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($related_ids as $related_id) :
                $related_product = wc_get_product($related_id);
                if (!$related_product) continue;
                
                // Get product data
                $related_image_id = $related_product->get_image_id();
                $related_image_url = $related_image_id ? wp_get_attachment_image_url($related_image_id, 'medium') : wc_placeholder_img_src();
                $related_title = $related_product->get_name();
                $related_price = $related_product->get_price_html();
                $related_link = get_permalink($related_id);
                $related_short_desc = $related_product->get_short_description();
                $related_rating = $related_product->get_average_rating();
                $related_review_count = $related_product->get_review_count();
                
                // Get categories
                $related_categories = get_the_terms($related_id, 'product_cat');
                $related_category_name = '';
                if ($related_categories && !is_wp_error($related_categories)) {
                    $related_category_name = $related_categories[0]->name;
                }
            ?>
            <a href="<?php echo esc_url($related_link); ?>" data-slot="card" class="text-card-foreground flex flex-col gap-6 rounded-xl border overflow-hidden hover:shadow-lg transition-shadow border-stone-200 bg-cream-50 cursor-pointer">
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="<?php echo esc_url($related_image_url); ?>" alt="<?php echo esc_attr($related_title); ?>" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
                <div data-slot="card-header" class="@container/card-header grid auto-rows-min grid-rows-[auto_auto] items-start gap-1.5 px-6 pt-6 has-data-[slot=card-action]:grid-cols-[1fr_auto] [.border-b]:pb-6 pb-2">
                    <div class="flex items-start justify-between gap-2">
                        <h4 data-slot="card-title" class="text-base text-stone-800 leading-tight"><?php echo esc_html($related_title); ?></h4>
                        <?php if ($related_category_name) : ?>
                            <span data-slot="badge" class="inline-flex items-center justify-center rounded-md border px-2 py-0.5 font-medium w-fit whitespace-nowrap [&amp;&gt;svg]:size-3 gap-1 [&amp;&gt;svg]:pointer-events-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive transition-[color,box-shadow] overflow-hidden [a&amp;]:hover:bg-accent [a&amp;]:hover:text-accent-foreground text-xs text-teal-700 border-teal-200 bg-teal-50 shrink-0">
                                <?php echo esc_html($related_category_name); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <p data-slot="card-description" class="text-sm text-stone-600 line-clamp-2">
                        <?php echo $related_short_desc ? wp_kses_post(wp_trim_words($related_short_desc, 15)) : ''; ?>
                    </p>
                    <?php if ($related_rating > 0) : ?>
                        <div class="flex items-center space-x-1 mt-2">
                            <div class="flex items-center space-x-1">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <svg class="w-3 h-3 <?php echo $i <= $related_rating ? 'text-yellow-300' : 'text-gray-200'; ?>" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                        <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"></path>
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <span class="text-sm text-stone-600 ml-2">
                                <?php echo number_format($related_rating, 1); ?>
                                (<?php echo $related_review_count; ?> <?php echo $related_review_count == 1 ? 'reseña' : 'reseñas'; ?>)
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
                <div data-slot="card-footer" class="px-6 pb-6 [.border-t]:pt-6 flex items-center justify-between pt-0">
                    <span class="text-lg text-teal-700"><?php echo $related_price; ?></span>
                    <button data-slot="button" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive hover:bg-primary/90 h-8 rounded-md gap-1.5 px-3 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white">
                        Agregar al carrito
                    </button>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php do_action('woocommerce_after_single_product'); ?>
