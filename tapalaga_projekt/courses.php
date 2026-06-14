<?php
$pageTitle = 'Linux Kurzy';
require __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/Course.php';

// 1. OCHRANA: Ak užívateľ NIE JE prihlásený, ukážeme mu pekný zámok a nepustíme ho ďalej
if (!$auth->isLoggedIn()) {
    ?>
    <main class="container" style="margin-top: 120px; min-height: 70vh;">
        <h1>Linux Vzdelávacie Kurzy</h1>
        <p>Zvýšte svoje zručnosti v systéme Linux s našimi certifikovanými kurzami.</p>
        <hr style="margin: 30px 0; border: 0; border-top: 1px solid #ddd;">
        
        <div class="alert alert-warning" style="text-align: center; padding: 40px; background-color: #fff3cd; color: #856404; border-radius: 8px; box-shadow: var(--shadow);">
            <h3 style="margin-top: 0;">🔒 Obsah uzamknutý</h3>
            <p>Pre zobrazenie ponuky kurzov, simuláciu nákupu a prístup do svojej knižnice sa musíte najprv prihlásiť.</p>
            <a href="login.php" class="btn btn-primary" style="display: inline-block; margin-top: 15px; text-decoration: none;">Prejsť na prihlásenie</a>
        </div>
    </main>
    <?php
    require __DIR__ . '/includes/footer.php';
    exit; // Týmto zastavíme načítavanie zvyšku kódu, takže nevznikne žiaden PHP error
}

// =============================================================
// KÓD NIŽŠIE SA SPUSTÍ IBA VTEDY, AK JE UŽÍVATEĽ PRIHLÁSENÝ
// =============================================================
$courseObj = new Course($db);
$current_user_id = $_SESSION['user_id']; // Už vieme na 100%, že session existuje
$msg = "";

// Spracovanie nákupu kurzu
if (isset($_GET['buy'])) {
    $course_id = intval($_GET['buy']);
    if ($courseObj->purchase($current_user_id, $course_id)) {
        $msg = "<div class='alert alert-success' style='background-color: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px;'>🎉 Kurz bol úspešne zakúpený! Nájdete ho nižšie vo vašej knižnici.</div>";
    } else {
        $msg = "<div class='alert alert-warning' style='background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 6px; margin-bottom: 20px;'>Tento kurz už vlastníte.</div>";
    }
}

// Spracovanie vytvorenia nového kurzu (IBA ADMIN)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_course') {
    if ($auth->isAdmin()) {
        $title = trim($_POST['title'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);

        if (!empty($title) && !empty($desc) && $price > 0) {
            $courseObj->create($title, $desc, $price);
            header("Location: courses.php");
            exit;
        } else {
            $msg = "<div class='alert alert-danger' style='background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px;'>Vyplňte všetky polia správne!</div>";
        }
    }
}

// Načítanie dát podľa roly
if ($auth->isAdmin()) {
    $courses = $courseObj->getAllCoursesForAdmin();
} else {
    $courses = $courseObj->getAllCoursesForUser($current_user_id);
    
    $myLibrary = [];
    $availableCourses = [];
    
    foreach ($courses as $c) {
        if ($c['is_owned'] > 0) {
            $myLibrary[] = $c;
        } else {
            $availableCourses[] = $c;
        }
    }
}
?>

