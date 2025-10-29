<?php
declare(strict_types=1);

require_once __DIR__ . '/DataBase.php';

const DATABASE_FILE = __DIR__ . '/../database.db';

if (file_exists(DATABASE_FILE)) {
    unlink(DATABASE_FILE);
}

$database = new DataBase('sqlite:' . DATABASE_FILE);
$database->execute('PRAGMA foreign_keys = ON;');

$schemaStatements = [
    'CREATE TABLE site (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        slug TEXT NOT NULL UNIQUE,
        title TEXT NOT NULL,
        template TEXT NOT NULL,
        keywords TEXT,
        description TEXT,
        hero_title TEXT,
        hero_subtitle TEXT,
        cta_label TEXT,
        cta_target TEXT,
        navigation_order INTEGER DEFAULT 0
    )',
    'CREATE TABLE highlight (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        cta_label TEXT NOT NULL,
        cta_target TEXT NOT NULL,
        position INTEGER DEFAULT 0
    )',
    'CREATE TABLE excursion (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT NOT NULL,
        difficulty TEXT NOT NULL,
        tags TEXT NOT NULL,
        position INTEGER DEFAULT 0
    )',
    'CREATE TABLE tip (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category TEXT NOT NULL,
        tip TEXT NOT NULL,
        position INTEGER DEFAULT 0
    )',
    'CREATE TABLE story (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        author TEXT NOT NULL,
        excerpt TEXT NOT NULL,
        published_at TEXT NOT NULL
    )',
    'CREATE TABLE contact (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        field TEXT NOT NULL,
        value TEXT NOT NULL,
        position INTEGER DEFAULT 0
    )',
];

foreach ($schemaStatements as $statement) {
    $database->execute($statement);
}

$siteStatement = $database->prepare('INSERT INTO site (slug, title, template, keywords, description, hero_title, hero_subtitle, cta_label, cta_target, navigation_order) VALUES (:slug, :title, :template, :keywords, :description, :hero_title, :hero_subtitle, :cta_label, :cta_target, :navigation_order)');
$sites = [
    [
        'slug' => 'home',
        'title' => 'Strona główna',
        'template' => 'home.html',
        'keywords' => 'ekoturystyka, wyprawy, natura',
        'description' => 'EcoTrail Explorer organizuje zrównoważone wyprawy w najpiękniejsze zakątki natury.',
        'hero_title' => 'Wyrusz na szlak, który szanuje naturę',
        'hero_subtitle' => 'Dołącz do wypraw prowadzonych przez lokalnych przewodników i odkrywaj dzikie miejsca odpowiedzialnie.',
        'cta_label' => 'Poznaj nasze wyprawy',
        'cta_target' => 'index.php?site=destinations',
        'navigation_order' => 1,
    ],
    [
        'slug' => 'destinations',
        'title' => 'Destinations',
        'template' => 'destinations.html',
        'keywords' => 'trasy, góry, jeziora',
        'description' => 'Przeglądaj ekologiczne trasy dobrane do różnych poziomów doświadczenia.',
        'hero_title' => null,
        'hero_subtitle' => null,
        'cta_label' => null,
        'cta_target' => null,
        'navigation_order' => 2,
    ],
    [
        'slug' => 'tips',
        'title' => 'Trail Tips',
        'template' => 'tips.html',
        'keywords' => 'porady, ekologia, sprzęt',
        'description' => 'Praktyczne wskazówki, które ułatwią przygotowanie do każdej przygody na szlaku.',
        'hero_title' => null,
        'hero_subtitle' => null,
        'cta_label' => null,
        'cta_target' => null,
        'navigation_order' => 3,
    ],
    [
        'slug' => 'stories',
        'title' => 'Stories',
        'template' => 'stories.html',
        'keywords' => 'relacje, przygody, doświadczenia',
        'description' => 'Relacje uczestników, którzy odkryli naturę z EcoTrail Explorer.',
        'hero_title' => null,
        'hero_subtitle' => null,
        'cta_label' => null,
        'cta_target' => null,
        'navigation_order' => 4,
    ],
    [
        'slug' => 'contact',
        'title' => 'Contact',
        'template' => 'contact.html',
        'keywords' => 'kontakt, rezerwacje, wyprawy',
        'description' => 'Skontaktuj się z naszym zespołem, aby omówić szczegóły wypraw.',
        'hero_title' => null,
        'hero_subtitle' => null,
        'cta_label' => null,
        'cta_target' => null,
        'navigation_order' => 5,
    ],
];

foreach ($sites as $site) {
    $siteStatement->execute($site);
}

