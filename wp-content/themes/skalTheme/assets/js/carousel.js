/**
 * Multiple Carousel functionality for skalTheme
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize homepage/section carousels
    const carouselSections = document.querySelectorAll('section');
    
    carouselSections.forEach((section, sectionIndex) => {
        const carouselContainer = section.querySelector('.carousel-container');
        if (carouselContainer) {
            initCarousel(section, sectionIndex);
        }
    });
    
    // Initialize single product gallery carousel
    initProductGalleryCarousel();
});

function initCarousel(section, carouselIndex) {
    const track = section.querySelector('.carousel-track');
    const slides = section.querySelectorAll('.carousel-slide');
    const indicators = section.querySelectorAll('.indicator');
    const nextBtn = section.querySelector('.carousel-next');
    const prevBtn = section.querySelector('.carousel-prev');
    
    if (!track || slides.length === 0) return;
    
    let currentSlide = 0;
    let autoPlayInterval;
    
    function updateCarousel() {
        // Check if desktop (3 cards) or mobile (1 card)
        const isDesktop = window.innerWidth >= 768;
        
        if (isDesktop) {
            // Desktop: slide by 400px per card
            track.style.transform = `translateX(-${currentSlide * 400}px)`;
        } else {
            // Mobile: slide by 100% (full width)
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
        }
        
        // Update indicators - filter visible indicators based on viewport
        const visibleIndicators = Array.from(indicators).filter(indicator => {
            const parent = indicator.parentElement;
            if (isDesktop) {
                // Desktop: select indicators in hidden md:flex container
                return parent.classList.contains('md:flex');
            } else {
                // Mobile: select indicators in md:hidden container
                return parent.classList.contains('md:hidden');
            }
        });
        
        visibleIndicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentSlide);
            if (index === currentSlide) {
                indicator.classList.add('bg-stone-600');
                indicator.classList.remove('bg-stone-400');
            } else {
                indicator.classList.add('bg-stone-400');
                indicator.classList.remove('bg-stone-600');
            }
        });
    }
    
    function nextSlide() {
        const isDesktop = window.innerWidth >= 768;
        const maxSlide = isDesktop ? Math.max(0, slides.length - 3) : slides.length - 1;
        
        if (currentSlide < maxSlide) {
            currentSlide++;
        } else {
            currentSlide = 0; // Loop back to start
        }
        updateCarousel();
    }
    
    function prevSlide() {
        const isDesktop = window.innerWidth >= 768;
        const maxSlide = isDesktop ? Math.max(0, slides.length - 3) : slides.length - 1;
        
        if (currentSlide > 0) {
            currentSlide--;
        } else {
            currentSlide = maxSlide; // Loop to end
        }
        updateCarousel();
    }
    
    function goToSlide(slideIndex) {
        currentSlide = slideIndex;
        updateCarousel();
    }
    
    function startAutoPlay() {
        autoPlayInterval = setInterval(nextSlide, 8000);
    }
    
    function stopAutoPlay() {
        clearInterval(autoPlayInterval);
    }
    
    function restartAutoPlay() {
        stopAutoPlay();
        startAutoPlay();
    }
    
    // Auto-play functionality
    startAutoPlay();
    
    // Pause auto-play on hover
    track.addEventListener('mouseenter', stopAutoPlay);
    track.addEventListener('mouseleave', startAutoPlay);
    
    // Navigation event listeners - restart autoplay on interaction
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            restartAutoPlay();
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            restartAutoPlay();
        });
    }
    
    // Indicator navigation - separate handlers for mobile and desktop
    const desktopIndicators = Array.from(indicators).filter(ind => 
        ind.parentElement.classList.contains('md:flex')
    );
    const mobileIndicators = Array.from(indicators).filter(ind => 
        ind.parentElement.classList.contains('md:hidden')
    );
    
    desktopIndicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            goToSlide(index);
            restartAutoPlay();
        });
    });
    
    mobileIndicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            goToSlide(index);
            restartAutoPlay();
        });
    });
    
    // Quantity buttons (+/-) - restart autoplay on interaction
    const quantityBtns = section.querySelectorAll('.quantity-increase, .quantity-decrease');
    quantityBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            restartAutoPlay();
        });
    });
    
    // Handle window resize
    window.addEventListener('resize', () => {
        updateCarousel();
    });
    
    // Initialize
    updateCarousel();
}

/**
 * ========================================
 * SINGLE PRODUCT GALLERY CAROUSEL
 * ========================================
 * Handles the product image gallery on single product pages
 */
