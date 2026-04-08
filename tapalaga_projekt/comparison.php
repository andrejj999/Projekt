<?php
$pageTitle = 'Porovnanie';
require __DIR__ . '/includes/header.php';
?>

    <main class="container">
        <h1>Porovnanie Linux distribúcií</h1>
        
        <section class="table-section">
            <h2>Technické porovnanie</h2>
            <div class="table-container">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th>Distribúcia</th>
                            <th>Základ</th>
                            <th>Package Manager</th>
                            <th>Release Model</th>
                            <th>Obtiažnosť</th>
                            <th>Podpora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ubuntu</td>
                            <td>Debian</td>
                            <td>APT</td>
                            <td>Standard (6 mesiacov)</td>
                            <td>Začiatočník</td>
                            <td>Komerčná + Komunita</td>
                        </tr>
                        <tr>
                            <td>Fedora</td>
                            <td>Red Hat</td>
                            <td>DNF</td>
                            <td>Rýchly (6 mesiacov)</td>
                            <td>Stredne pokročilý</td>
                            <td>Komunita + Red Hat</td>
                        </tr>
                        <tr>
                            <td>Arch Linux</td>
                            <td>Nezávislý</td>
                            <td>Pacman</td>
                            <td>Rolling Release</td>
                            <td>Pokročilý</td>
                            <td>Komunita</td>
                        </tr>
                        <tr>
                            <td>Debian</td>
                            <td>Nezávislý</td>
                            <td>APT</td>
                            <td>Konzervatívny (2-3 roky)</td>
                            <td>Stredne pokročilý</td>
                            <td>Komunita</td>
                        </tr>
                        <tr>
                            <td>Linux Mint</td>
                            <td>Ubuntu</td>
                            <td>APT</td>
                            <td>Standard (6 mesiacov)</td>
                            <td>Začiatočník</td>
                            <td>Komunita</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="comparison-info">
            <h2>Ktorú distribúciu si vybrať?</h2>
            <div class="info-grid">
                <div class="info-card">
                    <h3>Pre začiatočníkov</h3>
                    <ul>
                        <li>Ubuntu - najjednoduchšia na používanie</li>
                        <li>Linux Mint - podobné Windows</li>
                        <li>Zorin OS - veľmi užívateľsky prívetivé</li>
                    </ul>
                </div>
                <div class="info-card">
                    <h3>Pre vývojárov</h3>
                    <ul>
                        <li>Fedora - najnovšie technológie</li>
                        <li>Ubuntu - výborná podpora</li>
                        <li>Manjaro - Arch pre všetkých</li>
                    </ul>
                </div>
                <div class="info-card">
                    <h3>Pre server</h3>
                    <ul>
                        <li>Ubuntu Server - výborná dokumentácia</li>
                        <li>CentOS - stabilita</li>
                        <li>Debian - spoľahlivosť</li>
                    </ul>
                </div>
            </div>
        </section>

        <div class="alert alert-warning">
            <strong>Upozornenie:</strong> Výber distribúcie závisí od vašich potrieb a skúseností. Odporúčame vyskúšať viacero distribúcií v live režime pred inštaláciou.
        </div>
    </main>

<?php require __DIR__ . '/includes/footer.php'; ?>