$highlightStatement = $database->prepare('INSERT INTO highlight (title, description, cta_label, cta_target, position) VALUES (:title, :description, :cta_label, :cta_target, :position)');
$highlights = [
    ['title' => 'Zrównoważone wyprawy', 'description' => 'Każdy program minimalizuje wpływ na środowisko i wspiera lokalne społeczności.', 'cta_label' => 'Dowiedz się więcej', 'cta_target' => 'index.php?site=tips', 'position' => 1],
    ['title' => 'Lokalni przewodnicy', 'description' => 'Współpracujemy z certyfikowanymi przewodnikami, którzy znają każdy zakątek tras.', 'cta_label' => 'Poznaj przewodników', 'cta_target' => '#', 'position' => 2],
    ['title' => 'Małe grupy', 'description' => 'Oferujemy kameralne wyprawy, aby zapewnić komfort i bezpieczeństwo uczestników.', 'cta_label' => 'Sprawdź terminy', 'cta_target' => 'index.php?site=contact', 'position' => 3],
];

foreach ($highlights as $highlight) {
    $highlightStatement->execute($highlight);
}

$excursionStatement = $database->prepare('INSERT INTO excursion (name, description, difficulty, tags, position) VALUES (:name, :description, :difficulty, :tags, :position)');
$excursions = [
    ['name' => 'Szlak Doliny Mglistej', 'description' => 'Poranny trekking przez dolinę przykrytą mgłą, zakończony warsztatami fotografii przyrodniczej.', 'difficulty' => 'Średnia', 'tags' => 'fotografia, brzask, dolina', 'position' => 1],
    ['name' => 'Granią Zielonych Tatr', 'description' => 'Dwudniowa wyprawa granią z noclegiem w schronisku zero waste.', 'difficulty' => 'Zaawansowana', 'tags' => 'góry, nocleg, zero waste', 'position' => 2],
    ['name' => 'Kajakiem po Szmaragdowym Jeziorze', 'description' => 'Spokojna trasa kajakowa z przewodnikiem, połączona z warsztatami obserwacji ptaków.', 'difficulty' => 'Łatwa', 'tags' => 'jezioro, kajak, obserwacja ptaków', 'position' => 3],
];

foreach ($excursions as $excursion) {
    $excursionStatement->execute($excursion);
}

$tipStatement = $database->prepare('INSERT INTO tip (category, tip, position) VALUES (:category, :tip, :position)');
$tips = [
    ['category' => 'Sprzęt', 'tip' => 'Wybierz buty trekkingowe z podeszwą Vibram, które sprawdzą się zarówno w deszczu, jak i na suchym skalnym podłożu.', 'position' => 1],
    ['category' => 'Sprzęt', 'tip' => 'Spakuj lekką kurtkę przeciwdeszczową wykonaną z materiałów z recyklingu.', 'position' => 2],
    ['category' => 'Ekologia', 'tip' => 'Zabierz wielorazowy bidon i filtruj wodę ze strumieni, aby ograniczyć plastik.', 'position' => 3],
    ['category' => 'Ekologia', 'tip' => 'Poruszaj się po wyznaczonych szlakach, by chronić roślinność.', 'position' => 4],
    ['category' => 'Przygotowanie', 'tip' => 'Sprawdź prognozę pogody i zgłoś trasę bliskim przed wyruszeniem.', 'position' => 5],
];

foreach ($tips as $tip) {
    $tipStatement->execute($tip);
}

$storyStatement = $database->prepare('INSERT INTO story (title, author, excerpt, published_at) VALUES (:title, :author, :excerpt, :published_at)');
$stories = [
    ['title' => 'Poranek, który zapamiętam na zawsze', 'author' => 'Marta K.', 'excerpt' => 'Widok mgły unoszącej się nad doliną sprawił, że zapomniałam o całym świecie.', 'published_at' => '2023-08-12'],
    ['title' => 'Dlaczego warto wędrować powoli', 'author' => 'Tomek L.', 'excerpt' => 'Dzięki przewodnikom poznaliśmy historie mieszkańców gór i ich sposoby na życie w rytmie natury.', 'published_at' => '2023-09-03'],
    ['title' => 'Na kajakach między nenufarami', 'author' => 'Kasia i Michał', 'excerpt' => 'Spokojne tempo, śpiew ptaków i herbata z zebranych ziół – tak wyglądał nasz dzień.', 'published_at' => '2023-10-21'],
];

foreach ($stories as $story) {
    $storyStatement->execute($story);
}

$contactStatement = $database->prepare('INSERT INTO contact (field, value, position) VALUES (:field, :value, :position)');
$contacts = [
    ['field' => 'E-mail', 'value' => 'kontakt@ecotrail-explorer.pl', 'position' => 1],
    ['field' => 'Telefon', 'value' => '+48 600 900 200', 'position' => 2],
    ['field' => 'Biuro', 'value' => 'ul. Zielona 12, 30-001 Kraków', 'position' => 3],
];

foreach ($contacts as $contact) {
    $contactStatement->execute($contact);
}

echo "Baza danych została zainicjalizowana w pliku " . DATABASE_FILE . PHP_EOL;
