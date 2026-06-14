<?php
$pageTitle = 'Prihlásenie';
require __DIR__ . '/includes/header.php';

// Ak je už prihlásený, presmeruj na domovskú obrazovku
if ($auth->isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        if ($auth->login($email, $password)) {
            // Úspešné prihlásenie
            header("Location: index.php");
            exit;
        } else {
            $error = 'Nesprávny email alebo heslo.';
        }
    } else {
        $error = 'Prosím, vyplňte všetky polia.';
    }
}
?>

<main class="container" style="margin-top: 120px; min-height: 50vh;">
    <div style="max-width: 500px; margin: 0 auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h1 style="text-align: center; margin-bottom: 30px;">Prihlásenie do systému</h1>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-warning">
                <strong>Chyba:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="contact-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Heslo</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Prihlásiť sa</button>
            <p style="text-align: center; margin-top: 25px; font-size: 0.95rem;">
    Ešte nemáte účet? <a href="register.php" style="color: var(--primary-color); font-weight: bold; text-decoration: none;">Zaregistrujte sa tu</a>
</p>
        </form>
    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>