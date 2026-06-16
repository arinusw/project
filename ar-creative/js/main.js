/*=============== CHANGE BACKGROUND HEADER ===============*/
function scrollHeader() {
    const header = document.getElementById('header')
    // When the scroll is greater than 50 viewport height, add the scroll-header class to the header tag
    if (this.scrollY >= 50) header.classList.add('scroll-header'); else header.classList.remove('scroll-header')
}
window.addEventListener('scroll', scrollHeader)

/*=============== SERVICES MODAL (Optional if needed, but not used in current HTML) ===============*/

/*=============== SCROLL SECTIONS ACTIVE LINK ===============*/
const sections = document.querySelectorAll('section[id]')

function scrollActive() {
    const scrollY = window.pageYOffset

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight,
            sectionTop = current.offsetTop - 58,
            sectionId = current.getAttribute('id')

        if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
            document.querySelector('.nav__menu a[href*=' + sectionId + ']').classList.add('active-link')
        } else {
            document.querySelector('.nav__menu a[href*=' + sectionId + ']').classList.remove('active-link')
        }
    })
}
window.addEventListener('scroll', scrollActive)

/*=============== SHOW SCROLL UP ===============*/
function scrollUp() {
    const scrollUp = document.getElementById('scroll-up');
    // When the scroll is higher than 350 viewport height, add the show-scroll class to the a tag with the scroll-top class
    if (this.scrollY >= 350) scrollUp.classList.add('show-scroll'); else scrollUp.classList.remove('show-scroll')
}
window.addEventListener('scroll', scrollUp)

/*=============== DARK LIGHT THEME ===============*/
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
    themeButton.classList.toggle(iconTheme)
    // We save the theme and the current icon that the user chose
    localStorage.setItem('selected-theme', getCurrentTheme())
    localStorage.setItem('selected-icon', getCurrentIcon())
})

/*=============== SCROLL REVEAL ANIMATION ===============*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 2500,
    delay: 400,
    reset: false // Animations should happen only once
})

sr.reveal(`.home__data`)
sr.reveal(`.home__img-container`, { delay: 700, origin: 'bottom' })
sr.reveal(`.about__img-container`, { origin: 'left' })
sr.reveal(`.about__data`, { origin: 'right' })
sr.reveal(`.services__card, .social__card`, { interval: 100 })
sr.reveal(`.education__data`, { origin: 'left' })
sr.reveal(`.education__img-container`, { origin: 'right' })
sr.reveal(`.contact__info`, { origin: 'left' })
sr.reveal(`.contact__form-container`, { origin: 'right' })

/*=============== CONTACT FORM AJAX ===============*/
const contactForm = document.getElementById('contact-form')
const contactStatus = document.getElementById('contact-status')

if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault()

        const formData = new FormData(contactForm)

        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                contactStatus.textContent = data.message
                contactStatus.style.color = 'var(--first-color)'
                contactForm.reset()
            } else {
                contactStatus.textContent = data.message
                contactStatus.style.color = 'red'
            }
        })
        .catch(error => {
            contactStatus.textContent = 'Terjadi kesalahan. Silakan coba lagi nanti.'
            contactStatus.style.color = 'red'
        })
        .finally(() => {
            setTimeout(() => {
                contactStatus.textContent = ''
            }, 5000)
        })
    })
}
