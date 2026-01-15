# Ricettario per fuori sede

## Requisiti funzionali

Utenti
- Admin: gestire utenti, post altrui
- Utente: CRUD di sue ricette e i suoi dati

Sistema di recensioni

Creazione di menù (giornalieri/settimanali/etc...) in base ai gusti, recensioni, prezzo delle ricette, tempo di esecuzione e altri tag vari (valori nutrizionali).
- Integrazione con gusti dell'utente e decisione sul momento
- Generazione automatica della lista della spesa in base al menù creato

### DB (accenni)
#### Utenti
> User
- Username
- Email
- Password
- Gusti 
- Lista di Ricette Salvate

#### Gusti
[TODO] Refine Concept
> Gusto
- Tipologia
- Positivo/Negativo

Tipologie di Gusti
- Carne
- Pesce
- Verdure
- Piccante
#### Ricette
>Ricetta
- Nome
- Descrizione
- Tempo di Preparazione (veloce-media durata-lungo)
- Numero di Persone Target
- Serie di ingredienti
- Procedimento a step

> Ingrediente
- Nome
- Quantità
- Eventuale Codice a barre (per API).

> Step procedimento
- Descrizione
- Ordine (progressivo)

#### Tag
##### Scelta Singola
> Prezzo
- Low       $
- Medium    $$
- High      $$$

> Tempo
- Short     <15 min
- Medium    15 min< x < 30 min
- Long      > 30 min

> Difficoltà
- Easy
- Medium
- Hard

##### Scelta Multipla
> Tag
- Carne
- Pesce
- Verdure
- Piccante
- Diet
- Breakfast
- Launch
- Dinner
- Snack

#### Recensioni
> Recensione
- Rating (1-5)
- Commento
- Data

## Idee
- Filtri per tempo di preparazione
- Filtri per difficoltà
- Possibilità di salvare ricette preferite
- Notifiche per nuove ricette o menù basati sui gusti dell'utente
- Funzione di ricerca avanzata con parole chiave, ingredienti, e categorie
- Chatbot per chiedere consigli culinari o suggerimenti di ricette (WOW?)
- Funzione di calcolo delle porzioni in base al numero di persone
- Sezione dedicata alle ricette per intolleranze alimentari con opzioni sicure

# Piattaforma per pubblicazione e condivisione di appunti universitari

## Requisiti funzionali

3 Livelli di categorizzazione dei post, gerarchici:
1. Facoltà (es. Matematica, Informatica, Economia)
2. Materia (es. Analisi Matematica, Fisica 1, Microeconomia)
3. Argomento (es. Integrali, Leggi di Newton, Domanda e Offerta)

3 livelli di utenti:
1. Admin: potere assoluto
2. Studente: utente normale che può leggere, pubblicare, aggiornare (?) ed eliminare (i suoi post) 
3. Verificatore: può validare i post pubblicati dagli studenti in una **MATERIA** di competenza
4. Professore: può validare i post pubblicati dagli studenti in una **FACOLTÀ** di competenza

Possibilità di fare review.


## Idee
- Preview se non registrato


