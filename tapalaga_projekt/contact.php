<?php
$pageTitle = 'Kontakt';

$success = false;
$errors = [];
$formData = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['name']    = trim($_POST['name'] ?? '');
    $formData['email']   = trim($_POST['email'] ?? '');
    $formData['subject'] = trim($_POST['subject'] ?? '');
    $formData['message'] = trim($_POST['message'] ?? '');
    $privacy             = isset($_POST['privacy']);

    if (empty($formData['name']))    $errors[] = 'Meno je povinné.';
    if (empty($formData['email']))   $errors[] = 'Email je povinný.';
    elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email nie je platný.';
    if (empty($formData['message'])) $errors[] = 'Správa je povinná.';
    if (!$privacy)                   $errors[] = 'Musíte súhlasiť so spracovaním osobných údajov.';

    if (empty($errors)) {
        $success = true;
    }
}

require __DIR__ . '/includes/header.php';
?>

    <main class="container">
        <h1>Kontaktujte nás</h1>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <strong>Ďakujeme!</strong> Vaša správa bola úspešne odoslaná. Ozveme sa vám čo najskôr.
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-warning">
                <strong>Chyba:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="contact-layout">

            <section class="contact-info">
                <h2>Kontaktné informácie</h2>
                <div class="contact-item">
                    <h3>Email</h3>
                    <p><a href="mailto:info@linuxdistro.sk">info@linuxdistro.sk</a></p>
                </div>
                <div class="contact-item">
                    <h3>Telefón</h3>
                    <p><a href="tel:+421900123456">+421 900 123 456</a></p>
                </div>
                <div class="contact-item">
                    <h3>Podpora</h3>
                    <p><a href="mailto:podpora@linuxdistro.sk">podpora@linuxdistro.sk</a></p>
                </div>
            </section>

            <section class="contact-form-section">
                <h2>Napíšte nám</h2>
                <form id="contactForm" class="contact-form" method="POST" action="contact.php">
                    <div class="form-group">
                        <label for="name">Meno *</label>
                        <input type="text" id="name" name="name" required
                               value="<?php echo htmlspecialchars($formData['name']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required
                               value="<?php echo htmlspecialchars($formData['email']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="subject">Predmet</label>
                        <select id="subject" name="subject">
                            <option value="">Vyberte predmet</option>
                            <option value="general"   <?php echo $formData['subject'] === 'general'   ? 'selected' : ''; ?>>Všeobecná otázka</option>
                            <option value="technical" <?php echo $formData['subject'] === 'technical' ? 'selected' : ''; ?>>Technická podpora</option>
                            <option value="distro"    <?php echo $formData['subject'] === 'distro'    ? 'selected' : ''; ?>>Odporúčanie distribúcie</option>
                            <option value="other"     <?php echo $formData['subject'] === 'other'     ? 'selected' : ''; ?>>Iné</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Správa *</label>
                        <textarea id="message" name="message" rows="5" required><?php echo htmlspecialchars($formData['message']); ?></textarea>
                    </div>

                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="privacy" name="privacy" required>
                        <label for="privacy">Súhlasím so spracovaním osobných údajov *</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Odoslať správu</button>
                </form>
            </section>
        </div>
    </main>

<?php require __DIR__ . '/includes/footer.php'; ?>