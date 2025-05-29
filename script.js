document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu initialization
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        console.log('Mobile menu elements found');

        // Add click event to button
        mobileMenuButton.addEventListener('click', function(e) {
            console.log('Mobile menu button clicked');
            e.preventDefault();
            e.stopPropagation();

            const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
            console.log('Current expanded state:', isExpanded);
            
            mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
            mobileMenu.classList.toggle('hidden');
            
            // Update button icon
            const buttonIcon = mobileMenuButton.querySelector('svg');
            if (buttonIcon) {
                if (!isExpanded) {
                    buttonIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    `;
                } else {
                    buttonIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    `;
                }
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenu.contains(e.target) && 
                !mobileMenuButton.contains(e.target) && 
                !mobileMenu.classList.contains('hidden')) {
                console.log('Clicked outside menu');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
                mobileMenu.classList.add('hidden');
                
                // Reset button icon
                const buttonIcon = mobileMenuButton.querySelector('svg');
                if (buttonIcon) {
                    buttonIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    `;
                }
            }
        });

        // Close menu when clicking a menu item
        const menuItems = mobileMenu.querySelectorAll('a, button');
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                console.log('Menu item clicked');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
                mobileMenu.classList.add('hidden');
                
                // Reset button icon
                const buttonIcon = mobileMenuButton.querySelector('svg');
                if (buttonIcon) {
                    buttonIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    `;
                }
            });
        });

        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
                console.log('Escape key pressed');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
                mobileMenu.classList.add('hidden');
                
                // Reset button icon
                const buttonIcon = mobileMenuButton.querySelector('svg');
                if (buttonIcon) {
                    buttonIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    `;
                }
            }
        });

        // Ensure menu is hidden initially
        mobileMenuButton.setAttribute('aria-expanded', 'false');
        mobileMenu.classList.add('hidden');
        console.log('Initial menu setup complete');
    } else {
        console.error('Mobile menu elements not found');
    }
});
