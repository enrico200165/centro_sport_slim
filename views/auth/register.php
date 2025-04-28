<?php include __DIR__ . '/../partials/header.php'; ?>

<h2>Registrazione</h2>

<?php if (isset($_SESSION['error'])): ?>
    <div style="color:red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<form method="POST" action="/register">
    <input type="text" name="nome" placeholder="Nome" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    
    <label for="ruolo">Seleziona ruolo:</label><br>
    <select name="ruolo" required>
        <option value="atleta">Atleta</option>
        <option value="gestore">Gestore</option>
    </select><br><br>
    
    <button type="submit">Registrati</button>
</form>

<br>

<a href="/login">Hai già un account? Accedi</a>

<?php include __DIR__ . '/../partials/footer.php'; ?>