function initProductGalleryCarousel() {
    const gallery = document.querySelector('[data-product-gallery]');
    
    // Exit if not on a product page or gallery doesn't exist
    if (!gallery) return;
    
    const mainImages = gallery.querySelectorAll('.gallery-main-image');
    const thumbnails = gallery.querySelectorAll('.gallery-thumb-btn');
    const prevBtn = gallery.querySelector('.gallery-prev-btn');
    const nextBtn = gallery.querySelector('.gallery-next-btn');
    const currentImageSpan = gallery.querySelector('.current-image');
    const totalImagesSpan = gallery.querySelector('.total-images');
    
    // Exit if no images
    if (mainImages.length === 0) return;
    
    let currentIndex = 0;
    const totalImages = mainImages.length;
    
    /**
     * Update the gallery to show the image at the given index
     */
    function showImage(index) {
        // Ensure index is within bounds
        if (index < 0) {
            currentIndex = totalImages - 1;
        } else if (index >= totalImages) {
            currentIndex = 0;
        } else {
            currentIndex = index;
        }
        
        // Hide all images
        mainImages.forEach(img => {
            img.classList.add('hidden');
        });
        
        // Show current image
        mainImages[currentIndex].classList.remove('hidden');
        
        // Update counter
        if (currentImageSpan) {
            currentImageSpan.textContent = currentIndex + 1;
        }
        
        // Update thumbnail active states
        thumbnails.forEach((thumb, idx) => {
            if (idx === currentIndex) {
                // Active thumbnail
                thumb.classList.remove('border-stone-200', 'hover:border-stone-300');
                thumb.classList.add('border-teal-600', 'shadow-md');
            } else {
                // Inactive thumbnail
                thumb.classList.remove('border-teal-600', 'shadow-md');
                thumb.classList.add('border-stone-200', 'hover:border-stone-300');
            }
        });
    }
    
    /**
     * Go to next image
     */
    function nextImage() {
        showImage(currentIndex + 1);
    }
    
    /**
     * Go to previous image
     */
    function prevImage() {
        showImage(currentIndex - 1);
    }
    
    /**
     * Go to specific image by index
     */
    function goToImage(index) {
        showImage(index);
    }
    
    // Event Listeners
    
    // Previous button
    if (prevBtn) {
        prevBtn.addEventListener('click', prevImage);
    }
    
    // Next button
    if (nextBtn) {
        nextBtn.addEventListener('click', nextImage);
    }
    
    // Thumbnail clicks
    thumbnails.forEach((thumb, index) => {
        thumb.addEventListener('click', () => {
            goToImage(index);
        });
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        // Only work if we're on the product page and gallery exists
        if (!gallery) return;
        
        if (e.key === 'ArrowLeft') {
            prevImage();
        } else if (e.key === 'ArrowRight') {
            nextImage();
        }
    });
    
    // Initialize - show first image
    showImage(0);
}

// ============================================
// SINGLE PRODUCT ADD TO CART FUNCTIONALITY
// ============================================
function initAddToCart() {
    const addToCartForm = document.querySelector('[data-add-to-cart-form]');
    
    if (!addToCartForm) return;
    
    const qtyInput = addToCartForm.querySelector('[data-qty-input]');
    const qtyDecrease = addToCartForm.querySelector('[data-qty-decrease]');
    const qtyIncrease = addToCartForm.querySelector('[data-qty-increase]');
    const addToCartBtn = addToCartForm.querySelector('[data-add-to-cart-btn]');
    const productId = addToCartForm.dataset.productId;
    
    // Quantity decrease
    qtyDecrease.addEventListener('click', () => {
        const currentValue = parseInt(qtyInput.value) || 1;
        if (currentValue > 1) {
            qtyInput.value = currentValue - 1;
        }
    });
    
    // Quantity increase
    qtyIncrease.addEventListener('click', () => {
        const currentValue = parseInt(qtyInput.value) || 1;
        qtyInput.value = currentValue + 1;
    });
    
    // Validate quantity input
    qtyInput.addEventListener('change', () => {
        const value = parseInt(qtyInput.value) || 1;
        if (value < 1) {
            qtyInput.value = 1;
        }
    });
    
    // Add to cart button
    addToCartBtn.addEventListener('click', async () => {
        const quantity = parseInt(qtyInput.value) || 1;
        
        // Disable button and show loading state
        addToCartBtn.disabled = true;
        const originalText = addToCartBtn.textContent;
        addToCartBtn.textContent = 'Agregando...';
        
        try {
            // Use WooCommerce AJAX add to cart
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            formData.append('action', 'woocommerce_ajax_add_to_cart');
            
            const response = await fetch(wc_add_to_cart_params.ajax_url, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.error) {
                // Show error
                addToCartBtn.textContent = 'Error';
                setTimeout(() => {
                    addToCartBtn.textContent = originalText;
                    addToCartBtn.disabled = false;
                }, 2000);
            } else {
                // Success - trigger WooCommerce cart update
                document.body.dispatchEvent(new Event('wc_fragment_refresh'));
                
                // Show success feedback
                addToCartBtn.textContent = '✓ Agregado';
                addToCartBtn.classList.add('bg-green-600');
                
                setTimeout(() => {
                    addToCartBtn.textContent = originalText;
                    addToCartBtn.classList.remove('bg-green-600');
                    addToCartBtn.disabled = false;
                }, 2000);
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            addToCartBtn.textContent = 'Error';
            setTimeout(() => {
                addToCartBtn.textContent = originalText;
                addToCartBtn.disabled = false;
            }, 2000);
        }
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAddToCart);
} else {
    initAddToCart();
}
