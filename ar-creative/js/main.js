/**
 * Ar Creative - Portfolio & Service Website
 * JavaScript interactions & animations
 */

document.addEventListener('DOMContentLoaded', () => {
    /*==================== MENU SHOW / HIDDEN ====================*/
    const navMenu = document.getElementById('nav-menu');
    const navToggle = document.getElementById('nav-toggle');
    const navClose = document.getElementById('nav-close');

    // Menu Show
    if (navToggle) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.add('show-menu');
        });
    }

    // Menu Hidden
    if (navClose) {
        navClose.addEventListener('click', () => {
            navMenu.classList.remove('show-menu');
        });
    }

    // Remove Menu Mobile on Click Link
    const navLinks = document.querySelectorAll('.nav__link');
    function linkAction() {
        if (navMenu) {
            navMenu.classList.remove('show-menu');
        }
    }
    navLinks.forEach(n => n.addEventListener('click', linkAction));

    /*==================== CHANGE BACKGROUND HEADER ====================*/
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

    /*==================== SHOW SCROLL UP ====================*/
    function scrollUp() {
        const scrollUp = document.getElementById('scroll-up');
        if (scrollUp) {
            if (window.scrollY >= 350) {
                scrollUp.classList.add('show-scroll');
            } else {
                scrollUp.classList.remove('show-scroll');
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
            const menuLink = document.querySelector(`.nav__menu a[href*='${sectionId}']`);

            if (menuLink) {
                if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                    menuLink.classList.add('active-link');
                } else {
                    menuLink.classList.remove('active-link');
                }
            }
        });
    }
    window.addEventListener('scroll', scrollActive);

    /*==================== DARK LIGHT THEME ====================*/
    const themeButton = document.getElementById('theme-button');
    const darkTheme = 'dark-theme';
    const iconTheme = 'ri-sun-line';

    // Previously selected topic (if user selected)
    const selectedTheme = localStorage.getItem('selected-theme');
    const selectedIcon = localStorage.getItem('selected-icon');

    // Obtain current theme and icon state
    const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
    const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-sun-line' : 'ri-moon-line';

    // Validate if the user previously chose a topic
    if (selectedTheme) {
        document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
        if (themeButton) {
            themeButton.classList[selectedIcon === 'ri-sun-line' ? 'add' : 'remove'](iconTheme);
            if (selectedIcon !== 'ri-sun-line') {
                themeButton.classList.add('ri-moon-line');
            }
        }
    }

    // Activate / deactivate the theme manually with the button
    if (themeButton) {
        themeButton.addEventListener('click', () => {
            document.body.classList.toggle(darkTheme);
            themeButton.classList.toggle(iconTheme);
            if (themeButton.classList.contains(iconTheme)) {
                themeButton.classList.remove('ri-moon-line');
            } else {
                themeButton.classList.add('ri-moon-line');
            }
            // Save current theme and icon
            localStorage.setItem('selected-theme', getCurrentTheme());
            localStorage.setItem('selected-icon', getCurrentIcon());
        });
    }

    /*==================== CONTACT FORM SUBMISSION ====================*/
    const contactForm = document.getElementById('contact-form');
    const contactResponse = document.getElementById('contact-response');
    const contactSubmitBtn = document.getElementById('contact-submit');

    if (contactForm && contactResponse) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Disable submit button and show loading state
            if (contactSubmitBtn) {
                contactSubmitBtn.disabled = true;
                contactSubmitBtn.style.opacity = '0.7';
                contactSubmitBtn.querySelector('span').textContent = 'Mengirim Pesan...';
            }

            contactResponse.textContent = '';
            contactResponse.className = 'contact__message';

            const formData = new FormData(contactForm);

            try {
                const response = await fetch(contactForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                let result;
                try {
                    result = await response.json();
                } catch (err) {
                    // Fallback if not json
                    result = {
                        status: response.ok ? 'success' : 'error',
                        message: response.ok
                            ? 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.'
                            : 'Terjadi kendala teknis saat mengirim pesan. Silakan hubungi kami via WhatsApp.'
                    };
                }

                if (result.status === 'success') {
                    contactResponse.textContent = result.message || 'Pesan Anda berhasil terkirim. Terima kasih!';
                    contactResponse.classList.add('contact__message--success');
                    contactForm.reset();
                } else {
                    contactResponse.textContent = result.message || 'Maaf, terjadi kesalahan saat mengirim pesan.';
                    contactResponse.classList.add('contact__message--error');
                }
            } catch (error) {
                contactResponse.textContent = 'Gagal menghubungi server. Anda dapat menghubungi kami via WhatsApp di +62 812 4861 6454.';
                contactResponse.classList.add('contact__message--error');
            } finally {
                if (contactSubmitBtn) {
                    contactSubmitBtn.disabled = false;
                    contactSubmitBtn.style.opacity = '1';
                    contactSubmitBtn.querySelector('span').textContent = 'Kirim Pesan';
                }
            }
        });
    }
});
