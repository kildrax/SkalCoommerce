/**
 * Cart functionality for skalTheme
 * Handles cart counter, AJAX add to cart, and quantity selectors
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize cart functionality
    initCartCounter();
    initQuantitySelectors();
    initAjaxAddToCart();
    initAjaxAddToCartSpecial();
    initCartPageUpdates();
    initCartQuantityButtons();
    initRemoveFromCart();
    initOrderPopup();
    
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
 * Initialize cart page quantity buttons (+/-) with AJAX
 */
function initCartQuantityButtons() {
    console.log('Initializing cart quantity buttons with AJAX...');
    
    const decreaseButtons = document.querySelectorAll('.qty-decrease');
    const increaseButtons = document.querySelectorAll('.qty-increase');
    const qtyInputs = document.querySelectorAll('.qty-input');
    
    console.log('Found decrease buttons:', decreaseButtons.length);
    console.log('Found increase buttons:', increaseButtons.length);
    console.log('Found qty inputs:', qtyInputs.length);
    
    // Handle decrease button
    decreaseButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const cartKey = this.getAttribute('data-cart-key');
            const input = document.querySelector(`.qty-input[data-cart-key="${cartKey}"]`);
            
            if (input) {
                let value = parseInt(input.value);
                const min = parseInt(input.getAttribute('min')) || 0;
                
                if (value > min) {
                    const newValue = value - 1;
                    input.value = newValue;
                    
                    // Update cart via AJAX
                    updateCartQuantityAjax(cartKey, newValue);
                }
            }
        });
    });
    
    // Handle increase button
    increaseButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const cartKey = this.getAttribute('data-cart-key');
            const input = document.querySelector(`.qty-input[data-cart-key="${cartKey}"]`);
            
            if (input) {
                let value = parseInt(input.value);
                const max = parseInt(input.getAttribute('max')) || 999;
                
                if (value < max) {
                    const newValue = value + 1;
                    input.value = newValue;
                    
                    // Update cart via AJAX
                    updateCartQuantityAjax(cartKey, newValue);
                }
            }
        });
    });
    
    // Handle manual input changes with debounce
    let debounceTimer;
    qtyInputs.forEach(input => {
        input.addEventListener('change', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const cartKey = this.getAttribute('data-cart-key');
                const newValue = parseInt(this.value) || 0;
                updateCartQuantityAjax(cartKey, newValue);
            }, 500);
        });
    });
}

/**
 * Update cart quantity via AJAX
 */
function updateCartQuantityAjax(cartKey, quantity) {
    console.log('Updating cart via AJAX:', cartKey, quantity);
    
    const ajaxUrl = getAjaxUrl();
    const nonce = getWooCommerceNonce();
    
    // Show loading state
    const input = document.querySelector(`.qty-input[data-cart-key="${cartKey}"]`);
    if (input) {
        input.disabled = true;
        input.style.opacity = '0.5';
    }
    
    const formData = new URLSearchParams({
        action: 'update_cart_quantity',
        cart_key: cartKey,
        quantity: quantity,
        nonce: nonce
    });
    
    fetch(ajaxUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Cart update response:', data);
        
        if (data.success) {
            // Update cart counter in header
            updateCartCount();
            
            // Update subtotal for this item (the one in the product card)
            if (data.data.subtotal) {
                // Find the product card container
                const productCard = input.closest('.rounded-xl.border.border-stone-200');
                if (productCard) {
                    // Find the subtotal span within this specific product card
                    // Look for the div with "Subtotal" text, then get the next span
                    const subtotalContainer = productCard.querySelector('.flex.flex-col.items-end.justify-end');
                    if (subtotalContainer) {
                        const subtotalElement = subtotalContainer.querySelector('span.text-lg.text-stone-900');
                        if (subtotalElement) {
                            subtotalElement.innerHTML = data.data.subtotal;
                            console.log('Subtotal updated to:', data.data.subtotal);
                        } else {
                            console.error('Subtotal span not found');
                        }
                    } else {
                        console.error('Subtotal container not found');
                    }
                } else {
                    console.error('Product card not found');
                }
            }
            
            // Update cart total (the one in the teal box at the bottom)
            if (data.data.cart_total) {
                // Find the total in the teal box specifically
                const totalBox = document.querySelector('.border-teal-200.bg-teal-50');
                if (totalBox) {
                    const totalElement = totalBox.querySelector('.text-teal-700');
                    if (totalElement) {
                        totalElement.innerHTML = data.data.cart_total;
                    }
                }
            }
            
            // Also update the cart count badge
            if (data.data.cart_count !== undefined) {
                const cartCountElements = document.querySelectorAll('.cart-count');
                cartCountElements.forEach(element => {
                    element.textContent = data.data.cart_count;
                    
                    // Show/hide badge based on count
                    if (data.data.cart_count > 0) {
                        element.classList.remove('hidden');
                    } else {
                        element.classList.add('hidden');
                    }
                });
            }
            
            // If quantity is 0, remove the item from view
            if (quantity === 0) {
                const productCard = input.closest('.rounded-xl');
                if (productCard) {
                    productCard.style.transition = 'opacity 0.3s';
                    productCard.style.opacity = '0';
                    setTimeout(() => {
                        productCard.remove();
                        
                        // Check if cart is empty
                        const remainingItems = document.querySelectorAll('.qty-input').length;
                        if (remainingItems === 0) {
                            window.location.reload();
                        }
                    }, 300);
                }
            }
            
            showNotification('Carrito actualizado', 'success');
        } else {
            showNotification(data.data.message || 'Error al actualizar el carrito', 'error');
            // Revert the input value
            if (input && data.data.old_quantity) {
                input.value = data.data.old_quantity;
            }
        }
    })
    .catch(error => {
        console.error('Error updating cart:', error);
        showNotification('Error al actualizar el carrito', 'error');
    })
    .finally(() => {
        // Remove loading state
        if (input) {
            input.disabled = false;
            input.style.opacity = '1';
        }
    });
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

