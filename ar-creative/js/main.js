/*==================== MENU SHOW Y HIDDEN ====================*/
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close')

/*===== MENU SHOW =====*/
/* Validate if constant exists */
if(navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu')
    })
}

/*===== MENU HIDDEN =====*/
/* Validate if constant exists */
if(navClose) {
    navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu')
    })
}

/*==================== REMOVE MENU MOBILE ON LINK CLICK ====================*/
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
    // Use window.scrollY per strict mode and cross-browser consistency guideline
    if(window.scrollY >= 50) header.classList.add('scroll-header'); else header.classList.remove('scroll-header')
}
window.addEventListener('scroll', scrollHeader)

/*==================== SHOW SCROLL UP ====================*/
function scrollUp() {
    const scrollUpBtn = document.getElementById('scroll-up')
    // When the scroll is higher than 350 viewport height, add the show-scroll class to the a tag with the scroll-top class
    // Use window.scrollY per guideline
    if(window.scrollY >= 350) scrollUpBtn.classList.add('show-scroll'); else scrollUpBtn.classList.remove('show-scroll')
}
window.addEventListener('scroll', scrollUp)

/*==================== SCROLL SECTIONS ACTIVE LINK ====================*/
const sections = document.querySelectorAll('section[id]')

function scrollActive() {
    const scrollY = window.scrollY

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight
        // Buffer is 58px as per active scroll link tracking guideline
        const sectionTop = current.offsetTop - 58
        const sectionId = current.getAttribute('id')
        const activeClassLocator = document.querySelector('.nav__menu a[href*=' + sectionId + ']')

        if(activeClassLocator) {
            if(scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                activeClassLocator.classList.add('active-link')
            } else {
                activeClassLocator.classList.remove('active-link')
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

/*==================== SCROLL REVEAL ANIMATION ====================*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 1000, // Reduced from 2500ms to 1000ms for testing visibility per guideline
    delay: 400,
    reset: false // Keep elements visible once they've animated in as per guideline
})

// Entrance animations configured excluding .home__social per guidelines
sr.reveal(`.home__data`)
sr.reveal(`.home__img-container`, {delay: 500})
sr.reveal(`.about__img-container, .contact__info`, {origin: 'left'})
sr.reveal(`.about__data, .contact__content`, {origin: 'right'})
sr.reveal(`.services__card, .socials__card`, {interval: 100})
sr.reveal(`.education__data`, {origin: 'left'})
sr.reveal(`.education__img-container`, {origin: 'right'})

/*==================== CONTACT FORM SUBMISSION (AJAX FETCH) ====================*/
const contactForm = document.getElementById('contact-form')
const contactMessage = document.getElementById('contact-message')

if(contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault()

        contactMessage.textContent = 'Mengirim pesan...'
        contactMessage.style.color = 'var(--first-color)'

        const formData = new FormData(contactForm)

        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if(!response.ok) {
                throw new Error('Terjadi kesalahan koneksi server.')
            }
            return response.json()
        })
        .then(data => {
            if(data.status === 'success') {
                contactMessage.textContent = data.message
                contactMessage.style.color = 'green'
                contactForm.reset()
            } else {
                contactMessage.textContent = data.message
                contactMessage.style.color = 'red'
            }
        })
        .catch(error => {
            contactMessage.textContent = 'Gagal mengirim pesan: ' + error.message
            contactMessage.style.color = 'red'
        })
    })
}
