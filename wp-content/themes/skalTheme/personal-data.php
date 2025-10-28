<?php

/**
 * Template Name: Autorización datos
 * Description: Template para la página de Autorización para el tratamiento de datos personales
 */

get_header();

?>

<section class="py-16 bg-gradient-to-br from-stone-50/30 to-amber-50/40">
    <div class="mx-auto px-4 max-w-4xl">
        <?php while (have_posts()) : the_post(); ?>

            <!-- Page Title -->
            <h1 class="text-4xl md:text-5xl font-bold text-stone-900 mb-12">
                <?php the_title(); ?>
            </h1>
            <!-- Page Content -->
            <article class="prose prose-lg prose-stone prose-headings:font-bold prose-h1:text-3xl prose-h2:text-2xl prose-h3:text-xl prose-p:mb-4 prose-strong:font-bold prose-strong:text-stone-900 max-w-none bg-white rounded-2xl shadow-lg p-8 md:p-12">
                <?php the_content(); ?>
            </article>

        <?php endwhile; ?>
    </div>
</section>

<?php get_footer(); ?>