<?php

namespace Database\Seeders;

use Aurora\Enums\PageStatus;
use Aurora\Enums\RevisionSource;
use Aurora\Models\Layout;
use Aurora\Models\Page;
use Aurora\Models\User;
use Aurora\Support\BlockTranslations;
use Aurora\Support\BlockTreeBuilder;
use Aurora\Support\ContentLocale;
use Aurora\Support\RevisionContext;
use Closure;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * A first homepage for a new website, published, after php artisan aurora:install: a welcome
 * card in the spirit of Laravel's, the website's own Welcome block (app/Blocks/WelcomeBlock.php)
 * on the blank layout, to edit in the visual editor straight away. Written in English and Norwegian:
 * in the site's default language, with the other as its translation when the site has it.
 * Nothing happens when the site has a homepage.
 */
class HomepageSeeder extends Seeder
{
    /**
     * Aurora's documentation.
     */
    protected const DOCS_URL = 'https://shipaurora.com/docs';

    /**
     * The homepage's text in Norwegian: English => Norwegian.
     *
     * @var array<string, string>
     */
    protected const NORWEGIAN = [
        'Home' => 'Hjem',
        'home' => 'hjem',
        'A new website, built with Aurora.' => 'Et nytt nettsted, laget med Aurora.',
        'Let\'s get started' => 'La oss komme i gang',
        'Your new website runs on Aurora, a content management system for Laravel. We suggest starting with the following.' => 'Det nye nettstedet ditt kjører på Aurora, et publiseringssystem for Laravel. Vi foreslår at du begynner med dette.',
        "Sign in at /admin with the account you just created\nEdit this page in the visual editor\nSee how this page's Welcome block is made, in app/Blocks/WelcomeBlock.php\nMake the layout in resources/views/vendor/aurora/layouts your own\nAdd your own blocks with php artisan aurora:make-block" => "Logg inn på /admin med kontoen du nettopp lagde\nRediger denne siden i den visuelle editoren\nSe hvordan sidens Welcome-blokk er laget, i app/Blocks/WelcomeBlock.php\nGjør oppsettet i resources/views/vendor/aurora/layouts til ditt eget\nLag dine egne blokker med php artisan aurora:make-block",
        'Open the admin' => 'Åpne admin',
        'Read the docs' => 'Dokumentasjon',
        'Content management for Laravel' => 'Publiseringssystem for Laravel',
    ];

    public function run(): void
    {
        if (Page::query()->where('is_homepage', true)->exists()) {
            return;
        }

        // The page is written in the site's language; the other one becomes its translation.
        $default = ContentLocale::default();
        $base = $default === 'nb' ? 'nb' : 'en';
        $translations = array_values(array_intersect(['en', 'nb'], array_keys(ContentLocale::translations())));
        $languages = [$default => $base, ...array_combine($translations, $translations)];

        // The same block in every language: its id is fixed, its text differs.
        $id = (string) Str::uuid();
        $blocks = BlockTreeBuilder::flatten($this->blocks($this->in($base), $id));
        $author = User::query()->oldest()->first();

        RevisionContext::as(RevisionSource::System, 'Created the homepage', fn () => Page::query()->create([
            'title' => array_map(fn (string $language): string => $this->in($language)('Home'), $languages),
            'slug' => array_map(fn (string $language): string => $this->in($language)('home'), $languages),
            'excerpt' => array_map(fn (string $language): string => $this->in($language)('A new website, built with Aurora.'), $languages),
            'layout_id' => Layout::query()->where('handle', 'blank')->value('id'),
            'status' => PageStatus::Published,
            'published_at' => now(),
            'is_homepage' => true,
            'author_id' => $author?->getKey(),
            'last_edited_by_id' => $author?->getKey(),
            'blocks' => $blocks,
            'block_translations' => collect($translations)
                ->mapWithKeys(fn (string $locale): array => [$locale => $this->translationOf($blocks, BlockTreeBuilder::flatten($this->blocks($this->in($locale), $id)))])
                ->all(),
        ]));
    }

    /**
     * The page's one block, the Welcome block, with its text in one language.
     *
     * @param  Closure(string): string  $text  the homepage's text in one language
     * @return array<int, array<string, mixed>>
     */
    protected function blocks(Closure $text, string $id): array
    {
        return [
            ['id' => $id, 'type' => 'welcome', 'data' => [
                'title' => $text('Let\'s get started'),
                'text' => $text('Your new website runs on Aurora, a content management system for Laravel. We suggest starting with the following.'),
                'steps' => $text("Sign in at /admin with the account you just created\nEdit this page in the visual editor\nSee how this page's Welcome block is made, in app/Blocks/WelcomeBlock.php\nMake the layout in resources/views/vendor/aurora/layouts your own\nAdd your own blocks with php artisan aurora:make-block"),
                'primary_label' => $text('Open the admin'),
                'primary_type' => 'url',
                'primary_url' => '/admin',
                'secondary_label' => $text('Read the docs'),
                'secondary_type' => 'url',
                'secondary_url' => self::DOCS_URL,
                'name' => 'Aurora',
                'tagline' => $text('Content management for Laravel'),
            ]],
        ];
    }

    /**
     * The homepage's text in a language: English as it is, Norwegian from the list above.
     *
     * @return Closure(string): string
     */
    protected function in(string $language): Closure
    {
        return fn (string $english): string => $language === 'nb' ? (self::NORWEGIAN[$english] ?? $english) : $english;
    }

    /**
     * Another language's text for the page's blocks: each translatable field that differs.
     *
     * @param  array<int, array<string, mixed>>  $blocks
     * @param  array<int, array<string, mixed>>  $translated  the same blocks, in the other language
     * @return array<string, array<string, string>> block id => field => text
     */
    protected function translationOf(array $blocks, array $translated): array
    {
        $fields = [];

        foreach ($translated as $index => $block) {
            foreach (BlockTranslations::fieldsOf($block['type']) as $field) {
                $text = $block['data'][$field] ?? null;

                if (is_string($text) && $text !== ($blocks[$index]['data'][$field] ?? null)) {
                    $fields[$block['id']][$field] = $text;
                }
            }
        }

        return $fields;
    }
}
