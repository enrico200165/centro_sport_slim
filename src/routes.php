<?php
use Slim\App;
use App\Controllers\AuthController;
use App\Controllers\CentroController;
use App\Controllers\PropostaController;
use App\Controllers\PrenotazioneController;

return function (App $app) {

    // Rotte di autenticazione
    $app->get('/login', AuthController::class . ':showLogin');
    $app->post('/login', AuthController::class . ':login');
    $app->get('/register', AuthController::class . ':showRegister');
    $app->post('/register', AuthController::class . ':register');
    $app->get('/logout', AuthController::class . ':logout');

    // Google OAuth login
    $app->get('/google-login', AuthController::class . ':redirectToGoogle');
    $app->get('/oauth2callback', AuthController::class . ':handleGoogleCallback');

    // Dashboard e gestione centro
    $app->get('/dashboard', CentroController::class . ':dashboard')->setName('dashboard');
    $app->get('/gestione-centro', CentroController::class . ':gestioneCentro')->setName('gestione-centro');
    $app->post('/centro/crea', CentroController::class . ':creaCentro');

    // Proposte di gioco
    $app->get('/proposte', PropostaController::class . ':listaProposte')->setName('proposte');
    $app->post('/proposte/crea', PropostaController::class . ':creaProposta');

    // Prenotazioni
    $app->post('/prenota/{id}', PrenotazioneController::class . ':prenota');

    // Home reindirizzata
    $app->get('/', function ($request, $response) {
        return $response->withHeader('Location', '/login')->withStatus(302);
    });
};
