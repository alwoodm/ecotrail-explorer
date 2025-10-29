# EcoTrail Explorer

Projekt edukacyjny przygotowany w ramach zajęć, rozbudowa prostego szablonu PHP o funkcjonalny serwis z kilkoma podstronami. Wszystkie osoby, dane kontaktowe, trasy i historie opisane w projekcie są całkowicie fikcyjne i służą wyłącznie celom demonstracyjnym.

## Wymagania
- PHP 8.1+ z aktywnymi rozszerzeniami `pdo_sqlite` i `sqlite3`
- SQLite3 (zwykle wbudowane w PHP)
- Opcjonalnie Docker oraz Docker Compose

> **Uwaga:**
> - Na Windowsie włącz `pdo_sqlite`/`sqlite3` w `php.ini` (usuń średnik przed `extension=pdo_sqlite` i `extension=sqlite3`).
> - Na macOS/Linux upewnij się, że PHP zostało skompilowane z obsługą SQLite lub doinstaluj pakiet (`sudo apt install php8.2-sqlite3`, `brew install php@8.2`, itp.).

## Setup projektu

```bash
git clone https://github.com/alwoodm/ecotrail-explorer.git
cd ecotrail-explorer
```

> ### Formularz kontaktowy
> - Integracja korzysta z Web3Forms. Przed wysłaniem na produkcję w pliku `templates/contact.html` odkomentuj linijkę z `access_key` i wstaw własny klucz. Bez tego formularz nie prześle żadnych danych. Klucz można uzyskać bezpłatnie na [web3forms.com](https://web3forms.com/).

### Szybki start (lokalnie)
1. Zainicjalizuj bazę danych:
   ```bash
   php app/init.php
   ```
2. Uruchom wbudowany serwer PHP (możesz wybrać inny port):
   ```bash
   php -S 127.0.0.1:8000
   ```
3. Otwórz przeglądarkę pod adresem `http://127.0.0.1:8000`.

### Szybki start (Docker)
1. Dostosuj mapowanie portów w `docker-compose.yml` (domyślnie `8080:80`). Lewe `8080` to port hosta – możesz podać własny numer.
2. Zbuduj i odpal kontener:
   ```bash
   docker-compose build
   docker-compose up -d
   ```
3. Aplikacja będzie dostępna pod `http://127.0.0.1:8080` (zmień 8080, jeśli potrzeba).
4. Kontener sam uruchomi `app/init.php`, gdy `database.db` nie istnieje.


## Licencja
Projekt na licencji [MIT](LICENSE).
