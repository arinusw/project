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

/*=============== SCROLL REVEAL ANIMATION ===============*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 2500,
    delay: 400,
    reset: false // Animations should not repeat on scroll for better performance and screenshots
})

sr.reveal(`.home__data, .about__img, .education__data, .social__container`)
sr.reveal(`.home__img, .about__data, .education__img`, {origin: 'bottom'})
sr.reveal(`.services__card, .contact__card`, {interval: 100})
sr.reveal(`.contact__form`, {origin: 'right'})

/*=============== CONTACT FORM AJAX ===============*/
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message')

const sendEmail = (e) =>{
    e.preventDefault()

    // ServiceID - templateID - #form - publickey
    // For now, using a simple fetch to contact.php
    const formData = new FormData(contactForm)

    contactMessage.textContent = 'Mengirim pesan...'
    contactMessage.style.color = 'var(--text-color)'

    fetch('contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success'){
            contactMessage.textContent = data.message
            contactMessage.style.color = 'var(--first-color)'
            contactForm.reset()
        } else {
            contactMessage.textContent = data.message
            contactMessage.style.color = 'red'
        }
    })
    .catch(error => {
        contactMessage.textContent = 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.'
        contactMessage.style.color = 'red'
    })
    .finally(() => {
        setTimeout(() => {
            contactMessage.textContent = ''
        }, 5000)
    })
}

if(contactForm) {
    contactForm.addEventListener('submit', sendEmail)
}
