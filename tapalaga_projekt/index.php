<?php
$pageTitle = 'Domov';
require 'includes/header.php';
?>

    <section class="banner">
        <div class="banner-content">
            <h1>Vitajte vo svete Linux distribúcií</h1>
            <p>Objavte rôzne distribúcie Linuxu a nájdite tú pravú pre vás</p>
            <a href="distributions.php" class="btn btn-primary">Preskúmať distribúcie</a>
        </div>
    </section>

    <main class="container">
        <section class="intro-section">
            <h2>Čo je Linux distribúcia?</h2>
            <p>Linux distribúcia je operačný systém zostavený z kolekcie softvéru založeného na Linuxovom jadre a často na package management systéme.</p>

            <div class="feature-list">
                <h3>Hlavné výhody Linux distribúcií:</h3>
                <ul>
                    <li>Open source a bezplatné</li>
                    <li>Vysoká bezpečnosť a stabilita</li>
                    <li>Široká podpora komunity</li>
                    <li>Prispôsobiteľnosť a flexibilita</li>
                    <li>Podpora rôznych desktopových prostredí</li>
                </ul>
            </div>

            <div class="cta-section">
                <a href="comparison.php" class="btn btn-secondary">Porovnať distribúcie</a>
            </div>
        </section>

        <section class="carousel-section">
            <h2>Populárne distribúcie</h2>
            <div class="carousel">
                <div class="carousel-slide active">
                    <img src="img/Ubuntu_24.10_Oracular_Oriole_Desktop_English.webp" alt="Ubuntu Desktop">
                    <div class="slide-caption">Ubuntu - Najobľúbenejšia distribúcia</div>
                </div>
                <div class="carousel-slide">
                    <img src="img/fedora-41-wip-1.webp" alt="Fedora Desktop">
                    <div class="slide-caption">Fedora - Inovatívna distribúcia</div>
                </div>
                <div class="carousel-slide">
                    <img src="img/662383-linux-arch-linux-wallpaper-hd-desktop-and-mobile-background.webp" alt="Arch Linux Desktop">
                    <div class="slide-caption">Arch Linux - Pre pokročilých používateľov</div>
                </div>
            </div>
            <button class="carousel-btn prev" onclick="prevSlide()">❮</button>
            <button class="carousel-btn next" onclick="nextSlide()">❯</button>
        </section>

        <div class="alert alert-info">
            <strong>Tip:</strong> Pred inštaláciou si vždy overte kompatibilitu so svojím hardvérom.
        </div>
    </main>

<?php require __DIR__ . '/includes/footer.php';
      require __DIR__ . '/includes/header.php'; ?>