<main class="container" style="margin-top: 120px; min-height: 70vh;">
    <h1>Linux Vzdelávacie Kurzy</h1>
    <p>Zvýšte svoje zručnosti v systéme Linux s našimi certifikovanými kurzami.</p>
    
    <?php echo $msg; ?>

    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #ddd;">

    <?php if ($auth->isAdmin()): ?>
        <div style="background: #f4f6f9; border: 1px solid #ccdae5; padding: 25px; border-radius: 8px; margin-bottom: 40px;">
            <h3 style="color: var(--darker-color); margin-top: 0;">🛠 Admin Panel: Pridať nový kurz</h3>
            <form method="POST" action="courses.php" style="margin-top: 15px; display: grid; gap: 15px;">
                <input type="hidden" name="action" value="add_course">
                
                <div>
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Názov kurzu</label>
                    <input type="text" name="title" style="width:100%; padding:10px; border-radius:6px; border:1px solid var(--gray-light);" required>
                </div>
                <div>
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Popis kurzu</label>
                    <textarea name="description" rows="3" style="width:100%; padding:10px; border-radius:6px; border:1px solid var(--gray-light);" required></textarea>
                </div>
                <div>
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Cena (€)</label>
                    <input type="number" step="0.01" name="price" style="width:200px; padding:10px; border-radius:6px; border:1px solid var(--gray-light);" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width:fit-content;">Vytvoriť kurz</button>
            </form>
        </div>

        <h2>Prehľad kurzov a štatistiky predajov</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; margin-top: 20px;">
            <?php foreach ($courses as $c): ?>
                <div style="background: white; border-radius: 8px; box-shadow: var(--shadow); padding: 20px; display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--secondary-color);">
                    <div>
                        <h3 style="margin-top: 0; margin-bottom: 10px;"><?php echo htmlspecialchars($c['title']); ?></h3>
                        <p style="color: var(--black); font-size: 0.95rem; margin-bottom: 15px;"><?php echo htmlspecialchars($c['description']); ?></p>
                    </div>
                    <div style="background: #fff5f2; padding: 10px; border-radius: 6px; text-align: center; font-weight: bold; margin-bottom: 10px; border: 1px dashed var(--secondary-color);">
                        📈 Celkovo predané: <span style="color:var(--secondary-dark); font-size:1.2rem;"><?php echo $c['total_sales']; ?>x</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-weight: bold; margin-top: 10px;">
                        <span>Cena: <?php echo number_format($c['price'], 2); ?> €</span>
                        <span style="color: var(--gray-color); font-size: 0.85rem;">ID: #<?php echo $c['id']; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>

        <?php if (!empty($myLibrary)): ?>
            <section style="background: #eef9f2; border: 1px solid #c3e6cb; padding: 30px; border-radius: 12px; margin-bottom: 50px;">
                <h2 style="color: #155724; margin-top: 0; display: flex; align-items: center; gap: 10px;">
                    📚 Moja knižnica zakúpených kurzov (<?php echo count($myLibrary); ?>)
                </h2>
                <p style="color: #1c5a2c; margin-bottom: 25px;">Tu sú vaše aktívne kurzy. Môžete začať študovať.</p>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                    <?php foreach ($myLibrary as $c): ?>
                        <div style="background: white; border-radius: 8px; box-shadow: var(--shadow); padding: 20px; border-left: 5px solid var(--success-color); display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <h3 style="margin-top: 0; color: var(--darker-color);"><?php echo htmlspecialchars($c['title']); ?></h3>
                                <p style="color: #555; font-size: 0.9rem; line-height: 1.4;"><?php echo htmlspecialchars($c['description']); ?></p>
                            </div>
                            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee; text-align: right;">
                                <a href="#" class="btn btn-success" style="padding: 8px 20px; font-size: 0.85rem; background-color: var(--success-color); text-decoration: none;" onclick="alert('Spúšťam výučbové prostredie kurzu...'); return false;">Spustiť kurz ➔</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <h2>Dostupné kurzy na zakúpenie</h2>
        
        <?php if (empty($availableCourses)): ?>
            <div style="background: #f8f9fa; padding: 30px; text-align: center; border-radius: 8px; border: 1px dashed #ccc;">
                <p style="font-style: italic; color: var(--gray-color); margin: 0;">🎉 Neuveriteľné! Kúpili ste si všetky naše dostupné kurzy. Ďakujeme za dôveru!</p>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px; margin-top: 20px;">
                <?php foreach ($availableCourses as $c): ?>
                    <div style="background: white; border-radius: 8px; box-shadow: var(--shadow); padding: 25px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <h3 style="color: var(--darker-color); margin-top: 0; margin-bottom: 10px;"><?php echo htmlspecialchars($c['title']); ?></h3>
                            <p style="color: #555; font-size: 0.95rem; line-height: 1.5; margin-bottom: 20px;"><?php echo htmlspecialchars($c['description']); ?></p>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 15px;">
                            <span style="font-size: 1.3rem; font-weight: bold; color: var(--dark-color);"><?php echo number_format($c['price'], 2); ?> €</span>
                            <a href="courses.php?buy=<?php echo $c['id']; ?>" class="btn btn-primary" style="padding: 8px 20px; font-size: 0.9rem; text-decoration: none;" onclick="return confirm('Naozaj chcete simulovať kúpu tohto kurzu?');">Kúpiť kurz</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>