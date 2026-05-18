# 🍽️ Webová aplikace Jídelna (Ročníkový projekt)

Tento repozitář obsahuje zdrojové kódy pro webovou aplikaci školní/firemní jídelny. Projekt vznikl jako ročníková práce do předmětu Programování. Cílem aplikace je digitalizovat proces objednávání a výdeje jídel.

## 🌐 Živá ukázka (Live Demo)

Aplikaci si můžete reálně vyzkoušet zde: [script](objednavkovy-system.free.nf)
## ✨ Hlavní funkce

Systém je rozdělen pro 3 typy uživatelů, z nichž každý má specifické rozhraní a práva:

### 👤 Běžný uživatel (Strávník)
* Registrace a přihlášení do systému.
* Zobrazení aktuální nabídky jídel.
* Objednání jídla a správa vlastních objednávek.
* Možnost přidání/dobití kreditu na účet.

### 🧑‍🍳 Kuchař (Rozhraní kuchyně)
* Přehled všech aktivních objednávek v reálném čase.
* Zobrazení detailů: jméno strávníka, čas objednávky a název jídla.
* Správa stavu objednávky (Průchod fázemi: *Přijato -> V přípravě -> K vydání -> Vyzvednuto*).

### 👑 Administrátor (Admin panel)
* **Přehled a statistiky:** Celkový obrat systému, žebříček nejprodávanějších jídel.
* **Správa jídelního lístku:** Přidávání nových jídel a odstraňování stávajících z nabídky.
* **Správa uživatelů:** Přehled všech registrovaných uživatelů v systému.

## 🛠️ Použité technologie (Tech Stack)

* **Frontend:** HTML, CSS (čisté CSS bez frameworku)
* **Backend:** PHP 
* **Databáze:** MySQL

## 📁 Struktura projektu (Project Structure)

Projekt je pro zjednodušení navržen s plochou strukturou (všechny soubory v kořenovém adresáři):

```text
jidelna/
├── admin.php                  # Rozhraní pro administrátora
├── db.php                     # Připojení k databázi (PDO/MySQLi)
├── index.php                  # Hlavní stránka (nabídka jídel pro strávníky)
├── kosik.php                  # Zobrazení aktuálního nákupního košíku
├── kuchyne.php                # Rozhraní pro kuchaře (správa stavu objednávek)
├── login.php                  # Přihlášení uživatelů
├── logout.php                 # Odhlášení
├── nabit_kredit.php           # Formulář/logika pro dobití kreditu
├── odstranit_z_kosiku.php     # Back-end skript pro smazání položky z košíku
├── pridat_do_kosiku.php       # Back-end skript pro přidání jídla do košíku
├── pridat_jidlo.php           # Back-end skript pro vytvoření nového jídla
├── register.php               # Registrace nových uživatelů
├── style.css                  # Kaskádové styly pro celou aplikaci
├── upravit_kosik.php          # Back-end skript pro změnu množství v košíku
├── uspech.php                 # Stránka zobrazená po úspěšné akci (např. objednávce)
├── vysypat_kosik.php          # Back-end skript pro vymazání obsahu košíku
├── zpracovat_objednavku.php   # Back-end skript pro finální zápis objednávky do DB
└── .gitignore                 # Ignorované soubory pro Git (např. .DS_Store)
```
## 🎨 Design a ukázka aplikace

Zde je rychlý náhled hlavních obrazovek systému. Aplikaci si můžeš vyzkoušet i naživo na: [script](objednavkovy-system.free.nf)

### Hlavní stránka (Strávník)
<img width="1637" height="932" alt="image" src="https://github.com/user-attachments/assets/5a876808-69ef-45a9-8037-cc2b65936997" />


### Rozhraní kuchyně
<img width="1637" height="931" alt="image" src="https://github.com/user-attachments/assets/534199bc-1d4d-43e4-88c0-4b51eb5676c9" />


### Administrátorský panel
<img width="1637" height="838" alt="image" src="https://github.com/user-attachments/assets/5550c6be-be2f-4126-a1d5-7f4c527ee077" />

<img width="1637" height="723" alt="image" src="https://github.com/user-attachments/assets/39704ea8-ec5e-4acf-9578-49436e290cb0" />

<img width="1637" height="930" alt="image" src="https://github.com/user-attachments/assets/b13d74c8-f7b6-46e8-a8ed-be100f6203c5" />



