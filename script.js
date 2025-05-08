document.addEventListener('DOMContentLoaded', function() {
    // File input name update
    const fileInput = document.getElementById('payment');
    const fileNameSpan = document.querySelector('.file-name');

    if (fileInput && fileNameSpan) {
        fileInput.addEventListener('change', function() {
            fileNameSpan.textContent = this.files[0] ? this.files[0].name : 'No file chosen';
        });
    }

    // Scroll animation with Intersection Observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-zoom-out');
                observer.unobserve(entry.target); // Stop observing once animation is triggered
            }
        });
    }, {
        threshold: 0.1 // Trigger when 10% of the element is visible
    });

    // Observe product cards
    document.querySelectorAll('.bg-white.rounded-xl').forEach(card => {
        observer.observe(card);
    });

    // Observe location cards
    document.querySelectorAll('.location-card').forEach(card => {
        observer.observe(card);
    });

    // Warranty Form Functions
    function openWarrantyForm() {
        const modal = document.getElementById('warrantyModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeWarrantyForm() {
        const modal = document.getElementById('warrantyModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Make functions globally accessible
    window.openWarrantyForm = openWarrantyForm;
    window.closeWarrantyForm = closeWarrantyForm;

    // Handle form submission
    const warrantyForm = document.getElementById('warrantyForm');
    if (warrantyForm) {
        warrantyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Here you would typically send the form data to a server
            alert('Thank you for submitting your warranty registration!');
            closeWarrantyForm();
        });
    }

    // Close modal when clicking outside
    const warrantyModal = document.getElementById('warrantyModal');
    if (warrantyModal) {
        warrantyModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeWarrantyForm();
            }
        });
    }

    // Mobile menu functionality
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.querySelector('.mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });

// Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    }

    // Product Specification Modal Functions
    function openProductSpec(productName, specs) {
        const modal = document.createElement('div');
        modal.id = 'productSpecModal';
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        
        modal.innerHTML = `
            <div class="modal-content max-w-md w-full p-8 rounded-lg shadow-xl relative overflow-hidden border-4 border-blue-600" style="background-color: #ffffe0; background-image: none;">
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">${productName}</h2>
                        <button onclick="closeProductSpec()" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="space-y-4">
                        ${specs}
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeProductSpec();
            }
        });
    }

    function closeProductSpec() {
        const modal = document.getElementById('productSpecModal');
        if (modal) {
            modal.remove();
        }
    }

    // Make functions globally accessible
    window.openProductSpec = openProductSpec;
    window.closeProductSpec = closeProductSpec;

    // Product Slider functionality
    const slider = document.querySelector('.slides');
    const dots = document.querySelectorAll('.slider-dot');
    let currentSlide = 0;
    const slideCount = dots.length;
    let slideInterval;

    function updateSlider() {
        if (slider) {
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            // Update dots
            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.add('bg-yellow-500');
                    dot.classList.remove('bg-yellow-200');
                } else {
                    dot.classList.remove('bg-yellow-500');
                    dot.classList.add('bg-yellow-200');
                }
            });
        }
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slideCount;
        updateSlider();
    }

    function startSlideshow() {
        if (slideInterval) {
            clearInterval(slideInterval);
        }
        slideInterval = setInterval(nextSlide, 3000); // Change slide every 3 seconds
    }

    function stopSlideshow() {
        if (slideInterval) {
            clearInterval(slideInterval);
        }
    }

    // Initialize slider
    if (slider && dots.length > 0) {
        // Add click events to dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateSlider();
                stopSlideshow();
                startSlideshow();
            });
        });

        // Start automatic slideshow
        startSlideshow();

        // Touch events for mobile swipe
        let touchStartX = 0;
        let touchEndX = 0;

        slider.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
            stopSlideshow();
        }, false);

        slider.addEventListener('touchmove', (e) => {
            touchEndX = e.touches[0].clientX;
            const diff = touchStartX - touchEndX;
            const sensitivity = 50; // Minimum swipe distance
            
            if (Math.abs(diff) > sensitivity) {
                slider.style.transform = `translateX(${-currentSlide * 100 - (diff / slider.offsetWidth) * 100}%)`;
            }
        }, false);

        slider.addEventListener('touchend', () => {
            const swipeDistance = touchEndX - touchStartX;
            const sensitivity = 50; // Minimum swipe distance
            
            if (Math.abs(swipeDistance) > sensitivity) {
                if (swipeDistance > 0) {
                    // Swipe right - show previous slide
                    currentSlide = (currentSlide - 1 + slideCount) % slideCount;
                } else {
                    // Swipe left - show next slide
                    currentSlide = (currentSlide + 1) % slideCount;
                }
            }
            updateSlider();
            startSlideshow();
        }, false);

        // Pause slideshow on hover
        slider.addEventListener('mouseenter', stopSlideshow);
        slider.addEventListener('mouseleave', startSlideshow);

        // Initial update
        updateSlider();
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(targetId);
                if (target) {
                    const offset = 80; // Navbar height
                    const targetPosition = target.offsetTop - offset;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    if (mobileMenu) {
                        mobileMenu.classList.add('hidden');
                    }
                }
            }
        });
    });
});

// This is just a fallback in case CSS animations aren't supported
document.addEventListener('DOMContentLoaded', function() {
    // All animations are handled by CSS in this version
    console.log('Animation ready!');
});