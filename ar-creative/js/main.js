/*==================== MENU SHOW Y HIDDEN ====================*/
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close')

/*===== MENU SHOW =====*/
/* Validate if constant exists */
if(navToggle){
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu')
    })
}

/*===== MENU HIDDEN =====*/
/* Validate if constant exists */
if(navClose){
    navClose.addEventListener('click', () => {
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
    if(window.scrollY >= 50) header.classList.add('scroll-header'); else header.classList.remove('scroll-header')
}
window.addEventListener('scroll', scrollHeader)

/*==================== SHOW SCROLL UP ====================*/
function scrollUp(){
    const scrollUp = document.getElementById('scroll-up')
    // When the scroll is higher than 350 viewport height, add the show-scroll class to the a tag with the scroll-top class
    if(window.scrollY >= 350) scrollUp.classList.add('show-scroll'); else scrollUp.classList.remove('show-scroll')
}
window.addEventListener('scroll', scrollUp)

/*==================== SCROLL SECTIONS ACTIVE LINK ====================*/
const sections = document.querySelectorAll('section[id]')

function scrollActive(){
    const scrollY = window.scrollY

    sections.forEach(current =>{
        const sectionHeight = current.offsetHeight,
              sectionTop = current.offsetTop - 58,
              sectionId = current.getAttribute('id')

        // Using nav-menu scope to find correct active anchor
        const navAnchor = document.querySelector('.nav__menu a[href*=' + sectionId + ']')

        if(navAnchor) {
            if(scrollY > sectionTop && scrollY <= sectionTop + sectionHeight){
                navAnchor.classList.add('active-link')
            }else{
                navAnchor.classList.remove('active-link')
            }
        }
    })
}
window.addEventListener('scroll', scrollActive)

/*==================== DARK LIGHT THEME ====================*/
const themeButton = document.getElementById('theme-button')
const darkTheme = 'dark-theme'
const iconTheme = 'ri-sun-line' // Theme icon changes to sun on dark mode

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

/*==================== CONTACT FORM SUBMISSION (AJAX FETCH) ====================*/
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message')

if(contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault()

        // Display sending message
        contactMessage.textContent = 'Mengirim pesan...'
        contactMessage.className = 'contact__message'

        const formData = new FormData(contactForm)

        // Asynchronously send the form using Fetch API
        fetch(contactForm.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Terjadi kesalahan pada server.')
            }
            return response.json()
        })
        .then(data => {
            if(data.status === 'success') {
                // Show success feedback
                contactMessage.textContent = data.message
                contactMessage.className = 'contact__message success'

                // Clear inputs
                contactForm.reset()
            } else {
                // Show error feedback from server
                contactMessage.textContent = data.message
                contactMessage.className = 'contact__message error'
            }
        })
        .catch(error => {
            // Show standard network error message
            contactMessage.textContent = 'Gagal mengirim pesan. Silakan periksa koneksi Anda.'
            contactMessage.className = 'contact__message error'
        })
    })
}

/*==================== SCROLL REVEAL ANIMATION ====================*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 1000,
    delay: 400,
    reset: false // Keep elements visible once they have entered
})

// ScrollReveal on various components (excluding .home__social as per memory instructions)
sr.reveal(`.home__data, .home__img-wrapper`)
sr.reveal(`.about__img, .about__data`, {origin: 'left', delay: 500})
sr.reveal(`.services__card`, {interval: 150})
sr.reveal(`.education__data, .education__img`, {origin: 'right', delay: 500})
sr.reveal(`.social__description, .social__card`, {interval: 150})
sr.reveal(`.contact__info, .contact__content`, {origin: 'bottom', interval: 150})
