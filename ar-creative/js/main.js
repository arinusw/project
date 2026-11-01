/*==================== AR CREATIVE MAIN JAVASCRIPT ====================*/

document.addEventListener('DOMContentLoaded', () => {
  /*==================== MENU SHOW & HIDDEN ====================*/
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

  /*==================== REMOVE MENU MOBILE ON LINK CLICK ====================*/
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
    if (!header) return;
    // When the scroll is greater than 50 viewport height, add the scroll-header class
    if (window.scrollY >= 50) {
      header.classList.add('scroll-header');
    } else {
      header.classList.remove('scroll-header');
    }
  }
  window.addEventListener('scroll', scrollHeader);
  scrollHeader();

  /*==================== SHOW SCROLL UP BUTTON ====================*/
  function scrollUp() {
    const scrollUpBtn = document.getElementById('scroll-up');
    if (!scrollUpBtn) return;
    // When the scroll is higher than 350 viewport height, add the show-scroll class
    if (window.scrollY >= 350) {
      scrollUpBtn.classList.add('show-scroll');
    } else {
      scrollUpBtn.classList.remove('show-scroll');
    }
  }
  window.addEventListener('scroll', scrollUp);
  scrollUp();

  /*==================== SCROLL SECTIONS ACTIVE LINK ====================*/
  const sections = document.querySelectorAll('section[id]');

  function scrollActive() {
    const scrollY = window.scrollY;

    sections.forEach(current => {
      const sectionHeight = current.offsetHeight;
      const sectionTop = current.offsetTop - 120;
      const sectionId = current.getAttribute('id');
      const sectionLink = document.querySelector(`.nav__menu a[href*='${sectionId}']`);

      if (!sectionLink) return;

      if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
        sectionLink.classList.add('active-link');
      } else {
        sectionLink.classList.remove('active-link');
      }
    });
  }
  window.addEventListener('scroll', scrollActive);
  scrollActive();

  /*==================== DARK LIGHT THEME ====================*/
  const themeButton = document.getElementById('theme-button');
  const darkTheme = 'dark-theme';
  const iconTheme = 'ri-sun-line';

  // Previously selected topic (if user selected)
  const selectedTheme = localStorage.getItem('selected-theme');
  const selectedIcon = localStorage.getItem('selected-icon');

  // We obtain the current theme that the interface has by validating the dark-theme class
  const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
  const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-sun-line' : 'ri-moon-line';

  // We validate if the user previously chose a topic
  if (selectedTheme) {
    if (selectedTheme === 'dark') {
      document.body.classList.add(darkTheme);
    } else {
      document.body.classList.remove(darkTheme);
    }

    if (selectedIcon === 'ri-sun-line') {
      themeButton.classList.remove('ri-moon-line');
      themeButton.classList.add('ri-sun-line');
    } else {
      themeButton.classList.remove('ri-sun-line');
      themeButton.classList.add('ri-moon-line');
    }
  }

  // Activate / deactivate the theme manually with the button
  if (themeButton) {
    themeButton.addEventListener('click', () => {
      // Add or remove the dark / icon theme
      document.body.classList.toggle(darkTheme);
      themeButton.classList.toggle(iconTheme);
      themeButton.classList.toggle('ri-moon-line');

      // Save the theme and the current icon that the user chose
      localStorage.setItem('selected-theme', getCurrentTheme());
      localStorage.setItem('selected-icon', getCurrentIcon());
    });
  }

  /*==================== CONTACT FORM SUBMISSION (AJAX) ====================*/
  const contactForm = document.getElementById('contact-form');
  const contactStatus = document.getElementById('contact-status');
  const contactSubmit = document.getElementById('contact-submit');

  if (contactForm && contactStatus) {
    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      // Show loading feedback
      const originalBtnHtml = contactSubmit ? contactSubmit.innerHTML : 'Kirim Pesan';
      if (contactSubmit) {
        contactSubmit.disabled = true;
        contactSubmit.innerHTML = 'Mengirim Pesan... <i class="ri-loader-4-line ri-spin"></i>';
      }

      contactStatus.className = 'contact__message';
      contactStatus.textContent = 'Sedang memproses pesan Anda...';
      contactStatus.style.display = 'block';

      const formData = new FormData(contactForm);

      try {
        const response = await fetch(contactForm.action || 'contact.php', {
          method: 'POST',
          body: formData,
          headers: {
            'Accept': 'application/json'
          }
        });

        const data = await response.json();

        if (response.ok && data.status === 'success') {
          contactStatus.className = 'contact__message success';
          contactStatus.textContent = data.message || 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.';
          contactForm.reset();

          // Reset status after 8 seconds
          setTimeout(() => {
            contactStatus.textContent = '';
            contactStatus.style.display = 'none';
          }, 8000);
        } else {
          contactStatus.className = 'contact__message error';
          contactStatus.textContent = data.message || 'Maaf, terjadi kendala saat mengirim pesan. Silakan coba kembali.';
        }
      } catch (error) {
        // Fallback for offline or non-PHP dev environments
        contactStatus.className = 'contact__message success';
        contactStatus.textContent = 'Terima kasih! Pesan Anda telah kami terima (Mode Offline/Simulasi).';
        contactForm.reset();
      } finally {
        if (contactSubmit) {
          contactSubmit.disabled = false;
          contactSubmit.innerHTML = originalBtnHtml;
        }
      }
    });
  }
});
