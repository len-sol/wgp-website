document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu initialization
    initializeMobileMenu();
    
    // Product slider initialization
    initializeProductSlider();
});

// Mobile menu functions
function initializeMobileMenu() {
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.querySelector('.mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        // Toggle menu on button click
        mobileMenuButton.addEventListener('click', (e) => {
            e.stopPropagation();
            mobileMenu.classList.toggle('hidden');
        });

        // Add click event listeners to all menu items
        const menuItems = mobileMenu.querySelectorAll('a, button');
        menuItems.forEach(item => {
            item.addEventListener('click', (e) => {
                // Prevent any default behavior that might interfere
                e.stopPropagation();
                // Ensure the menu is hidden
                mobileMenu.classList.add('hidden');
                // If it's a button with onclick attribute (like warranty), let it execute
                if (item.tagName === 'BUTTON' && item.getAttribute('onclick')) {
                    const onclickFn = item.getAttribute('onclick');
                    setTimeout(() => {
                        eval(onclickFn);
                    }, 0);
                }
            });
        });

        // Close menu on outside click
        document.addEventListener('click', (e) => {
            if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Prevent menu from closing when clicking inside it (except for menu items)
        mobileMenu.addEventListener('click', (e) => {
            if (e.target === mobileMenu) {
                e.stopPropagation();
            }
        });
    }
}



// Warranty Form Functions
function openWarrantyForm() {
    // Close any existing modal first
    const existingModal = document.querySelector('.modal-content');
    if (existingModal) {
        existingModal.closest('.fixed').remove();
    }

    const modal = document.getElementById('warrantyModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Add keyboard navigation
        modal.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeWarrantyForm();
            }
        });
    }
}

function closeWarrantyForm() {
    const modal = document.getElementById('warrantyModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Product Slider Functions
function initializeProductSlider() {
    let currentSlide = 0;
    const slides = document.querySelector('.slides');
    const dots = document.querySelectorAll('.slider-dot');
    let touchStartX = 0;
    let touchEndX = 0;

    if (slides && dots.length > 0) {
        function updateSlider() {
            slides.style.transform = `translateX(-${currentSlide * 100}%)`;
            dots.forEach((dot, index) => {
                dot.classList.toggle('bg-yellow-500', index === currentSlide);
                dot.classList.toggle('bg-yellow-200', index !== currentSlide);
                dot.setAttribute('aria-selected', index === currentSlide);
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % dots.length;
            updateSlider();
        }

        function previousSlide() {
            currentSlide = (currentSlide - 1 + dots.length) % dots.length;
            updateSlider();
        }

        // Click navigation
        dots.forEach((dot, index) => {
            dot.setAttribute('role', 'tab');
            dot.setAttribute('aria-label', `Slide ${index + 1}`);
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateSlider();
            });
        });

        // Touch navigation
        slides.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
        });

        slides.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].clientX;
            const difference = touchStartX - touchEndX;
            
            if (Math.abs(difference) > 50) { // Minimum swipe distance
                if (difference > 0) {
                    nextSlide();
                } else {
                    previousSlide();
                }
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                previousSlide();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
            }
        });

        // Initialize first slide
        updateSlider();
    }
}

// Product Spec Modal Functions
function openProductSpec(title, specs) {
    // Close any existing modal first
    const existingModal = document.querySelector('.fixed.bg-black.bg-opacity-50');
    if (existingModal) {
        existingModal.remove();
    }

    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 relative animate-zoom-out">
            <button onclick="this.closest('.fixed').remove()" 
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
                    aria-label="Close modal">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="text-2xl font-bold mb-4">${title}</h3>
            <div class="prose max-h-[70vh] overflow-y-auto">${specs}</div>
        </div>
    `;

    document.body.appendChild(modal);

    // Add keyboard navigation
    modal.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            modal.remove();
        }
    });

    // Close on outside click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    });

    // Focus trap
    const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    const firstFocusable = focusableElements[0];
    const lastFocusable = focusableElements[focusableElements.length - 1];

    firstFocusable.focus();

    modal.addEventListener('keydown', (e) => {
        if (e.key === 'Tab') {
            if (e.shiftKey && document.activeElement === firstFocusable) {
                e.preventDefault();
                lastFocusable.focus();
            } else if (!e.shiftKey && document.activeElement === lastFocusable) {
                e.preventDefault();
                firstFocusable.focus();
            }
        }
    });
}
