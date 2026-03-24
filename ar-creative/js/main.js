/* MENU SHOW Y HIDDEN */
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navLinks = document.querySelectorAll('.nav__link');

/* MENU SHOW */
if(navToggle){
    navToggle.addEventListener('click', () =>{
        navMenu.classList.toggle('show-menu');
    });
}

/* REMOVE MENU MOBILE */
const linkAction = () =>{
    navMenu.classList.remove('show-menu');
}
navLinks.forEach(n => n.addEventListener('click', linkAction));

/* CHANGE BACKGROUND HEADER */
const scrollHeader = () =>{
    const header = document.getElementById('header');
    // When the scroll is greater than 50 viewport height, add the scroll-header class to the header tag
    if(window.scrollY >= 50) header.classList.add('scroll-header'); else header.classList.remove('scroll-header');
}
window.addEventListener('scroll', scrollHeader);

/* SCROLL SECTIONS ACTIVE LINK */
const sections = document.querySelectorAll('section[id]');

const scrollActive = () =>{
    const scrollY = window.scrollY;

    sections.forEach(current =>{
        const sectionHeight = current.offsetHeight,
              sectionTop = current.offsetTop - 58,
              sectionId = current.getAttribute('id'),
              navItem = document.querySelector('.nav__menu a[href*=' + sectionId + ']');

        if(navItem){
            if(scrollY > sectionTop && scrollY <= sectionTop + sectionHeight){
                navItem.classList.add('active-link');
            }else{
                navItem.classList.remove('active-link');
            }
        }
    });
}
window.addEventListener('scroll', scrollActive);

/* SCROLL REVEAL ANIMATION (Using Intersection Observer) */
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
        }
    });
}, {
    threshold: 0.1
});

const applyReveal = () => {
    const revealElements = [
        '.hero__content', '.hero__img',
        '.about__data', '.about__img',
        '.services__card',
        '.education__data', '.education__img',
        '.social-media__link',
        '.contact__info', '.contact__content',
        '.section__title'
    ];

    revealElements.forEach(selector => {
        document.querySelectorAll(selector).forEach(el => {
            el.classList.add('reveal');
            revealObserver.observe(el);
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    applyReveal();
});
