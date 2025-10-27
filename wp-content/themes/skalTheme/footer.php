    </div><!-- #content -->

    <footer id="colophon" class="site-footer bg-gray-900 text-white mt-auto">
        <div class="container mx-auto p-4">
            <div class="flex flex-col md:flex-row justify-center items-center text-center">

                <!-- Company Info -->
                <div class="footer-info">
                    <h3 class="text-lg font-semibold mb-2"><?php bloginfo('name'); ?></h3>
                    <div class="flex flex-col justify-between items-center">
                        <p class="text-gray-300 mb-1.5">
                            <?php
                            $description = get_bloginfo('description', 'display');
                            echo $description ? $description : 'Gracias por ser parte de esta aventura.';
                            ?>
                        </p>
                        <!-- Social Media Icons -->
                        <a href="https://www.instagram.com/skalatelier/" target="_blank" class="text-gray-300 hover:text-white transition-colors">
                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path fill="currentColor" fill-rule="evenodd" d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-3 pt-4 text-center">
                <p class="text-gray-400">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

    </div><!-- #page -->

    <!-- Mobile menu toggle script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileNavigation = document.querySelector('.mobile-navigation');

            if (mobileMenuButton && mobileNavigation) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileNavigation.classList.toggle('hidden');
                });
            }
        });
    </script>

    <?php wp_footer(); ?>

    </body>

    </html>