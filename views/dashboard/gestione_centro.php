<?php include __DIR__ . '/../partials/header.php'; ?>

<h2>Gestione Centro Sportivo</h2>

<?php if (isset($_SESSION['error'])): ?>
    <div style="color:red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div style="color:green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if (isset($centro) && $centro): ?>
    <h3>Il tuo centro:</h3>
    <p><strong>Nome:</strong> <?php echo htmlspecialchars($centro['nome']); ?></p>
    <p><strong>Indirizzo:</strong> <?php echo htmlspecialchars($centro['indirizzo']); ?></p>
    <p><strong>Città:</strong> <?php echo htmlspecialchars($centro['citta']); ?></p>

    <h3>Crea una nuova proposta di gioco:</h3>
    <form method="POST" action="/proposte/crea">
        <input type="hidden" name="id_centro" value="<?php echo $centro['id']; ?>">
        <input type="text" name="titolo" placeholder="Titolo" required><br><br>
        <textarea name="descrizione" placeholder="Descrizione" rows="4" cols="40"></textarea><br><br>
        <input type="datetime-local" name="data_evento" required><br><br>
        <input type="number" name="max_partecipanti" placeholder="Max partecipanti" value="10" required><br><br>
        <button type="submit">Crea proposta</button>
    </form>
<?php else: ?>
    <h3>Non hai ancora registrato un centro sportivo.</h3>

    <form method="POST" action="/centro/crea">
        <input type="text" name="nome" placeholder="Nome centro" required><br><br>
        <input type="text" name="indirizzo" placeholder="Indirizzo" required><br><br>
        <input type="text" name="citta" placeholder="Città" required><br><br>
        <button type="submit">Crea Centro Sportivo</button>
    </form>
<?php endif; ?>

<br><a href="/dashboard">Torna alla Dashboard</a>

<?php include __DIR__ . '/../partials/footer.php'; ?>
