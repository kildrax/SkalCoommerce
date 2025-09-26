<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<div class="container mx-auto px-4">
  <?php do_action( 'woocommerce_before_main_content' ); ?>

  <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <aside class="md:col-span-1">
      <?php if ( is_active_sidebar( 'shop-sidebar' ) ) dynamic_sidebar( 'shop-sidebar' ); ?>
    </aside>

    <section class="md:col-span-3">
      <?php if ( woocommerce_product_loop() ) : ?>
        <?php woocommerce_product_loop_start(); ?>
          <?php while ( have_posts() ) : the_post(); wc_get_template_part( 'content', 'product' ); endwhile; ?>
        <?php woocommerce_product_loop_end(); ?>
        <?php do_action( 'woocommerce_after_shop_loop' ); ?>
      <?php else : ?>
        <?php do_action( 'woocommerce_no_products_found' ); ?>
      <?php endif; ?>
    </section>
  </div>

  <?php do_action( 'woocommerce_after_main_content' ); ?>
</div>
<?php get_footer(); ?>
