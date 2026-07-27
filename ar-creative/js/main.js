/* ==================== MENU SHOW Y HIDDEN ==================== */
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close')

/* ===== MENU SHOW ===== */
/* Validate if constant exists */
if (navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu')
    })
}

/* ===== MENU HIDDEN ===== */
/* Validate if constant exists */
if (navClose) {
    navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu')
    })
}

/* ==================== REMOVE MENU MOBILE ==================== */
const navLink = document.querySelectorAll('.nav__link')

const linkAction = () => {
    const navMenu = document.getElementById('nav-menu')
    // When we click on each nav__link, we remove the show-menu class
    navMenu.classList.remove('show-menu')
}
navLink.forEach(n => n.addEventListener('click', linkAction))

/* ==================== CHANGE BACKGROUND HEADER ==================== */
const scrollHeader = () => {
    const header = document.getElementById('header')
    // When the scroll is greater than 50 viewport height, add the scroll-header class to the header tag
    if (window.scrollY >= 50) {
        header.classList.add('scroll-header')
    } else {
        header.classList.remove('scroll-header')
    }
}
window.addEventListener('scroll', scrollHeader)

/* ==================== SHOW SCROLL UP ==================== */
const scrollUp = () => {
    const scrollUpButton = document.getElementById('scroll-up')
    // When the scroll is higher than 350 viewport height, add the show-scroll class to the a tag with the scrollup class
    if (window.scrollY >= 350) {
        scrollUpButton.classList.add('show-scroll')
    } else {
        scrollUpButton.classList.remove('show-scroll')
    }
}
window.addEventListener('scroll', scrollUp)

/* ==================== SCROLL SECTIONS ACTIVE LINK ==================== */
const sections = document.querySelectorAll('section[id]')

const scrollActive = () => {
    const scrollY = window.scrollY

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight,
              sectionTop = current.offsetTop - 58,
              sectionId = current.getAttribute('id'),
              sectionsClass = document.querySelector('.nav__menu a[href*=' + sectionId + ']')

        if (sectionsClass) {
            if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                sectionsClass.classList.add('active-link')
            } else {
                sectionsClass.classList.remove('active-link')
            }
        }
    })
}
window.addEventListener('scroll', scrollActive)

/* ==================== DARK LIGHT THEME ==================== */
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
  // If the validation is fulfilled, we ask what the issue was to know if we activated or deactivated the dark theme
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

/* ==================== CONTACT FORM SUBMISSION ==================== */
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message')

if (contactForm && contactMessage) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault()

        const formData = new FormData(contactForm)

        // Reset message styles and text
        contactMessage.classList.remove('success-color', 'error-color')
        contactMessage.textContent = 'Mengirim pesan...'

        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok')
            }
            return response.json()
        })
        .then(data => {
            contactMessage.textContent = data.message

            if (data.status === 'success') {
                contactMessage.classList.add('success-color')
                contactForm.reset()

                // Clear message after 5 seconds
                setTimeout(() => {
                    contactMessage.textContent = ''
                }, 5000)
            } else {
                contactMessage.classList.add('error-color')
            }
        })
        .catch(error => {
            console.error('Error during form submission:', error)
            contactMessage.classList.add('error-color')
            contactMessage.textContent = 'Terjadi kesalahan sistem, silakan coba lagi nanti.'
        })
    })
}

/* ==================== SCROLL REVEAL ANIMATION ==================== */
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 1000,
    delay: 400,
    // reset: true // To repeat animations on scroll
})

// Entrance animations (excluding .home__social to avoid immediate rendering issues)
sr.reveal('.home__data')
sr.reveal('.home__images', { delay: 600, origin: 'bottom' })

sr.reveal('.about__images', { origin: 'left' })
sr.reveal('.about__data', { origin: 'right' })

sr.reveal('.services__card', { interval: 100 })

sr.reveal('.education__data', { origin: 'left' })
sr.reveal('.education__images', { origin: 'right' })

sr.reveal('.socials__card', { interval: 100 })

sr.reveal('.contact__info', { origin: 'left' })
sr.reveal('.contact__form-container', { origin: 'right', delay: 500 })
