<?php
declare(strict_types=1);

require_once __DIR__ . '/ToArrayInterface.php';
require_once __DIR__ . '/Site.php';
require_once __DIR__ . '/DataTemplate.php';
require_once __DIR__ . '/File.php';
require_once __DIR__ . '/ParseTemplate.php';
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/DataBase.php';

const TEMPLATE_PATH = __DIR__ . '/../templates/';
const PARTIAL_PATH = TEMPLATE_PATH . 'partials/';
const DATABASE_PATH = __DIR__ . '/../database.db';

function appDatabase(): DataBase {
    static $database = null;
    if ($database === null) {
        $database = new DataBase('sqlite:' . DATABASE_PATH);
    }
    return $database;
}

function renderPage(string $slug): void {
    $database = appDatabase();
    $site = $database->getSiteBySlug($slug);

    if ($site === null) {
        renderNotFound($slug);
        return;
    }

    $pageTokens = preparePageTokens($site, $database);
    $pageHtml = renderTemplate($site->template, $pageTokens);

    $navigationSites = $database->getData('SELECT * FROM site ORDER BY navigation_order', Site::class);

    $layoutTokens = [
        'pageTitle' => escape($site->title),
        'metaDescription' => escape($site->description),
        'metaKeywords' => escape($site->keywords),
        'navigation' => renderNavigation($navigationSites, $site->slug),
        'content' => $pageHtml,
        'currentYear' => date('Y'),
        'siteTitle' => 'EcoTrail Explorer',
    ];

    $layoutHtml = renderTemplate('layout.html', $layoutTokens);
    (new Response($layoutHtml))->send();
}

function renderTemplate(string $templateName, array $tokens): string {
    $file = new File(TEMPLATE_PATH . $templateName);
    $parser = new ParseTemplate($file);
    return $parser->parse(new DataTemplate($tokens));
}

function renderPartial(string $partialName, array $tokens): string {
    $file = new File(PARTIAL_PATH . $partialName);
    $parser = new ParseTemplate($file);
    return $parser->parse(new DataTemplate($tokens));
}

function renderNotFound(string $slug): void {
    $database = appDatabase();

    $content = renderTemplate('404.html', [
        'requestedSlug' => escape($slug),
    ]);

    $navigationSites = $database->getData('SELECT * FROM site ORDER BY navigation_order', Site::class);

    $layout = renderTemplate('layout.html', [
        'pageTitle' => 'Nie znaleziono strony',
        'metaDescription' => 'Żądana podstrona nie została odnaleziona.',
        'metaKeywords' => '',
        'navigation' => renderNavigation($navigationSites, 'home'),
        'content' => $content,
        'currentYear' => date('Y'),
        'siteTitle' => 'EcoTrail Explorer',
    ]);

    (new Response($layout, 404))->send();
}

function preparePageTokens(Site $site, DataBase $database): array {
    switch ($site->slug) {
        case 'home':
            return buildHomeTokens($site, $database);
        case 'destinations':
            return buildDestinationsTokens($site, $database);
        case 'tips':
            return buildTipsTokens($site, $database);
        case 'stories':
            return buildStoriesTokens($site, $database);
        case 'contact':
            return buildContactTokens($site, $database);
        default:
            return $site->toArray();
    }
}

function buildHomeTokens(Site $site, DataBase $database): array {
    $highlights = $database->getRows('SELECT title, description, cta_label, cta_target FROM highlight ORDER BY position');
    $stories = array_slice($database->getRows('SELECT title, author, excerpt FROM story ORDER BY published_at DESC'), 0, 3);

    return [
        'heroTitle' => escape($site->hero_title ?: 'Ekologiczne wyprawy bez kompromisów'),
        'heroSubtitle' => escape($site->hero_subtitle ?: 'Poznaj dziką naturę i zostaw jedynie ślad swoich kroków.'),
        'ctaLabel' => escape($site->cta_label ?: 'Zapisz się na wyprawę'),
        'ctaTarget' => escape($site->cta_target ?: 'index.php?site=contact#form'),
        'highlightCards' => renderList('home_highlight.html', $highlights, [
            'title' => 'title',
            'description' => 'description',
            'ctaLabel' => 'cta_label',
            'ctaTarget' => 'cta_target',
        ]),
        'storiesList' => renderStories($stories, true),
    ];
}

function buildDestinationsTokens(Site $site, DataBase $database): array {
    $excursions = $database->getRows('SELECT name, description, difficulty, tags FROM excursion ORDER BY position');

    return [
        'pageTitle' => escape($site->title),
        'pageIntro' => escape($site->description ?: 'Wybierz trasę, która pasuje do Twojego tempa i doświadczenia.'),
        'destinationCards' => renderList('destination_card.html', $excursions, [
            'name' => 'name',
            'description' => 'description',
            'difficulty' => 'difficulty',
            'tags' => 'tags',
        ]),
    ];
}

function buildTipsTokens(Site $site, DataBase $database): array {
    $tips = $database->getRows('SELECT category, tip FROM tip ORDER BY category, position');

    return [
        'pageTitle' => escape($site->title),
        'tipsList' => renderList('tip_item.html', $tips, [
            'category' => 'category',
            'tip' => 'tip',
        ]),
    ];
}

function buildStoriesTokens(Site $site, DataBase $database): array {
    $stories = $database->getRows('SELECT title, author, excerpt FROM story ORDER BY published_at DESC');

    return [
        'pageTitle' => escape($site->title),
        'storiesList' => renderStories($stories, false),
    ];
}

function buildContactTokens(Site $site, DataBase $database): array {
    $contactEntries = $database->getRows('SELECT field, value FROM contact ORDER BY position');

    return [
        'pageTitle' => escape($site->title),
        'contactDetails' => renderList('contact_detail.html', $contactEntries, [
            'field' => 'field',
            'value' => 'value',
        ]),
        'formHeading' => escape('Skontaktuj się z nami'),
    ];
}

function renderStories(array $stories, bool $compact): string {
    if (empty($stories)) {
        return '';
    }

    $partial = $compact ? 'home_story.html' : 'story_card.html';
    $items = [];

    foreach ($stories as $story) {
        $items[] = renderPartial($partial, [
            'title' => escape($story['title'] ?? ''),
            'author' => escape($story['author'] ?? ''),
            'excerpt' => escape($story['excerpt'] ?? ''),
        ]);
    }

    return implode(PHP_EOL, $items);
}

function renderList(string $partial, array $rows, array $map): string {
    if (empty($rows)) {
        return '';
    }

    $items = [];
    foreach ($rows as $row) {
        $tokens = [];
        foreach ($map as $token => $column) {
            $tokens[$token] = escape($row[$column] ?? '');
        }
        $items[] = renderPartial($partial, $tokens);
    }

    return implode(PHP_EOL, $items);
}

function renderNavigation(array $sites, string $activeSlug): string {
    if (empty($sites)) {
        return '';
    }

    $items = [];
    foreach ($sites as $site) {
        $items[] = renderPartial('navigation_item.html', [
            'activeClass' => $site->slug === $activeSlug ? ' active' : '',
            'href' => escape(routeForSite($site->slug)),
            'label' => escape($site->title),
        ]);
    }
    return implode(PHP_EOL, $items);
}

function routeForSite(string $slug): string {
    return 'index.php?site=' . $slug;
}

function escape(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
