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
    // When the scroll is greater than 80 viewport height, add the scroll-header class to the header tag
    if(window.scrollY >= 80) nav.classList.add('scroll-header'); else nav.classList.remove('scroll-header')
}
window.addEventListener('scroll', scrollHeader)


/*==================== SCROLL SECTIONS ACTIVE LINK ====================*/
const sections = document.querySelectorAll('section[id]')

function scrollActive(){
    const scrollY = window.scrollY

    sections.forEach(current =>{
        const sectionHeight = current.offsetHeight
        const sectionTop = current.offsetTop - 50;
        sectionId = current.getAttribute('id')

        const navItem = document.querySelector('.nav__menu a[href*=' + sectionId + ']')
        if(navItem) {
            if(scrollY > sectionTop && scrollY <= sectionTop + sectionHeight){
                navItem.classList.add('active-link')
            }else{
                navItem.classList.remove('active-link')
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

    // Explicitly swap classes to prevent multiple icon classes
    if (themeButton.classList.contains('ri-moon-line')) {
        themeButton.classList.remove('ri-moon-line')
        themeButton.classList.add('ri-sun-line')
    } else {
        themeButton.classList.remove('ri-sun-line')
        themeButton.classList.add('ri-moon-line')
    }

    // We save the theme and the current icon that the user chose
    localStorage.setItem('selected-theme', getCurrentTheme())
    localStorage.setItem('selected-icon', getCurrentIcon())
})

/*==================== SCROLL REVEAL ANIMATION ====================*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '30px',
    duration: 2000,
    reset: false
});

sr.reveal(`.home__data, .home__img,
            .about__data, .about__img,
            .services__card,
            .education__data, .education__img,
            .social__content, .social__img,
            .contact__info, .contact__form-container`, {
    interval: 200
})

/*==================== CONTACT FORM AJAX ====================*/
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message')

const sendEmail = (e) => {
    e.preventDefault()

    // Service, Template, Form, Public Key (Placeholder logic for PHP)
    const formData = new FormData(contactForm)

    fetch('contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            contactMessage.classList.add('success')
            contactMessage.classList.remove('error')
            contactMessage.textContent = data.message
        } else {
            contactMessage.classList.add('error')
            contactMessage.classList.remove('success')
            contactMessage.textContent = data.message
        }
    })
    .catch(error => {
        contactMessage.classList.add('error')
        contactMessage.textContent = 'Terjadi kesalahan. Silakan coba lagi nanti.'
    })
    .finally(() => {
        contactForm.reset()
        setTimeout(() => {
            contactMessage.textContent = ''
        }, 5000)
    })
}

if (contactForm) {
    contactForm.addEventListener('submit', sendEmail)
}
