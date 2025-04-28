<?php include __DIR__ . '/../partials/header.php'; ?>

<h2>Proposte di Gioco Disponibili</h2>

<?php if (isset($_SESSION['error'])): ?>
    <div style="color:red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div style="color:green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if (!empty($proposte)): ?>
    <ul>
        <?php foreach ($proposte as $proposta): ?>
            <li>
                <strong><?php echo htmlspecialchars($proposta['titolo']); ?></strong><br>
                Data evento: <?php echo htmlspecialchars($proposta['data_evento']); ?><br>
                Centro: <?php echo htmlspecialchars($proposta['nome_centro']); ?><br>
                <form method="POST" action="/prenota/<?php echo $proposta['id']; ?>">
                    <button type="submit">Prenota</button>
                </form>
            </li>
            <br>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Non ci sono proposte disponibili al momento.</p>
<?php endif; ?>

<br><a href="/dashboard">Torna alla Dashboard</a>

<?php include __DIR__ . '/../partials/footer.php'; ?>
