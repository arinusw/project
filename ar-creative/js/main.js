/*==================== MENU SHOW Y HIDDEN ====================*/
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close')

/*===== MENU SHOW =====*/
/* Validate if constant exists */
if(navToggle){
    navToggle.addEventListener('click', () =>{
        navMenu.classList.add('show-menu')
    })
}

/*===== MENU HIDDEN =====*/
/* Validate if constant exists */
if(navClose){
    navClose.addEventListener('click', () =>{
        navMenu.classList.remove('show-menu')
    })
}

/*==================== REMOVE MENU MOBILE ====================*/
const navLink = document.querySelectorAll('.nav__link')

function linkAction(){
    const navMenu = document.getElementById('nav-menu')
    // When we click on each nav__link, we remove the show-menu class
    navMenu.classList.remove('show-menu')
}
navLink.forEach(n => n.addEventListener('click', linkAction))


/*==================== CHANGE BACKGROUND HEADER ====================*/
function scrollHeader(){
    const header = document.getElementById('header')
    // When the scroll is greater than 50 viewport height, add the scroll-header class to the header tag
    // Memory: scroll handling functions should use window.scrollY instead of this.scrollY
    if(window.scrollY >= 50) header.classList.add('scroll-header'); else header.classList.remove('scroll-header')
}
window.addEventListener('scroll', scrollHeader)


/*==================== SHOW SCROLL UP ====================*/
function scrollUp(){
    const scrollUp = document.getElementById('scroll-up')
    // When the scroll is higher than 350 viewport height, add the show-scroll class to the a tag with the scroll-top class
    // Memory: scroll handling functions should use window.scrollY instead of this.scrollY
    if(window.scrollY >= 350) scrollUp.classList.add('show-scroll'); else scrollUp.classList.remove('show-scroll')
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


/*==================== SCROLL SECTIONS ACTIVE LINK ====================*/
const sections = document.querySelectorAll('section[id]')

function scrollActive(){
    // Memory: scroll handling functions should use window.scrollY instead of this.scrollY
    const scrollY = window.scrollY

    sections.forEach(current =>{
        const sectionHeight = current.offsetHeight
        // 58px buffer as specified by memories
        const sectionTop = current.offsetTop - 58
        const sectionId = current.getAttribute('id')

        if(scrollY > sectionTop && scrollY <= sectionTop + sectionHeight){
            document.querySelector('.nav__menu a[href*=' + sectionId + ']').classList.add('active-link')
        }else{
            document.querySelector('.nav__menu a[href*=' + sectionId + ']').classList.remove('active-link')
        }
    })
}
window.addEventListener('scroll', scrollActive)


/*==================== CONTACT FORM SUBMISSION VIA FETCH API ====================*/
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message')

if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault()

        // Provide sending status feedback
        contactMessage.textContent = 'Mengirim pesan...'
        contactMessage.className = 'contact__message'

        // Prepare form data
        const formData = new FormData(contactForm)

        // Send request asynchronously to contact.php
        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Terjadi kesalahan pada server. Coba lagi nanti.')
            }
            return response.json()
        })
        .then(data => {
            // Apply correct status class
            if (data.status === 'success') {
                contactMessage.className = 'contact__message success'
                contactMessage.textContent = data.message
                // Clear inputs
                contactForm.reset()
            } else {
                contactMessage.className = 'contact__message error'
                contactMessage.textContent = data.message
            }
        })
        .catch(error => {
            contactMessage.className = 'contact__message error'
            contactMessage.textContent = error.message || 'Gagal mengirim pesan. Silakan periksa koneksi Anda.'
        })
        .finally(() => {
            // Optional: Auto clear status message after a few seconds
            setTimeout(() => {
                contactMessage.textContent = ''
            }, 5000)
        })
    })
}


/*==================== SCROLL REVEAL ANIMATION ====================*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 1000,
    delay: 400,
    reset: false // Keep static visible after reveal
})

// Revelations configuration
// Memory: Exclude '.home__social' elements in main.js to ensure immediate visibility and prevent rendering/UI test delays.
sr.reveal(`.home__data`)
sr.reveal(`.home__image`, {delay: 500, origin: 'bottom'})
sr.reveal(`.about__image`, {origin: 'left'})
sr.reveal(`.about__data`, {origin: 'right'})
sr.reveal(`.services__card`, {interval: 100})
sr.reveal(`.education__data`, {origin: 'left'})
sr.reveal(`.education__image`, {origin: 'right'})
sr.reveal(`.socialmedia__card`, {interval: 100})
sr.reveal(`.contact__info`, {origin: 'left'})
sr.reveal(`.contact__form-container`, {origin: 'right'})
