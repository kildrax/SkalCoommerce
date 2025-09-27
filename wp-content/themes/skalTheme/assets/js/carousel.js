/**
 * Multiple Carousel functionality for skalTheme
 */

document.addEventListener('DOMContentLoaded', function() {
    // Find all carousel sections and initialize them
    const carouselSections = document.querySelectorAll('section');
    
    carouselSections.forEach((section, sectionIndex) => {
        const carouselContainer = section.querySelector('.carousel-container');
        if (carouselContainer) {
            initCarousel(section, sectionIndex);
        }
    });
});

function initCarousel(section, carouselIndex) {
    const track = section.querySelector('.carousel-track');
    const slides = section.querySelectorAll('.carousel-slide');
    const indicators = section.querySelectorAll('.indicator');
    const nextBtn = section.querySelector('.carousel-next');
    const prevBtn = section.querySelector('.carousel-prev');
    
    if (!track || slides.length === 0) return;
    
    let currentSlide = 0;
    let isDragging = false;
    let startX = 0;
    let currentX = 0;
    let autoPlayInterval;
    
    function updateCarousel() {
        track.style.transform = `translateX(-${currentSlide * 100}%)`;
        
        // Update indicators
        indicators.forEach((indicator, index) => {
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
        currentSlide = (currentSlide + 1) % slides.length;
        updateCarousel();
    }
    
    function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
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
    
    // Auto-play functionality
    startAutoPlay();
    
    // Pause auto-play on hover
    track.addEventListener('mouseenter', stopAutoPlay);
    track.addEventListener('mouseleave', startAutoPlay);
    
    // Navigation event listeners
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);
    
    // Indicator navigation
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => goToSlide(index));
    });
    
    // Touch/drag support for mobile
    track.addEventListener('touchstart', (e) => {
        // Don't start dragging if touching interactive elements
        const target = e.target;
        if (target.closest('button') || target.closest('input') || target.closest('form') || target.closest('a')) {
            return;
        }
        
        startX = e.touches[0].clientX;
        isDragging = true;
        track.style.transition = 'none';
        stopAutoPlay();
    });
    
    track.addEventListener('touchmove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        currentX = e.touches[0].clientX;
        const diffX = currentX - startX;
        const translateX = -currentSlide * 100 + (diffX / track.offsetWidth) * 100;
        track.style.transform = `translateX(${translateX}%)`;
    });
    
    track.addEventListener('touchend', (e) => {
        if (!isDragging) return;
        isDragging = false;
        track.style.transition = 'transform 0.3s ease-in-out';
        
        const diffX = currentX - startX;
        const threshold = track.offsetWidth * 0.2; // 20% threshold
        
        if (diffX > threshold) {
            prevSlide();
        } else if (diffX < -threshold) {
            nextSlide();
        } else {
            updateCarousel(); // Snap back to current slide
        }
        
        startAutoPlay();
    });
    
    // Mouse drag support for desktop
    track.addEventListener('mousedown', (e) => {
        // Don't start dragging if clicking interactive elements
        const target = e.target;
        if (target.closest('button') || target.closest('input') || target.closest('form') || target.closest('a')) {
            return;
        }
        
        startX = e.clientX;
        isDragging = true;
        track.style.transition = 'none';
        track.style.cursor = 'grabbing';
        stopAutoPlay();
    });
    
    // Create scoped mouse event handlers for this specific carousel
    const handleMouseMove = (e) => {
        if (!isDragging) return;
        e.preventDefault();
        currentX = e.clientX;
        const diffX = currentX - startX;
        const translateX = -currentSlide * 100 + (diffX / track.offsetWidth) * 100;
        track.style.transform = `translateX(${translateX}%)`;
    };
    
    const handleMouseUp = (e) => {
        if (!isDragging) return;
        isDragging = false;
        track.style.transition = 'transform 0.3s ease-in-out';
        track.style.cursor = 'grab';
        
        const diffX = currentX - startX;
        const threshold = track.offsetWidth * 0.2;
        
        if (diffX > threshold) {
            prevSlide();
        } else if (diffX < -threshold) {
            nextSlide();
        } else {
            updateCarousel();
        }
        
        startAutoPlay();
        
        // Remove event listeners after use
        document.removeEventListener('mousemove', handleMouseMove);
        document.removeEventListener('mouseup', handleMouseUp);
    };
    
    // Add mouse event listeners when dragging starts
    track.addEventListener('mousedown', () => {
        if (isDragging) {
            document.addEventListener('mousemove', handleMouseMove);
            document.addEventListener('mouseup', handleMouseUp);
        }
    });
    
    // Initialize
    updateCarousel();
    track.style.cursor = 'grab';
}
