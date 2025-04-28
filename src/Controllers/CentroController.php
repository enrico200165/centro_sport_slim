<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Core\Database;
use App\Core\Auth;

class CentroController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function dashboard(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!Auth::check()) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        $user = Auth::user();

        ob_start();
        include __DIR__ . '/../../views/dashboard/home.php';
        $response->getBody()->write(ob_get_clean());
        return $response;
    }

    public function gestioneCentro(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!Auth::check() || Auth::user()['ruolo'] !== 'gestore') {
            return $response->withHeader('Location', '/dashboard')->withStatus(302);
        }

        $user = Auth::user();

        $stmt = $this->db->prepare('SELECT * FROM centri WHERE id_gestore = ?');
        $stmt->execute([$user['id']]);
        $centro = $stmt->fetch();

        ob_start();
        include __DIR__ . '/../../views/dashboard/gestione_centro.php';
        $response->getBody()->write(ob_get_clean());
        return $response;
    }

    public function creaCentro(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!Auth::check() || Auth::user()['ruolo'] !== 'gestore') {
            return $response->withHeader('Location', '/dashboard')->withStatus(302);
        }

        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';
        $indirizzo = $data['indirizzo'] ?? '';
        $citta = $data['citta'] ?? '';
        $idGestore = Auth::user()['id'];

        if (empty($nome) || empty($indirizzo) || empty($citta)) {
            $_SESSION['error'] = 'Tutti i campi sono obbligatori.';
            return $response->withHeader('Location', '/gestione-centro')->withStatus(302);
        }

        $stmt = $this->db->prepare('INSERT INTO centri (nome, indirizzo, citta, id_gestore) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nome, $indirizzo, $citta, $idGestore]);

        $_SESSION['success'] = 'Centro sportivo creato con successo.';
        return $response->withHeader('Location', '/gestione-centro')->withStatus(302);
    }
}
