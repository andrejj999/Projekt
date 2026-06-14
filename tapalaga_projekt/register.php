<?php
$pageTitle = 'Registrácia';
require __DIR__ . '/includes/header.php';

// Ak je užívateľ už prihlásený, netreba ho registrovať - presmerujeme ho na domov
if ($auth->isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$errors = [];
$success = false;
$username = '';
$email = '';

// Spracovanie odoslaného formulára
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username        = trim($_POST['username'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $password        = $_POST['password'] ?? '';
    $password_repeat = $_POST['password_repeat'] ?? '';

    // Základná validácia inputov
    if (empty($username)) { $errors[] = "Meno je povinné."; }
    if (empty($email)) { $errors[] = "Email je povinný."; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "Zadajte platný email."; }
    if (strlen($password) < 6) { $errors[] = "Heslo musí mať aspoň 6 znakov."; }
    if ($password !== $password_repeat) { $errors[] = "Heslá sa nezhodujú!"; }

    // Ak nevznikla žiadna chyba vo validácii, skúsime registráciu cez OOP
    if (empty($errors)) {
        $result = $auth->register($username, $email, $password);
        
        if ($result === true) {
            $success = true;
            // Vyčistíme polia po úspešnej registrácii
            $username = '';
            $email = '';
        } else {
            // Ak metóda vrátila text (napr. "Email už existuje"), pridáme ho medzi chyby
            $errors[] = $result;
        }
    }
}
?>

<main class="container" style="margin-top: 120px; min-height: 70vh; display: flex; justify-content: center; align-items: center;">
    <div style="background: white; padding: 40px; border-radius: var(--border-radius); box-shadow: var(--shadow-lg); width: 100%; max-width: 450px;">
        
        <h1 style="text-align: center; margin-bottom: 10px; font-size: 2rem;">Vytvoriť účet</h1>
        <p style="text-align: center; color: var(--gray-color); margin-bottom: 30px;">Zaregistrujte sa a zapojte sa do diskusie.</p>

        <?php if ($success): ?>
            <div class="alert alert-success" style="margin-bottom: 20px;">
                <strong>Úspech!</strong> Účet bol úspešne vytvorený. Teraz sa môžete <a href="login.php" style="font-weight: bold; color: inherit;">prihlásiť</a>.
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px; background-color: #fce8e6; color: var(--danger-color); padding: 12px; border-radius: 6px;">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="username" style="display: block; margin-bottom: 8px; font-weight: 600;">Používateľské meno</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" style="width: 100%; padding: 12px; border: 1px solid var(--gray-light); border-radius: 6px;" placeholder="Napr. Andrej" required>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600;">E-mailová adresa</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" style="width: 100%; padding: 12px; border: 1px solid var(--gray-light); border-radius: 6px;" placeholder="andrej@gmail.com" required>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="password" style="display: block; margin-bottom: 8px; font-weight: 600;">Heslo</label>
                <input type="password" id="password" name="password" style="width: 100%; padding: 12px; border: 1px solid var(--gray-light); border-radius: 6px;" placeholder="Minimálne 6 znakov" required>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="password_repeat" style="display: block; margin-bottom: 8px; font-weight: 600;">Zopakujte heslo</label>
                <input type="password" id="password_repeat" name="password_repeat" style="width: 100%; padding: 12px; border: 1px solid var(--gray-light); border-radius: 6px;" placeholder="Znovu zadajte heslo" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 1.1rem; font-weight: bold;">Zaregistrovať sa</button>
        </form>

        <p style="text-align: center; margin-top: 25px; font-size: 0.95rem; color: var(--black);">
            Už máte účet? <a href="login.php" style="color: var(--primary-color); font-weight: bold; text-decoration: none;">Prihláste sa tu</a>
        </p>
    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>