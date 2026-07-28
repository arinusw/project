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
    const nav = document.getElementById('header')
    // When the scroll is greater than 50 viewport height, add the scroll-header class to the header tag
    if(window.scrollY >= 50) nav.classList.add('scroll-header'); else nav.classList.remove('scroll-header')
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
        const sectionHeight = current.offsetHeight
        const sectionTop = current.offsetTop - 58
        const sectionId = current.getAttribute('id')
        const activeTarget = document.querySelector('.nav__menu a[href*=' + sectionId + ']')

        if(activeTarget) {
            if(scrollY > sectionTop && scrollY <= sectionTop + sectionHeight){
                activeTarget.classList.add('active-link')
            }else{
                activeTarget.classList.remove('active-link')
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
const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-sun-line' : 'ri-moon-line'

// We validate if the user previously chose a topic
if (selectedTheme) {
  // If the validation is fulfilled, we ask what the issue was to know if we activated or deactivated the dark theme
  document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme)
  themeButton.classList[selectedIcon === 'ri-sun-line' ? 'add' : 'remove'](iconTheme)
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


/*==================== CONTACT FORM SUBMISSION VIA FETCH ====================*/
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message')

if(contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault()

        // Clear any previous classes and set message loading style
        contactMessage.classList.remove('success', 'error')
        contactMessage.textContent = 'Mengirim pesan...'

        // Read all inputs
        const formData = new FormData(contactForm)

        // Retrieve the current simulation mode if set, otherwise fallback to default
        // Standard payload requires: user_name, user_email, user_project
        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Respons jaringan tidak sukses.')
            }
            return response.json()
        })
        .then(data => {
            if(data.status === 'success') {
                contactMessage.classList.add('success')
                contactMessage.textContent = data.message
                // Reset form on success
                contactForm.reset()
            } else {
                contactMessage.classList.add('error')
                contactMessage.textContent = data.message
            }
        })
        .catch(error => {
            contactMessage.classList.add('error')
            contactMessage.textContent = 'Oops! Terjadi kesalahan koneksi. Silakan coba lagi.'
            console.error('Submission Error:', error)
        })
    })
}


/*==================== SCROLL REVEAL ANIMATION ====================*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 1000,
    delay: 400,
    reset: false
})

// Entrance animations configured for elements (except for immediately visible home social icons)
sr.reveal('.home__data, .home__img-wrapper')
sr.reveal('.about__image', {origin: 'left'})
sr.reveal('.about__data', {origin: 'right'})
sr.reveal('.services__card', {interval: 100})
sr.reveal('.education__data', {origin: 'left'})
sr.reveal('.education__img-wrapper', {origin: 'right'})
sr.reveal('.social__card', {interval: 100})
sr.reveal('.contact__info, .contact__content', {origin: 'bottom', interval: 100})
