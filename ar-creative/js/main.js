/*=============== SHOW MENU ===============*/
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

/*=============== REMOVE MENU MOBILE ===============*/
const navLink = document.querySelectorAll('.nav__link');

const linkAction = () => {
    const navMenu = document.getElementById('nav-menu');
    // When we click on each nav__link, we remove the show-menu class
    if (navMenu) {
        navMenu.classList.remove('show-menu');
    }
};
navLink.forEach(n => n.addEventListener('click', linkAction));

/*=============== CHANGE BACKGROUND HEADER ===============*/
const scrollHeader = () => {
    const header = document.getElementById('header');
    if (header) {
        // When the scroll is greater than 50 viewport height, add the scroll-header class to the header tag
        window.scrollY >= 50 ? header.classList.add('scroll-header')
                             : header.classList.remove('scroll-header');
    }
};
window.addEventListener('scroll', scrollHeader);

/*=============== SHOW SCROLL UP ===============*/
const scrollUp = () => {
    const scrollUpBtn = document.getElementById('scroll-up');
    if (scrollUpBtn) {
        // When the scroll is higher than 350 viewport height, add the show-scroll class to the a tag with the scrollup class
        window.scrollY >= 350 ? scrollUpBtn.classList.add('show-scroll')
                              : scrollUpBtn.classList.remove('show-scroll');
    }
};
window.addEventListener('scroll', scrollUp);

/*=============== SCROLL SECTIONS ACTIVE LINK ===============*/
const sections = document.querySelectorAll('section[id]');

const scrollActive = () => {
    const scrollDown = window.scrollY;

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight,
              sectionTop = current.offsetTop - 58,
              sectionId = current.getAttribute('id'),
              sectionsClass = document.querySelector('.nav__menu a[href*=' + sectionId + ']');

        if (sectionsClass) {
            if (scrollDown > sectionTop && scrollDown <= sectionTop + sectionHeight) {
                sectionsClass.classList.add('active-link');
            } else {
                sectionsClass.classList.remove('active-link');
            }
        }
    });
};
window.addEventListener('scroll', scrollActive);

/*=============== DARK LIGHT THEME ===============*/
const themeButton = document.getElementById('theme-button');
const darkTheme = 'dark-theme';
const iconTheme = 'ri-sun-line';

// Previously selected topic (if user selected)
const selectedTheme = localStorage.getItem('selected-theme');
const selectedIcon = localStorage.getItem('selected-icon');

// We obtain the current theme that the interface has by validating the dark-theme class
const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-moon-line' : 'ri-sun-line';

// We validate if the user previously chose a topic
if (selectedTheme) {
  // If the validation is fulfilled, we ask what the issue was to know if we activated or deactivated the dark
  document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
  if (themeButton) {
      themeButton.classList[selectedIcon === 'ri-moon-line' ? 'add' : 'remove'](iconTheme);
  }
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

/*=============== SCROLL REVEAL ANIMATION ===============*/
if (typeof ScrollReveal !== 'undefined') {
    const sr = ScrollReveal({
        origin: 'top',
        distance: '60px',
        duration: 1000,
        delay: 400,
        reset: false
    });

    sr.reveal(`.home__data, .about__data, .education__data, .contact__info`);
    sr.reveal(`.home__img-wrapper, .about__image-wrapper, .education__image-wrapper, .contact__form`, { origin: 'bottom' });
    sr.reveal(`.services__card, .social__card`, { interval: 100 });
}

/*=============== CONTACT FORM AJAX HANDLER ===============*/
const contactForm = document.getElementById('contact-form');
const contactMessage = document.getElementById('contact-message');
const contactButton = document.getElementById('contact-button');

if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (contactButton) {
            contactButton.disabled = true;
            contactButton.innerHTML = 'Sending... <i class="ri-loader-4-line"></i>';
        }

        const formData = new FormData(contactForm);

        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                contactMessage.classList.remove('error');
                contactMessage.classList.add('success');
                contactMessage.textContent = data.message;
                contactForm.reset();
            } else {
                contactMessage.classList.remove('success');
                contactMessage.classList.add('error');
                contactMessage.textContent = data.message || 'Gagal mengirim pesan. Silakan coba lagi.';
            }
        })
        .catch(error => {
            contactMessage.classList.remove('success');
            contactMessage.classList.add('error');
            contactMessage.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
        })
        .finally(() => {
            if (contactButton) {
                contactButton.disabled = false;
                contactButton.innerHTML = 'Kirim Pesan <i class="ri-send-plane-line button__icon"></i>';
            }
            setTimeout(() => {
                contactMessage.textContent = '';
            }, 6000);
        });
    });
}
