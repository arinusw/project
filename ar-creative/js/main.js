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

		if(sectionsClass){
			if(scrollY > sectionTop && scrollY <= sectionTop + sectionHeight){
				sectionsClass.classList.add('active-link')
			}else{
				sectionsClass.classList.remove('active-link')
			}
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
    reset: false // Keep animations from re-hiding
})

sr.reveal(`.home__data`)
sr.reveal(`.home__img`, {delay: 700, origin: 'bottom'})
sr.reveal(`.about__img`, {origin: 'left'})
sr.reveal(`.about__data`, {origin: 'right'})
sr.reveal(`.services__card`, {interval: 100})
sr.reveal(`.education__data`, {origin: 'left'})
sr.reveal(`.education__img`, {origin: 'right'})
sr.reveal(`.social__container`)
sr.reveal(`.contact__info`, {origin: 'left'})
sr.reveal(`.contact__form`, {origin: 'right'})

/*=============== CONTACT FORM ===============*/
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message')

const sendEmail = (e) =>{
    e.preventDefault()

    // serviceID - templateID - #form - publicKey
    // Using a simulation for this project as per instructions

    // Simulate Fetch API call to contact.php
    const formData = new FormData(contactForm)

    fetch('contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success'){
            // Show message and add color
            contactMessage.classList.add('color-light')
            contactMessage.textContent = data.message

            // Remove message after five seconds
            setTimeout(() =>{
                contactMessage.textContent = ''
            }, 5000)

            // Clear input fields
            contactForm.reset()
        } else {
            contactMessage.textContent = data.message
        }
    })
    .catch(error => {
        contactMessage.textContent = 'Terjadi kesalahan. Silakan coba lagi nanti.'
    })
}

if(contactForm) {
    contactForm.addEventListener('submit', sendEmail)
}
