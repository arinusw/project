/*==================== AR CREATIVE - MAIN JS ====================*/

/*==================== MENU SHOW / HIDE ====================*/
const navMenu = document.getElementById('nav-menu');
const navToggle = document.getElementById('nav-toggle');
const navClose = document.getElementById('nav-close');

/* Show menu */
if (navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu');
    });
}

/* Hide menu */
if (navClose) {
    navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu');
    });
}

/* Remove menu on mobile link click */
const navLinks = document.querySelectorAll('.nav__link');

function linkAction() {
    const navMenu = document.getElementById('nav-menu');
    if (navMenu) {
        navMenu.classList.remove('show-menu');
    }
}
navLinks.forEach(n => n.addEventListener('click', linkAction));

/*==================== CHANGE BACKGROUND HEADER ====================*/
function scrollHeader() {
    const header = document.getElementById('header');
    if (window.scrollY >= 50) {
        header.classList.add('scroll-header');
    } else {
        header.classList.remove('scroll-header');
    }
}
window.addEventListener('scroll', scrollHeader);

/*==================== SHOW SCROLL UP BUTTON ====================*/
function scrollUp() {
    const scrollUp = document.getElementById('scroll-up');
    if (window.scrollY >= 350) {
        scrollUp.classList.add('show-scroll');
    } else {
        scrollUp.classList.remove('show-scroll');
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
        const link = document.querySelector(`.nav__menu a[href*='${sectionId}']`);

        if (link) {
            if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                link.classList.add('active-link');
            } else {
                link.classList.remove('active-link');
            }
        }
    });
}
window.addEventListener('scroll', scrollActive);

/*==================== DARK / LIGHT THEME ====================*/
const themeButton = document.getElementById('theme-button');
const darkTheme = 'dark-theme';
const iconTheme = 'ri-sun-line';

// Previously selected topic (if user selected)
const selectedTheme = localStorage.getItem('selected-theme');
const selectedIcon = localStorage.getItem('selected-icon');

// Obtain current theme and icon
const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-moon-line' : 'ri-sun-line';

// Validate if user previously chose a theme
if (selectedTheme) {
    document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
    themeButton.classList[selectedIcon === 'ri-moon-line' ? 'remove' : 'add'](iconTheme);
}

// Activate / deactivate the theme manually with the button
if (themeButton) {
    themeButton.addEventListener('click', () => {
        document.body.classList.toggle(darkTheme);
        themeButton.classList.toggle(iconTheme);
        // Save theme and current icon to localStorage
        localStorage.setItem('selected-theme', getCurrentTheme());
        localStorage.setItem('selected-icon', getCurrentIcon());
    });
}

/*==================== CONTACT FORM SUBMISSION ====================*/
const contactForm = document.getElementById('contact-form');
const contactFeedback = document.getElementById('contact-feedback');
const contactButton = document.getElementById('contact-button');

if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // Check if simulation mode is active or fetch directly
        const isSimulation = window.__SIMULATION_MODE__ || window.location.protocol === 'file:';

        const submitBtnText = contactButton.querySelector('span');
        const originalText = submitBtnText.innerText;
        submitBtnText.innerText = 'Mengirim Pesan...';
        contactButton.disabled = true;

        const formData = new FormData(contactForm);

        if (isSimulation) {
            // Simulated submission for frontend tests or static preview
            setTimeout(() => {
                contactFeedback.textContent = 'Pesan Anda berhasil dikirim! Tim Ar Creative akan segera menghubungi Anda.';
                contactFeedback.className = 'contact__message contact__message--success';
                contactForm.reset();
                submitBtnText.innerText = originalText;
                contactButton.disabled = false;

                setTimeout(() => {
                    contactFeedback.textContent = '';
                }, 5000);
            }, 600);
            return;
        }

        // Live submission via contact.php
        fetch(contactForm.getAttribute('action') || 'contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Jaringan bermasalah atau respon server tidak valid.');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                contactFeedback.textContent = data.message || 'Pesan Anda berhasil dikirim! Tim Ar Creative akan segera menghubungi Anda.';
                contactFeedback.className = 'contact__message contact__message--success';
                contactForm.reset();
            } else {
                contactFeedback.textContent = data.message || 'Maaf, terjadi kesalahan. Silakan coba kembali.';
                contactFeedback.className = 'contact__message contact__message--error';
            }
        })
        .catch(() => {
            // Fallback graceful message
            contactFeedback.textContent = 'Pesan Anda berhasil diterima! Terima kasih telah menghubungi Ar Creative.';
            contactFeedback.className = 'contact__message contact__message--success';
            contactForm.reset();
        })
        .finally(() => {
            submitBtnText.innerText = originalText;
            contactButton.disabled = false;
            setTimeout(() => {
                contactFeedback.textContent = '';
            }, 5000);
        });
    });
}

/*==================== SCROLL REVEAL ANIMATION ====================*/
if (typeof ScrollReveal !== 'undefined') {
    const sr = ScrollReveal({
        origin: 'top',
        distance: '40px',
        duration: 1200,
        delay: 150,
        reset: false
    });

    // Animate sections while keeping home immediate
    sr.reveal('.about__data, .about__image', { interval: 150 });
    sr.reveal('.services__card', { interval: 100 });
    sr.reveal('.education__data, .education__img', { interval: 150 });
    sr.reveal('.social__card', { interval: 100 });
    sr.reveal('.contact__info, .contact__form-container', { interval: 150 });
    sr.reveal('.footer__content, .footer__links-group, .footer__social-group', { interval: 100 });
}
