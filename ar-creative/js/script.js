// Mobile Navigation Menu Toggle
const mobileMenu = document.getElementById('mobile-menu');
const navLinks = document.querySelector('.nav-links');

mobileMenu.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    // Change icon between menu and x
    const icon = mobileMenu.querySelector('i');
    if (navLinks.classList.contains('active')) {
        icon.classList.replace('bx-menu', 'bx-x');
    } else {
        icon.classList.replace('bx-x', 'bx-menu');
    }
});

// Close mobile menu when a link is clicked
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
        navLinks.classList.remove('active');
        mobileMenu.querySelector('i').classList.replace('bx-x', 'bx-menu');
    });
});

// Sticky Header on Scroll
const header = document.querySelector('header');
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        header.classList.add('sticky');
    } else {
        header.classList.remove('sticky');
    }
});

// Scroll-triggered Animations using Intersection Observer
const animationOptions = {
    threshold: 0.2
};

const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            // Add a small delay if specified
            const delay = entry.target.getAttribute('data-delay');
            if (delay) {
                setTimeout(() => {
                    entry.target.classList.add('appeared');
                }, parseFloat(delay) * 1000);
            } else {
                entry.target.classList.add('appeared');
            }
            observer.unobserve(entry.target);
        }
    });
}, animationOptions);

document.querySelectorAll('.animate-on-scroll').forEach(el => {
    observer.observe(el);
});

// Contact Form Handling (Simple Client-side logic)
const contactForm = document.querySelector('.contact-form form');
if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
        // If it was real PHP, we might let it submit,
        // but for this demo let's show how we'd handle it.
        // If we want to use the PHP file, we can either use fetch or just let it redirect.
        // The prompt says "Optional PHP only for contact form", so I'll let it submit to contact.php.
        // But I'll add a console log for verification.
        console.log('Form submitted');
    });
}
