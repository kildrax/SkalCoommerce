<?php

/**
 * Template Name: Front Page
 *
 * @package skalTheme
 */

get_header(); ?>

<main id="main" class="min-h-screen bg-gradient-to-br from-stone-50/30 to-amber-50/40">

  <section class="flex align-center justify-center w-full sm:h-[calc(100vh-155px)] h-auto py-4">
    <div class="sm:w-1/2 w-full">
      <div class="flex flex-col items-center justify-center sm:w-[600px] w-full px-6 m-auto h-full">
        <h1 class="text-4xl sm:text-6xl font-bold mb-6 text-stone-900">¿Qué es Skal?</h1>
        <p class="text-xl sm:text-2xl mb-2 text-stone-600">Skål nació como un restaurante, un homenaje al lugar donde estaba y a mis raíces.
          Con el tiempo, se transformó en un espacio de desarrollo creativo: un taller donde no solo importan las recetas y los productos, sino también la manera de hacer las cosas, siempre con <span class="text-[#7ad7aa] font-bold">manos, magia y propósito.</span></p>
        <p class="text-xl sm:text-2xl text-stone-600">En Skal no solo elaboramos brownies, tortas o comidas; convertimos nuestros sentimientos en fuente de inspiración para compartir con quienes nos conocen.</p>
      </div>
    </div>
    <img class="sm:w-1/2 object-cover hidden sm:block" src="<?php echo get_template_directory_uri(); ?>/assets/images/LogoSkal2.jpeg" alt="Logo Skal 2">
  </section>

  <!-- <?php
        // Categorías que quieres mostrar
        $categories = ['brownies', 'cakes', 'syrups'];

        foreach ($categories as $cat_slug) :
          $term = get_term_by('slug', $cat_slug, 'product_cat');
          if (! $term) continue;

          echo '<section class="mb-16">';
          echo '<h2 class="text-2xl font-bold mb-4">' . esc_html($term->name) . '</h2>';
          echo '<p class="text-gray-500 mb-6">' . esc_html($term->description) . '</p>';

          $products = wc_get_products([
            'status' => 'publish',
            'limit'  => 3,
            'category' => [$cat_slug],
          ]);

          if ($products) {
            echo '<div class="grid grid-cols-1 md:grid-cols-3 gap-6">';
            foreach ($products as $product) {
              wc_get_template_part('content', 'product', ['product' => $product]);
            }
            echo '</div>';
          }
          echo '</section>';

        endforeach;
        ?> -->

  <!-- Brownies -->
  <section class="container mx-auto p-4">
    <h2 class="text-3xl font-bold mb-8 text-center text-stone-900">Brownies</h2>
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
</main>

<?php get_footer(); ?>