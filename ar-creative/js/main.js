/*=============== SHOW MENU ===============*/
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close')

/* Menu show */
if(navToggle){
    navToggle.addEventListener('click', () =>{
        navMenu.classList.add('show-menu')
    })
}

/* Menu hidden */
if(navClose){
    navClose.addEventListener('click', () =>{
        navMenu.classList.remove('show-menu')
    })
}

/*=============== REMOVE MENU MOBILE ===============*/
const navLink = document.querySelectorAll('.nav__link')

const linkAction = () =>{
    const navMenu = document.getElementById('nav-menu')
    // When we click on each nav__link, we remove the show-menu class
    navMenu.classList.remove('show-menu')
}
navLink.forEach(n => n.addEventListener('click', linkAction))

/*=============== CHANGE BACKGROUND HEADER ===============*/
const scrollHeader = () =>{
    const header = document.getElementById('header')
    // When the scroll is greater than 50 viewport height, add the scroll-header class to the header tag
    if(window.scrollY >= 50) header.classList.add('scroll-header'); else header.classList.remove('scroll-header')
}
window.addEventListener('scroll', scrollHeader)

/*=============== SCROLL SECTIONS ACTIVE LINK ===============*/
const sections = document.querySelectorAll('section[id]')

const scrollActive = () =>{
    const scrollY = window.scrollY

    sections.forEach(current =>{
        const sectionHeight = current.offsetHeight,
              sectionTop = current.offsetTop - 58,
              sectionId = current.getAttribute('id'),
              sectionsClass = document.querySelector('.nav__menu a[href*=' + sectionId + ']')

        if(scrollY > sectionTop && scrollY <= sectionTop + sectionHeight){
            if(sectionsClass) sectionsClass.classList.add('active-link')
        }else{
            if(sectionsClass) sectionsClass.classList.remove('active-link')
        }
    })
}
window.addEventListener('scroll', scrollActive)

/*=============== CONTACT FORM ===============*/
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message')

const sendEmail = (e) =>{
    e.preventDefault()

    // serviceID - templateID - #form - publicKey
    // Using fetch to contact.php for demonstration as per technical notes
    const formData = new FormData(contactForm)

    // Add simulation flag for contact.php
    formData.append('simulate', '1')

    fetch('contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success'){
            // Show sent message
            contactMessage.textContent = 'Pesan terkirim ✅'
            contactMessage.style.color = 'hsl(142, 69%, 61%)'

            // Remove message after five seconds
            setTimeout(() =>{
                contactMessage.textContent = ''
            }, 5000)

            // Clear input fields
            contactForm.reset()
        } else {
            // Show error message
            contactMessage.textContent = 'Terjadi kesalahan: ' + data.message + ' ❌'
            contactMessage.style.color = 'hsl(0, 69%, 61%)'
        }
    })
    .catch(error => {
        contactMessage.textContent = 'Gagal menghubungi server ❌'
        contactMessage.style.color = 'hsl(0, 69%, 61%)'
    })
}

if(contactForm){
    contactForm.addEventListener('submit', sendEmail)
}

/*=============== SCROLL REVEAL ANIMATION ===============*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 2500,
    delay: 400,
    reset: false // Do not repeat animations
})

sr.reveal(`.home__data, .home__img, .about__data, .about__img, .services__card, .education__data, .education__img, .contact__data, .contact__info, .contact__form, .social__container`)
sr.reveal(`.home__data, .education__data, .contact__info`, {origin: 'left'})
sr.reveal(`.home__img, .education__img, .contact__form`, {origin: 'right'})
sr.reveal(`.services__card`, {interval: 100})
