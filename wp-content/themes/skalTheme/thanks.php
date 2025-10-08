<?php

/**
 * Template Name: Página de Gracias
 * Description: Template para la página de agradecimiento después de realizar una orden
 */

get_header();

// Get order ID from URL
$order_id = isset($_GET['order_id']) ? absint($_GET['order_id']) : 0;
$customer_name = '';
$order_number = '';

if ($order_id) {
    $order = wc_get_order($order_id);
    if ($order) {
        $customer_name = $order->get_billing_first_name();
        $order_number = $order->get_order_number();
    }
}
?>

<section class="py-16 bg-gradient-to-br from-stone-50/30 to-amber-50/40">
    <div class="container mx-auto px-4 max-w-4xl ">
        <div class="text-center">
            <!-- Icono de éxito -->
            <div class="mb-8">
                <svg class="mx-auto h-24 w-24 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <h1 class="text-4xl font-bold text-stone-900 mb-4">
                <?php 
                $titulo = get_field('titulo_principal_gracias');
                if ($customer_name) {
                    echo '¡'. esc_html($titulo) . ' ' . esc_html($customer_name) . '!';
                } else {
                    echo esc_html($titulo);
                }
                ?>
            </h1>
            <p class="text-xl text-stone-600 mb-8">
                <?php 
                $texto = get_field('texto_pequeno_gracias');
                echo esc_html($texto);
                if ($order_number) {
                    echo ' <span class="font-semibold text-teal-700">Orden #' . esc_html($order_number) . '</span>';
                }
                ?>
            </p>

            <div class="bg-teal-50 border border-teal-200 rounded-lg p-6 mb-8">
                <?php the_field('informacion_del_proceso_gracias'); ?>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-block px-8 py-3 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition-colors">
                    Volver al inicio
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>