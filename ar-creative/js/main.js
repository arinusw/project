/**
 * Ar Creative - Main JavaScript
 * Digital Creative Portfolio & Service Website
 */

document.addEventListener('DOMContentLoaded', () => {

    // --- Mobile Navigation Toggle ---
    const navToggle = document.getElementById('nav-toggle');
    const navLinks = document.querySelector('.nav-links');
    const navToggleIcon = navToggle.querySelector('i');

    if (navToggle) {
        navToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');

            // Change icon between menu and x
            if (navLinks.classList.contains('active')) {
                navToggleIcon.className = 'bx bx-x';
            } else {
                navToggleIcon.className = 'bx bx-menu';
            }
        });
    }

    // Close mobile menu when a link is clicked
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            navLinks.classList.remove('active');
            navToggleIcon.className = 'bx bx-menu';
        });
    });

    // --- Scroll Reveal Animation ---
    const revealElements = document.querySelectorAll('.reveal');

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                // Once it's revealed, we don't need to observe it anymore
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15 // Reveal when 15% of the element is visible
    });

    revealElements.forEach(element => {
        revealObserver.observe(element);
    });

    // --- Active Link Highlighting on Scroll ---
    const sections = document.querySelectorAll('section[id]');

    window.addEventListener('scroll', () => {
        const scrollY = window.pageYOffset;

        sections.forEach(current => {
            const sectionHeight = current.offsetHeight;
            const sectionTop = current.offsetTop - 100;
            const sectionId = current.getAttribute('id');
            const navItem = document.querySelector(`.nav-links a[href*=${sectionId}]`);

            if (navItem) {
                if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                    navItem.classList.add('active-link');
                } else {
                    navItem.classList.remove('active-link');
                }
            }
        });
    });

    // --- Sticky Header Effect ---
    const header = document.querySelector('header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.style.padding = '10px 0';
            header.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.padding = '0';
            header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.05)';
        }
    });

});
