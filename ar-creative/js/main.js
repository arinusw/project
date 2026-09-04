/*=============== MAIN JS FOR AR CREATIVE PORTFOLIO ===============*/

document.addEventListener('DOMContentLoaded', () => {
    /*=============== MENU SHOW & HIDDEN ===============*/
    const navMenu = document.getElementById('nav-menu'),
          navToggle = document.getElementById('nav-toggle'),
          navClose = document.getElementById('nav-close');

    /* Menu Show */
    if (navToggle) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.add('show-menu');
        });
    }

    /* Menu Hidden */
    if (navClose) {
        navClose.addEventListener('click', () => {
            navMenu.classList.remove('show-menu');
        });
    }

    /*=============== REMOVE MENU MOBILE ON LINK CLICK ===============*/
    const navLinks = document.querySelectorAll('.nav__link');

    function linkAction() {
        if (navMenu) {
            navMenu.classList.remove('show-menu');
        }
    }
    navLinks.forEach(n => n.addEventListener('click', linkAction));

    /*=============== CHANGE BACKGROUND HEADER ===============*/
    function scrollHeader() {
        const header = document.getElementById('header');
        if (window.scrollY >= 50) {
            header.classList.add('scroll-header');
        } else {
            header.classList.remove('scroll-header');
        }
    }
    window.addEventListener('scroll', scrollHeader);

    /*=============== SHOW SCROLL UP ===============*/
    function scrollUp() {
        const scrollUpBtn = document.getElementById('scroll-up');
        if (window.scrollY >= 350) {
            scrollUpBtn.classList.add('show-scroll');
        } else {
            scrollUpBtn.classList.remove('show-scroll');
        }
    }
    window.addEventListener('scroll', scrollUp);

    /*=============== SCROLL SECTIONS ACTIVE LINK ===============*/
    const sections = document.querySelectorAll('section[id]');

    function scrollActive() {
        const scrollY = window.scrollY;

        sections.forEach(current => {
            const sectionHeight = current.offsetHeight,
                  sectionTop = current.offsetTop - 58,
                  sectionId = current.getAttribute('id'),
                  sectionsClass = document.querySelector('.nav__menu a[href*=' + sectionId + ']');

            if (sectionsClass) {
                if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                    sectionsClass.classList.add('active-link');
                } else {
                    sectionsClass.classList.remove('active-link');
                }
            }
        });
    }
    window.addEventListener('scroll', scrollActive);

    /*=============== DARK / LIGHT THEME TOGGLE ===============*/
    const themeButton = document.getElementById('theme-button');
    const darkTheme = 'dark-theme';
    const iconTheme = 'ri-sun-line';

    // Previously selected topic (if user selected)
    const selectedTheme = localStorage.getItem('selected-theme');
    const selectedIcon = localStorage.getItem('selected-icon');

    // We obtain the current theme that the interface has by validating the dark-theme class
    const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
    const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-sun-line' : 'ri-moon-line';

    // We validate if the user previously chose a topic
    if (selectedTheme) {
        document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
        themeButton.classList[selectedIcon === 'ri-sun-line' ? 'add' : 'remove'](iconTheme);
        themeButton.classList[selectedIcon === 'ri-sun-line' ? 'remove' : 'add']('ri-moon-line');
    }

    // Activate / deactivate the theme manually with the button
    if (themeButton) {
        themeButton.addEventListener('click', () => {
            // Add or remove the dark / icon theme
            document.body.classList.toggle(darkTheme);
            if (document.body.classList.contains(darkTheme)) {
                themeButton.classList.remove('ri-moon-line');
                themeButton.classList.add('ri-sun-line');
            } else {
                themeButton.classList.remove('ri-sun-line');
                themeButton.classList.add('ri-moon-line');
            }
            // We save the theme and the current icon that the user chose
            localStorage.setItem('selected-theme', getCurrentTheme());
            localStorage.setItem('selected-icon', getCurrentIcon());
        });
    }

    /*=============== CONTACT FORM AJAX SUBMISSION ===============*/
    const contactForm = document.getElementById('contact-form');
    const contactMessage = document.getElementById('contact-message');

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(contactForm);

            // Display sending state
            contactMessage.textContent = 'Sedang mengirim pesan...';
            contactMessage.className = 'contact__message';

            fetch('contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    contactMessage.textContent = data.message;
                    contactMessage.className = 'contact__message success';
                    contactForm.reset();

                    // Clear message after 6 seconds
                    setTimeout(() => {
                        contactMessage.textContent = '';
                    }, 6000);
                } else {
                    contactMessage.textContent = data.message || 'Gagal mengirim pesan. Silakan coba lagi.';
                    contactMessage.className = 'contact__message error';
                }
            })
            .catch(error => {
                console.error('Error submitting form:', error);
                contactMessage.textContent = 'Terjadi kesalahan server. Silakan coba beberapa saat lagi.';
                contactMessage.className = 'contact__message error';
            });
        });
    }

    /*=============== SCROLL REVEAL ANIMATIONS ===============*/
    if (typeof ScrollReveal !== 'undefined') {
        const sr = ScrollReveal({
            origin: 'top',
            distance: '60px',
            duration: 1000,
            delay: 300,
            reset: false
        });

        sr.reveal('.home__data, .about__img-wrapper, .education__data');
        sr.reveal('.home__img-wrapper, .about__data, .education__img-wrapper', { delay: 400 });
        sr.reveal('.services__card, .social__card, .contact__card', { interval: 150 });
        sr.reveal('.contact__form', { delay: 300 });
    }
});
