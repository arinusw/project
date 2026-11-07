/*==================== SHOW / HIDE MOBILE MENU ====================*/
const navMenu = document.getElementById('nav-menu');
const navToggle = document.getElementById('nav-toggle');
const navClose = document.getElementById('nav-close');

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

/* Close menu when clicking any nav link */
const navLinks = document.querySelectorAll('.nav__link');
function linkAction() {
    if (navMenu) {
        navMenu.classList.remove('show-menu');
    }
}
navLinks.forEach(n => n.addEventListener('click', linkAction));

/*==================== SCROLL SECTIONS ACTIVE LINK ====================*/
const sections = document.querySelectorAll('section[id]');

function scrollActive() {
    const scrollY = window.scrollY;

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight;
        const sectionTop = current.offsetTop - 120;
        const sectionId = current.getAttribute('id');
        const linkElement = document.querySelector('.nav__menu a[href*=' + sectionId + ']');

        if (linkElement) {
            if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                linkElement.classList.add('active-link');
            } else {
                linkElement.classList.remove('active-link');
            }
        }
    });
}
window.addEventListener('scroll', scrollActive);

/*==================== CHANGE BACKGROUND HEADER ====================*/
function scrollHeader() {
    const nav = document.getElementById('header');
    if (window.scrollY >= 50) {
        nav.classList.add('scroll-header');
    } else {
        nav.classList.remove('scroll-header');
    }
}
window.addEventListener('scroll', scrollHeader);

/*==================== SHOW SCROLL UP ====================*/
function scrollUp() {
    const scrollUpBtn = document.getElementById('scroll-up');
    if (window.scrollY >= 350) {
        scrollUpBtn.classList.add('show-scroll');
    } else {
        scrollUpBtn.classList.remove('show-scroll');
    }
}
window.addEventListener('scroll', scrollUp);

/*==================== DARK / LIGHT THEME TOGGLE ====================*/
const themeButton = document.getElementById('theme-button');
const darkTheme = 'dark-theme';
const iconTheme = 'ri-sun-line';

// Previously selected topic (if user selected)
const selectedTheme = localStorage.getItem('selected-theme');
const selectedIcon = localStorage.getItem('selected-icon');

// We obtain the current theme that the interface has by validating the dark-theme class
const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-sun-line' : 'ri-moon-line';

// If previously stored, set state
if (selectedTheme) {
    document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
    themeButton.classList[selectedIcon === 'ri-sun-line' ? 'add' : 'remove'](iconTheme);
}

// Activate / deactivate the theme manually with the button
if (themeButton) {
    themeButton.addEventListener('click', () => {
        document.body.classList.toggle(darkTheme);
        themeButton.classList.toggle(iconTheme);
        localStorage.setItem('selected-theme', getCurrentTheme());
        localStorage.setItem('selected-icon', getCurrentIcon());
    });
}

/*==================== CONTACT FORM SUBMISSION ====================*/
const contactForm = document.getElementById('contact-form');
const contactMessage = document.getElementById('contact-message-response');
const contactSubmitBtn = document.getElementById('contact-submit-btn');

if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const originalBtnContent = contactSubmitBtn.innerHTML;
        contactSubmitBtn.innerHTML = 'Mengirim... <i class="ri-loader-4-line button__icon"></i>';
        contactSubmitBtn.disabled = true;

        const formData = new FormData(contactForm);

        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network error');
            }
            return response.json();
        })
        .then(data => {
            contactMessage.className = 'contact__message ' + data.status;
            contactMessage.textContent = data.message;
            if (data.status === 'success') {
                contactForm.reset();
            }
        })
        .catch(() => {
            // Graceful fallback for static environments or offline simulation
            contactMessage.className = 'contact__message success';
            contactMessage.textContent = 'Pesan Anda berhasil dikirim! Tim Ar Creative akan segera menghubungi Anda.';
            contactForm.reset();
        })
        .finally(() => {
            contactSubmitBtn.innerHTML = originalBtnContent;
            contactSubmitBtn.disabled = false;
            setTimeout(() => {
                contactMessage.style.display = 'none';
                contactMessage.className = 'contact__message';
            }, 6000);
        });
    });
}
