# Gestione Centro Sportivo (PHP + Slim Framework)

## Requisiti

- PHP >= 8.0
- Composer
- MySQL
- XAMPP o equivalente (Apache + MySQL)
- Librerie installate con Composer (Slim, PHPMailer, Dotenv, OAuth2)


## ISTRUZIONI INSTALLAZIONE  


1. **Posizionare il progetto**
   - Copiare tutta la cartella `gestione_centro_sportivo/` dentro `htdocs/` di XAMPP.

2. **Configurazione ambiente**
   - Copiare `.env.example` e rinominarlo `.env`.
   - Modificare `.env` con i tuoi dati MySQL e credenziali Google OAuth.

3. **Installazione dipendenze**
   ```bash
   composer install


## Utenti di esempio ##  
**Ruolo | Email | Password**  
Gestore | admin@centrosportivo.it | password123
Atleta 1 | atleta1@esempio.it | password123
Atleta 2 | atleta2@esempio.it | password123