<?php
/**
 * The main template file
 *
 * Used to display a page when nothing more specific matches.
 *
 * @package skalTheme
 */

get_header(); ?>

<main id="main" class="container mx-auto px-4 py-8">
  <?php if ( have_posts() ) : ?>
    
    <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class("mb-8"); ?>>
        
        <h2 class="text-2xl font-bold mb-2">
          <a href="<?php the_permalink(); ?>" class="text-blue-600 hover:underline">
            <?php the_title(); ?>
          </a>
        </h2>
        
        <div class="prose">
          <?php the_excerpt(); ?>
        </div>
        
      </article>
    <?php endwhile; ?>

    <div class="mt-6">
      <?php
      // Paginación
      the_posts_pagination( array(
        'prev_text' => __('« Anterior', 'skalTheme'),
        'next_text' => __('Siguiente »', 'skalTheme'),
      ) );
      ?>
    </div>

  <?php else : ?>
    
    <p class="text-gray-600"><?php _e('No se encontraron publicaciones.', 'skalTheme'); ?></p>
    
  <?php endif; ?>
</main>

<?php get_footer(); ?>
