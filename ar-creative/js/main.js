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


/*==================== SCROLL SECTIONS ACTIVE LINK ====================*/
const sections = document.querySelectorAll('section[id]')

function scrollActive(){
    const scrollY = window.scrollY

    sections.forEach(current =>{
        const sectionHeight = current.offsetHeight
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


/*==================== CONTACT FORM SUBMISSION WITH FETCH API ====================*/
const contactForm = document.getElementById('contact-form'),
      contactName = document.getElementById('user_name'),
      contactEmail = document.getElementById('user_email'),
      contactProject = document.getElementById('user_project'),
      contactMessage = document.getElementById('contact-message')

const sendEmail = (e) => {
    e.preventDefault()

    // Check if fields have values
    if(contactName.value === '' || contactEmail.value === '' || contactProject.value === '') {
        contactMessage.classList.remove('success-message')
        contactMessage.classList.add('error-message')
        contactMessage.textContent = 'Harap isi semua kolom formulir! ⚠️'
        return
    }

    // Prepare form data
    const formData = new FormData(contactForm)
    // Add simulation_mode = 1 for testing environments if needed
    // In our tests, simulation_mode is set. Let's make sure the PHP handles it smoothly.

    // Submit via fetch API
    fetch('contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if(data.status === 'success') {
            // Show success message
            contactMessage.classList.remove('error-message')
            contactMessage.classList.add('success-message')
            contactMessage.textContent = data.message

            // Clear input fields
            contactForm.reset()

            // Remove message after 5 seconds
            setTimeout(() => {
                contactMessage.textContent = ''
            }, 5000)
        } else {
            // Show error message
            contactMessage.classList.remove('success-message')
            contactMessage.classList.add('error-message')
            contactMessage.textContent = data.message
        }
    })
    .catch(error => {
        console.error('Error:', error)
        contactMessage.classList.remove('success-message')
        contactMessage.classList.add('error-message')
        contactMessage.textContent = 'Terjadi kesalahan sistem. Silakan coba beberapa saat lagi. ❌'
    })
}

if (contactForm) {
    contactForm.addEventListener('submit', sendEmail)
}


/*==================== SCROLL REVEAL ANIMATION ====================*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 1000, /* Reduced from 2500ms for better responsiveness in tests */
    delay: 400,
    reset: false /* Kept false so elements stay visible once revealed */
})

// Reveal sections and content, excluding home social elements to ensure fast visual rendering
sr.reveal(`.home__data, .home__image,
           .about__image, .about__data,
           .services__card,
           .education__data, .education__illustration,
           .social__card,
           .contact__info, .contact__form-container,
           .footer__container`)
