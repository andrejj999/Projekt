<?php
// Toto MUSÍ byť na prvom riadku, inak session nebude fungovať naprieč stránkami
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Auth.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Komplexný sprievodca Linux distribúciami - Ubuntu, Fedora, Arch Linux a ďalšie">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - LinuxDistro' : 'LinuxDistro'; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                 <span class="logo-text">LinuxDistro</span>
            </div>
            <ul class="nav-menu">
    <li class="nav-item"><a href="index.php" class="nav-link">Domov</a></li>
    <li class="nav-item"><a href="distributions.php" class="nav-link">Distribúcie</a></li>
    <li class="nav-item"><a href="comparison.php" class="nav-link">Porovnanie</a></li>
    <li class="nav-item"><a href="contact.php" class="nav-link">Kontakt</a></li>
    
    <li class="nav-item"><a href="qna.php" class="nav-link">Q&A Poradňa</a></li>
<li class="nav-item"><a href="courses.php" class="nav-link" style="color: var(--secondary-color); font-weight: bold;">Kurzy Linuxu</a></li>
    
    <?php if ($auth->isLoggedIn()): ?>
        <li class="nav-item"><a href="logout.php" class="nav-link">Odhlásiť sa (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
    <?php else: ?>
        <li class="nav-item"><a href="login.php" class="nav-link">Prihlásenie</a></li>
    <?php endif; ?>
</ul>
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </nav>