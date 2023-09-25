<?php

namespace Local;

use Extended\ACF\Fields\Textarea;
use Extended\ACF\Location;

class Options
{
    const OPTIONS_MENU_SLUG = 'options';

    const OPTIONS_HEAD_CODE_SNIPPET = 'head_code_snippet';
    const OPTIONS_BODY_CODE_SNIPPET = 'body_code_snippet';

    public static function wp_init(): void
    {
        add_filter('init', [self::class, 'register_options']);
        add_filter('init', [self::class, 'register_fields']);
    }

    public static function register_options(): void
    {
        acf_add_options_page(
            [
                'page_title' => 'Настройки сайта',
                'menu_title' => 'Настройки сайта',
                'menu_slug' => self::OPTIONS_MENU_SLUG,
                'capability' => 'edit_posts',
                'redirect' => false,
                'position' => 7,
            ]
        );
    }

    public static function register_fields(): void
    {
        register_extended_field_group([
            'title' => 'Настройки',
            'key' => 'site_options',
            'position' => 'acf_after_title',
            'style' => 'default',
            'location' => [
                Location::where('options_page', self::OPTIONS_MENU_SLUG),
            ],
            'fields' => [
                Textarea::make('Произвольный код в конец <code>head</code>', self::OPTIONS_HEAD_CODE_SNIPPET)
                    ->rows(10)
                    ->instructions('Может использоваться для вставки кода счетчиков'),
                Textarea::make('Произвольный код в конец <code>body</code>', self::OPTIONS_BODY_CODE_SNIPPET)
                    ->rows(10)
                    ->instructions('Может использоваться для вставки кода счетчиков'),
            ],
        ]);
    }

    public static function get(string $option, $default = null)
    {
        return get_field($option, 'option') ?? $default;
    }
}