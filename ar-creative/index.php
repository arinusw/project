<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ar Creative - Digital Creative Studio & Edukasi Teknologi</title>

    <!-- Remixicons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- HEADER / NAVIGATION -->
    <header class="header" id="header">
        <nav class="nav container">
            <a href="#home" class="nav__logo">
                <i class="ri-code-s-slash-line nav__logo-icon"></i> Ar Creative
            </a>

            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="#home" class="nav__link active-link">
                            <i class="ri-home-4-line nav__icon"></i> Beranda
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="#about" class="nav__link">
                            <i class="ri-user-line nav__icon"></i> Tentang
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="#services" class="nav__link">
                            <i class="ri-briefcase-line nav__icon"></i> Layanan
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="#education" class="nav__link">
                            <i class="ri-book-read-line nav__icon"></i> Edukasi
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="#social" class="nav__link">
                            <i class="ri-share-line nav__icon"></i> Sosial
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="#contact" class="nav__link">
                            <i class="ri-message-3-line nav__icon"></i> Kontak
                        </a>
                    </li>
                </ul>

                <!-- Close button for mobile nav -->
                <div class="nav__close" id="nav-close">
                    <i class="ri-close-line"></i>
                </div>
            </div>

            <div class="nav__buttons">
                <!-- Theme change button -->
                <i class="ri-moon-line change-theme" id="theme-button"></i>

                <!-- Toggle button for mobile nav -->
                <div class="nav__toggle" id="nav-toggle">
                    <i class="ri-apps-2-line"></i>
                </div>
            </div>
        </nav>
    </header>

    <!-- MAIN CONTENT -->
    <main class="main">
        <!-- HERO SECTION -->
        <section class="home section" id="home">
            <div class="home__container container grid">
                <div class="home__social">
                    <a href="https://www.facebook.com/arkreative" target="_blank" rel="noopener" class="home__social-icon" aria-label="Facebook">
                        <i class="ri-facebook-fill"></i>
                    </a>
                    <a href="https://www.instagram.com/arcreative_26" target="_blank" rel="noopener" class="home__social-icon" aria-label="Instagram">
                        <i class="ri-instagram-line"></i>
                    </a>
                    <a href="https://www.youtube.com/@arcreative-24" target="_blank" rel="noopener" class="home__social-icon" aria-label="YouTube">
                        <i class="ri-youtube-line"></i>
                    </a>
                </div>

                <div class="home__data">
                    <span class="home__subtitle">Digital Creative Studio</span>
                    <h1 class="home__title">Ar Creative</h1>
                    <h2 class="home__education">Jasa & Produk Digital | Edukasi Teknologi</h2>
                    <p class="home__description">
                        Ar Creative adalah studio kreatif digital yang berdedikasi untuk menghadirkan solusi teknologi modern, produk inovatif, dan konten edukasi digital berkualitas tinggi untuk individu, komunitas, instansi, hingga pelaku usaha kecil.
                    </p>
                    <div class="home__buttons">
                        <a href="#services" class="button button--flex">
                            Lihat Layanan <i class="ri-arrow-right-line button__icon"></i>
                        </a>
                        <a href="#contact" class="button button--ghost button--flex">
                            Hubungi Kami <i class="ri-chat-3-line button__icon"></i>
                        </a>
                    </div>
                </div>

                <div class="home__img">
                    <!-- Digital Art / SVG Vector Illustration -->
                    <svg class="home__blob-img" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:var(--first-color);stop-opacity:1" />
                                <stop offset="100%" style="stop-color:var(--first-color-alt);stop-opacity:1" />
                            </linearGradient>
                            <linearGradient id="grad2" x1="100%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#a29bfe;stop-opacity:0.8" />
                                <stop offset="100%" style="stop-color:var(--first-color);stop-opacity:0.9" />
                            </linearGradient>
                        </defs>
                        <!-- Background art blob -->
                        <path fill="url(#grad1)" d="M410,310Q370,370,310,410Q250,450,185,415Q120,380,85,315Q50,250,85,185Q120,120,185,85Q250,50,315,85Q380,120,410,185Q440,250,410,310Z" />
                        <!-- Laptop / Workspace SVG Art -->
                        <rect x="150" y="160" width="200" height="130" rx="10" fill="#ffffff" opacity="0.95" />
                        <rect x="160" y="170" width="180" height="100" rx="5" fill="#1e1e2e" />
                        <!-- Screen Code Art -->
                        <rect x="175" y="185" width="70" height="8" rx="4" fill="var(--first-color)" />
                        <rect x="175" y="200" width="110" height="6" rx="3" fill="#a29bfe" />
                        <rect x="175" y="212" width="90" height="6" rx="3" fill="#6c5ce7" />
                        <circle cx="310" cy="220" r="20" fill="url(#grad2)" />
                        <!-- Base Laptop -->
                        <path d="M120,290 L380,290 L400,305 L100,305 Z" fill="#dcdde1" />
                        <rect x="220" y="293" width="60" height="6" rx="3" fill="#a4b0be" />
                    </svg>
                </div>
            </div>
        </section>

        <!-- ABOUT SECTION -->
        <section class="about section" id="about">
            <h2 class="section__title">Tentang Kami</h2>
            <span class="section__subtitle">Solusi Digital & Edukasi Terpercaya</span>

            <div class="about__container container grid">
                <div class="about__image">
                    <!-- SVG Digital Illustration for About -->
                    <svg class="about__img-art" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="200" cy="200" r="160" fill="var(--container-color)" stroke="var(--first-color)" stroke-width="4" stroke-dasharray="8 8" />
                        <rect x="110" y="120" width="180" height="160" rx="16" fill="var(--first-color)" opacity="0.1" />
                        <path d="M140 180 L200 130 L260 180 L260 250 L140 250 Z" fill="none" stroke="var(--first-color)" stroke-width="6" stroke-linejoin="round" />
                        <circle cx="200" cy="190" r="25" fill="var(--first-color)" />
                        <path d="M160 250 Q200 210 240 250" fill="none" stroke="var(--first-color)" stroke-width="6" stroke-linecap="round" />
                    </svg>
                </div>

                <div class="about__data">
                    <p class="about__description">
                        <strong>Ar Creative</strong> menyediakan berbagai layanan jasa profesional dan produk digital inovatif. Fokus utama kami adalah menggabungkan kreativitas tinggi, teknologi modern, dan solusi digital yang terjangkau namun memiliki nilai estetika dan performa terbaik.
                    </p>
                    <p class="about__description">
                        Selain mengembangkan karya dan produk digital, kami juga berkomitmen aktif dalam membagikan edukasi seputar teknologi, pemrograman, dan dunia digital. Kami percaya bahwa literasi teknologi adalah kunci kemajuan bagi individu, komunitas lokal, hingga usaha kecil mandiri.
                    </p>

                    <div class="about__info">
                        <div>
                            <span class="about__info-title">100%</span>
                            <span class="about__info-name">Kreatif &<br>Inovatif</span>
                        </div>
                        <div>
                            <span class="about__info-title">Digital</span>
                            <span class="about__info-name">Solusi<br>Lengkap</span>
                        </div>
                        <div>
                            <span class="about__info-title">Edukasi</span>
                            <span class="about__info-name">Literasi<br>Teknologi</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SERVICES SECTION -->
        <section class="services section" id="services">
            <h2 class="section__title">Layanan Kami</h2>
            <span class="section__subtitle">Pilihan Jasa & Produk Digital Terbaik</span>

            <div class="services__container container grid">
                <!-- Service Card 1 -->
                <div class="services__card">
                    <div class="services__icon">
                        <i class="ri-layout-4-line"></i>
                    </div>
                    <h3 class="services__title">Jasa Kreatif Digital</h3>
                    <p class="services__description">
                        Pembuatan website profesional, desain UI/UX interaktif, pengembangan sistem informasi, dan solusi branding digital yang disesuaikan dengan kebutuhan Anda.
                    </p>
                </div>

                <!-- Service Card 2 -->
                <div class="services__card">
                    <div class="services__icon">
                        <i class="ri-box-3-line"></i>
                    </div>
                    <h3 class="services__title">Produk Digital</h3>
                    <p class="services__description">
                        Penyediaan template website modern, aset grafis digital, source code aplikasi, serta tools produktivitas berbasis teknologi untuk mempercepat pekerjaan Anda.
                    </p>
                </div>

                <!-- Service Card 3 -->
                <div class="services__card">
                    <div class="services__icon">
                        <i class="ri-video-line"></i>
                    </div>
                    <h3 class="services__title">Multimedia & Konten Kreatif</h3>
                    <p class="services__description">
                        Pembuatan konten visual digital, desain grafis promosi, video editing, serta materi pemasaran digital yang menarik untuk memperkuat citra brand Anda.
                    </p>
                </div>

                <!-- Service Card 4 -->
                <div class="services__card">
                    <div class="services__icon">
                        <i class="ri-lightbulb-line"></i>
                    </div>
                    <h3 class="services__title">Edukasi & Literasi Teknologi</h3>
                    <p class="services__description">
                        Pelatihan dasar pemrograman, workshop kreativitas digital, modul pembelajaran online, serta mentoring pengembangan talenta digital lokal.
                    </p>
                </div>
            </div>
        </section>

        <!-- EDUCATION / CONTENT SECTION -->
        <section class="education section" id="education">
            <h2 class="section__title">Edukasi Teknologi</h2>
            <span class="section__subtitle">Mendukung Pembelajaran Digital & Kreativitas Lokal</span>

            <div class="education__container container grid">
                <div class="education__data">
                    <h3 class="education__heading">Membangun Generasi Digital yang Mandiri</h3>
                    <p class="education__description">
                        Ar Creative secara aktif membagikan edukasi seputaran teknologi, panduan praktis pengembangan web, serta wawasan seputar industri kreatif digital.
                    </p>
                    <p class="education__description">
                        Kami percaya bahwa dengan mendukung pembelajaran digital dan kreativitas lokal, setiap individu dan komunitas dapat menciptakan peluang karya yang berdampak luas.
                    </p>

                    <ul class="education__list">
                        <li class="education__item">
                            <i class="ri-checkbox-circle-line education__icon"></i>
                            <span>Tutorial & Artikel Praktis Teknologi</span>
                        </li>
                        <li class="education__item">
                            <i class="ri-checkbox-circle-line education__icon"></i>
                            <span>Dukungan Komunitas & Kreator Lokal</span>
                        </li>
                        <li class="education__item">
                            <i class="ri-checkbox-circle-line education__icon"></i>
                            <span>Materi Pembelajaran Berbasis Proyek Nyata</span>
                        </li>
                    </ul>
                </div>

                <div class="education__image">
                    <!-- SVG Digital Illustration for Education -->
                    <svg class="education__img-art" viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg">
                        <rect x="20" y="20" width="360" height="260" rx="12" fill="var(--container-color)" stroke="var(--first-color)" stroke-width="2" />
                        <path d="M60 70 L340 70" stroke="var(--text-color-light)" stroke-width="2" stroke-dasharray="4 4" />
                        <!-- Book Icon / Digital Screen -->
                        <path d="M120 120 C160 110, 190 125, 200 135 C210 125, 240 110, 280 120 L280 220 C240 210, 210 225, 200 235 C190 225, 160 210, 120 220 Z" fill="var(--first-color)" opacity="0.8" />
                        <line x1="200" y1="135" x2="200" y2="235" stroke="#ffffff" stroke-width="3" />
                        <circle cx="200" cy="90" r="18" fill="var(--first-color)" />
                        <path d="M195 85 L205 90 L195 95 Z" fill="#ffffff" />
                    </svg>
                </div>
            </div>
        </section>

        <!-- SOCIAL MEDIA SECTION -->
        <section class="social section" id="social">
            <h2 class="section__title">Media Sosial</h2>
            <span class="section__subtitle">Ikuti Perkembangan & Konten Edukasi Kami</span>

            <div class="social__container container grid">
                <!-- Facebook Card -->
                <a href="https://www.facebook.com/arkreative" target="_blank" rel="noopener" class="social__card">
                    <div class="social__icon social__icon--facebook">
                        <i class="ri-facebook-fill"></i>
                    </div>
                    <h3 class="social__name">Facebook</h3>
                    <span class="social__handle">@arkreative</span>
                    <span class="social__button">Kunjungi <i class="ri-arrow-right-line"></i></span>
                </a>

                <!-- Instagram Card -->
                <a href="https://www.instagram.com/arcreative_26" target="_blank" rel="noopener" class="social__card">
                    <div class="social__icon social__icon--instagram">
                        <i class="ri-instagram-line"></i>
                    </div>
                    <h3 class="social__name">Instagram</h3>
                    <span class="social__handle">@arcreative_26</span>
                    <span class="social__button">Kunjungi <i class="ri-arrow-right-line"></i></span>
                </a>

                <!-- YouTube Card -->
                <a href="https://www.youtube.com/@arcreative-24" target="_blank" rel="noopener" class="social__card">
                    <div class="social__icon social__icon--youtube">
                        <i class="ri-youtube-line"></i>
                    </div>
                    <h3 class="social__name">YouTube</h3>
                    <span class="social__handle">@arcreative-24</span>
                    <span class="social__button">Kunjungi <i class="ri-arrow-right-line"></i></span>
                </a>
            </div>
        </section>

        <!-- CONTACT SECTION -->
        <section class="contact section" id="contact">
            <h2 class="section__title">Hubungi Kami</h2>
            <span class="section__subtitle">Mari Mulai Kolaborasi & Diskusi Proyek Anda</span>

            <div class="contact__container container grid">
                <div class="contact__information-container">
                    <div class="contact__information">
                        <i class="ri-whatsapp-line contact__icon"></i>
                        <div>
                            <h3 class="contact__title">WhatsApp / Telepon</h3>
                            <span class="contact__subtitle">+62 812 4861 6454</span>
                        </div>
                    </div>

                    <div class="contact__information">
                        <i class="ri-mail-line contact__icon"></i>
                        <div>
                            <h3 class="contact__title">Email</h3>
                            <span class="contact__subtitle">infostudio ari</span>
                        </div>
                    </div>

                    <div class="contact__information">
                        <i class="ri-map-pin-line contact__icon"></i>
                        <div>
                            <h3 class="contact__title">Lokasi</h3>
                            <span class="contact__subtitle">Indonesia</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <form action="contact.php" method="POST" class="contact__form grid" id="contact-form">
                    <div class="contact__inputs grid">
                        <div class="contact__content">
                            <label for="name" class="contact__label">Nama Lengkap</label>
                            <input type="text" id="name" name="name" class="contact__input" required placeholder="Masukkan nama Anda">
                        </div>

                        <div class="contact__content">
                            <label for="email" class="contact__label">Email</label>
                            <input type="email" id="email" name="email" class="contact__input" required placeholder="Masukkan email Anda">
                        </div>
                    </div>

                    <div class="contact__content">
                        <label for="subject" class="contact__label">Subjek</label>
                        <input type="text" id="subject" name="subject" class="contact__input" required placeholder="Subjek pesan / proyek">
                    </div>

                    <div class="contact__content contact__form-area">
                        <label for="message" class="contact__label">Pesan</label>
                        <textarea id="message" name="message" cols="0" rows="7" class="contact__input" required placeholder="Tuliskan pesan atau detail kebutuhan Anda..."></textarea>
                    </div>

                    <div>
                        <button type="submit" class="button button--flex contact__button">
                            Kirim Pesan <i class="ri-send-plane-fill button__icon"></i>
                        </button>
                    </div>

                    <!-- AJAX Response Feedback Message -->
                    <p class="contact__message" id="contact-message"></p>
                </form>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer__bg">
            <div class="footer__container container grid">
                <div>
                    <h1 class="footer__title">Ar Creative</h1>
                    <span class="footer__subtitle">Digital Creative & Technology Education</span>
                </div>

                <ul class="footer__links">
                    <li>
                        <a href="#services" class="footer__link">Layanan</a>
                    </li>
                    <li>
                        <a href="#education" class="footer__link">Edukasi</a>
                    </li>
                    <li>
                        <a href="#contact" class="footer__link">Kontak</a>
                    </li>
                </ul>

                <div class="footer__socials">
                    <a href="https://www.facebook.com/arkreative" target="_blank" rel="noopener" class="footer__social" aria-label="Facebook">
                        <i class="ri-facebook-fill"></i>
                    </a>
                    <a href="https://www.instagram.com/arcreative_26" target="_blank" rel="noopener" class="footer__social" aria-label="Instagram">
                        <i class="ri-instagram-line"></i>
                    </a>
                    <a href="https://www.youtube.com/@arcreative-24" target="_blank" rel="noopener" class="footer__social" aria-label="YouTube">
                        <i class="ri-youtube-line"></i>
                    </a>
                </div>
            </div>

            <p class="footer__copy">&#169; Ar Creative – Digital Creative & Technology Education. All rights reserved.</p>
        </div>
    </footer>

    <!-- SCROLL UP BUTTON -->
    <a href="#" class="scrollup" id="scroll-up" aria-label="Scroll ke atas">
        <i class="ri-arrow-up-line scrollup__icon"></i>
    </a>

    <!-- ScrollReveal CDN -->
    <script src="https://unpkg.com/scrollreveal"></script>

    <!-- Custom JS -->
    <script src="js/main.js"></script>
</body>
</html>
