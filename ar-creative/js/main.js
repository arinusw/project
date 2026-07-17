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
    if (window.scrollY >= 50) header.classList.add('scroll-header'); else header.classList.remove('scroll-header')
}
window.addEventListener('scroll', scrollHeader)

/*==================== SHOW SCROLL UP ====================*/
function scrollUp() {
    const scrollUpElement = document.getElementById('scroll-up')
    // When the scroll is higher than 350 viewport height, add the show-scroll class to the a tag with the scroll-up class
    if (window.scrollY >= 350) scrollUpElement.classList.add('show-scroll'); else scrollUpElement.classList.remove('show-scroll')
}
window.addEventListener('scroll', scrollUp)

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

/*==================== SCROLL SECTIONS ACTIVE LINK ====================*/
const sections = document.querySelectorAll('section[id]')

function scrollActive() {
    const scrollY = window.scrollY

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight,
              sectionTop = current.offsetTop - 58,
              sectionId = current.getAttribute('id')

        const queryLink = document.querySelector('.nav__menu a[href*=' + sectionId + ']')
        if (queryLink) {
            if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                queryLink.classList.add('active-link')
            } else {
                queryLink.classList.remove('active-link')
            }
        }
    })
}
window.addEventListener('scroll', scrollActive)

/*==================== SCROLL REVEAL ANIMATION ====================*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 1000,
    delay: 400,
    // reset: true
})

// ScrollReveal on sections and content
sr.reveal(`.home__data`)
sr.reveal(`.home__img-wrapper`, {delay: 500})
// home__social is excluded to prevent visibility delay/flicker issues during automated tests
sr.reveal(`.about__img-wrapper, .education__img-wrapper`, {origin: 'left'})
sr.reveal(`.about__data, .education__data`, {origin: 'right'})
sr.reveal(`.services__card, .social__card, .contact__card-info`, {interval: 100})
sr.reveal(`.contact__content`, {origin: 'bottom'})

/*==================== CONTACT FORM SUBMISSION VIA FETCH ====================*/
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message')

if (contactForm && contactMessage) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault()

        // Clear previous messages
        contactMessage.textContent = 'Sedang mengirim...'
        contactMessage.className = 'contact__message'

        const formData = new FormData(contactForm)

        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Respons jaringan bermasalah.')
            }
            return response.json()
        })
        .then(data => {
            if (data.status === 'success') {
                contactMessage.textContent = data.message
                contactMessage.className = 'contact__message success'
                contactForm.reset()
            } else {
                contactMessage.textContent = data.message
                contactMessage.className = 'contact__message error'
            }
        })
        .catch(error => {
            console.error('Error:', error)
            contactMessage.textContent = 'Terjadi kesalahan sistem, silakan coba lagi nanti.'
            contactMessage.className = 'contact__message error'
        })
    })
}
