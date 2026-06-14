<?php
$pageTitle = 'Linux Kurzy';
require __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/Course.php';

$courseObj = new Course($db);
$current_user_id = $_SESSION['user_id'] ?? 0;
$msg = "";


if (isset($_GET['buy']) && $auth->isLoggedIn()) {
    $course_id = intval($_GET['buy']);
    if ($courseObj->purchase($current_user_id, $course_id)) {
        $msg = "<div class='alert alert-success'>🎉 Kurz bol úspešne zakúpený! Nájdete ho vo svojej knižnici nižšie.</div>";
    } else {
        $msg = "<div class='alert alert-warning'>Tento kurz už vlastníte.</div>";
    }
}


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
            $msg = "<div class='alert alert-danger'>Vyplňte všetky polia správne!</div>";
        }
    }
}

if ($auth->isAdmin()) {
    $courses = $courseObj->getAllCoursesForAdmin();
} else {
    $courses = $courseObj->getAllCoursesForUser($current_user_id);
}
?>

<main class="container" style="margin-top: 120px; min-height: 70vh;">
    <h1>Linux Vzdelávacie Kurzy</h1>
    <p>Zvýšte svoje zručnosti v systéme Linux s našimi certifikovanými kurzami.</p>
    
    <?php echo $msg; ?>

    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #ddd;">

  
    <?php if ($auth->isAdmin()): ?>
        <div style="background: #f4f6f9; border: 1px solid #ccdae5; padding: 25px; border-radius: 8px; margin-bottom: 40px;">
            <h3 style="color: var(--darker-color);">🛠 Admin Panel: Pridať nový kurz</h3>
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

        <h2>Prehľad kurzov a predajov (Admin pohľad)</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; margin-top: 20px;">
            <?php foreach ($courses as $c): ?>
                <div style="background: white; border-radius: 8px; box-shadow: var(--shadow); padding: 20px; display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--secondary-color);">
                    <div>
                        <h3 style="margin-bottom: 10px;"><?php echo htmlspecialchars($c['title']); ?></h3>
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
        <h2>Dostupné kurzy na zakúpenie</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px; margin-top: 20px;">
            <?php foreach ($courses as $c): ?>
                <div style="background: white; border-radius: 8px; box-shadow: var(--shadow); padding: 25px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <h3 style="color: var(--darker-color); margin-bottom: 10px;"><?php echo htmlspecialchars($c['title']); ?></h3>
                        <p style="color: #555; font-size: 0.95rem; line-height: 1.5; margin-bottom: 20px;"><?php echo htmlspecialchars($c['description']); ?></p>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 15px;">
                        <span style="font-size: 1.3rem; font-weight: bold; color: var(--dark-color);"><?php echo number_format($c['price'], 2); ?> €</span>
                        
                        <?php if (!$auth->isLoggedIn()): ?>
                            <a href="login.php" class="btn btn-secondary" style="padding: 8px 15px; font-size: 0.9rem;">Prihláste sa pre kúpu</a>
                        <?php else: ?>
                            <?php if ($c['is_owned'] > 0): ?>
                                <span style="background: var(--success-color); color: white; padding: 8px 15px; border-radius: 6px; font-weight: bold; font-size: 0.9rem;">✓ Vlastníte kurz</span>
                            <?php else: ?>
                                <a href="courses.php?buy=<?php echo $c['id']; ?>" class="btn btn-primary" style="padding: 8px 20px; font-size: 0.9rem;" onclick="return confirm('Naozaj chcete simulovať kúpu tohto kurzu?');">Kúpiť kurz</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>