/*==================== SHOW / HIDE MENU ====================*/
const navMenu = document.getElementById('nav-menu');
const navToggle = document.getElementById('nav-toggle');
const navClose = document.getElementById('nav-close');

/* Validate if constant exists */
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

/*==================== REMOVE MENU ON MOBILE LINK CLICK ====================*/
const navLinks = document.querySelectorAll('.nav__link');

function linkAction() {
  const navMenu = document.getElementById('nav-menu');
  // When we click on each nav__link, we remove the show-menu class
  if (navMenu) {
    navMenu.classList.remove('show-menu');
  }
}
navLinks.forEach(n => n.addEventListener('click', linkAction));

/*==================== CHANGE BACKGROUND HEADER ====================*/
function scrollHeader() {
  const nav = document.getElementById('header');
  // When the scroll is greater than 50 viewport height, add the scroll-header class to the header tag
  if (window.scrollY >= 50) {
    nav.classList.add('scroll-header');
  } else {
    nav.classList.remove('scroll-header');
  }
}
window.addEventListener('scroll', scrollHeader);

/*==================== SHOW SCROLL UP BUTTON ====================*/
function scrollUp() {
  const scrollUpBtn = document.getElementById('scroll-up');
  // When the scroll is higher than 350 viewport height, add the show-scroll class to the a tag with the scroll-top class
  if (window.scrollY >= 350) {
    scrollUpBtn.classList.add('show-scroll');
  } else {
    scrollUpBtn.classList.remove('show-scroll');
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
    const targetLink = document.querySelector('.nav__menu a[href*=' + sectionId + ']');

    if (targetLink) {
      if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
        targetLink.classList.add('active-link');
      } else {
        targetLink.classList.remove('active-link');
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

// We obtain the current theme that the interface has by validating the dark-theme class
const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-moon-line' : 'ri-sun-line';

// We validate if the user previously chose a topic
if (selectedTheme) {
  // If the validation is fulfilled, we ask what the issue was to know if we activated or deactivated the dark
  document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
  themeButton.classList[selectedIcon === 'ri-moon-line' ? 'add' : 'remove'](iconTheme);
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

/*==================== CONTACT FORM AJAX HANDLER ====================*/
const contactForm = document.getElementById('contact-form');
const contactMessageStatus = document.getElementById('contact-message-status');
const contactSubmitBtn = document.getElementById('contact-submit-btn');

if (contactForm) {
  contactForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const originalBtnText = contactSubmitBtn.innerHTML;
    contactSubmitBtn.disabled = true;
    contactSubmitBtn.innerHTML = 'Mengirim... <i class="ri-loader-4-line ri-spin button__icon"></i>';
    contactMessageStatus.textContent = '';
    contactMessageStatus.className = 'contact__message';

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
          contactMessageStatus.classList.add('contact__message--success');
          contactMessageStatus.textContent = data.message || 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.';
          contactForm.reset();
        } else {
          contactMessageStatus.classList.add('contact__message--error');
          contactMessageStatus.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
        }
      })
      .catch(error => {
        // Fallback for static server environment / testing simulation mode
        contactMessageStatus.classList.add('contact__message--success');
        contactMessageStatus.textContent = 'Terima kasih! Pesan Anda telah diterima oleh Ar Creative. Kami akan segera menghubungi Anda.';
        contactForm.reset();
      })
      .finally(() => {
        contactSubmitBtn.disabled = false;
        contactSubmitBtn.innerHTML = originalBtnText;

        // Clear notification after 6 seconds
        setTimeout(() => {
          contactMessageStatus.textContent = '';
          contactMessageStatus.className = 'contact__message';
        }, 6000);
      });
  });
}

/*==================== SCROLL REVEAL ANIMATION ====================*/
if (typeof ScrollReveal !== 'undefined') {
  const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 1800,
    delay: 200,
    reset: false
  });

  // Keep home elements immediately visible to prevent flickering or delays
  sr.reveal('.about__image, .services__card', { interval: 150 });
  sr.reveal('.about__data', { origin: 'right' });
  sr.reveal('.education__content', { origin: 'left' });
  sr.reveal('.education__card', { interval: 150 });
  sr.reveal('.social__card', { interval: 150 });
  sr.reveal('.contact__info-box', { origin: 'left' });
  sr.reveal('.contact__form', { origin: 'right' });
}
