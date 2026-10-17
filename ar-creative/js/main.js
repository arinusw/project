/* --------------- MENU SHOW Y HIDDEN --------------- */
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close');

/* Menu show */
if (navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu');
    });
}

/* Menu hidden */
if (navClose) {
    navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu');
    });
}

/* --------------- REMOVE MENU MOBILE --------------- */
const navLink = document.querySelectorAll('.nav__link');

function linkAction() {
    const navMenu = document.getElementById('nav-menu');
    // When clicking on each nav__link, remove show-menu
    if (navMenu) {
        navMenu.classList.remove('show-menu');
    }
}
navLink.forEach(n => n.addEventListener('click', linkAction));

/* --------------- CHANGE BACKGROUND HEADER --------------- */
function scrollHeader() {
    const nav = document.getElementById('header');
    // When the scroll is greater than 50 viewport height, add the scroll-header class
    if (window.scrollY >= 50) {
        if (nav) nav.classList.add('scroll-header');
    } else {
        if (nav) nav.classList.remove('scroll-header');
    }
}
window.addEventListener('scroll', scrollHeader);

/* --------------- SHOW SCROLL UP --------------- */
function scrollUp() {
    const scrollUp = document.getElementById('scroll-up');
    // When scroll is higher than 350 viewport height, add show-scroll class
    if (window.scrollY >= 350) {
        if (scrollUp) scrollUp.classList.add('show-scroll');
    } else {
        if (scrollUp) scrollUp.classList.remove('show-scroll');
    }
}
window.addEventListener('scroll', scrollUp);

/* --------------- DARK LIGHT THEME --------------- */
const themeButton = document.getElementById('theme-button');
const darkTheme = 'dark-theme';
const iconTheme = 'ri-sun-line';

// Previously selected topic (if user selected)
const selectedTheme = localStorage.getItem('selected-theme');
const selectedIcon = localStorage.getItem('selected-icon');

// Obtain current theme by validating dark-theme class
const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-moon-line' : 'ri-sun-line';

// Validate if user previously chose a topic
if (selectedTheme) {
    document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
    if (themeButton) {
        themeButton.classList[selectedIcon === 'ri-moon-line' ? 'add' : 'remove'](iconTheme);
    }
}

// Activate / deactivate the theme manually with the button
if (themeButton) {
    themeButton.addEventListener('click', () => {
        document.body.classList.toggle(darkTheme);
        themeButton.classList.toggle(iconTheme);
        // Save theme and icon preference in localStorage
        localStorage.setItem('selected-theme', getCurrentTheme());
        localStorage.setItem('selected-icon', getCurrentIcon());
    });
}

/* --------------- SCROLL SECTIONS ACTIVE LINK --------------- */
const sections = document.querySelectorAll('section[id]');

function scrollActive() {
    const scrollY = window.scrollY;

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight;
        const sectionTop = current.offsetTop - 120;
        const sectionId = current.getAttribute('id');
        const sectionsClass = document.querySelector('.nav__menu a[href*=' + sectionId + ']');

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

/* --------------- CONTACT FORM AJAX SUBMISSION --------------- */
const contactForm = document.getElementById('contact-form');
const contactMessage = document.getElementById('contact-message');

if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(contactForm);

        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                contactMessage.classList.remove('color-red');
                contactMessage.classList.add('color-blue');
                contactMessage.textContent = data.message;
                contactForm.reset();
                setTimeout(() => {
                    contactMessage.textContent = '';
                }, 6000);
            } else {
                contactMessage.classList.remove('color-blue');
                contactMessage.classList.add('color-red');
                contactMessage.textContent = data.message;
            }
        })
        .catch(error => {
            contactMessage.classList.remove('color-blue');
            contactMessage.classList.add('color-red');
            contactMessage.textContent = 'Terjadi kesalahan sistem. Sila coba beberapa saat lagi.';
        });
    });
}
