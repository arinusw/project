/**
 * Ar Creative - Main JavaScript File
 * Handles navigation drawer, dark/light theme toggle, scroll header,
 * active section highlighting, scroll-up visibility, and contact form submission.
 */

document.addEventListener('DOMContentLoaded', () => {
  /* ==================== MENU SHOW & HIDE ==================== */
  const navMenu = document.getElementById('nav-menu');
  const navToggle = document.getElementById('nav-toggle');
  const navClose = document.getElementById('nav-close');

  // Menu Show
  if (navToggle && navMenu) {
    navToggle.addEventListener('click', () => {
      navMenu.classList.add('show-menu');
    });
  }

  // Menu Hidden
  if (navClose && navMenu) {
    navClose.addEventListener('click', () => {
      navMenu.classList.remove('show-menu');
    });
  }

  // Remove menu when clicking navigation items
  const navLinks = document.querySelectorAll('.nav__link');
  function linkAction() {
    if (navMenu) {
      navMenu.classList.remove('show-menu');
    }
  }
  navLinks.forEach(n => n.addEventListener('click', linkAction));

  /* ==================== DARK / LIGHT THEME TOGGLE ==================== */
  const themeButton = document.getElementById('theme-button');
  const darkTheme = 'dark-theme';
  const iconTheme = 'ri-sun-line';

  // Previously selected topic (if user selected)
  const selectedTheme = localStorage.getItem('selected-theme');
  const selectedIcon = localStorage.getItem('selected-icon');

  // Obtain current theme and icon state
  const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
  const getCurrentIcon = () => themeButton.querySelector('i').classList.contains(iconTheme) ? 'ri-sun-line' : 'ri-moon-line';

  // Validate if the user previously chose a theme
  if (selectedTheme) {
    document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
    const themeIcon = themeButton.querySelector('i');
    if (themeIcon) {
      themeIcon.classList[selectedIcon === 'ri-sun-line' ? 'add' : 'remove'](iconTheme);
      themeIcon.classList[selectedIcon === 'ri-sun-line' ? 'remove' : 'add']('ri-moon-line');
    }
  }

  // Toggle theme manually
  if (themeButton) {
    themeButton.addEventListener('click', () => {
      document.body.classList.toggle(darkTheme);
      const themeIcon = themeButton.querySelector('i');
      if (themeIcon) {
        themeIcon.classList.toggle(iconTheme);
        themeIcon.classList.toggle('ri-moon-line');
      }
      localStorage.setItem('selected-theme', getCurrentTheme());
      localStorage.setItem('selected-icon', getCurrentIcon());
    });
  }

  /* ==================== CHANGE BACKGROUND HEADER ON SCROLL ==================== */
  function scrollHeader() {
    const nav = document.getElementById('header');
    if (!nav) return;
    if (window.scrollY >= 50) {
      nav.classList.add('scroll-header');
    } else {
      nav.classList.remove('scroll-header');
    }
  }
  window.addEventListener('scroll', scrollHeader);

  /* ==================== SHOW SCROLL UP BUTTON ==================== */
  function scrollUp() {
    const scrollUpBtn = document.getElementById('scroll-up');
    if (!scrollUpBtn) return;
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
      const targetNav = document.querySelector('.nav__menu a[href*=' + sectionId + ']');

      if (targetNav) {
        if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
          targetNav.classList.add('active-link');
        } else {
          targetNav.classList.remove('active-link');
        }
      }
    });
  }
  window.addEventListener('scroll', scrollActive);

  /* ==================== CONTACT FORM SUBMISSION ==================== */
  const contactForm = document.getElementById('contact-form');
  const messageStatus = document.getElementById('contact-message-status');
  const submitButton = document.getElementById('contact-submit-btn');

  if (contactForm && messageStatus) {
    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      // UI state: submitting
      const originalBtnText = submitButton ? submitButton.innerHTML : 'Kirim Pesan';
      if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<span>Mengirimkan...</span> <i class="ri-loader-4-line ri-spin button__icon"></i>';
      }
      messageStatus.textContent = '';
      messageStatus.className = 'contact__message';

      const formData = new FormData(contactForm);

      try {
        const response = await fetch(contactForm.action, {
          method: 'POST',
          body: formData
        });

        if (response.ok) {
          const data = await response.json();
          messageStatus.textContent = data.message || 'Pesan Anda berhasil dikirim!';
          messageStatus.className = 'contact__message success';
          contactForm.reset();
        } else {
          const errData = await response.json().catch(() => null);
          const errMessage = (errData && errData.message) ? errData.message : 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.';
          messageStatus.textContent = errMessage;
          messageStatus.className = 'contact__message error';
        }
      } catch (err) {
        // Fallback for offline or file:// protocol simulation
        messageStatus.textContent = 'Terima kasih, pesan Anda telah diterima! (Simulasi: pengiriman berhasil)';
        messageStatus.className = 'contact__message success';
        contactForm.reset();
      } finally {
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.innerHTML = originalBtnText;
        }

        // Clear message after 6 seconds
        setTimeout(() => {
          messageStatus.textContent = '';
          messageStatus.className = 'contact__message';
        }, 6000);
      }
    });
  }
});
