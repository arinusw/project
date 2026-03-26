/*=============== SHOW MENU ===============*/
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

/*=============== REMOVE MENU MOBILE ===============*/
const navLink = document.querySelectorAll('.nav__link')

function linkAction(){
    const navMenu = document.getElementById('nav-menu')
    // When we click on each nav__link, we remove the show-menu class
    navMenu.classList.remove('show-menu')
}
navLink.forEach(n => n.addEventListener('click', linkAction))

/*=============== CHANGE BACKGROUND HEADER ===============*/
function scrollHeader(){
    const header = document.getElementById('header')
    // When the scroll is greater than 80 viewport height, add the scroll-header class to the header tag
    if(this.scrollY >= 80) header.classList.add('scroll-header'); else header.classList.remove('scroll-header')
}
window.addEventListener('scroll', scrollHeader)

/*=============== SCROLL SECTIONS ACTIVE LINK ===============*/
const sections = document.querySelectorAll('section[id]')

function scrollActive(){
    const scrollY = window.scrollY

    sections.forEach(current =>{
        const sectionHeight = current.offsetHeight,
              sectionTop = current.offsetTop - 58,
              sectionId = current.getAttribute('id')

        const navItem = document.querySelector('.nav__menu a[href*=' + sectionId + ']')
        if(navItem){
            if(scrollY > sectionTop && scrollY <= sectionTop + sectionHeight){
                navItem.classList.add('active-link')
            }else{
                navItem.classList.remove('active-link')
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
    reset: false // Keep elements visible after animation
})

sr.reveal(`.hero__data`)
sr.reveal(`.hero__img`, {delay: 700, origin: 'bottom'})
sr.reveal(`.about__img, .contact__info`,{origin: 'left'})
sr.reveal(`.about__data, .contact__form-wrapper`,{origin: 'right'})
sr.reveal(`.services__card, .social-media__link`,{interval: 100})
sr.reveal(`.education__data`, {origin: 'left'})
sr.reveal(`.education__img`, {origin: 'right'})
