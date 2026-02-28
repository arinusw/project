/*
    Ar Creative Main JavaScript
    Author: Jules
    Language: JavaScript
    Comments: English
*/

/* --- MENU SHOW Y HIDDEN --- */
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close');

/* Menu show */
if(navToggle){
    navToggle.addEventListener('click', () =>{
        navMenu.classList.add('show-menu');
    });
}

/* Menu hidden */
if(navClose){
    navClose.addEventListener('click', () =>{
        navMenu.classList.remove('show-menu');
    });
}

/* --- REMOVE MENU MOBILE --- */
const navLink = document.querySelectorAll('.nav-link');

function linkAction(){
    const navMenu = document.getElementById('nav-menu');
    // When we click on each nav-link, we remove the show-menu class
    navMenu.classList.remove('show-menu');
}
navLink.forEach(n => n.addEventListener('click', linkAction));

/* --- CHANGE BACKGROUND HEADER --- */
function scrollHeader(){
    const header = document.getElementById('header');
    // When the scroll is greater than 50 viewport height, add the scroll-header class to the header tag
    if(window.scrollY >= 50) header.classList.add('scroll-header'); else header.classList.remove('scroll-header');
}
window.addEventListener('scroll', scrollHeader);

/* --- SCROLL SECTIONS ACTIVE LINK --- */
const sections = document.querySelectorAll('section[id]');

function scrollActive(){
    const scrollY = window.scrollY;

    sections.forEach(current =>{
        const sectionHeight = current.offsetHeight;
        const sectionTop = current.offsetTop - 50;
        const sectionId = current.getAttribute('id');
        const navItem = document.querySelector('.nav-menu a[href*=' + sectionId + ']');

        if(navItem) {
            if(scrollY > sectionTop && scrollY <= sectionTop + sectionHeight){
                navItem.classList.add('active-link');
            } else {
                navItem.classList.remove('active-link');
            }
        }
    });
}
window.addEventListener('scroll', scrollActive);

/* --- REVEAL ANIMATIONS ON SCROLL --- */
const observerOptions = {
    threshold: 0.1
};

const revealObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
            // We can unobserve if we only want the animation to happen once
            // observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Add reveal class to sections or specific elements
document.querySelectorAll('.section, .service-card').forEach(el => {
    el.classList.add('reveal');
    revealObserver.observe(el);
});
