<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Dotenv\Dotenv;
use App\Core\Database;
use App\Core\Auth;

// Carica variabili ambiente
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Avvia la sessione
session_start();

// Istanzia l'app Slim
$app = AppFactory::create();

// Middleware per parsing body richieste
$app->addBodyParsingMiddleware();

// Includi le rotte
(require __DIR__ . '/../src/routes.php')($app);

// Avvia l'applicazione
$app->run();
