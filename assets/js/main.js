    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });

    // Dynamic Indicators
    document.addEventListener("DOMContentLoaded", function() {
        const slider = document.querySelector('#heroSlider');
        if (!slider) return; // Guard: slider only exists on index.php
        
        const slides = slider.querySelectorAll('.carousel-item');
        const indicatorsContainer = slider.querySelector('.carousel-indicators');

        slides.forEach((slide, index) => {
            const button = document.createElement('button');
            button.setAttribute('type', 'button');
            button.setAttribute('data-bs-target', '#heroSlider');
            button.setAttribute('data-bs-slide-to', index);
            button.setAttribute('aria-label', `Slide ${index + 1}`);
            if (index === 0) button.classList.add('active');
            indicatorsContainer.appendChild(button);
        });

        // Loading Spinner
        setTimeout(() => {
            document.getElementById('loadingSpinner').classList.add('fade-out');
        }, 500);
    });

    // Navbar Scroll Effect
    window.addEventListener('scroll', function() {
        const header = document.querySelector('.main-header');
        if (window.scrollY > 50) {
            header.style.background = '#16232e';
            header.style.padding = '8px 0';
        } else {
            header.style.background = '#1e2f3c';
            header.style.padding = '12px 0';
        }
    });

    // Active Link Highlight
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });