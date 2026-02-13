/**
 * Ar Creative - JavaScript
 * Features: Mobile Menu, Header Scroll, Reveal Animation, Scroll Active Link
 */

/* Menu Show Y Hidden */
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close');

/* Menu Show */
if(navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('active');
    });
}

/* Menu Hidden */
if(navClose) {
    navClose.addEventListener('click', () => {
        navMenu.classList.remove('active');
    });
}

/* Remove Menu Mobile */
const navLink = document.querySelectorAll('.nav-link');

function linkAction() {
    const navMenu = document.getElementById('nav-menu');
    navMenu.classList.remove('active');
}
navLink.forEach(n => n.addEventListener('click', linkAction));

/* Change Background Header */
function scrollHeader() {
    const header = document.getElementById('header');
    if(this.scrollY >= 50) {
        header.classList.add('scroll-header');
    } else {
        header.classList.remove('scroll-header');
    }
}
window.addEventListener('scroll', scrollHeader);

/* Show Scroll Up */
function scrollUp() {
    const scrollUp = document.getElementById('scroll-up');
    if(this.scrollY >= 200) {
        scrollUp.classList.add('show-scroll');
    } else {
        scrollUp.classList.remove('show-scroll');
    }
}
window.addEventListener('scroll', scrollUp);

/* Scroll Sections Active Link */
const sections = document.querySelectorAll('section[id]');

function scrollActive() {
    const scrollY = window.pageYOffset;

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight;
        const sectionTop = current.offsetTop - 50;
        let sectionId = current.getAttribute('id');

        if(scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
            document.querySelector('.nav-menu a[href*=' + sectionId + ']').classList.add('active-link');
        } else {
            document.querySelector('.nav-menu a[href*=' + sectionId + ']').classList.remove('active-link');
        }
    });
}
window.addEventListener('scroll', scrollActive);

/* Reveal Animation using Intersection Observer */
const revealElements = document.querySelectorAll('.reveal');

const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if(entry.isIntersecting) {
            entry.target.classList.add('active');
            revealObserver.unobserve(entry.target);
        }
    });
}, {
    threshold: 0.1
});

revealElements.forEach(el => {
    revealObserver.observe(el);
});

/* Simple Contact Form Submission (Simulation) */
const contactForm = document.querySelector('.contact-form');
if(contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Terima kasih! Pesan Anda telah dikirim (simulasi). Kami akan segera menghubungi Anda.');
        contactForm.reset();
    });
}
