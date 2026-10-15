<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ar Creative | Digital Creative Studio & Edukasi Teknologi</title>

    <!-- Remixicons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS Stylesheet -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!--==================== HEADER ====================-->
    <header class="header" id="header">
        <nav class="nav container">
            <a href="#home" class="nav__logo">
                Ar<span>.Creative</span>
            </a>

            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="#home" class="nav__link active-link">Beranda</a>
                    </li>
                    <li class="nav__item">
                        <a href="#about" class="nav__link">Tentang</a>
                    </li>
                    <li class="nav__item">
                        <a href="#services" class="nav__link">Layanan</a>
                    </li>
                    <li class="nav__item">
                        <a href="#education" class="nav__link">Edukasi</a>
                    </li>
                    <li class="nav__item">
                        <a href="#social" class="nav__link">Sosial Media</a>
                    </li>
                    <li class="nav__item">
                        <a href="#contact" class="nav__link">Kontak</a>
                    </li>
                </ul>

                <!-- Close button -->
                <div class="nav__close" id="nav-close">
                    <i class="ri-close-line"></i>
                </div>
            </div>

            <div class="nav__actions">
                <!-- Theme change button -->
                <i class="ri-moon-line change-theme" id="theme-button"></i>

                <!-- Toggle button -->
                <div class="nav__toggle" id="nav-toggle">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
        </nav>
    </header>

    <!--==================== MAIN ====================-->
    <main class="main">
        <!--==================== HERO / HOME ====================-->
        <section class="home section" id="home">
            <div class="home__container container grid">
                <div class="home__social">
                    <a href="https://www.facebook.com/arkreative" target="_blank" rel="noopener noreferrer" class="home__social-link">
                        <i class="ri-facebook-circle-fill"></i>
                    </a>
                    <a href="https://www.instagram.com/arcreative_26" target="_blank" rel="noopener noreferrer" class="home__social-link">
                        <i class="ri-instagram-line"></i>
                    </a>
                    <a href="https://www.youtube.com/@arcreative-24" target="_blank" rel="noopener noreferrer" class="home__social-link">
                        <i class="ri-youtube-fill"></i>
                    </a>
                </div>

                <div class="home__data">
                    <span class="home__subtitle">Selamat Datang di Studio Kami</span>
                    <h1 class="home__title">Ar Creative</h1>
                    <h3 class="home__education">Jasa & Produk Digital | Edukasi Teknologi</h3>
                    <p class="home__description">
                        Ar Creative adalah studio kreatif digital yang berdedikasi untuk memberikan solusi teknologi, desain interaktif, dan pengembangan produk digital yang inovatif serta mendukung literasi teknologi bagi masyarakat.
                    </p>
                    <div class="home__buttons">
                        <a href="#services" class="button">Lihat Layanan <i class="ri-arrow-right-line"></i></a>
                        <a href="#contact" class="button button--ghost">Hubungi Kami</a>
                    </div>
                </div>

                <div class="home__img">
                    <svg class="home__blob" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                        <path fill="var(--first-color-lighten)" d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,79.6,-45.8C87.4,-32.6,90.1,-16.3,88.5,-0.9C87,14.5,81.2,29,72.9,41.4C64.6,53.8,53.8,64.1,40.9,71.3C28,78.5,14,82.6,-0.7,83.8C-15.4,85,-30.8,83.3,-44.1,76.2C-57.4,69.1,-68.6,56.6,-76.2,42.3C-83.8,28,-87.8,14,-86.3,-0.9C-84.8,-15.7,-77.8,-31.4,-68.5,-44.2C-59.2,-57,-47.6,-66.9,-34.5,-74.7C-21.4,-82.5,-6.8,-88.2,7.3,-86.9C21.4,-85.7,42.8,-77.5,44.7,-76.4Z" transform="translate(100 100)" />
                        <!-- Digital Illustration Graphics -->
                        <g transform="translate(35, 35)">
                            <rect x="25" y="25" width="80" height="60" rx="8" fill="var(--first-color)" opacity="0.9" />
                            <rect x="35" y="35" width="60" height="40" rx="4" fill="var(--body-color)" />
                            <circle cx="45" cy="45" r="4" fill="var(--first-color)" />
                            <circle cx="58" cy="45" r="4" fill="var(--color-blue)" />
                            <rect x="45" y="55" width="40" height="4" rx="2" fill="var(--text-color-light)" />
                            <rect x="45" y="63" width="25" height="4" rx="2" fill="var(--first-color)" />
                            <path d="M70,90 L60,110 L100,110 L90,90 Z" fill="var(--first-color)" />
                            <rect x="50" y="110" width="60" height="6" rx="3" fill="var(--text-color)" />
                        </g>
                    </svg>
                </div>
            </div>
        </section>

        <!--==================== ABOUT ====================-->
        <section class="about section" id="about">
            <h2 class="section__title">Tentang Kami</h2>
            <span class="section__subtitle">Mengenal Lebih Dekat Ar Creative</span>

            <div class="about__container container grid">
                <div class="about__image">
                    <svg class="about__svg" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">
                        <rect x="20" y="40" width="260" height="180" rx="16" fill="var(--container-color)" stroke="var(--first-color)" stroke-width="4" />
                        <rect x="40" y="70" width="100" height="120" rx="8" fill="var(--first-color-lighten)" />
                        <rect x="155" y="70" width="105" height="12" rx="4" fill="var(--first-color)" />
                        <rect x="155" y="95" width="90" height="10" rx="4" fill="var(--text-color-light)" />
                        <rect x="155" y="115" width="100" height="10" rx="4" fill="var(--text-color-light)" />
                        <rect x="155" y="135" width="70" height="10" rx="4" fill="var(--text-color-light)" />
                        <rect x="155" y="160" width="80" height="25" rx="6" fill="var(--first-color)" />
                    </svg>
                </div>

                <div class="about__data">
                    <p class="about__description">
                        <strong>Ar Creative</strong> menyediakan layanan jasa dan produk digital unggulan yang dirancang untuk membantu perorangan, komunitas, instansi, hingga pelaku usaha kecil (UMKM) berkembang di era transformasi digital.
                    </p>
                    <p class="about__description">
                        Fokus utama kami terletak pada pengembangan kreativitas, pemanfaatan teknologi tepat guna, dan penyediaan solusi digital yang terjangkau namun memiliki nilai estetika dan performa tinggi.
                    </p>
                    <p class="about__description">
                        Selain itu, kami juga aktif berbagi edukasi seputar teknologi dan dunia digital untuk mendorong pemahaman serta kemampuan digitalitas secara berkelanjutan.
                    </p>

                    <div class="about__info grid">
                        <div class="about__box">
                            <i class="ri-award-line about__icon"></i>
                            <h3 class="about__title">Kreativitas</h3>
                            <span class="about__subtitle">Solusi Unik & Elegan</span>
                        </div>
                        <div class="about__box">
                            <i class="ri-cpu-line about__icon"></i>
                            <h3 class="about__title">Teknologi</h3>
                            <span class="about__subtitle">Standar Modern</span>
                        </div>
                        <div class="about__box">
                            <i class="ri-book-read-line about__icon"></i>
                            <h3 class="about__title">Edukasi</h3>
                            <span class="about__subtitle">Berbagi Pengetahuan</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--==================== SERVICES ====================-->
        <section class="services section" id="services">
            <h2 class="section__title">Layanan Kami</h2>
            <span class="section__subtitle">Solusi Digital Komprehensif</span>

            <div class="services__container container grid">
                <!-- Service Card 1 -->
                <div class="services__card">
                    <div class="services__icon">
                        <i class="ri-paint-brush-line"></i>
                    </div>
                    <h3 class="services__title">Jasa Kreatif Digital</h3>
                    <p class="services__description">
                        Desain grafis profesional, identitas visual brand, UI/UX design untuk aplikasi & website, serta pembuatan aset digital kreatif.
                    </p>
                </div>

                <!-- Service Card 2 -->
                <div class="services__card">
                    <div class="services__icon">
                        <i class="ri-code-s-slash-line"></i>
                    </div>
                    <h3 class="services__title">Produk Digital</h3>
                    <p class="services__description">
                        Pengembangan website landing page, aplikasi berbasis web, template digital interaktif, dan alat bantu produktivitas.
                    </p>
                </div>

                <!-- Service Card 3 -->
                <div class="services__card">
                    <div class="services__icon">
                        <i class="ri-video-add-line"></i>
                    </div>
                    <h3 class="services__title">Multimedia & Konten Kreatif</h3>
                    <p class="services__description">
                        Penyuntingan video, animasi motion graphics, materi promosi media sosial, serta aset multimedia pendukung promosi.
                    </p>
                </div>

                <!-- Service Card 4 -->
                <div class="services__card">
                    <div class="services__icon">
                        <i class="ri-graduation-cap-line"></i>
                    </div>
                    <h3 class="services__title">Edukasi & Literasi Teknologi</h3>
                    <p class="services__description">
                        Pelatihan teknologi digital, konsultasi dasar pengembangan web, serta materi edukatif seputar dunia IT dan industri kreatif.
                    </p>
                </div>
            </div>
        </section>

        <!--==================== EDUCATION ====================-->
        <section class="education section" id="education">
            <h2 class="section__title">Edukasi & Literasi Digital</h2>
            <span class="section__subtitle">Mendukung Perkembangan Kreativitas Lokal</span>

            <div class="education__container container grid">
                <div class="education__content">
                    <h3 class="education__heading">Komitmen Kami dalam Pembelajaran Digital</h3>
                    <p class="education__description">
                        Di Ar Creative, kami percaya bahwa akses terhadap pengetahuan teknologi merupakan kunci pertumbuhan ekonomi kreatif. Kami secara aktif membagikan edukasi seputaran teknologi, panduan praktis, dan wawasan digital terkini.
                    </p>

                    <div class="education__features">
                        <div class="education__item">
                            <i class="ri-checkbox-circle-fill education__icon"></i>
                            <div>
                                <h4>Membagikan Edukasi Seputaran Teknologi</h4>
                                <p>Tutorial, artikel, dan materi pembelajaran seputar web development, desain, dan alat digital.</p>
                            </div>
                        </div>

                        <div class="education__item">
                            <i class="ri-checkbox-circle-fill education__icon"></i>
                            <div>
                                <h4>Mendukung Pembelajaran Digital & Kreativitas Lokal</h4>
                                <p>Mendampingi UMKM, talenta muda, dan komunitas lokal agar mampu mengoptimalkan teknologi digital.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="education__img">
                    <svg class="education__svg" viewBox="0 0 300 250" xmlns="http://www.w3.org/2000/svg">
                        <rect x="30" y="30" width="240" height="180" rx="12" fill="var(--container-color)" stroke="var(--first-color)" stroke-width="3" />
                        <path d="M50 70 H250" stroke="var(--first-color-lighten)" stroke-width="2" />
                        <circle cx="60" cy="50" r="4" fill="var(--color-red)" />
                        <circle cx="75" cy="50" r="4" fill="var(--first-color)" />
                        <circle cx="90" cy="50" r="4" fill="var(--color-blue)" />

                        <!-- Book & Code graphic -->
                        <path d="M80 120 C110 110, 140 110, 150 125 C160 110, 190 110, 220 120 V170 C190 160, 160 160, 150 175 C140 160, 110 160, 80 170 Z" fill="none" stroke="var(--first-color)" stroke-width="3" />
                        <line x1="150" y1="125" x2="150" y2="175" stroke="var(--first-color)" stroke-width="3" />
                    </svg>
                </div>
            </div>
        </section>

        <!--==================== SOCIAL MEDIA ====================-->
        <section class="social section" id="social">
            <h2 class="section__title">Sosial Media</h2>
            <span class="section__subtitle">Terhubung Bersama Ar Creative</span>

            <div class="social__container container grid">
                <!-- Facebook -->
                <a href="https://www.facebook.com/arkreative" target="_blank" rel="noopener noreferrer" class="social__card">
                    <div class="social__icon social__icon--facebook">
                        <i class="ri-facebook-fill"></i>
                    </div>
                    <h3 class="social__title">Facebook</h3>
                    <span class="social__subtitle">@arkreative</span>
                    <span class="social__button">Kunjungi <i class="ri-arrow-right-line"></i></span>
                </a>

                <!-- Instagram -->
                <a href="https://www.instagram.com/arcreative_26" target="_blank" rel="noopener noreferrer" class="social__card">
                    <div class="social__icon social__icon--instagram">
                        <i class="ri-instagram-line"></i>
                    </div>
                    <h3 class="social__title">Instagram</h3>
                    <span class="social__subtitle">@arcreative_26</span>
                    <span class="social__button">Kunjungi <i class="ri-arrow-right-line"></i></span>
                </a>

                <!-- YouTube -->
                <a href="https://www.youtube.com/@arcreative-24" target="_blank" rel="noopener noreferrer" class="social__card">
                    <div class="social__icon social__icon--youtube">
                        <i class="ri-youtube-fill"></i>
                    </div>
                    <h3 class="social__title">YouTube</h3>
                    <span class="social__subtitle">@arcreative-24</span>
                    <span class="social__button">Kunjungi <i class="ri-arrow-right-line"></i></span>
                </a>
            </div>
        </section>

        <!--==================== CONTACT ====================-->
        <section class="contact section" id="contact">
            <h2 class="section__title">Hubungi Kami</h2>
            <span class="section__subtitle">Mari Berdiskusi Mengenai Proyek Anda</span>

            <div class="contact__container container grid">
                <div class="contact__info">
                    <div class="contact__card">
                        <i class="ri-whatsapp-line contact__card-icon"></i>
                        <h3 class="contact__card-title">WhatsApp / Telepon</h3>
                        <span class="contact__card-data">+62 812 4861 6454</span>
                        <a href="https://api.whatsapp.com/send?phone=6281248616454&text=Halo%20Ar%20Creative,%20saya%20ingin%20berdiskusi" target="_blank" rel="noopener noreferrer" class="contact__button">
                            Kirim Pesan <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>

                    <div class="contact__card">
                        <i class="ri-mail-line contact__card-icon"></i>
                        <h3 class="contact__card-title">Email</h3>
                        <span class="contact__card-data">infostudio ari</span>
                        <a href="mailto:infostudio.ari@gmail.com" class="contact__button">
                            Kirim Email <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                </div>

                <div class="contact__form-area">
                    <form action="contact.php" method="POST" class="contact__form" id="contact-form">
                        <div class="contact__form-div">
                            <label for="name" class="contact__form-tag">Nama Lengkap</label>
                            <input type="text" name="name" id="name" placeholder="Masukkan nama Anda" class="contact__form-input" required>
                        </div>

                        <div class="contact__form-div">
                            <label for="email" class="contact__form-tag">Alamat Email</label>
                            <input type="email" name="email" id="email" placeholder="Masukkan email Anda" class="contact__form-input" required>
                        </div>

                        <div class="contact__form-div">
                            <label for="subject" class="contact__form-tag">Subjek</label>
                            <input type="text" name="subject" id="subject" placeholder="Subjek pesan" class="contact__form-input">
                        </div>

                        <div class="contact__form-div contact__form-area-div">
                            <label for="message" class="contact__form-tag">Pesan</label>
                            <textarea name="message" id="message" cols="30" rows="10" placeholder="Tuliskan pesan atau pertanyaan Anda di sini..." class="contact__form-input" required></textarea>
                        </div>

                        <input type="hidden" name="simulation_mode" id="simulation_mode" value="0">

                        <button type="submit" class="button contact__submit-btn">
                            Kirim Pesan <i class="ri-send-plane-line"></i>
                        </button>

                        <p class="contact__message" id="contact-message"></p>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!--==================== FOOTER ====================-->
    <footer class="footer">
        <div class="footer__container container">
            <h1 class="footer__title">Ar Creative</h1>
            <p class="footer__subtitle">Digital Creative & Technology Education</p>

            <ul class="footer__links">
                <li><a href="#about" class="footer__link">Tentang</a></li>
                <li><a href="#services" class="footer__link">Layanan</a></li>
                <li><a href="#education" class="footer__link">Edukasi</a></li>
                <li><a href="#contact" class="footer__link">Kontak</a></li>
            </ul>

            <div class="footer__social">
                <a href="https://www.facebook.com/arkreative" target="_blank" rel="noopener noreferrer" class="footer__social-link">
                    <i class="ri-facebook-fill"></i>
                </a>
                <a href="https://www.instagram.com/arcreative_26" target="_blank" rel="noopener noreferrer" class="footer__social-link">
                    <i class="ri-instagram-line"></i>
                </a>
                <a href="https://www.youtube.com/@arcreative-24" target="_blank" rel="noopener noreferrer" class="footer__social-link">
                    <i class="ri-youtube-fill"></i>
                </a>
            </div>

            <span class="footer__copy">
                &#169; Ar Creative – Digital Creative & Technology Education. All rights reserved.
            </span>
        </div>
    </footer>

    <!--==================== SCROLL UP ====================-->
    <a href="#home" class="scrollup" id="scroll-up">
        <i class="ri-arrow-up-line scrollup__icon"></i>
    </a>

    <!-- ScrollReveal CDN -->
    <script src="https://unpkg.com/scrollreveal"></script>

    <!-- Main JavaScript -->
    <script src="js/main.js"></script>
</body>
</html>