/**
 * Initialize order popup functionality
 */
function initOrderPopup() {
    console.log('Initializing order popup...');
    
    const openButton = document.getElementById('open-order-popup');
    const closeButton = document.getElementById('close-order-popup');
    const popup = document.getElementById('order-popup');
    const orderForm = document.getElementById('order-details-form');
    
    if (!openButton || !popup) {
        console.log('Order popup elements not found on this page');
        return;
    }
    
    // Open popup
    openButton.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Opening order popup...');
        popup.classList.remove('hidden');
        popup.classList.add('flex');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    });
    
    // Close popup
    if (closeButton) {
        closeButton.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Closing order popup...');
            popup.classList.add('hidden');
            popup.classList.remove('flex');
            document.body.style.overflow = ''; // Restore scrolling
        });
    }
    
    // Close popup when clicking outside
    popup.addEventListener('click', function(e) {
        if (e.target === popup) {
            console.log('Closing popup (clicked outside)...');
            popup.classList.add('hidden');
            popup.classList.remove('flex');
            document.body.style.overflow = '';
        }
    });
    
    // Handle form submission
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Order form submitted!');
            
            const submitButton = document.getElementById('submit-order-btn');
            const originalText = submitButton.textContent;
            
            // Disable button and show loading
            submitButton.disabled = true;
            submitButton.textContent = 'Procesando...';
            submitButton.style.opacity = '0.6';
            submitButton.style.cursor = 'not-allowed';
            submitButton.classList.add('pointer-events-none');
            
            // Get form data
            const formData = new FormData(orderForm);
            const customerData = {
                nombre: formData.get('nombre'),
                apellido: formData.get('apellido'),
                celular: formData.get('celular'),
                zona: formData.get('zona'),
                direccion: formData.get('direccion')
            };
            
            console.log('Customer data:', customerData);
            
            // Get AJAX URL
            const ajaxUrl = getAjaxUrl();
            
            // Prepare data for AJAX request
            const requestData = new URLSearchParams({
                action: 'process_custom_order',
                nonce: getWooCommerceNonce(),
                customer_data: JSON.stringify(customerData)
            });
            
            // Send AJAX request
            fetch(ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: requestData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Order response:', data);
                
                if (data.success) {
                    // Show success message
                    showNotification('¡Orden realizada con éxito!', 'success');
                    
                    // Redirect to thank you page
                    setTimeout(() => {
                        if (data.data.redirect_url) {
                            window.location.href = data.data.redirect_url;
                        } else {
                            window.location.reload();
                        }
                    }, 1500);
                } else {
                    // Show error message
                    showNotification(data.data.message || 'Error al procesar la orden', 'error');
                    
                    // Re-enable button
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                    submitButton.style.opacity = '1';
                    submitButton.style.cursor = 'pointer';
                    submitButton.classList.remove('pointer-events-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al procesar la orden', 'error');
                
                // Re-enable button
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                submitButton.style.opacity = '1';
                submitButton.style.cursor = 'pointer';
                submitButton.classList.remove('pointer-events-none');
            });
        });
    }
}

/**
 * Initialize AJAX add to cart for special products section
 */
function initAjaxAddToCartSpecial() {
    const specialForm = document.querySelector('.ajax-add-to-cart-special');
    
    if (!specialForm) {
        return; // Form not present on this page
    }
    
    specialForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Special product add to cart triggered');
        
        const productId = this.dataset.productId;
        const quantity = this.querySelector('input[name="quantity"]').value;
        const button = this.querySelector('.special-add-to-cart-btn');
        const notification = document.getElementById('cart-notification');
        
        // Save original button text
        const originalText = button.innerHTML;
        
        // Disable button and show loading
        button.disabled = true;
        button.innerHTML = '⏳ Agregando...';
        
        // Get AJAX URL
        const ajaxUrl = getAjaxUrl();
        
        // Prepare form data
        const formData = new URLSearchParams({
            action: 'woocommerce_ajax_add_to_cart',
            product_id: productId,
            quantity: quantity
        });
        
        // Send AJAX request
        fetch(ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Add to cart response:', data);
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Update cart count
            updateCartCount();
            
            // Show success notification
            if (notification) {
                notification.classList.remove('hidden');
                notification.classList.add('animate-bounce');
                
                // Hide notification after 3 seconds
                setTimeout(() => {
                    notification.classList.add('hidden');
                    notification.classList.remove('animate-bounce');
                }, 3000);
            }
            
            // Reset button
            button.disabled = false;
            button.innerHTML = originalText;
            
            // Trigger WooCommerce fragments refresh
            jQuery(document.body).trigger('wc_fragment_refresh');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al agregar el producto al carrito. Por favor, intenta nuevamente.');
            
            // Reset button
            button.disabled = false;
            button.innerHTML = originalText;
        });
    });
}
