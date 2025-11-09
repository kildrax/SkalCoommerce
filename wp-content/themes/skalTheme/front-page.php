<?php

/**
 * Template Name: Front Page
 *
 * @package skalTheme
 */

get_header(); ?>

<main id="main" class="py-10 md:pt-0 bg-gradient-to-br from-stone-50/30 to-amber-50/40">

  <?php while (have_posts()) : the_post(); ?>
    <section class="flex align-center justify-center w-full md:h-[calc(100vh-155px)] h-auto ">
      <div class="md:w-1/2 w-full">
        <div class="flex flex-col items-center justify-center md:w-[600px] w-full px-6 m-auto h-full">
          <h1 class="text-4xl md:text-6xl font-bold mb-6 text-stone-900"><?php the_field('titulo_banner'); ?></h1>
          <p class="text-xl md:text-2xl mb-2 text-stone-600"><?php the_field('parrafo_banner'); ?></p>
        </div>
      </div>

      <img class="md:w-1/2 object-cover hidden md:block" src="<?php the_field('imagen_banner'); ?>" />
    </section>
  <?php endwhile; ?>

  <!-- Special holydays -->
  <?php
  // Get the latest product from "especial" category
  $special_products = wc_get_products([
    'status' => 'publish',
    'limit'  => 1,
    'orderby' => 'date',
    'order' => 'DESC',
    'category' => ['especial'],
  ]);

  if (!empty($special_products)) :
    $product = $special_products[0];
    
    // Get product data using WooCommerce methods
    $product_id = $product->get_id();
    $product_name = $product->get_name();
    $product_description = $product->get_description();
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    $price = $product->get_price();
    $stock_quantity = $product->get_stock_quantity();
    $is_in_stock = $product->is_in_stock();
    
    // Get product image using WooCommerce methods
    $image_id = $product->get_image_id();
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : wc_placeholder_img_src();
  ?>
  <div class="mb-4 mx-auto p-4 mt-8 max-w-7xl">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-red-900/95 via-green-900/95 to-red-900/95 shadow-2xl border-2 border-white/10 animate-gradient">
      <div class="relative z-10 p-4 lg:p-14">
        <div class="text-center mb-5">
          <div class="inline-flex mb-2">
            <span data-slot="badge" class="inline-flex items-center justify-center rounded-md font-medium w-fit shrink-0 [&amp;&gt;svg]:size-3 gap-1 [&amp;&gt;svg]:pointer-events-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive transition-[color,box-shadow] overflow-hidden [a&amp;]:hover:bg-primary/90 bg-red-50/20 border-red-200/40 text-red-50 border-2 px-6 py-2.5 text-base text-center">⏰ Por Tiempo Limitado <?php if ($stock_quantity): echo '• ' . $stock_quantity . ' disponibles'; endif; ?></span>
          </div>
          <h2 class="text-4xl lg:text-6xl mb-4 text-white drop-shadow-lg"><?php echo esc_html($product_name); ?></h2>
          <?php if ($product_description): ?>
          <p class="text-xl lg:text-2xl text-white opacity-95 max-w-3xl mx-auto"><?php echo wp_kses_post($product_description); ?></p>
          <?php endif; ?>
        </div>
        <div class="max-w-6xl mx-auto">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div class="relative">
              <div class="relative rounded-2xl overflow-hidden shadow-2xl border-4 border-white/20">
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product_name); ?>" class="w-full aspect-square object-cover">
              </div>
              <div class="absolute -top-4 -left-4 animate-sparkle-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles h-12 w-12 text-white drop-shadow-lg" aria-hidden="true">
                  <path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"></path>
                  <path d="M20 2v4"></path>
                  <path d="M22 4h-4"></path>
                  <circle cx="4" cy="20" r="2"></circle>
                </svg>
              </div>
              <div class="absolute -bottom-4 -right-4 animate-sparkle-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles h-12 w-12 text-white drop-shadow-lg" aria-hidden="true">
                  <path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"></path>
                  <path d="M20 2v4"></path>
                  <path d="M22 4h-4"></path>
                  <circle cx="4" cy="20" r="2"></circle>
                </svg>
              </div>
            </div>
            <div class="">
              <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border-2 border-white/20">
                <h3 class="text-3xl mb-6 text-white">Que incluye:</h3>
                <div class="space-y-4 mb-8">
                  <div class="flex items-start space-x-4 bg-white/10 rounded-xl p-4 border border-white/10" style="opacity: 1; transform: none;">
                    <div class="text-4xl flex-shrink-0">👨‍🍳</div>
                    <div>
                      <h4 class="text-lg text-white mb-1">Delantal de Skål Personalizado</h4>
                      <p class="text-white opacity-80 text-sm">Delantal de Skål con logotipo personalizado</p>
                    </div>
                  </div>
                  <div class="flex items-start space-x-4 bg-white/10 rounded-xl p-4 border border-white/10" style="opacity: 1; transform: none;">
                    <div class="text-4xl flex-shrink-0">🎁</div>
                    <div>
                      <h4 class="text-lg text-white mb-1">Caja de Brownies Navideño</h4>
                      <p class="text-white opacity-80 text-sm">Caja de brownies navideño con 6 brownies de diferentes sabores</p>
                    </div>
                  </div>
                  <div class="flex items-start space-x-4 bg-white/10 rounded-xl p-4 border border-white/10" style="opacity: 1; transform: none;">
                    <div class="text-4xl flex-shrink-0">📜</div>
                    <div>
                      <h4 class="text-lg text-white mb-1">Tarjeta con Receta Navideña</h4>
                      <p class="text-white opacity-80 text-sm">Tarjeta con receta navideña con mensaje de "Feliz Navidad"</p>
                    </div>
                  </div>
                  <div class="flex items-start space-x-4 bg-white/10 rounded-xl p-4 border border-white/10" style="opacity: 1; transform: none;">
                    <div class="text-4xl flex-shrink-0">🍯</div>
                    <div>
                      <h4 class="text-lg text-white mb-1">Botella de Syrup Artesanal</h4>
                      <p class="text-white opacity-80 text-sm">Botella de syrup artesanal para el perfecto final de receta</p>
                    </div>
                  </div>
                </div>
                <div class="border-t-2 border-white/20 pt-6">
                  <div class="flex items-center justify-between mb-6">
                    <span class="text-xl text-white opacity-90">Precio del combo:</span>
                    <div class="text-right">
                      <?php if ($sale_price): ?>
                        <div class="text-xl text-white/60 line-through"><?php echo wc_price($regular_price); ?></div>
                        <span class="text-2xl text-white drop-shadow-lg"><?php echo wc_price($sale_price); ?></span>
                      <?php else: ?>
                        <span class="text-2xl text-white drop-shadow-lg"><?php echo wc_price($price); ?></span>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div tabindex="0" style="transform: none;">
                    <form class="ajax-add-to-cart-special" method="post" enctype="multipart/form-data" data-product-id="<?php echo esc_attr($product_id); ?>">
                      <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">
                      <input type="hidden" name="quantity" value="1">
                      <button type="submit" <?php echo !$is_in_stock ? 'disabled' : ''; ?> class="special-add-to-cart-btn inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg:not([class*='size-'])]:size-4 shrink-0 [&amp;_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive rounded-md px-6 has-[&gt;svg]:px-4 w-full h-16 text-xl bg-white hover:bg-stone-50 text-stone-900 shadow-2xl transition-all duration-300 cursor-pointer">
                        <?php if ($is_in_stock): ?> 
                          🛒 Agregar combo al carrito
                        <?php else: ?>
                          ❌ Agotado
                        <?php endif; ?>
                      </button>
                    </form>
                    </div>
                  <!-- Notification toast -->
                  <div id="cart-notification" class="hidden fixed top-20 right-4 bg-green-600 text-white px-6 py-4 rounded-lg shadow-2xl z-50 animate-slide-in">
                    <div class="flex items-center gap-3">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      <span class="font-medium">¡Producto agregado al carrito!</span>
                    </div>
                  </div>
                  <?php if ($is_in_stock && $stock_quantity && $stock_quantity <= 10): ?>
                  <p class="text-center mt-4 text-white opacity-75 text-sm">⏰ Solo quedan <?php echo $stock_quantity; ?> unidades • ¡Pide antes de que se agoten!</p>
                  <?php elseif ($is_in_stock): ?>
                  <p class="text-center mt-4 text-white opacity-75 text-sm">⏰ Cantidad limitada • ¡Pide antes de que se agoten!</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center mt-5">
          <div class="inline-flex items-center space-x-2 text-white opacity-90 text-md bg-white/10 px-6 py-3 rounded-full border border-white/20"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles h-5 w-5" aria-hidden="true">
              <path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"></path>
              <path d="M20 2v4"></path>
              <path d="M22 4h-4"></path>
              <circle cx="4" cy="20" r="2"></circle>
            </svg>
            <span>¡Perfecto para regalar o para ti mismo(a)! 🎁</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles h-5 w-5" aria-hidden="true">
              <path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"></path>
              <path d="M20 2v4"></path>
              <path d="M22 4h-4"></path>
              <circle cx="4" cy="20" r="2"></circle>
            </svg></div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Brownies -->
  <section id="brownies" class="mx-auto p-4 mt-8 max-w-7xl">
    <h2 class="text-3xl font-bold mb-8 text-center text-stone-900">Brownies</h2>
    <!-- Carousel Container -->
    <div class="relative mx-auto">
      <!-- Carousel Wrapper -->
      <div class="carousel-container overflow-hidden rounded-lg md:rounded-none bg-white md:bg-transparent">
        <div class="carousel-track flex transition-transform duration-300 ease-in-out" id="carouselTrack">

          <?php
          // Get products from brownies category
          $products = wc_get_products([
            'status' => 'publish',
            'limit'  => -1, // Get all products
            'category' => ['brownies'],
          ]);

          if ($products) :
            foreach ($products as $product) :
              $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'full');
              $image_url = $product_image ? $product_image[0] : wc_placeholder_img_src();
          ?>
              <!-- Product Slide -->
              <div class="carousel-slide min-w-full md:min-w-[400px] md:max-w-[400px] md:flex-shrink-0 md:px-2">
                <div class="md:bg-white md:rounded-lg md:overflow-hidden md:h-full">
                  <a href="<?php echo esc_url($product->get_permalink()); ?>">
                    <img class="w-full h-80 object-cover mb-4 md:mb-0" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                  </a>
                  <div class="px-4 pb-4">
                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                      <h3 class="text-2xl font-bold text-stone-800 leading-tight mb-2 mt-6"><?php echo esc_html($product->get_name()); ?></h3>
                      <p class="text-stone-600"><?php echo wp_trim_words($product->get_short_description(), 20); ?></p>
                    </a>
                    <?php
                    // Get product rating data
                    $average_rating = $product->get_average_rating();
                    $rating_count = $product->get_rating_count();
                    $review_count = $product->get_review_count();
                    ?>

                    <div class="hidden items-center space-x-1 my-2.5">
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

                    <div class="flex flex-col text-xl text-teal-700 my-2 specialPrice"><?php echo $product->get_price_html(); ?></div>

                    <form class="cart ajax-add-to-cart flex items-center justify-between" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                      <div class="relative flex items-center max-w-[8rem] h-8 my-2.5">
                        <button type="button" class="quantity-decrease bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg px-2.5 h-full focus:ring-gray-100 focus:ring-2 focus:outline-none">
                          <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                          </svg>
                        </button>
                        <input type="number" name="quantity" class="quantity-input bg-gray-50 border-x-0 border-gray-300 h-full text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full appearance-none" value="1" min="1" />
                        <button type="button" class="quantity-increase bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-e-lg px-2.5 h-full focus:ring-gray-100 focus:ring-2 focus:outline-none">
                          <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                          </svg>
                        </button>
                      </div>

                      <button type="submit" class="single_add_to_cart_button bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white px-4 py-2 rounded-md">Agregar al carrito</button>
                    </form>
                  </div>
                </div>
              </div>
          <?php
            endforeach;
          endif;
          ?>

        </div>
      </div>

      <!-- Desktop Navigation Arrows -->
      <button class="carousel-prev hidden md:flex absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg transition-all duration-200" id="prevBtn">
        <svg class="w-6 h-6 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </button>

      <button class="carousel-next hidden md:flex absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg transition-all duration-200" id="nextBtn">
        <svg class="w-6 h-6 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </button>

      <!-- Bullet Indicators -->
      <div class="flex justify-center mt-6 space-x-2" id="indicators">
        <?php
        if ($products) :
          $product_count = count($products);
          // Desktop: show indicators for pages (groups of 3)
          // Mobile: show indicators for each product
        ?>
          <!-- Desktop indicators (hidden on mobile) -->
          <div class="hidden md:flex space-x-2">
            <?php
            $desktop_pages = max(1, $product_count - 2); // Number of "pages" on desktop
            for ($i = 0; $i < $desktop_pages; $i++) :
            ?>
              <button class="indicator w-3 h-3 rounded-full bg-stone-400 hover:bg-stone-600 transition-colors duration-200 <?php echo $i === 0 ? 'active bg-stone-600' : ''; ?>" data-slide="<?php echo $i; ?>"></button>
            <?php endfor; ?>
          </div>

          <!-- Mobile indicators (hidden on desktop) -->
          <div class="flex md:hidden space-x-2">
            <?php foreach ($products as $index => $product) : ?>
              <button class="indicator w-3 h-3 rounded-full bg-stone-400 hover:bg-stone-600 transition-colors duration-200 <?php echo $index === 0 ? 'active bg-stone-600' : ''; ?>" data-slide="<?php echo $index; ?>"></button>
            <?php endforeach; ?>
          </div>
        <?php
        endif;
        ?>
      </div>

    </div>
  </section>

  <!-- Sirope -->
  <section id="siropes" class="container mx-auto p-4 mt-4 max-w-7xl">
    <h2 class="text-3xl font-bold mb-8 text-center text-stone-900">Siropes</h2>
    <!-- Carousel Container -->
    <div class="relative mx-auto">
      <!-- Carousel Wrapper -->
      <div class="carousel-container overflow-hidden rounded-lg md:rounded-none bg-white md:bg-transparent">
        <div class="carousel-track flex transition-transform duration-300 ease-in-out" id="carouselTrack">

          <?php
          // Get products from brownies category
          $products = wc_get_products([
            'status' => 'publish',
            'limit'  => -1, // Get all products
            'category' => ['siropes'],
          ]);

          if ($products) :
            foreach ($products as $product) :
              $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'full');
              $image_url = $product_image ? $product_image[0] : wc_placeholder_img_src();
          ?>
              <!-- Product Slide -->
              <div class="carousel-slide min-w-full md:min-w-[400px] md:max-w-[400px] md:flex-shrink-0 md:px-2">
                <div class="md:bg-white md:rounded-lg md:overflow-hidden md:h-full">
                  <a href="<?php echo esc_url($product->get_permalink()); ?>">
                    <img class="w-full h-80 object-cover mb-4 md:mb-0" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                  </a>
                  <div class="px-4 pb-4">
                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                      <h3 class="text-2xl font-bold text-stone-800 leading-tight mb-2 mt-6"><?php echo esc_html($product->get_name()); ?></h3>
                      <p class="text-stone-600"><?php echo wp_trim_words($product->get_short_description(), 20); ?></p>
                    </a>
                    <?php
                    // Get product rating data
                    $average_rating = $product->get_average_rating();
                    $rating_count = $product->get_rating_count();
                    $review_count = $product->get_review_count();
                    ?>

                    <div class="hidden items-center space-x-1 my-2.5">
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
                    <p class="text-xl text-teal-700 font-bold"><?php echo $product->get_price_html(); ?></p>

                    <form class="cart ajax-add-to-cart flex items-center justify-between" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                      <div class="relative flex items-center max-w-[8rem] h-8 my-2.5">
                        <button type="button" class="quantity-decrease bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg px-2.5 h-full focus:ring-gray-100 focus:ring-2 focus:outline-none">
                          <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                          </svg>
                        </button>
                        <input type="number" name="quantity" class="quantity-input bg-gray-50 border-x-0 border-gray-300 h-full text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full appearance-none" value="1" min="1" />
                        <button type="button" class="quantity-increase bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-e-lg px-2.5 h-full focus:ring-gray-100 focus:ring-2 focus:outline-none">
                          <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                          </svg>
                        </button>
                      </div>

                      <button type="submit" class="single_add_to_cart_button bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white px-4 py-2 rounded-md">Agregar al carrito</button>
                    </form>
                  </div>
                </div>
              </div>
          <?php
            endforeach;
          endif;
          ?>

        </div>
      </div>

      <!-- Desktop Navigation Arrows -->
      <button class="carousel-prev hidden md:flex absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg transition-all duration-200" id="prevBtn">
        <svg class="w-6 h-6 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </button>

      <button class="carousel-next hidden md:flex absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg transition-all duration-200" id="nextBtn">
        <svg class="w-6 h-6 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </button>

      <!-- Bullet Indicators -->
      <div class="flex justify-center mt-6 space-x-2" id="indicators">
        <?php
        if ($products) :
          $product_count = count($products);
          // Desktop: show indicators for pages (groups of 3)
          // Mobile: show indicators for each product
        ?>
          <!-- Desktop indicators (hidden on mobile) -->
          <div class="hidden md:flex space-x-2">
            <?php
            $desktop_pages = max(1, $product_count - 2); // Number of "pages" on desktop
            for ($i = 0; $i < $desktop_pages; $i++) :
            ?>
              <button class="indicator w-3 h-3 rounded-full bg-stone-400 hover:bg-stone-600 transition-colors duration-200 <?php echo $i === 0 ? 'active bg-stone-600' : ''; ?>" data-slide="<?php echo $i; ?>"></button>
            <?php endfor; ?>
          </div>

          <!-- Mobile indicators (hidden on desktop) -->
          <div class="flex md:hidden space-x-2">
            <?php foreach ($products as $index => $product) : ?>
              <button class="indicator w-3 h-3 rounded-full bg-stone-400 hover:bg-stone-600 transition-colors duration-200 <?php echo $index === 0 ? 'active bg-stone-600' : ''; ?>" data-slide="<?php echo $index; ?>"></button>
            <?php endforeach; ?>
          </div>
        <?php
        endif;
        ?>
      </div>

    </div>
  </section>

  <!-- Tortas -->
  <section id="tortas" class="container mx-auto p-4 mt-4 max-w-7xl">
    <h2 class="text-3xl font-bold mb-4 text-center text-stone-900">Tortas</h2>
    <!-- Total amount and place order card -->
    <div class="flex flex-col rounded-xl border border-orange-500 bg-orange-50 p-2 mb-4 md:w-3/4 w-[97%] mx-auto">
      <div class="flex items-center justify-center text-md text-stone-900">
        <p><span class="font-bold">Importante:</span> Las tortas necesitan 3 días de anticipación para su debida elaboración</p>
      </div>
    </div>
    <!-- Carousel Container -->
    <div class="relative mx-auto">
      <!-- Carousel Wrapper -->
      <div class="carousel-container overflow-hidden rounded-lg md:rounded-none bg-white md:bg-transparent">
        <div class="carousel-track flex transition-transform duration-300 ease-in-out" id="carouselTrack">

          <?php
          // Get products from brownies category
          $products = wc_get_products([
            'status' => 'publish',
            'limit'  => -1, // Get all products
            'category' => ['tortas'],
          ]);

          if ($products) :
            foreach ($products as $product) :
              $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'full');
              $image_url = $product_image ? $product_image[0] : wc_placeholder_img_src();
          ?>
              <!-- Product Slide -->
              <div class="carousel-slide min-w-full md:min-w-[400px] md:max-w-[400px] md:flex-shrink-0 md:px-2">
                <div class="md:bg-white md:rounded-lg md:overflow-hidden md:h-full">
                  <a href="<?php echo esc_url($product->get_permalink()); ?>">
                    <img class="w-full h-80 object-cover mb-4 md:mb-0" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                  </a>
                  <div class="px-4 pb-4">
                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                      <h3 class="text-2xl font-bold text-stone-800 leading-tight mb-2 mt-6"><?php echo esc_html($product->get_name()); ?></h3>
                      <p class="text-stone-600"><?php echo wp_trim_words($product->get_short_description(), 20); ?></p>
                    </a>
                    <?php
                    // Get product rating data
                    $average_rating = $product->get_average_rating();
                    $rating_count = $product->get_rating_count();
                    $review_count = $product->get_review_count();
                    ?>

                    <div class="hidden items-center space-x-1 my-2.5">
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
                    <p class="text-xl text-teal-700 font-bold"><?php echo $product->get_price_html(); ?></p>

                    <form class="cart ajax-add-to-cart flex items-center justify-between" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                      <div class="relative flex items-center max-w-[8rem] h-8 my-2.5">
                        <button type="button" class="quantity-decrease bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg px-2.5 h-full focus:ring-gray-100 focus:ring-2 focus:outline-none">
                          <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                          </svg>
                        </button>
                        <input type="number" name="quantity" class="quantity-input bg-gray-50 border-x-0 border-gray-300 h-full text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full appearance-none" value="1" min="1" />
                        <button type="button" class="quantity-increase bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-e-lg px-2.5 h-full focus:ring-gray-100 focus:ring-2 focus:outline-none">
                          <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                          </svg>
                        </button>
                      </div>

                      <button type="submit" class="single_add_to_cart_button bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white px-4 py-2 rounded-md">Agregar al carrito</button>
                    </form>
                  </div>
                </div>
              </div>
          <?php
            endforeach;
          endif;
          ?>

        </div>
      </div>

      <!-- Desktop Navigation Arrows -->
      <button class="carousel-prev hidden md:flex absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg transition-all duration-200" id="prevBtn">
        <svg class="w-6 h-6 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </button>

      <button class="carousel-next hidden md:flex absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg transition-all duration-200" id="nextBtn">
        <svg class="w-6 h-6 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </button>

      <!-- Bullet Indicators -->
      <div class="flex justify-center mt-6 space-x-2" id="indicators">
        <?php
        if ($products) :
          $product_count = count($products);
          // Desktop: show indicators for pages (groups of 3)
          // Mobile: show indicators for each product
        ?>
          <!-- Desktop indicators (hidden on mobile) -->
          <div class="hidden md:flex space-x-2">
            <?php
            $desktop_pages = max(1, $product_count - 2); // Number of "pages" on desktop
            for ($i = 0; $i < $desktop_pages; $i++) :
            ?>
              <button class="indicator w-3 h-3 rounded-full bg-stone-400 hover:bg-stone-600 transition-colors duration-200 <?php echo $i === 0 ? 'active bg-stone-600' : ''; ?>" data-slide="<?php echo $i; ?>"></button>
            <?php endfor; ?>
          </div>

          <!-- Mobile indicators (hidden on desktop) -->
          <div class="flex md:hidden space-x-2">
            <?php foreach ($products as $index => $product) : ?>
              <button class="indicator w-3 h-3 rounded-full bg-stone-400 hover:bg-stone-600 transition-colors duration-200 <?php echo $index === 0 ? 'active bg-stone-600' : ''; ?>" data-slide="<?php echo $index; ?>"></button>
            <?php endforeach; ?>
          </div>
        <?php
        endif;
        ?>
      </div>

    </div>
  </section>

  <!-- Eventos -->
  <?php while (have_posts()) : the_post(); ?>
    <section id="eventos" class="p-8 rounded-xl bg-gradient-to-r from-stone-800 to-stone-900 border-0 w-[90%] mx-auto mt-12 max-w-7xl">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div class="text-white">
          <h3 class="text-3xl mb-4 font-roboto font-medium"><?php the_field('titulo_principal_catering'); ?></h3>
          <p class="text-lg mb-6 text-stone-200 font-sans"><?php the_field('parrafo_de_texto_catering'); ?></p>
          <a href="https://wa.me/+<?php the_field('numero_de_telefono_catering'); ?>" target="_blank" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md font-medium transition-all h-9 px-4 py-2 bg-white text-stone-800 hover:bg-stone-50 text-lg">
            <svg class="w-6 h-6 text-stone-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m17.0896 13.371 1.1431 1.1439c.1745.1461.3148.3287.4111.5349.0962.2063.1461.4312.1461.6588 0 .2276-.0499.4525-.1461.6587-.0963.2063-.4729.6251-.6473.7712-3.1173 3.1211-6.7739 1.706-9.90477-1.4254-3.13087-3.1313-4.54323-6.7896-1.41066-9.90139.62706-.61925 1.71351-1.14182 2.61843-.23626l1.1911 1.19193c1.1911 1.19194.3562 1.93533-.4926 2.80371-.92477.92481-.65643 1.72741 0 2.38391l1.8713 1.8725c.3159.3161.7443.4936 1.191.4936.4468 0 .8752-.1775 1.1911-.4936.8624-.8261 1.6952-1.6004 2.8382-.4565ZM14 8.98134l5.0225-4.98132m0 0L15.9926 4m3.0299.00002v2.98135" />
            </svg>
            WhatsApp</a>
        </div>
        <div class="h-36 md:h-80">
          <img class="rounded-lg w-full h-full object-cover" src="<?php the_field('imagen_principal_catering'); ?>" alt="Imagen Eventos">
        </div>
      </div>
    </section>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>