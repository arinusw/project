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
    const nav = document.getElementById('header')
    // When the scroll is greater than 50 viewport height, add the scroll-header class to the header tag
    if(window.scrollY >= 50) nav.classList.add('scroll-header'); else nav.classList.remove('scroll-header')
}
window.addEventListener('scroll', scrollHeader)


/*==================== SHOW SCROLL UP ====================*/
function scrollUp(){
    const scrollUp = document.getElementById('scroll-up');
    // When the scroll is higher than 350 viewport height, add the show-scroll class to the a tag with the scroll-top class
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


/*==================== CONTACT FORM SUBMISSION ====================*/
const contactForm = document.getElementById('contact-form')
const contactMessage = document.getElementById('contact-message')

if(contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault()

        const formData = new FormData(contactForm)

        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                contactMessage.classList.add('color-blue')
                contactMessage.classList.remove('color-red')
                contactMessage.textContent = data.message
                contactForm.reset()
            } else {
                contactMessage.classList.add('color-red')
                contactMessage.classList.remove('color-blue')
                contactMessage.textContent = data.message
            }

            // Remove message after 5 seconds
            setTimeout(() => {
                contactMessage.textContent = ''
            }, 6000)
        })
        .catch(error => {
            console.error('Error:', error)
            contactMessage.classList.add('color-red')
            contactMessage.classList.remove('color-blue')
            contactMessage.textContent = 'Terjadi kesalahan sistem. Silakan coba lagi.'
        })
    })
}


/*==================== SCROLL SECTIONS ACTIVE LINK ====================*/
const sections = document.querySelectorAll('section[id]')

function scrollActive(){
    const scrollY = window.scrollY

    sections.forEach(current =>{
        const sectionHeight = current.offsetHeight
        const sectionTop = current.offsetTop - 58;
        const sectionId = current.getAttribute('id')

        // Scope scroll-active link highlighting to the '#nav-menu' locator
        const activeItem = document.querySelector('#nav-menu a[href*=' + sectionId + ']')

        if(activeItem) {
            if(scrollY > sectionTop && scrollY <= sectionTop + sectionHeight){
                activeItem.classList.add('active-link')
            }else{
                activeItem.classList.remove('active-link')
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

// Excluding immediate visible components or handling them with care
sr.reveal(`.home__data, .about__image, .education__image, .contact__info`, {delay: 100})
sr.reveal(`.home__image, .about__data, .education__data, .contact__content`, {origin: 'bottom', delay: 150})
sr.reveal(`.services__card, .socials__card`, {interval: 100})
