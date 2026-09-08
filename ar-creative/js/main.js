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
  navMenu.classList.remove('show-menu');
};
navLink.forEach(n => n.addEventListener('click', linkAction));

/*=============== CHANGE BACKGROUND HEADER ===============*/
const scrollHeader = () => {
  const header = document.getElementById('header');
  if (window.scrollY >= 50) {
    header.classList.add('scroll-header');
  } else {
    header.classList.remove('scroll-header');
  }
};
window.addEventListener('scroll', scrollHeader);

/*=============== SHOW SCROLL UP ===============*/
const scrollUp = () => {
  const scrollUpBtn = document.getElementById('scroll-up');
  if (window.scrollY >= 350) {
    scrollUpBtn.classList.add('show-scroll');
  } else {
    scrollUpBtn.classList.remove('show-scroll');
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
  document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
  themeButton.classList[selectedIcon === 'ri-moon-line' ? 'add' : 'remove'](iconTheme);
}

// Activate / deactivate the theme manually with the button
themeButton.addEventListener('click', () => {
  // Add or remove the dark / icon theme
  document.body.classList.toggle(darkTheme);
  themeButton.classList.toggle(iconTheme);
  // We save the theme and the current icon that the user chose
  localStorage.setItem('selected-theme', getCurrentTheme());
  localStorage.setItem('selected-icon', getCurrentIcon());
});

/*=============== SCROLL REVEAL ANIMATION ===============*/
if (typeof ScrollReveal !== 'undefined') {
  const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 1000,
    delay: 400,
    reset: false // Keep elements visible once revealed
  });

  sr.reveal('.home__data, .about__data, .education__data, .contact__form');
  sr.reveal('.home__img, .about__img, .education__img, .contact__info', { delay: 500 });
  sr.reveal('.services__card, .social__card', { interval: 100 });
}

/*=============== CONTACT FORM HANDLING (AJAX) ===============*/
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message');

if (contactForm) {
  contactForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(contactForm);

    contactMessage.textContent = 'Mengirim pesan...';
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
        setTimeout(() => {
          contactMessage.textContent = '';
        }, 5000);
      } else {
        contactMessage.textContent = data.message;
        contactMessage.className = 'contact__message error';
      }
    })
    .catch(error => {
      contactMessage.textContent = 'Terjadi kesalahan pada server. Silakan coba lagi nanti.';
      contactMessage.className = 'contact__message error';
      console.error('Contact Form Error:', error);
    });
  });
}
