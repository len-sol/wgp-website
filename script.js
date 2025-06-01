// Mobile menu functions
function initializeMobileMenu() {
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.querySelector('.mobile-menu');

    if (!mobileMenuButton || !mobileMenu) {
        console.error('Mobile menu elements not found');
        return;
    }

    // Toggle menu on button click
    mobileMenuButton.addEventListener('click', (e) => {
        e.stopPropagation();
        const isExpanded = mobileMenu.classList.contains('hidden') === false;
        mobileMenu.classList.toggle('hidden');
        mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
    });

    // Add click event listeners to all menu items
    const menuItems = mobileMenu.querySelectorAll('a, button');
    menuItems.forEach(item => {
        item.addEventListener('click', (e) => {
            // Prevent any default behavior that might interfere
            e.stopPropagation();
            // Ensure the menu is hidden and update ARIA state
            mobileMenu.classList.add('hidden');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
            
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
            mobileMenuButton.setAttribute('aria-expanded', 'false');
        }
    });

    // Prevent menu from closing when clicking inside it (except for menu items)
    mobileMenu.addEventListener('click', (e) => {
        if (e.target === mobileMenu) {
            e.stopPropagation();
        }
    });

    // Add keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileMenu.classList.contains('hidden') === false) {
            mobileMenu.classList.add('hidden');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
        }
    });
}

// Warranty Form Functions
function openWarrantyForm() {
    const modal = document.getElementById('warrantyModal');
    if (!modal) {
        console.error("Warranty modal element not found!");
        return;
    }

    // Toggle visibility without removing the modal element
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // For accessibility, add a one-time keyboard event listener to close on Escape key
    const escapeHandler = function(e) {
        if (e.key === 'Escape') {
            closeWarrantyForm();
            // Remove the event listener after use to prevent duplicates
            modal.removeEventListener('keydown', escapeHandler);
        }
    };
    
    // Remove any existing handlers before adding a new one
    modal.removeEventListener('keydown', escapeHandler);
    modal.addEventListener('keydown', escapeHandler);

    // Reset form fields
    const form = modal.querySelector('#warrantyForm');
    if (form) {
        form.reset();
    }
}

function closeWarrantyForm() {
    const modal = document.getElementById('warrantyModal');
    if (!modal) {
        console.error("Warranty modal element not found!");
        return;
    }

    modal.classList.add('hidden');
    modal.classList.remove('flex');
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

// Warranty Checker Functions
function openWarrantyCheckerForm() {
    const modal = document.getElementById('warrantyCheckerModal');
    if (!modal) {
        console.error("Warranty checker modal element not found!");
        return;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Reset form
    const form = modal.querySelector('#warrantyCheckerForm');
    if (form) {
        form.reset();
    }

    // Add escape key handler
    const escapeHandler = function(e) {
        if (e.key === 'Escape') {
            closeWarrantyCheckerForm();
            modal.removeEventListener('keydown', escapeHandler);
        }
    };
    modal.removeEventListener('keydown', escapeHandler);
    modal.addEventListener('keydown', escapeHandler);
}

function closeWarrantyCheckerForm() {
    const modal = document.getElementById('warrantyCheckerModal');
    if (!modal) {
        console.error("Warranty checker modal element not found!");
        return;
    }
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function openWarrantyResultModal() {
    const modal = document.getElementById('warrantyResultModal');
    if (!modal) {
        console.error("Warranty result modal element not found!");
        return;
    }
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeWarrantyResultModal() {
    const modal = document.getElementById('warrantyResultModal');
    if (!modal) {
        console.error("Warranty result modal element not found!");
        return;
    }
    modal.classList.add('hidden');
    modal.classList.remove('flex');
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

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize core functionality
    initializeMobileMenu();
    initializeProductSlider();
    
    // Initialize Warranty Checker Form
    const warrantyCheckerForm = document.getElementById('warrantyCheckerForm');
    if (warrantyCheckerForm) {
        warrantyCheckerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const serial = document.getElementById('checkerSerial').value.trim();
            
            if (!serial) {
                alert("Please enter a serial number.");
                return;
            }
            
            fetch('Database/check_warranty.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ serial: serial })
            })
            .then(response => response.json())
            .then(data => {
                const contentDiv = document.getElementById('warrantyResultContent');
                contentDiv.innerHTML = "";
                
                if (data.success) {
                    if (data.warranty_start || data.warranty_end) {
                        contentDiv.innerHTML = `
                            <div class="space-y-4">
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <p class="font-medium text-gray-700">Warranty Start:</p>
                                    <p class="text-lg">${data.warranty_start || 'Not available'}</p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <p class="font-medium text-gray-700">Warranty End:</p>
                                    <p class="text-lg">${data.warranty_end || 'Not available'}</p>
                                </div>
                            </div>`;
                    } else {
                        contentDiv.innerHTML = `
                            <div class="p-4 bg-red-50 rounded-lg">
                                <p class="text-red-600 text-center">The data have not been updated yet.</p>
                            </div>`;
                    }
                } else {
                    contentDiv.innerHTML = `
                        <div class="p-4 bg-red-50 rounded-lg">
                            <p class="text-red-600 text-center">${data.message || "Record not found."}</p>
                        </div>`;
                }
                
                closeWarrantyCheckerForm();
                openWarrantyResultModal();
            })
            .catch(error => {
                console.error('Error:', error);
                alert("An error occurred while checking warranty details. Please try again later.");
            });
        });
    }

    // Add click-outside handlers for modals
    const warrantyCheckerModal = document.getElementById('warrantyCheckerModal');
    const warrantyResultModal = document.getElementById('warrantyResultModal');

    if (warrantyCheckerModal) {
        warrantyCheckerModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeWarrantyCheckerForm();
            }
        });
    }

    if (warrantyResultModal) {
        warrantyResultModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeWarrantyResultModal();
            }
        });
    }
});
