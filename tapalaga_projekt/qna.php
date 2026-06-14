<?php
$pageTitle = 'Otázky a Odpovede';
require __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/Question.php';

// Inicializácia objektu otázok
$questionObj = new Question($db);

// Spracovanie odoslaných formulárov (iba ak je užívateľ prihlásený)
if ($auth->isLoggedIn()) {
    
    // 1. Prijatie novej otázky (od usera alebo admina)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ask') {
        $text = trim($_POST['question_text'] ?? '');
        if (!empty($text)) {
            $questionObj->create($_SESSION['user_id'], $text);
            header("Location: qna.php");
            exit;
        }
    }

    // 2. Prijatie odpovede (IBA ADMIN)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'answer') {
        if ($auth->isAdmin()) {
            $q_id = intval($_POST['question_id'] ?? 0);
            $ans_text = trim($_POST['answer_text'] ?? '');
            if ($q_id > 0 && !empty($ans_text)) {
                $questionObj->answer($q_id, $_SESSION['user_id'], $ans_text);
                header("Location: qna.php");
                exit;
            }
        }
    }

    // 3. Vymazanie otázky (IBA ADMIN)
    if (isset($_GET['delete']) && $auth->isAdmin()) {
        $q_id = intval($_GET['delete']);
        $questionObj->delete($q_id);
        header("Location: qna.php");
        exit;
    }
}

// Načítanie všetkých otázok z databázy
$allQuestions = $questionObj->readAll();
?>

<main class="container" style="margin-top: 120px; min-height: 70vh;">
    <h1>Poradňa a Diskusia</h1>
    <p>Sekcia otázok od našich používateľov a oficiálnych odpovedí od administrátorov.</p>

    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #ddd;">

    <?php if (!$auth->isLoggedIn()): ?>
        <div class="alert alert-warning" style="text-align: center; padding: 40px;">
            <h3>Obsah uzamknutý</h3>
            <p>Pre zobrazenie otázok, odpovedí a posielanie vlastných príspevkov sa musíte prihlásiť do svojho účtu.</p>
            <a href="login.php" class="btn btn-primary" style="display: inline-block; margin-top: 15px;">Prejsť na prihlásenie</a>
        </div>

    <?php else: ?>
        <section style="background: white; padding: 25px; border-radius: 8px; box-shadow: var(--shadow); margin-bottom: 40px;">
            <h3>Položiť novú otázku</h3>
            <form method="POST" action="qna.php" style="margin-top: 15px;">
                <input type="hidden" name="action" value="ask">
                <div class="form-group">
                    <textarea name="question_text" rows="3" placeholder="Sem napíšte vašu otázku ohľadom Linuxu..." style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid var(--gray-light); resize: vertical;" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Odoslať otázku</button>
            </form>
        </section>

        <section>
            <h2>Všetky príspevky (<?php echo count($allQuestions); ?>)</h2>
            
            <?php if (empty($allQuestions)): ?>
                <p style="color: var(--gray-color); font-style: italic; margin-top: 20px;">Zatiaľ neboli položené žiadne otázky. Buďte prvý!</p>
            <?php else: ?>
                <?php foreach ($allQuestions as $q): ?>
                    <div style="background: white; border-radius: 8px; box-shadow: var(--shadow); padding: 20px; margin-top: 20px; border-left: 5px solid var(--primary-color); position: relative;">
                        
                        <?php if ($auth->isAdmin()): ?>
                            <a href="qna.php?delete=<?php echo $q['id']; ?>" onclick="return confirm('Naozaj chcete vymazať túto otázku?');" style="position: absolute; top: 15px; right: 15px; color: var(--danger-color); text-decoration: none; font-weight: bold;">✕ Vymazať</a>
                        <?php endif; ?>

                        <div style="margin-bottom: 15px;">
                            <strong style="color: var(--darker-color);"><?php echo htmlspecialchars($q['author']); ?></strong> 
                            <span style="font-size: 0.85rem; color: var(--gray-color); margin-left: 10px;"><?php echo date('d.m.Y H:i', strtotime($q['created_at'])); ?></span>
                            <p style="margin-top: 8px; font-size: 1.1rem;"><?php echo nl2br(htmlspecialchars($q['question_text'])); ?></p>
                        </div>

                        <?php if (!empty($q['answer_text'])): ?>
                            <div style="background: var(--lighter-color); padding: 15px; border-radius: 6px; border-left: 4px solid var(--success-color); margin-top: 10px;">
                                <strong style="color: var(--success-color);">Odpoveď od Admina (<?php echo htmlspecialchars($q['admin_name']); ?>):</strong>
                                <p style="margin-top: 5px;"><?php echo nl2br(htmlspecialchars($q['answer_text'])); ?></p>
                            </div>
                        <?php else: ?>
                            <?php if ($auth->isAdmin()): ?>
                                <div style="background: #fff8f5; padding: 15px; border-radius: 6px; border: 1px dashed var(--secondary-color); margin-top: 10px;">
                                    <strong style="color: var(--secondary-color);">Odpovedať na otázku (Admin panel):</strong>
                                    <form method="POST" action="qna.php" style="margin-top: 10px;">
                                        <input type="hidden" name="action" value="answer">
                                        <input type="hidden" name="question_id" value="<?php echo $q['id']; ?>">
                                        <textarea name="answer_text" rows="2" placeholder="Napíšte oficiálnu odpoveď..." style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--gray-light);" required></textarea>
                                        <button type="submit" class="btn btn-secondary" style="margin-top: 10px; padding: 8px 20px; font-size: 0.9rem;">Odoslať odpoveď</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <p style="font-style: italic; color: var(--gray-color); font-size: 0.9rem; margin-top: 10px;">Waiting for admin response...</p>
                            <?php endif; ?>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

    <?php endif; ?>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>