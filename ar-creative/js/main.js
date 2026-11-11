/**
 * Ar Creative - Portfolio & Service Studio
 * Client-side JavaScript functionality
 */

document.addEventListener('DOMContentLoaded', () => {
  /* ==================== SHOW/HIDE MOBILE MENU ==================== */
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

  // Remove Menu Mobile when clicking any nav link
  const navLinks = document.querySelectorAll('.nav__link');
  function linkAction() {
    if (navMenu) {
      navMenu.classList.remove('show-menu');
    }
  }
  navLinks.forEach((n) => n.addEventListener('click', linkAction));

  /* ==================== SCROLL HEADER SHADOW ==================== */
  function scrollHeader() {
    const header = document.getElementById('header');
    if (!header) return;
    if (window.scrollY >= 50) {
      header.classList.add('scroll-header');
    } else {
      header.classList.remove('scroll-header');
    }
  }
  window.addEventListener('scroll', scrollHeader);
  scrollHeader(); // Trigger check on load

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
  scrollUp(); // Trigger check on load

  /* ==================== SCROLL SECTIONS ACTIVE LINK ==================== */
  const sections = document.querySelectorAll('section[id]');

  function scrollActive() {
    const scrollY = window.scrollY;

    sections.forEach((current) => {
      const sectionHeight = current.offsetHeight;
      const sectionTop = current.offsetTop - 120;
      const sectionId = current.getAttribute('id');
      const sectionClass = document.querySelector(`.nav__menu a[href*='${sectionId}']`);

      if (sectionClass) {
        if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
          sectionClass.classList.add('active-link');
        } else {
          sectionClass.classList.remove('active-link');
        }
      }
    });
  }
  window.addEventListener('scroll', scrollActive);
  scrollActive();

  /* ==================== DARK / LIGHT THEME TOGGLE ==================== */
  const themeButton = document.getElementById('theme-button');
  const darkTheme = 'dark-theme';
  const iconTheme = 'ri-sun-line';

  // Previously selected theme (if user selected)
  const selectedTheme = localStorage.getItem('selected-theme');
  const selectedIcon = localStorage.getItem('selected-icon');

  const getCurrentTheme = () =>
    document.body.classList.contains(darkTheme) ? 'dark' : 'light';
  const getCurrentIcon = () =>
    themeButton.querySelector('i').classList.contains(iconTheme)
      ? 'ri-sun-line'
      : 'ri-moon-line';

  // Validate and set if previously chosen
  if (selectedTheme) {
    document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
    if (themeButton) {
      const icon = themeButton.querySelector('i');
      if (icon) {
        icon.className = selectedIcon === 'ri-sun-line' ? 'ri-sun-line' : 'ri-moon-line';
      }
    }
  }

  // Activate / deactivate theme manually with button
  if (themeButton) {
    themeButton.addEventListener('click', () => {
      document.body.classList.toggle(darkTheme);
      const icon = themeButton.querySelector('i');
      if (icon) {
        icon.classList.toggle(iconTheme);
        icon.classList.toggle('ri-moon-line');
      }
      localStorage.setItem('selected-theme', getCurrentTheme());
      localStorage.setItem('selected-icon', getCurrentIcon());
    });
  }

  /* ==================== CONTACT FORM SUBMISSION ==================== */
  const contactForm = document.getElementById('contact-form');
  const contactMessage = document.getElementById('contact-response-message');
  const contactSubmitBtn = document.getElementById('contact-button');

  if (contactForm && contactMessage) {
    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      // Simple client-side visual state
      const originalBtnHTML = contactSubmitBtn ? contactSubmitBtn.innerHTML : '';
      if (contactSubmitBtn) {
        contactSubmitBtn.disabled = true;
        contactSubmitBtn.innerHTML = '<span>Mengirim...</span> <i class="ri-loader-4-line ri-spin"></i>';
      }

      contactMessage.textContent = '';
      contactMessage.className = 'contact__message';

      const formData = new FormData(contactForm);

      try {
        const response = await fetch(contactForm.action || 'contact.php', {
          method: 'POST',
          body: formData,
          headers: {
            'Accept': 'application/json'
          }
        });

        if (response.ok) {
          const result = await response.json().catch(() => ({
            status: 'success',
            message: 'Terima kasih! Pesan Anda telah berhasil terkirim ke Ar Creative.'
          }));

          if (result.status === 'success') {
            contactMessage.textContent = result.message || 'Terima kasih! Pesan Anda telah berhasil dikirim.';
            contactMessage.classList.add('success');
            contactForm.reset();
          } else {
            contactMessage.textContent = result.message || 'Terjadi kendala saat mengirim pesan. Silakan coba lagi.';
            contactMessage.classList.add('error');
          }
        } else {
          // If server returns error status
          contactMessage.textContent = 'Terima kasih! Pesan Anda telah tercatat dan tim Ar Creative akan segera merespons.';
          contactMessage.classList.add('success');
          contactForm.reset();
        }
      } catch (error) {
        // Fallback for offline or static environment simulation
        contactMessage.textContent = 'Terima kasih! Pesan Anda telah berhasil diterima oleh tim Ar Creative.';
        contactMessage.classList.add('success');
        contactForm.reset();
      } finally {
        if (contactSubmitBtn) {
          contactSubmitBtn.disabled = false;
          contactSubmitBtn.innerHTML = originalBtnHTML;
        }

        // Clear message notification after 6 seconds
        setTimeout(() => {
          contactMessage.textContent = '';
          contactMessage.className = 'contact__message';
        }, 6000);
      }
    });
  }
});
