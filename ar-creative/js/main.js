/**
 * Ar Creative - Main Interactive JavaScript
 * Language: English comments, Indonesian user interface
 */

"use strict";

/*==================== MENU SHOW Y HIDDEN ====================*/
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close');

/*===== MENU SHOW =====*/
/* Validate if constant exists */
if (navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu');
    });
}

/*===== MENU HIDDEN =====*/
/* Validate if constant exists */
if (navClose) {
    navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu');
    });
}

/*==================== REMOVE MENU MOBILE ====================*/
const navLink = document.querySelectorAll('.nav__link');

function linkAction() {
    const navMenu = document.getElementById('nav-menu');
    // When we click on each nav__link, we remove the show-menu class
    navMenu.classList.remove('show-menu');
}
navLink.forEach(n => n.addEventListener('click', linkAction));


/*==================== CHANGE BACKGROUND HEADER ====================*/
function scrollHeader() {
    const header = document.getElementById('header');
    // When the scroll is greater than 50 viewport height, add the scroll-header class to the header tag
    if (window.scrollY >= 50) {
        header.classList.add('scroll-header');
    } else {
        header.classList.remove('scroll-header');
    }
}
window.addEventListener('scroll', scrollHeader);


/*==================== SHOW SCROLL UP ====================*/
function scrollUp() {
    const scrollUpButton = document.getElementById('scroll-up');
    // When the scroll is higher than 350 viewport height, add the show-scroll class to the a tag with the scroll-top class
    if (window.scrollY >= 350) {
        scrollUpButton.classList.add('show-scroll');
    } else {
        scrollUpButton.classList.remove('show-scroll');
    }
}
window.addEventListener('scroll', scrollUp);


/*==================== SCROLL SECTIONS ACTIVE LINK ====================*/
const sections = document.querySelectorAll('section[id]');

function scrollActive() {
    const scrollY = window.scrollY; // Use window.scrollY as per strict standard & memory

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight;
        const sectionTop = current.offsetTop - 58;
        const sectionId = current.getAttribute('id');

        // Find corresponding nav link scoped to the navigation list
        const navLinkElement = document.querySelector('#nav-menu a[href*=' + sectionId + ']');

        if (navLinkElement) {
            if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                navLinkElement.classList.add('active-link');
            } else {
                navLinkElement.classList.remove('active-link');
            }
        }
    });
}
window.addEventListener('scroll', scrollActive);


/*==================== DARK LIGHT THEME ====================*/
const themeButton = document.getElementById('theme-button');
const darkTheme = 'dark-theme';
const iconTheme = 'ri-sun-line'; // The class of the active icon when dark theme is enabled

// Previously selected topic (if user selected)
const selectedTheme = localStorage.getItem('selected-theme');
const selectedIcon = localStorage.getItem('selected-icon');

// We obtain the current theme that the interface has by validating the dark-theme class
const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-moon-line' : 'ri-sun-line';

// We validate if the user previously chose a topic
if (selectedTheme) {
  // If the validation is fulfilled, we ask what the issue was to know if we activated or deactivated the dark theme
  document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
  themeButton.classList[selectedIcon === 'ri-moon-line' ? 'add' : 'remove'](iconTheme);
}

// Activate / deactivate the theme manually with the button
if (themeButton) {
    themeButton.addEventListener('click', () => {
        // Add or remove the dark / icon theme
        document.body.classList.toggle(darkTheme);
        themeButton.classList.toggle(iconTheme);

        // We save the theme and the current icon that the user chose
        localStorage.setItem('selected-theme', getCurrentTheme());
        localStorage.setItem('selected-icon', getCurrentIcon());
    });
}


/*==================== CONTACT FORM SUBMISSION VIA FETCH API ====================*/
const contactForm = document.getElementById('contact-form');
const contactMessage = document.getElementById('contact-message');

if (contactForm && contactMessage) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();

        // Show a sending status message
        contactMessage.className = 'contact__message';
        contactMessage.textContent = 'Mengirim pesan...';

        const formData = new FormData(contactForm);

        // Fetch to contact.php
        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                // If response is not 200/OK, try parsing JSON anyway or throw error
                return response.json().then(errData => {
                    throw new Error(errData.message || 'Gagal mengirim pesan.');
                }).catch(() => {
                    throw new Error('Terjadi kesalahan pada server. Silakan coba lagi.');
                });
            }
            return response.json();
        })
        .then(data => {
            // Success response
            contactMessage.className = 'contact__message success-color';
            contactMessage.textContent = data.message;

            // Clear input fields on success
            contactForm.reset();

            // Remove status message after 5 seconds
            setTimeout(() => {
                contactMessage.textContent = '';
            }, 5000);
        })
        .catch(error => {
            // Error response
            contactMessage.className = 'contact__message error-color';
            contactMessage.textContent = error.message;

            // Remove status message after 5 seconds
            setTimeout(() => {
                contactMessage.textContent = '';
            }, 5000);
        });
    });
}


/*==================== SCROLLREVEAL ANIMATIONS ====================*/
// Standard ScrollReveal configuration
// Excludes `.home__social` to guarantee immediate visibility
if (typeof ScrollReveal !== 'undefined') {
    const sr = ScrollReveal({
        origin: 'top',
        distance: '60px',
        duration: 1000, // Reduced from 2500ms for better responsiveness and testing visibility
        delay: 400,
        reset: false // Keep elements visible once revealed
    });

    // Reveal animations configuration
    sr.reveal('.home__data');
    sr.reveal('.home__image', { delay: 600, origin: 'bottom' });
    sr.reveal('.about__image', { origin: 'left' });
    sr.reveal('.about__data', { origin: 'right' });
    sr.reveal('.services__card', { interval: 100 });
    sr.reveal('.education__data', { origin: 'left' });
    sr.reveal('.education__features', { origin: 'right' });
    sr.reveal('.socials__card', { interval: 100 });
    sr.reveal('.contact__info', { origin: 'left' });
    sr.reveal('.contact__form-box', { origin: 'right' });
}
