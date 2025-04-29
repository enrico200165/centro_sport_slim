<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Dotenv\Dotenv;
use App\Core\Database;
use App\Core\Auth;


/**
 * Legge le credenziali Google OAuth da file e imposta le variabili di ambiente
 *
 * @param string $filePath Percorso al file JSON delle credenziali
 * @return array|null Array delle credenziali, o null se errore
 */
function loadGoogleCredentials(string $filePath): ?array
{
    if (!file_exists($filePath)) {
        error_log('File delle credenziali non trovato: ' . $filePath);
        return null;
    }

    $jsonContents = file_get_contents($filePath);
    $credentials = json_decode($jsonContents, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('Errore nella decodifica JSON: ' . json_last_error_msg());
        return null;
    }

    if (!isset($credentials['web'])) {
        error_log('Formato file non valido: chiave "web" mancante.');
        return null;
    }

    $web = $credentials['web'];

    // Imposta variabili di ambiente
    if (isset($web['client_id'])) {
        putenv('GOOGLE_CLIENT_ID=' . $web['client_id']);
    }

    if (isset($web['client_secret'])) {
        putenv('GOOGLE_CLIENT_SECRET=' . $web['client_secret']);
    }

    if (isset($web['redirect_uris'][0])) {
        putenv('GOOGLE_REDIRECT_URI=' . $web['redirect_uris'][0]);
    }

    return $credentials;
}


loadGoogleCredentials(getenv('GDRIVE_ENRICO200165_HOME') . "\\08_dev_gdrive\\configs\\Glogin\\social-login-example-prj.json");


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
