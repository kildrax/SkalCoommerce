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
    initCartQuantityButtons();
    initRemoveFromCart();
    
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

/**
 * Initialize cart page quantity buttons (+/-)
 */
function initCartQuantityButtons() {
    console.log('Initializing cart quantity buttons...');
    
    const decreaseButtons = document.querySelectorAll('.qty-decrease');
    const increaseButtons = document.querySelectorAll('.qty-increase');
    const qtyInputs = document.querySelectorAll('.qty-input');
    
    console.log('Found decrease buttons:', decreaseButtons.length);
    console.log('Found increase buttons:', increaseButtons.length);
    console.log('Found qty inputs:', qtyInputs.length);
    
    // Handle decrease button
    decreaseButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            console.log('Decrease button clicked!');
            e.preventDefault();
            
            const cartKey = this.getAttribute('data-cart-key');
            const input = document.querySelector(`.qty-input[data-cart-key="${cartKey}"]`);
            
            console.log('Cart key:', cartKey);
            console.log('Input found:', input);
            
            if (input) {
                let value = parseInt(input.value);
                const min = parseInt(input.getAttribute('min')) || 0;
                
                console.log('Current value:', value, 'Min:', min);
                
                if (value > min) {
                    input.value = value - 1;
                    console.log('New value:', input.value);
                    
                    // Trigger input event to notify WooCommerce
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    
                    showUpdateButton();
                    
                    // Auto-submit if quantity reaches 0
                    if (input.value == 0) {
                        setTimeout(() => {
                            document.querySelector('.woocommerce-cart-form').submit();
                        }, 300);
                    }
                }
            }
        });
    });
    
    // Handle increase button
    increaseButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            console.log('Increase button clicked!');
            e.preventDefault();
            
            const cartKey = this.getAttribute('data-cart-key');
            const input = document.querySelector(`.qty-input[data-cart-key="${cartKey}"]`);
            
            console.log('Cart key:', cartKey);
            console.log('Input found:', input);
            
            if (input) {
                let value = parseInt(input.value);
                const max = parseInt(input.getAttribute('max')) || 999;
                
                console.log('Current value:', value, 'Max:', max);
                
                if (value < max) {
                    input.value = value + 1;
                    console.log('New value:', input.value);
                    
                    // Trigger input event to notify WooCommerce
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    
                    showUpdateButton();
                }
            }
        });
    });
    
    // Handle manual input changes
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            showUpdateButton();
        });
    });
}

/**
 * Show the update cart button when quantity changes
 */
function showUpdateButton() {
    console.log('Showing update button...');
    const updateBtn = document.querySelector('.update-cart-btn');
    console.log('Update button found:', updateBtn);
    
    if (updateBtn) {
        updateBtn.classList.remove('hidden');
        updateBtn.removeAttribute('disabled'); // Remove disabled attribute
        updateBtn.disabled = false; // Ensure it's enabled
        console.log('Update button is now visible and enabled');
        
        // Ensure the button submits the form when clicked
        if (!updateBtn.hasAttribute('data-listener-added')) {
            updateBtn.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default to debug
                console.log('Update cart button clicked!');
                
                const form = document.querySelector('.woocommerce-cart-form');
                console.log('Form found:', form);
                
                if (form) {
                    // Check all qty inputs before submitting
                    const qtyInputs = form.querySelectorAll('input[name*="[qty]"]');
                    console.log('Found qty inputs:', qtyInputs.length);
                    
                    qtyInputs.forEach(input => {
                        console.log(`Input name: ${input.name}, value: ${input.value}, type: ${typeof input.value}`);
                    });
                    
                    // Log all form data before submitting
                    const formData = new FormData(form);
                    console.log('Form data being submitted:');
                    for (let [key, value] of formData.entries()) {
                        console.log(`${key}: ${value} (type: ${typeof value})`);
                    }
                    
                    console.log('Submitting form...');
                    form.submit();
                } else {
                    console.error('Form not found!');
                }
            });
            updateBtn.setAttribute('data-listener-added', 'true');
        }
    } else {
        console.error('Update button not found!');
    }
}

/**
 * Initialize remove from cart buttons
 */
function initRemoveFromCart() {
    console.log('Initializing remove from cart buttons...');
    
    const removeButtons = document.querySelectorAll('.remove-from-cart');
    console.log('Found remove buttons:', removeButtons.length);
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Remove button clicked!');
            
            const cartKey = this.getAttribute('data-cart-key');
            console.log('Cart key to remove:', cartKey);
            
            if (!cartKey) {
                console.error('No cart key found!');
                return;
            }
            
            // Get the remove URL from WooCommerce
            const removeUrl = wc_cart_params && wc_cart_params.wc_ajax_url 
                ? wc_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_from_cart')
                : window.location.origin + '/?wc-ajax=remove_from_cart';
            
            console.log('Remove URL:', removeUrl);
            
            // Show confirmation
            if (confirm('¿Estás seguro de que quieres eliminar este producto del carrito?')) {
                console.log('User confirmed removal');
                
                // Simply redirect to the remove URL with the cart item key
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('remove_item', cartKey);
                currentUrl.searchParams.set('_wpnonce', getWooCommerceNonce());
                
                console.log('Redirecting to:', currentUrl.toString());
                window.location.href = currentUrl.toString();
            } else {
                console.log('User cancelled removal');
            }
        });
    });
}

/**
 * Get WooCommerce nonce from the page
 */
function getWooCommerceNonce() {
    const nonceInput = document.querySelector('input[name="woocommerce-cart-nonce"]');
    if (nonceInput) {
        return nonceInput.value;
    }
    
    // Fallback: try to get from WooCommerce params
    if (typeof wc_cart_params !== 'undefined' && wc_cart_params.nonce) {
        return wc_cart_params.nonce;
    }
    
    console.error('Could not find WooCommerce nonce!');
    return '';
}
