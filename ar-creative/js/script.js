/*
   JavaScript for Ar Creative Portfolio
   - Mobile Navigation Toggle
   - Scroll-triggered reveal animations using Intersection Observer
   - Sticky Header effect
*/

document.addEventListener('DOMContentLoaded', () => {

    // Mobile Menu Toggle
    const menuBtn = document.querySelector('.menu-btn');
    const navLinks = document.querySelector('.nav-links');
    const menuIcon = document.querySelector('.menu-btn i');

    if (menuBtn && navLinks) {
        menuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');

            // Change icon when menu is open/closed
            if (navLinks.classList.contains('active')) {
                menuIcon.classList.replace('bx-menu', 'bx-x');
            } else {
                menuIcon.classList.replace('bx-x', 'bx-menu');
            }
        });

        // Close menu when a link is clicked
        const links = document.querySelectorAll('.nav-links a');
        links.forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                menuIcon.classList.replace('bx-x', 'bx-menu');
            });
        });
    }

    // Sticky Header on Scroll
    const header = document.querySelector('header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.style.padding = '15px 0';
            header.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.padding = '20px 0';
            header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.05)';
        }
    });

    // Intersection Observer for Reveal Animations
    const revealElements = document.querySelectorAll('.reveal');

    const revealOptions = {
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
    };

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                // Optional: stop observing once revealed
                // observer.unobserve(entry.target);
            }
        });
    }, revealOptions);

    revealElements.forEach(el => {
        revealObserver.observe(el);
    });

    // Form Submission (Simulated)
    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Terima kasih! Pesan Anda telah dikirim. (Ini adalah simulasi)');
            contactForm.reset();
        });
    }
});
