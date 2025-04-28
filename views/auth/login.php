<?php include __DIR__ . '/../partials/header.php'; ?>

<h2>Login</h2>

<?php if (isset($_SESSION['error'])): ?>
    <div style="color:red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<form method="POST" action="/login">
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Accedi</button>
</form>

<br>

<a href="/google-login">Accedi con Google</a><br><br>
<a href="/register">Non hai un account? Registrati</a>

<?php include __DIR__ . '/../partials/footer.php'; ?>
