/**
 * Ar Creative - Main JavaScript
 * Handles navigation drawer, active scroll links, scroll-up button,
 * dark/light theme toggle, and AJAX contact form submission.
 */

document.addEventListener('DOMContentLoaded', () => {
  /*==================== MENU SHOW & HIDDEN ====================*/
  const navMenu = document.getElementById('nav-menu');
  const navToggle = document.getElementById('nav-toggle');
  const navClose = document.getElementById('nav-close');

  // Show menu
  if (navToggle) {
    navToggle.addEventListener('click', () => {
      navMenu.classList.add('show-menu');
    });
  }

  // Hide menu
  if (navClose) {
    navClose.addEventListener('click', () => {
      navMenu.classList.remove('show-menu');
    });
  }

  // Close menu when clicking any nav link
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
      const targetLink = document.querySelector(`.nav__menu a[href*='${sectionId}']`);

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
  scrollActive(); // Run once on page load

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

  /*==================== DARK / LIGHT THEME ====================*/
  const themeButton = document.getElementById('theme-button');
  const themeIcon = document.getElementById('theme-icon');
  const darkTheme = 'dark-theme';
  const iconTheme = 'ri-sun-line';

  // Previously selected topic (if user selected)
  const selectedTheme = localStorage.getItem('selected-theme');
  const selectedIcon = localStorage.getItem('selected-icon');

  // Obtain current theme and icon
  const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
  const getCurrentIcon = () => themeIcon.classList.contains(iconTheme) ? 'ri-sun-line' : 'ri-moon-line';

  // Validate if the user previously chose a topic
  if (selectedTheme) {
    if (selectedTheme === 'dark') {
      document.body.classList.add(darkTheme);
    } else {
      document.body.classList.remove(darkTheme);
    }

    if (themeIcon) {
      if (selectedIcon === 'ri-sun-line') {
        themeIcon.classList.add(iconTheme);
        themeIcon.classList.remove('ri-moon-line');
      } else {
        themeIcon.classList.remove(iconTheme);
        themeIcon.classList.add('ri-moon-line');
      }
    }
  }

  // Activate / deactivate the theme manually with the button
  if (themeButton) {
    themeButton.addEventListener('click', () => {
      document.body.classList.toggle(darkTheme);
      if (themeIcon) {
        themeIcon.classList.toggle(iconTheme);
        themeIcon.classList.toggle('ri-moon-line');
      }
      // Save the theme and current icon that the user chose
      localStorage.setItem('selected-theme', getCurrentTheme());
      localStorage.setItem('selected-icon', getCurrentIcon());
    });
  }

  /*==================== CONTACT FORM SUBMISSION ====================*/
  const contactForm = document.getElementById('contact-form');
  const contactFeedback = document.getElementById('contact-feedback');
  const contactSubmitBtn = document.getElementById('contact-submit-btn');

  if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      if (!contactFeedback) return;

      // Update button state
      const originalBtnHtml = contactSubmitBtn ? contactSubmitBtn.innerHTML : '';
      if (contactSubmitBtn) {
        contactSubmitBtn.disabled = true;
        contactSubmitBtn.innerHTML = `<span>Mengirim...</span> <i class="ri-loader-4-line ri-spin button__icon"></i>`;
      }

      contactFeedback.textContent = '';
      contactFeedback.className = 'contact__message';

      try {
        const formData = new FormData(contactForm);
        const response = await fetch('contact.php', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

        if (response.ok && data.status === 'success') {
          contactFeedback.textContent = data.message || 'Pesan Anda berhasil dikirim!';
          contactFeedback.classList.add('success');
          contactForm.reset();
        } else {
          contactFeedback.textContent = data.message || 'Terjadi kesalahan saat mengirim pesan.';
          contactFeedback.classList.add('error');
        }
      } catch (err) {
        contactFeedback.textContent = 'Gagal menghubungi server. Silakan coba lagi.';
        contactFeedback.classList.add('error');
      } finally {
        if (contactSubmitBtn) {
          contactSubmitBtn.disabled = false;
          contactSubmitBtn.innerHTML = originalBtnHtml;
        }

        // Clear feedback message after 8 seconds
        setTimeout(() => {
          if (contactFeedback) {
            contactFeedback.textContent = '';
            contactFeedback.className = 'contact__message';
          }
        }, 8000);
      }
    });
  }
});
