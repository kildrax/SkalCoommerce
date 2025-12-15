<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <meta name="keywords" content="Skål, Skal, brownies, brownies artesanales, brownies gourmet, brownies a domicilio, tienda de brownies, cajas de brownies, brownies para regalar, brownies x4, brownies en bandeja, postres artesanales, repostería artesanal, pastelería online, regalos dulces, siropes, sirope artesanal, syrup artesanal, siropes para postres, postres gourmet, catering de postres, catering para eventos, tienda online de postres, dulces artesanales, eventos, regalo de navidad, combo, combo de brownies, brownies navideños, bandeja de brownies, tortas, tortas personalizadas, tortas de cumpleaños, tortas de boda, tortas de graduación, tortas de bautismo, brownie red velvet, brownie arequipe, brownie chocolate, brookies, brownie vegano, brownie tradicional, brownie bajo en azucar, brownie sin gluten, brownie sin lactosa, blondie chocolate y arandanos, blondie chocolate blanco y glaseado de limón">
    <link rel="canonical" href="<?php echo home_url(); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div id="page" class="site min-h-screen flex flex-col">

        <header id="masthead" class="bg-white shadow-sm border-b border-stone-200">
            <div class="container mx-auto px-4 py-2 grid grid-cols-3 items-center">
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex justify-start">
                    <ul class="flex justify-between items-center w-full">
                        <li><a class="text-lg font-roboto" href="<?php echo home_url('/'); ?>">Inicio</a></li>
                        <li><a class="text-lg font-roboto" href="#browniesBandejas">Brownies</a></li>
                        <li><a class="text-lg font-roboto" href="#brownies_x4">Brownies x4</a></li>
                        <li><a class="text-lg font-roboto" href="#siropes">Siropes</a></li>
                        <li><a class="text-lg font-roboto" href="#eventos">Eventos</a></li>
                    </ul>
                </nav>

                <!-- Mobile Hamburger Menu Button -->
                <div class="md:hidden flex justify-start">
                    <button id="mobile-menu-button" class="p-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                <div class="my-2 flex justify-center">
                    <a href="<?php echo home_url('/'); ?>">
                        <img class="w-20 h-20 rounded-full" src="<?php echo get_template_directory_uri(); ?>/assets/images/LogoSkal1.jpeg" alt="Logo Skal 1">
                    </a>
                </div>
                <div class="flex justify-end">
                    <ul class="flex justify-between items-center">
                        <!-- <li>
                            <a class="block mr-2" href="<?php echo wp_login_url(); ?>" rel="noopener">
                                <svg class="w-10 h-10 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </a>
                        </li> -->
                        <li>
                            <a href="<?php echo wc_get_cart_url(); ?>" class="relative" rel="noopener">
                                <svg class="w-10 h-10 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312" />
                                </svg>
                                <?php if (WC()->cart->get_cart_contents_count() > 0) : ?>
                                    <span class="cart-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        <?php echo WC()->cart->get_cart_contents_count(); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-stone-200">
                <nav class="container mx-auto px-4 py-4">
                    <ul class="flex flex-col space-y-3">
                        <li><a class="text-lg font-roboto" href="<?php echo home_url('/'); ?>">Inicio</a></li>
                        <li><a class="text-lg font-roboto" href="#browniesBandejas">Brownies</a></li>
                        <li><a class="text-lg font-roboto" href="#brownies_x4">Brownies x4</a></li>
                        <li><a class="text-lg font-roboto" href="#siropes">Siropes</a></li>
                        <li><a class="text-lg font-roboto" href="#eventos">Eventos</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <div id="content" class="site-content flex-1">