<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Core\Database;
use App\Core\Auth;

class PrenotazioneController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function prenota(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if (!Auth::check() || Auth::user()['ruolo'] !== 'atleta') {
            return $response->withHeader('Location', '/dashboard')->withStatus(302);
        }

        $id_proposta = $args['id'];
        $id_utente = Auth::user()['id'];

        // Verifica se l'utente ha già prenotato
        $stmt = $this->db->prepare('SELECT * FROM prenotazioni WHERE id_proposta = ? AND id_utente = ?');
        $stmt->execute([$id_proposta, $id_utente]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = 'Sei già prenotato per questa proposta.';
            return $response->withHeader('Location', '/proposte')->withStatus(302);
        }

        // Inserisce la nuova prenotazione
        $stmt = $this->db->prepare('INSERT INTO prenotazioni (id_proposta, id_utente) VALUES (?, ?)');
        $stmt->execute([$id_proposta, $id_utente]);

        $_SESSION['success'] = 'Prenotazione effettuata con successo!';
        return $response->withHeader('Location', '/proposte')->withStatus(302);
    }
}
