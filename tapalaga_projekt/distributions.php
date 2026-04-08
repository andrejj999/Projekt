<?php
$pageTitle = 'Distribúcie';
require __DIR__ . '/includes/header.php';
?>

    <main class="container">
        <h1>Linux Distribúcie</h1>

        <section class="accordion-section">
            <h2>Prehľad distribúcií</h2>
            <div class="accordion">
                <div class="accordion-item">
                    <button class="accordion-header">Ubuntu</button>
                    <div class="accordion-content">
                        <p><strong>Ubuntu</strong> je jedna z najpopulárnejších Linux distribúcií, vhodná pre začiatočníkov. Využíva desktopové prostredie GNOME a je založená na Debiane.</p>
                        <h4>Hlavné charakteristiky:</h4>
                        <ul>
                            <li><strong>Vhodné pre:</strong> Začiatočníkov a bežných používateľov</li>
                            <li><strong>Package manager:</strong> APT (.deb balíčky)</li>
                            <li><strong>Desktop prostredie:</strong> GNOME (predvolené), dostupné aj KDE, Xfce</li>
                            <li><strong>Release cyklus:</strong> Každých 6 mesiacov, LTS každé 2 roky</li>
                            <li><strong>Podpora:</strong> Komerčná + komunita</li>
                        </ul>
                        <h4>Výhody:</h4>
                        <ul>
                            <li>Jednoduchá inštalácia a používanie</li>
                            <li>Vynikajúca hardvérová podpora</li>
                            <li>Rozsiahla dokumentácia a komunita</li>
                            <li>Pravidelné bezpečnostné aktualizácie</li>
                        </ul>
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-header">Fedora</button>
                    <div class="accordion-content">
                        <p><strong>Fedora</strong> je inovatívna distribúcia sponzorovaná spoločnosťou Red Hat. Prináša najnovšie technológie a je vhodná pre vývojárov.</p>
                        <h4>Hlavné charakteristiky:</h4>
                        <ul>
                            <li><strong>Vhodné pre:</strong> Vývojárov a pokročilých používateľov</li>
                            <li><strong>Package manager:</strong> DNF (moderný nástupca YUM)</li>
                            <li><strong>Desktop prostredie:</strong> GNOME (predvolené)</li>
                            <li><strong>Release cyklus:</strong> Každých 6 mesiacov</li>
                            <li><strong>Podpora:</strong> Komunita + Red Hat</li>
                        </ul>
                        <h4>Výhody:</h4>
                        <ul>
                            <li>Najnovšie verzie softvéru</li>
                            <li>Vynikajúca podpora pre vývojárov</li>
                            <li>Silný dôraz na open source</li>
                            <li>Skvelá integrácia s container technológiami</li>
                        </ul>
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-header">Arch Linux</button>
                    <div class="accordion-content">
                        <p><strong>Arch Linux</strong> je minimalistická a flexibilná distribúcia pre pokročilých používateľov. Ponúka rolling release model a rozsiahlu dokumentáciu.</p>
                        <h4>Hlavné charakteristiky:</h4>
                        <ul>
                            <li><strong>Vhodné pre:</strong> Pokročilých používateľov a nadšencov</li>
                            <li><strong>Package manager:</strong> Pacman</li>
                            <li><strong>Desktop prostredie:</strong> Žiadne predvolené (užívateľ si vyberá)</li>
                            <li><strong>Release model:</strong> Rolling release</li>
                            <li><strong>Podpora:</strong> Komunita (Arch Wiki)</li>
                        </ul>
                        <h4>Výhody:</h4>
                        <ul>
                            <li>Maximálna prispôsobiteľnosť</li>
                            <li>Vždy aktuálny softvér</li>
                            <li>Vynikajúca dokumentácia (Arch Wiki)</li>
                            <li>Minimalistický prístup</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="distro-grid">
            <div class="distro-card">
                <img src="https://images.unsplash.com/photo-1547658719-da2b51169166?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Debian Desktop">
                <div class="distro-card-content">
                    <h3>Debian</h3>
                    <p>Stabilná a spoľahlivá distribúcia, základ pre mnoho ďalších distribúcií.</p>
                </div>
            </div>
            <div class="distro-card">
                <img src="https://images.unsplash.com/photo-1592609931095-54a2168ae893?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Linux Mint Desktop">
                <div class="distro-card-content">
                    <h3>Linux Mint</h3>
                    <p>Užívateľsky prívetivá distribúcia založená na Ubuntu, s prostredím Cinnamon.</p>
                </div>
            </div>
            <div class="distro-card">
                <img src="https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="openSUSE Desktop">
                <div class="distro-card-content">
                    <h3>openSUSE</h3>
                    <p>Výkonná distribúcia s výbornými nástrojmi na správu systému.</p>
                </div>
            </div>
        </section>
    </main>

<?php require __DIR__ . '/includes/footer.php'; ?>