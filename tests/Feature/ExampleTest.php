<?php

use Aurora\Database\Seeders\EssentialSeeder;
use Aurora\Models\Locale;
use Aurora\Models\Page;
use Database\Seeders\HomepageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a new website shows its seeded homepage: the Welcome block, on the blank layout', function () {
    $this->seed([EssentialSeeder::class, HomepageSeeder::class]);

    $this->get('/')
        ->assertOk()
        ->assertSee("Let's get started")
        ->assertSee('Open the admin')
        ->assertSee('favicon.svg')
        ->assertSee('brand-mark.svg')
        ->assertDontSee('site-footer');

    $page = Page::query()->sole();

    expect($page)
        ->is_homepage->toBeTrue()
        ->isLive()->toBeTrue()
        ->and($page->layout->handle)->toBe('blank')
        ->and(array_column($page->blocks, 'type'))->toBe(['welcome']);
});

test('the homepage is in Norwegian too when the site has it', function () {
    $this->seed(EssentialSeeder::class);
    Locale::query()->create(['code' => 'nb', 'name' => 'Norsk bokmål', 'prefix' => 'nb', 'is_active' => true, 'sort' => 2]);
    $this->seed(HomepageSeeder::class);

    $this->get('/nb')
        ->assertOk()
        ->assertSee('La oss komme i gang')
        ->assertSee('Åpne admin');

    expect(Page::query()->sole()->getTranslation('title', 'nb'))->toBe('Hjem');
});

test('the homepage is only seeded once', function () {
    $this->seed([EssentialSeeder::class, HomepageSeeder::class, HomepageSeeder::class]);

    expect(Page::query()->count())->toBe(1);
});

test('installing the CMS with --seed creates the homepage', function () {
    $this->artisan('aurora:install', [
        '--no-interaction' => true,
        '--seed' => true,
        '--site-name' => 'Acme',
        '--language' => 'en',
        '--name' => 'Ada Admin',
        '--email' => 'ada@example.com',
        '--password' => 'secret-pass',
    ])->assertSuccessful();

    expect(Page::query()->where('is_homepage', true)->sole())
        ->isLive()->toBeTrue()
        ->author->email->toBe('ada@example.com');
});
