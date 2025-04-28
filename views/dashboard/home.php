<?php include __DIR__ . '/../partials/header.php'; ?>

<h2>Benvenuto, <?php echo htmlspecialchars($_SESSION['user']['nome']); ?>!</h2>

<p>Ruolo: <?php echo htmlspecialchars($_SESSION['user']['ruolo']); ?></p>
<p>Skill Score: <?php echo htmlspecialchars($_SESSION['user']['skill_score']); ?></p>

<?php if ($_SESSION['user']['ruolo'] === 'gestore'): ?>
    <a href="/gestione-centro">Gestisci il tuo centro sportivo</a><br><br>
<?php else: ?>
    <a href="/proposte">Visualizza proposte di gioco</a><br><br>
<?php endif; ?>

<a href="/logout">Logout</a>

<?php include __DIR__ . '/../partials/footer.php'; ?>
