<?php

namespace App\Blocks;

use Aurora\Blocks\Block;
use Aurora\Blocks\Editable;
use Aurora\Blocks\Fields;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

/**
 * Welcome: the new website's first homepage, a card with where to start beside Aurora's mark.
 * An example of a block of the website's own: this class says what editors can change (its
 * fields), its view (resources/views/components/blocks/welcome.blade.php) how it looks.
 */
class WelcomeBlock extends Block
{
    public static function type(): string
    {
        return 'welcome';
    }

    public static function label(): string
    {
        return __('Welcome');
    }

    public static function description(): string
    {
        return __('A welcome card on the night sky: where to start, with two buttons, beside Aurora’s mark.');
    }

    public static function icon(): ?string
    {
        return 'phosphor-hand-waving';
    }

    /**
     * Its picture beside it in the visual editor's block list: a screenshot of it on a page.
     */
    public static function preview(): ?string
    {
        return asset('images/blocks/welcome.webp');
    }

    public static function category(): string
    {
        return 'layout';
    }

    /**
     * What a freshly dropped block starts with: enough to look finished on the canvas.
     */
    public static function defaults(): array
    {
        return [
            'title' => 'Let\'s get started',
            'text' => 'Your new website runs on Aurora, a content management system for Laravel.',
            'steps' => "Sign in at /admin\nEdit this page in the visual editor",
            'primary_label' => 'Open the admin',
            'primary_type' => 'url',
            'primary_url' => '/admin',
            'secondary_label' => 'Read the docs',
            'secondary_type' => 'url',
            'secondary_url' => 'https://shipaurora.com/docs',
            'name' => 'Aurora',
            'tagline' => 'Content management for Laravel',
        ];
    }

    /**
     * Fields typed straight onto the page in the visual editor (@editable in the view).
     */
    public static function editables(): array
    {
        return [
            'title' => Editable::text()->placeholder(__('Title')),
            'text' => Editable::text()->multiline()->placeholder(__('Text')),
            'primary_label' => Editable::text()->placeholder(__('Button')),
            'secondary_label' => Editable::text()->placeholder(__('Button')),
            'name' => Editable::text()->placeholder(__('Name')),
            'tagline' => Editable::text()->placeholder(__('Tagline')),
        ];
    }

    /**
     * For AI assistants composing pages: when to use it and what good content looks like.
     */
    public static function aiHints(): ?string
    {
        return 'The first thing on a new site\'s homepage, on its own on the blank layout. title is short; text is one or two sentences; steps is one short instruction per line; name and tagline sit under the mark.';
    }

    public static function schema(): array
    {
        return [
            TextInput::make('title')->label(__('Title'))->maxLength(120),
            Textarea::make('text')->label(__('Text'))->rows(3),
            Textarea::make('steps')->label(__('Steps'))->rows(4)->helperText(__('One per line, each with a check mark.')),
            Section::make(__('Main button'))->compact()->schema([
                TextInput::make('primary_label')->label(__('Label')),
                ...Fields::link('primary', 'Links to'),
            ]),
            Section::make(__('Second button'))->compact()->schema([
                TextInput::make('secondary_label')->label(__('Label')),
                ...Fields::link('secondary', 'Links to'),
            ]),
            Section::make(__('Beside the mark'))->compact()->schema([
                TextInput::make('name')->label(__('Name'))->maxLength(60),
                TextInput::make('tagline')->label(__('Tagline'))->maxLength(120),
            ]),
        ];
    }
}
