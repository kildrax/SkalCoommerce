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

  <!-- Brownies -->
  <section class="container mx-auto p-4 mt-8 max-w-7xl">
    <h2 class="text-3xl font-bold mb-8 text-center text-stone-900">Brownies</h2>
    <!-- Carousel Container -->
    <div class="relative mx-auto">
      <!-- Carousel Wrapper -->
      <div class="carousel-container overflow-hidden rounded-lg bg-white">
        <div class="carousel-track flex transition-transform duration-300 ease-in-out " id="carouselTrack">

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
              <div class="carousel-slide min-w-full">
                <img class="w-full h-80 object-cover mb-4" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                <div class="px-4 pb-4">
                  <h2 class="text-2xl font-bold text-stone-800 leading-tight mb-2"><?php echo esc_html($product->get_name()); ?></h2>
                  <p class="text-stone-600"><?php echo wp_trim_words($product->get_short_description(), 20); ?></p>
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
                  <p class="text-xl text-teal-700 font-bold"><?php echo $product->get_price_html(); ?></p>

                  <form class="cart ajax-add-to-cart flex items-center justify-between" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                    <div class="relative flex items-center max-w-[8rem] h-8 my-2.5">
                      <button type="button" class="quantity-decrease bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg px-2.5 h-full focus:ring-gray-100 focus:ring-2 focus:outline-none">
                        <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                        </svg>
                      </button>
                      <input type="number" name="quantity" class="quantity-input bg-gray-50 border-x-0 border-gray-300 h-full text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full" value="1" min="1" />
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
          foreach ($products as $index => $product) :
        ?>
            <button class="indicator w-3 h-3 rounded-full bg-stone-400 hover:bg-stone-600 transition-colors duration-200 <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></button>
        <?php
          endforeach;
        endif;
        ?>
      </div>

    </div>
  </section>

  <!-- Sirope -->
  <section class="container mx-auto p-4">
    <h2 class="text-3xl font-bold mb-8 text-center text-stone-900">Siropes</h2>
    <!-- Carousel Container -->
    <div class="relative max-w-4xl mx-auto">
      <!-- Carousel Wrapper -->
      <div class="carousel-container overflow-hidden rounded-lg bg-white">
        <div class="carousel-track flex transition-transform duration-300 ease-in-out " id="carouselTrack">

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
              <div class="carousel-slide min-w-full">
                <img class="w-full h-80 object-cover mb-4" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                <div class="px-4 pb-4">
                  <h2 class="text-2xl font-bold text-stone-800 leading-tight mb-2"><?php echo esc_html($product->get_name()); ?></h2>
                  <p class="text-stone-600"><?php echo wp_trim_words($product->get_short_description(), 20); ?></p>
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
                  <p class="text-xl text-teal-700 font-bold"><?php echo $product->get_price_html(); ?></p>

                  <form class="cart ajax-add-to-cart flex items-center justify-between" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                    <div class="relative flex items-center max-w-[8rem] h-8 my-2.5">
                      <button type="button" class="quantity-decrease bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg px-2.5 h-full focus:ring-gray-100 focus:ring-2 focus:outline-none">
                        <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                        </svg>
                      </button>
                      <input type="number" name="quantity" class="quantity-input bg-gray-50 border-x-0 border-gray-300 h-full text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full" value="1" min="1" />
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
          foreach ($products as $index => $product) :
        ?>
            <button class="indicator w-3 h-3 rounded-full bg-stone-400 hover:bg-stone-600 transition-colors duration-200 <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></button>
        <?php
          endforeach;
        endif;
        ?>
      </div>

    </div>
  </section>

  <!-- Tortas -->
  <section class="container mx-auto p-4">
    <h2 class="text-3xl font-bold mb-8 text-center text-stone-900">Tortas</h2>
    <!-- Carousel Container -->
    <div class="relative max-w-4xl mx-auto">
      <!-- Carousel Wrapper -->
      <div class="carousel-container overflow-hidden rounded-lg bg-white">
        <div class="carousel-track flex transition-transform duration-300 ease-in-out " id="carouselTrack">

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
              <div class="carousel-slide min-w-full">
                <img class="w-full h-80 object-cover mb-4" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                <div class="px-4 pb-4">
                  <h2 class="text-2xl font-bold text-stone-800 leading-tight mb-2"><?php echo esc_html($product->get_name()); ?></h2>
                  <p class="text-stone-600"><?php echo wp_trim_words($product->get_short_description(), 20); ?></p>
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
                  <p class="text-xl text-teal-700 font-bold"><?php echo $product->get_price_html(); ?></p>

                  <form class="cart ajax-add-to-cart flex items-center justify-between" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                    <div class="relative flex items-center max-w-[8rem] h-8 my-2.5">
                      <button type="button" class="quantity-decrease bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg px-2.5 h-full focus:ring-gray-100 focus:ring-2 focus:outline-none">
                        <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                        </svg>
                      </button>
                      <input type="number" name="quantity" class="quantity-input bg-gray-50 border-x-0 border-gray-300 h-full text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full" value="1" min="1" />
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
          foreach ($products as $index => $product) :
        ?>
            <button class="indicator w-3 h-3 rounded-full bg-stone-400 hover:bg-stone-600 transition-colors duration-200 <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></button>
        <?php
          endforeach;
        endif;
        ?>
      </div>

    </div>
  </section>

  <!-- Eventos -->
  <?php while (have_posts()) : the_post(); ?>
  <section class="p-8 rounded-xl bg-gradient-to-r from-stone-800 to-stone-900 border-0 w-[90%] mx-auto mt-12 max-w-7xl">
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