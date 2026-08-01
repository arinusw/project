/*==================== SHOW MENU ====================*/
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close')

/* Menu show */
if (navToggle) {
  navToggle.addEventListener('click', () => {
    navMenu.classList.add('show-menu')
  })
}

/* Menu hidden */
if (navClose) {
  navClose.addEventListener('click', () => {
    navMenu.classList.remove('show-menu')
  })
}

/*==================== REMOVE MENU MOBILE ====================*/
const navLink = document.querySelectorAll('.nav__link')

function linkAction() {
  const navMenu = document.getElementById('nav-menu')
  // When we click on each nav__link, we remove the show-menu class
  navMenu.classList.remove('show-menu')
}
navLink.forEach(n => n.addEventListener('click', linkAction))

/*==================== CHANGE BACKGROUND HEADER ====================*/
function scrollHeader() {
  const header = document.getElementById('header')
  // When the scroll is greater than 50 viewport height, add the scroll-header class to the header tag
  if (window.scrollY >= 50) {
    header.classList.add('scroll-header')
  } else {
    header.classList.remove('scroll-header')
  }
}
window.addEventListener('scroll', scrollHeader)

/*==================== SHOW SCROLL UP ====================*/
function scrollUp() {
  const scrollUp = document.getElementById('scroll-up')
  // When the scroll is higher than 350 viewport height, add the show-scroll class to the a tag with the scroll-top class
  if (window.scrollY >= 350) {
    scrollUp.classList.add('show-scroll')
  } else {
    scrollUp.classList.remove('show-scroll')
  }
}
window.addEventListener('scroll', scrollUp)

/*==================== SCROLL SECTIONS ACTIVE LINK ====================*/
const sections = document.querySelectorAll('section[id]')

function scrollActive() {
  const scrollY = window.scrollY

  sections.forEach(current => {
    const sectionHeight = current.offsetHeight,
          sectionTop = current.offsetTop - 58,
          sectionId = current.getAttribute('id')

    // Scroll active link highlighted by targeting nav-menu links
    const targetLink = document.querySelector(`#nav-menu a[href*=${sectionId}]`)
    if (targetLink) {
      if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
        targetLink.classList.add('active-link')
      } else {
        targetLink.classList.remove('active-link')
      }
    }
  })
}
window.addEventListener('scroll', scrollActive)

/*==================== DARK LIGHT THEME ====================*/
const themeButton = document.getElementById('theme-button')
const darkTheme = 'dark-theme'
const iconTheme = 'ri-sun-line'

// Previously selected topic (if user selected)
const selectedTheme = localStorage.getItem('selected-theme')
const selectedIcon = localStorage.getItem('selected-icon')

// We obtain the current theme that the interface has by validating the dark-theme class
const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light'
const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-moon-line' : 'ri-sun-line'

// We validate if the user previously chose a topic
if (selectedTheme) {
  // If the validation is fulfilled, we ask what the issue was to know if we activated or deactivated the dark
  document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme)
  themeButton.classList[selectedIcon === 'ri-moon-line' ? 'add' : 'remove'](iconTheme)
}

// Activate / deactivate the theme manually with the button
themeButton.addEventListener('click', () => {
  // Add or remove the dark / icon theme
  document.body.classList.toggle(darkTheme)
  themeButton.classList.toggle(iconTheme)
  // We save the theme and the current icon that the user chose
  localStorage.setItem('selected-theme', getCurrentTheme())
  localStorage.setItem('selected-icon', getCurrentIcon())
})

/*==================== SCROLL REVEAL ANIMATION ====================*/
const sr = ScrollReveal({
  origin: 'top',
  distance: '60px',
  duration: 1000,
  delay: 400,
  reset: false
})

// CRITICAL: .home__social is EXCLUDED from ScrollReveal to prevent visibility issues
sr.reveal(`.home__data`)
sr.reveal(`.home__images`, { delay: 600, origin: 'bottom' })

sr.reveal(`.about__images`, { origin: 'left' })
sr.reveal(`.about__data`, { origin: 'right' })

sr.reveal(`.services__card`, { interval: 100 })

sr.reveal(`.education__data`, { origin: 'left' })
sr.reveal(`.education__images`, { origin: 'right' })

sr.reveal(`.social__description`)
sr.reveal(`.social__card`, { interval: 100, origin: 'bottom' })

sr.reveal(`.contact__info`, { origin: 'left' })
sr.reveal(`.contact__form-container`, { origin: 'right' })

sr.reveal(`.footer__container`)

/*==================== CONTACT FORM SUBMISSION ====================*/
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message')

if (contactForm && contactMessage) {
  contactForm.addEventListener('submit', (e) => {
    e.preventDefault()

    // Show loading state
    contactMessage.textContent = 'Mengirim pesan...'
    contactMessage.className = 'contact__message'

    const formData = new FormData(contactForm)

    // Send POST request via Fetch API
    fetch('contact.php', {
      method: 'POST',
      body: formData
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Respons jaringan bermasalah')
      }
      return response.json()
    })
    .then(data => {
      if (data.status === 'success') {
        // Success
        contactMessage.textContent = data.message
        contactMessage.className = 'contact__message success'

        // Reset form inputs after successful submission
        contactForm.reset()
      } else {
        // Error status in JSON
        contactMessage.textContent = data.message
        contactMessage.className = 'contact__message error'
      }
    })
    .catch(error => {
      console.error('Error submitting form:', error)
      contactMessage.textContent = 'Gagal mengirim pesan. Silakan coba lagi.'
      contactMessage.className = 'contact__message error'
    })
  })
}
