<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Core\Database;
use App\Core\Auth;
use League\OAuth2\Client\Provider\Google;

class AuthController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function showLogin(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        ob_start();
        include __DIR__ . '/../../views/auth/login.php';
        $response->getBody()->write(ob_get_clean());
        return $response;
    }

    public function showRegister(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        ob_start();
        include __DIR__ . '/../../views/auth/register.php';
        $response->getBody()->write(ob_get_clean());
        return $response;
    }

    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $stmt = $this->db->prepare('SELECT * FROM utenti WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            Auth::login($user);
            return $response->withHeader('Location', '/dashboard')->withStatus(302);
        }

        $_SESSION['error'] = 'Credenziali non valide.';
        return $response->withHeader('Location', '/login')->withStatus(302);
    }

    public function register(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $ruolo = $data['ruolo'] ?? 'atleta';

        if (empty($nome) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Tutti i campi sono obbligatori.';
            return $response->withHeader('Location', '/register')->withStatus(302);
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare('INSERT INTO utenti (nome, email, password, ruolo) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nome, $email, $hash, $ruolo]);

        $_SESSION['success'] = 'Registrazione completata. Ora puoi accedere.';
        return $response->withHeader('Location', '/login')->withStatus(302);
    }

    public function logout(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        Auth::logout();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }

    public function redirectToGoogle(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $provider = new Google([
            'clientId'     => $_ENV['GOOGLE_CLIENT_ID'],
            'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
            'redirectUri'  => $_ENV['GOOGLE_REDIRECT_URI'],
        ]);

        $authUrl = $provider->getAuthorizationUrl();
        $_SESSION['oauth2state'] = $provider->getState();
        return $response->withHeader('Location', $authUrl)->withStatus(302);
    }

    public function handleGoogleCallback(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $provider = new Google([
            'clientId'     => $_ENV['GOOGLE_CLIENT_ID'],
            'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
            'redirectUri'  => $_ENV['GOOGLE_REDIRECT_URI'],
        ]);

        $queryParams = $request->getQueryParams();

        if (empty($queryParams['state']) || ($queryParams['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        }

        $token = $provider->getAccessToken('authorization_code', [
            'code' => $queryParams['code']
        ]);

        $googleUser = $provider->getResourceOwner($token);

        $email = $googleUser->getEmail();
        $googleId = $googleUser->getId();
        $nome = $googleUser->getName();

        // Verifica se esiste
        $stmt = $this->db->prepare('SELECT * FROM utenti WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            // Registrazione automatica
            $stmt = $this->db->prepare('INSERT INTO utenti (nome, email, ruolo, google_id) VALUES (?, ?, ?, ?)');
            $stmt->execute([$nome, $email, 'atleta', $googleId]);

            $stmt = $this->db->prepare('SELECT * FROM utenti WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();
        }

        Auth::login($user);

        return $response->withHeader('Location', '/dashboard')->withStatus(302);
    }
}
