/**
 * Cart functionality for skalTheme
 * Handles cart counter, AJAX add to cart, and quantity selectors
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize cart functionality
    initCartCounter();
    initQuantitySelectors();
    initAjaxAddToCart();
    initCartPageUpdates();
    
});

/**
 * Initialize cart counter functionality
 */
function initCartCounter() {
    // Update cart count on page load
    updateCartCount();
}

/**
 * Initialize quantity selector buttons
 */
function initQuantitySelectors() {
    const quantityDecrease = document.querySelectorAll('.quantity-decrease');
    const quantityIncrease = document.querySelectorAll('.quantity-increase');
    
    quantityDecrease.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.quantity-input');
            let value = parseInt(input.value);
            if (value > 1) {
                input.value = value - 1;
            }
        });
    });
    
    quantityIncrease.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.quantity-input');
            let value = parseInt(input.value);
            input.value = value + 1;
        });
    });
}

/**
 * Initialize AJAX add to cart functionality
 */
function initAjaxAddToCart() {
    const ajaxAddToCartForms = document.querySelectorAll('.ajax-add-to-cart');
    ajaxAddToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productId = this.dataset.productId;
            const quantity = this.querySelector('.quantity-input').value;
            const button = this.querySelector('.single_add_to_cart_button');
            
            // Disable button and show loading
            button.disabled = true;
            button.textContent = 'Agregando...';
            
            // Use WooCommerce's built-in AJAX add to cart
            const formData = new FormData();
            formData.append('action', 'woocommerce_add_to_cart');
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            
            // Get AJAX URL with fallbacks
            const ajaxUrl = getAjaxUrl();
            
            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log('Add to cart response:', data);
                
                // Trigger WooCommerce cart fragments update
                document.body.dispatchEvent(new CustomEvent('wc_fragment_refresh'));
                
                showNotification('Producto agregado al carrito', 'success');
                
                // Reset button
                button.disabled = false;
                button.textContent = 'Agregar al carrito';
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al agregar al carrito', 'error');
                
                // Reset button
                button.disabled = false;
                button.textContent = 'Agregar al carrito';
            });
        });
    });
}

/**
 * Initialize cart page update listeners
 */
function initCartPageUpdates() {
    // Listen for cart updates on cart page
    if (document.body.classList.contains('woocommerce-cart') || window.location.href.includes('cart')) {
        // Listen for cart update events with more specific selectors
        document.addEventListener('click', function(e) {
            // Check for remove item links (×)
            if (e.target.closest('a.remove') || e.target.classList.contains('remove')) {
                console.log('Remove item clicked');
                setTimeout(() => {
                    updateCartCount();
                    // Also reload the page to show updated cart
                    window.location.reload();
                }, 500);
            }
            
            // Check for update cart button
            if (e.target.name === 'update_cart' || e.target.closest('[name="update_cart"]')) {
                console.log('Update cart clicked');
                setTimeout(() => {
                    updateCartCount();
                }, 1500);
            }
        });
        
        // Listen for quantity changes
        const quantityInputs = document.querySelectorAll('input[name*="[qty]"]');
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                console.log('Quantity changed');
                setTimeout(() => {
                    updateCartCount();
                }, 500);
            });
        });
        
        // Also check for form submissions on cart page
        const cartForm = document.querySelector('.woocommerce-cart-form');
        if (cartForm) {
            cartForm.addEventListener('submit', function() {
                console.log('Cart form submitted');
                setTimeout(() => {
                    updateCartCount();
                }, 1500);
            });
        }
    }
}

/**
 * Get AJAX URL with fallbacks
 */
function getAjaxUrl() {
    // Use WordPress AJAX URL with multiple fallbacks
    let ajaxUrl = '/wp-admin/admin-ajax.php'; // Default fallback
    
    if (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.ajax_url) {
        ajaxUrl = wc_add_to_cart_params.ajax_url;
    } else if (typeof skal_ajax !== 'undefined' && skal_ajax.ajax_url) {
        ajaxUrl = skal_ajax.ajax_url;
    }
    
    return ajaxUrl;
}

/**
 * Update cart count in header
 */
function updateCartCount() {
    console.log('Updating cart count...');
    
    const ajaxUrl = getAjaxUrl();
    console.log('Using AJAX URL:', ajaxUrl);
    
    fetch(ajaxUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'action': 'get_cart_count'
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Cart count response:', data);
        const cartCountElement = document.querySelector('.cart-count');
        const cartLink = document.querySelector('a[href*="cart"]') || document.querySelector('a[href*="carrito"]');
        console.log('Cart link found:', cartLink);
        console.log('Cart link href:', cartLink ? cartLink.href : 'No cart link');
        
        // Handle both response formats
        const count = data.success ? data.data.count : data.count;
        console.log('Extracted count:', count);
        
        if (count > 0) {
            if (cartCountElement) {
                cartCountElement.textContent = count;
                console.log('Updated existing cart count to:', count);
            } else {
                // Create cart count element if it doesn't exist
                const span = document.createElement('span');
                span.className = 'cart-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center';
                span.textContent = count;
                if (cartLink) {
                    cartLink.appendChild(span);
                    console.log('Created new cart count element with:', count);
                } else {
                    console.error('Cart link not found!');
                }
            }
        } else {
            // Remove cart count if cart is empty
            if (cartCountElement) {
                cartCountElement.remove();
                console.log('Removed cart count element - cart is empty');
            }
        }
    })
    .catch(error => {
        console.error('Error updating cart count:', error);
    });
}

/**
 * Show notification messages
 * This function is shared with main.js, so we check if it exists first
 */
if (typeof showNotification === 'undefined') {
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full`;
        
        const typeClasses = {
            'success': 'bg-green-500 text-white',
            'error': 'bg-red-500 text-white',
            'warning': 'bg-yellow-500 text-black',
            'info': 'bg-blue-500 text-white'
        };
        
        notification.className += ` ${typeClasses[type] || typeClasses.info}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }
}
