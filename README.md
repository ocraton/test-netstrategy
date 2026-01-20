## Test Netstrategy


1. Far partire i container con:
```bash
sail up -d
```


2. Database & Seeder (Resetta il DB e crea gli utenti Mario e Luigi + Eventi):
```bash
sail art migrate:fresh --seed
```

3. Frontend (Compila gli asset Vue/Tailwind):
```bash
sail npm install
sail npm run dev
```

Scheduler (Opzionale): In un nuovo terminale, lancia:
```bash
sail art schedule:work
```

Oppure esegui manualmente per pulire le code "zombie"
```bash
sail art queue:clean-expired
```

### Scenario test

Passo 1: L'Acquisto (Mario)
Apri il browser e vai su http://localhost.

Login con:

User: mario@test.com
Pass: password

Nella Dashboard, trova Taylor Swift e clicca su "Acquista Biglietto".
Risultato: Entri direttamente nella pagina di Checkout.
Nota il Timer in alto (2:00) che scorre.
Sei ora l'unico utente ammesso nel sistema.


Passo 2: La Coda (Luigi)
Apri una finestra di Navigazione in Incognito (o un altro browser).

Login con:
User: luigi@test.com
Pass: password

Clicca su "Acquista Biglietto" per lo stesso evento (Taylor Swift).
Risultato: Vieni reindirizzato alla Sala d'Attesa.
Messaggio: "Sei il prossimo! Tieniti pronto..."
Il sistema fa polling ogni 3 secondi per controllare se Mario ha finito.


Passo 3: Acquisizione posizione acquisto
Torna sulla finestra di Mario (Checkout).
Clicca sul pulsante verde "Paga e Concludi".
Mario viene reindirizzato alla Dashboard (Acquisto completato, posto liberato).
Osserva subito la finestra di Luigi.
Risultato: Entro pochi secondi, la pagina si aggiorna automaticamente e Luigi entra nel Checkout.
