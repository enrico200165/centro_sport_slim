<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Core\Database;
use App\Core\Auth;

class PropostaController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listaProposte(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!Auth::check()) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        $stmt = $this->db->query('SELECT proposte.*, centri.nome as nome_centro 
                                  FROM proposte 
                                  INNER JOIN centri ON proposte.id_centro = centri.id
                                  ORDER BY data_evento ASC');
        $proposte = $stmt->fetchAll();

        ob_start();
        include __DIR__ . '/../../views/dashboard/lista_proposte.php';
        $response->getBody()->write(ob_get_clean());
        return $response;
    }

    public function creaProposta(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!Auth::check() || Auth::user()['ruolo'] !== 'gestore') {
            return $response->withHeader('Location', '/dashboard')->withStatus(302);
        }

        $data = $request->getParsedBody();
        $titolo = $data['titolo'] ?? '';
        $descrizione = $data['descrizione'] ?? '';
        $data_evento = $data['data_evento'] ?? '';
        $max_partecipanti = $data['max_partecipanti'] ?? 10;
        $id_centro = $data['id_centro'] ?? null;

        if (empty($titolo) || empty($data_evento) || !$id_centro) {
            $_SESSION['error'] = 'Tutti i campi obbligatori devono essere compilati.';
            return $response->withHeader('Location', '/gestione-centro')->withStatus(302);
        }

        $stmt = $this->db->prepare('INSERT INTO proposte (titolo, descrizione, data_evento, id_centro, max_partecipanti) 
                                    VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$titolo, $descrizione, $data_evento, $id_centro, $max_partecipanti]);

        $_SESSION['success'] = 'Proposta creata con successo.';
        return $response->withHeader('Location', '/gestione-centro')->withStatus(302);
    }
}
