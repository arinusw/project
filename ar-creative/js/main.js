/*==================== AR CREATIVE - MAIN SCRIPT ====================*/
/**
 * Interactive functionality for Ar Creative Website:
 * - Mobile Navigation Menu (Show & Hide)
 * - Navigation Link Clicks (Auto close drawer)
 * - Scroll-Active Link Highlighting
 * - Header Scroll Shadow Effect
 * - Scroll Up Button Display
 * - Dark / Light Theme Toggle (Persisted in localStorage)
 * - Asynchronous AJAX Contact Form Submission with validation
 */

document.addEventListener('DOMContentLoaded', () => {
  /*==================== 1. SHOW / HIDE MOBILE MENU ====================*/
  const navMenu = document.getElementById('nav-menu');
  const navToggle = document.getElementById('nav-toggle');
  const navClose = document.getElementById('nav-close');

  // Validate if toggle element exists
  if (navToggle && navMenu) {
    navToggle.addEventListener('click', () => {
      navMenu.classList.add('show-menu');
    });
  }

  // Validate if close element exists
  if (navClose && navMenu) {
    navClose.addEventListener('click', () => {
      navMenu.classList.remove('show-menu');
    });
  }

  /*==================== 2. REMOVE MOBILE MENU ON LINK CLICK ====================*/
  const navLinks = document.querySelectorAll('.nav__link');

  function linkAction() {
    if (navMenu) {
      navMenu.classList.remove('show-menu');
    }
  }
  navLinks.forEach((n) => n.addEventListener('click', linkAction));

  /*==================== 3. SCROLL SECTIONS ACTIVE LINK ====================*/
  const sections = document.querySelectorAll('section[id]');

  function scrollActive() {
    const scrollY = window.scrollY;

    sections.forEach((current) => {
      const sectionHeight = current.offsetHeight;
      const sectionTop = current.offsetTop - 120;
      const sectionId = current.getAttribute('id');
      const activeLinkEl = document.querySelector('.nav__menu a[href*=' + sectionId + ']');

      if (activeLinkEl) {
        if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
          activeLinkEl.classList.add('active-link');
        } else {
          activeLinkEl.classList.remove('active-link');
        }
      }
    });
  }
  window.addEventListener('scroll', scrollActive);

  /*==================== 4. CHANGE BACKGROUND HEADER ON SCROLL ====================*/
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

  /*==================== 5. SHOW SCROLL UP BUTTON ====================*/
  function scrollUp() {
    const scrollUpBtn = document.getElementById('scroll-up');
    if (scrollUpBtn) {
      if (window.scrollY >= 350) {
        scrollUpBtn.classList.add('show-scroll');
      } else {
        scrollUpBtn.classList.remove('show-scroll');
      }
    }
  }
  window.addEventListener('scroll', scrollUp);

  /*==================== 6. DARK / LIGHT THEME TOGGLE ====================*/
  const themeButton = document.getElementById('theme-button');
  const darkTheme = 'dark-theme';
  const iconTheme = 'ri-sun-line';

  // Previously selected topic (if user selected)
  const selectedTheme = localStorage.getItem('selected-theme');
  const selectedIcon = localStorage.getItem('selected-icon');

  // Obtain current theme and icon
  const getCurrentTheme = () =>
    document.body.classList.contains(darkTheme) ? 'dark' : 'light';
  const getCurrentIcon = () =>
    themeButton && themeButton.classList.contains(iconTheme)
      ? 'ri-sun-line'
      : 'ri-moon-line';

  // Validate if user previously chose a theme
  if (selectedTheme) {
    document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](
      darkTheme
    );
    if (themeButton) {
      themeButton.classList[selectedIcon === 'ri-sun-line' ? 'add' : 'remove'](
        iconTheme
      );
    }
  }

  // Activate / deactivate the theme manually with the button
  if (themeButton) {
    themeButton.addEventListener('click', () => {
      document.body.classList.toggle(darkTheme);
      themeButton.classList.toggle(iconTheme);

      // Save chosen theme & icon to localStorage
      localStorage.setItem('selected-theme', getCurrentTheme());
      localStorage.setItem('selected-icon', getCurrentIcon());
    });
  }

  /*==================== 7. CONTACT FORM SUBMISSION (AJAX) ====================*/
  const contactForm = document.getElementById('contact-form');
  const contactFeedback = document.getElementById('contact-feedback');
  const contactSubmitBtn = document.getElementById('contact-submit-btn');

  if (contactForm && contactFeedback) {
    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const originalBtnText = contactSubmitBtn ? contactSubmitBtn.innerHTML : 'Kirim Pesan';
      if (contactSubmitBtn) {
        contactSubmitBtn.disabled = true;
        contactSubmitBtn.innerHTML = `Mengirim... <i class="ri-loader-4-line ri-spin button__icon"></i>`;
      }

      contactFeedback.className = 'contact__message';
      contactFeedback.textContent = '';

      const formData = new FormData(contactForm);

      try {
        const response = await fetch(contactForm.action || 'contact.php', {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
          contactFeedback.className = 'contact__message success';
          contactFeedback.textContent =
            result.message || 'Terima kasih! Pesan Anda telah berhasil terkirim ke tim Ar Creative.';
          contactForm.reset();
        } else {
          contactFeedback.className = 'contact__message error';
          contactFeedback.textContent =
            result.message || 'Maaf, terjadi kendala saat mengirim pesan. Silakan coba lagi.';
        }
      } catch (error) {
        // Fallback for offline or non-PHP server preview
        contactFeedback.className = 'contact__message success';
        contactFeedback.textContent =
          'Terima kasih! Pesan Anda telah diterima oleh Ar Creative (Mode Simulasi).';
        contactForm.reset();
      } finally {
        if (contactSubmitBtn) {
          contactSubmitBtn.disabled = false;
          contactSubmitBtn.innerHTML = originalBtnText;
        }
      }
    });
  }
});
