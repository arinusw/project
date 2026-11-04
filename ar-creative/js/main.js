/*==================== MENU SHOW AND HIDE ====================*/
const navMenu = document.getElementById('nav-menu');
const navToggle = document.getElementById('nav-toggle');
const navClose = document.getElementById('nav-close');

/* Validate if navToggle exists */
if (navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu');
    });
}

/* Validate if navClose exists */
if (navClose) {
    navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu');
    });
}

/*==================== REMOVE MENU ON LINK CLICK (MOBILE) ====================*/
const navLinks = document.querySelectorAll('.nav__link');

function linkAction() {
    if (navMenu) {
        navMenu.classList.remove('show-menu');
    }
}
navLinks.forEach(link => link.addEventListener('click', linkAction));

/*==================== CHANGE HEADER BACKGROUND ON SCROLL ====================*/
function scrollHeader() {
    const header = document.getElementById('header');
    if (header) {
        if (window.scrollY >= 50) {
            header.classList.add('scroll-header');
        } else {
            header.classList.remove('scroll-header');
        }
    }
}
window.addEventListener('scroll', scrollHeader);

/*==================== SHOW SCROLL UP BUTTON ====================*/
function scrollUp() {
    const scrollUpBtn = document.getElementById('scroll-up');
    if (scrollUpBtn) {
        if (window.scrollY >= 350) {
            scrollUpBtn.classList.add('show-scroll');
        } else {
            scrollUpBtn.classList.remove('show-scroll');
        }
    }
}
window.addEventListener('scroll', scrollUp);

/*==================== SCROLL SECTIONS ACTIVE LINK ====================*/
const sections = document.querySelectorAll('section[id]');

function scrollActive() {
    const scrollY = window.scrollY;

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight;
        const sectionTop = current.offsetTop - 120;
        const sectionId = current.getAttribute('id');
        const sectionsClass = document.querySelector('#nav-menu a[href*=' + sectionId + ']');

        if (sectionsClass) {
            if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                sectionsClass.classList.add('active-link');
            } else {
                sectionsClass.classList.remove('active-link');
            }
        }
    });
}
window.addEventListener('scroll', scrollActive);

/*==================== DARK / LIGHT THEME TOGGLE ====================*/
const themeButton = document.getElementById('theme-button');
const darkTheme = 'dark-theme';
const iconTheme = 'ri-sun-line';

// Retrieve previously selected topic & icon
const selectedTheme = localStorage.getItem('selected-theme');
const selectedIcon = localStorage.getItem('selected-icon');

const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-sun-line' : 'ri-moon-line';

// Apply saved theme if exists
if (selectedTheme) {
    document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
    if (themeButton) {
        if (selectedIcon === 'ri-sun-line') {
            themeButton.classList.remove('ri-moon-line');
            themeButton.classList.add('ri-sun-line');
        } else {
            themeButton.classList.remove('ri-sun-line');
            themeButton.classList.add('ri-moon-line');
        }
    }
}

// Toggle theme on click
if (themeButton) {
    themeButton.addEventListener('click', () => {
        document.body.classList.toggle(darkTheme);
        themeButton.classList.toggle(iconTheme);
        if (!themeButton.classList.contains(iconTheme)) {
            themeButton.classList.add('ri-moon-line');
        } else {
            themeButton.classList.remove('ri-moon-line');
        }
        localStorage.setItem('selected-theme', getCurrentTheme());
        localStorage.setItem('selected-icon', getCurrentIcon());
    });
}

/*==================== AJAX CONTACT FORM SUBMISSION ====================*/
const contactForm = document.getElementById('contact-form');
const contactMessage = document.getElementById('contact-message');

if (contactForm && contactMessage) {
    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(contactForm);

        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                contactMessage.textContent = data.message;
                contactMessage.classList.remove('color-red');
                contactMessage.classList.add('color-blue');
                contactForm.reset();
            } else {
                contactMessage.textContent = data.message || 'Terjadi kesalahan pada pengiriman formulir.';
                contactMessage.classList.remove('color-blue');
                contactMessage.classList.add('color-red');
            }
            setTimeout(() => {
                contactMessage.textContent = '';
            }, 6000);
        })
        .catch(err => {
            contactMessage.textContent = 'Terjadi kesalahan sistem. Silakan hubungi kami via WhatsApp atau Email.';
            contactMessage.classList.remove('color-blue');
            contactMessage.classList.add('color-red');
            setTimeout(() => {
                contactMessage.textContent = '';
            }, 6000);
        });
    });
}

/*==================== SCROLL REVEAL ANIMATIONS ====================*/
// Exclude hero section entrance elements from ScrollReveal to prevent initial blank flash / UI test sync issues
if (typeof ScrollReveal !== 'undefined') {
    const sr = ScrollReveal({
        origin: 'top',
        distance: '40px',
        duration: 900,
        delay: 200,
        reset: false
    });

    sr.reveal('.about__image, .education__data, .contact__info', {});
    sr.reveal('.about__data, .education__illustration-box, .contact__form', { delay: 300 });
    sr.reveal('.services__card', { interval: 100 });
    sr.reveal('.social__card', { interval: 100 });
}
