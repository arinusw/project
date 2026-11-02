// Ar Creative - Client Side Interactivity

document.addEventListener('DOMContentLoaded', () => {
  /* ==================== SHOW/HIDE MOBILE MENU ==================== */
  const navMenu = document.getElementById('nav-menu');
  const navToggle = document.getElementById('nav-toggle');
  const navClose = document.getElementById('nav-close');

  if (navToggle) {
    navToggle.addEventListener('click', () => {
      navMenu.classList.add('show-menu');
    });
  }

  if (navClose) {
    navClose.addEventListener('click', () => {
      navMenu.classList.remove('show-menu');
    });
  }

  /* Close mobile menu when clicking nav links */
  const navLinks = document.querySelectorAll('.nav__link');
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      if (navMenu) {
        navMenu.classList.remove('show-menu');
      }
    });
  });

  /* ==================== CHANGE BACKGROUND HEADER ==================== */
  function scrollHeader() {
    const header = document.getElementById('header');
    if (window.scrollY >= 50) {
      header.classList.add('scroll-header');
    } else {
      header.classList.remove('scroll-header');
    }
  }
  window.addEventListener('scroll', scrollHeader);

  /* ==================== SHOW SCROLL UP BUTTON ==================== */
  function scrollUp() {
    const scrollUpBtn = document.getElementById('scroll-up');
    if (window.scrollY >= 350) {
      scrollUpBtn.classList.add('show-scroll');
    } else {
      scrollUpBtn.classList.remove('show-scroll');
    }
  }
  window.addEventListener('scroll', scrollUp);

  /* ==================== SCROLL SECTIONS ACTIVE LINK ==================== */
  const sections = document.querySelectorAll('section[id]');

  function scrollActive() {
    const scrollY = window.scrollY;

    sections.forEach(current => {
      const sectionHeight = current.offsetHeight;
      const sectionTop = current.offsetTop - 120;
      const sectionId = current.getAttribute('id');
      const sectionLink = document.querySelector(`.nav__menu a[href*='${sectionId}']`);

      if (sectionLink) {
        if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
          sectionLink.classList.add('active-link');
        } else {
          sectionLink.classList.remove('active-link');
        }
      }
    });
  }
  window.addEventListener('scroll', scrollActive);

  /* ==================== DARK / LIGHT THEME TOGGLE ==================== */
  const themeButton = document.getElementById('theme-button');
  const darkTheme = 'dark-theme';
  const iconTheme = 'ri-sun-line';

  // Retrieve previous user preferences from localStorage
  const selectedTheme = localStorage.getItem('selected-theme');
  const selectedIcon = localStorage.getItem('selected-icon');

  const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
  const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-sun-line' : 'ri-moon-line';

  if (selectedTheme) {
    document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
    if (themeButton) {
      themeButton.classList[selectedIcon === 'ri-sun-line' ? 'add' : 'remove'](iconTheme);
      themeButton.classList[selectedIcon === 'ri-sun-line' ? 'remove' : 'add']('ri-moon-line');
    }
  }

  if (themeButton) {
    themeButton.addEventListener('click', () => {
      document.body.classList.toggle(darkTheme);
      themeButton.classList.toggle('ri-moon-line');
      themeButton.classList.toggle(iconTheme);

      localStorage.setItem('selected-theme', getCurrentTheme());
      localStorage.setItem('selected-icon', getCurrentIcon());
    });
  }

  /* ==================== AJAX CONTACT FORM SUBMISSION ==================== */
  const contactForm = document.getElementById('contact-form');
  const contactMessage = document.getElementById('contact-message');
  const submitButton = document.getElementById('contact-submit-btn');

  if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      if (!contactForm.checkValidity()) {
        contactForm.reportValidity();
        return;
      }

      const formData = new FormData(contactForm);
      const originalBtnText = submitButton ? submitButton.innerHTML : '';
      if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<span>Mengirim...</span> <i class="ri-loader-4-line ri-spin"></i>';
      }

      try {
        const response = await fetch('contact.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (contactMessage) {
          contactMessage.textContent = result.message;
          contactMessage.className = `contact__message ${result.status}`;
          contactMessage.style.display = 'block';

          if (result.status === 'success') {
            contactForm.reset();
            setTimeout(() => {
              contactMessage.style.display = 'none';
            }, 6000);
          }
        }
      } catch (error) {
        if (contactMessage) {
          contactMessage.textContent = 'Terjadi kesalahan sistem. Silakan coba lagi nanti atau hubungi WhatsApp kami.';
          contactMessage.className = 'contact__message error';
          contactMessage.style.display = 'block';
        }
      } finally {
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.innerHTML = originalBtnText;
        }
      }
    });
  }
});
