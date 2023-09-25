<?php

namespace Local;

use Local\Helpers\Svg;

class ACF
{
    public static function wp_init(): void
    {
        add_filter('acf/format_value/type=image', [self::class, 'svg_dimensions'], 20, 3);
        add_filter('acf/fields/wysiwyg/toolbars', [self::class, 'register_toolbars']);
        add_filter('mce_external_plugins', [self::class, 'mce_external_plugins']);
        add_filter('tiny_mce_before_init', [self::class, 'tiny_mce_before_init']);
        add_action('admin_head', [self::class, 'alter_wysiwyg_height']);
        add_filter('acf/settings/load_json', [self::class, 'set_json_load_point']);
    }

    /**
     * Returns ACF fields partial.
     *
     * @param string $partial
     * @return mixed
     */
    public static function partial(string $partial): mixed
    {
        $partial = str_replace('.', '/', $partial);
        return include(get_stylesheet_directory() . "/acf/{$partial}.php");
    }

    public static function set_json_load_point(array $paths): array
    {
        unset($paths[0]);

        $upload_dir = wp_upload_dir();
        if (!empty($upload_dir['basedir'])) {
            $user_dirname = $upload_dir['basedir'] . '/acf_json_data';
            if (!file_exists($user_dirname)) {
                wp_mkdir_p($user_dirname);
            }

            $paths[] = $user_dirname;
        }

        return $paths;
    }

    /**
     * Registers TinyMCE external plugins.
     *
     * @param $plugins
     */
    public static function mce_external_plugins($plugins): void
    {
    }

    /**
     * Makes Classic editors fields a little shorter.
     */
    public static function alter_wysiwyg_height(): void
    {
        ?>
        <style>
            [data-toolbar="bare"] iframe[id^='acf-editor-'] {
                min-height: 25px;
                height: auto !important;
            }
        </style>
        <?php
    }

    /**
     * Changes Classic editor's style formats.
     *
     * @param $settings
     * @return mixed
     */
    public static function tiny_mce_before_init($settings): mixed
    {
        $style_formats = [
            ['title' => 'Заголовок', 'block' => 'h2'],
            ['title' => 'Заголовок 2', 'block' => 'h3'],
            ['title' => 'Обычный текст', 'block' => 'p'],
        ];
        $settings['style_formats'] = json_encode($style_formats);

        return $settings;
    }

    /**
     * Adds or changes Classic Wordpress Editor toolbars.
     *
     * @param $toolbars
     * @return mixed
     */
    public static function register_toolbars($toolbars): mixed
    {
        $toolbars['Bare'] = [];
        $toolbars['Bare'][1] = [
            'bold',
            'italic',
            'link',
            'subscript',
            'undo',
            'redo',
            'fullscreen',
            'pastetext',
        ];

        return $toolbars;
    }

    /**
     * Fills in svg dimensions to the ACF image fields.
     *
     * @param mixed $value The field value
     * @param int|string $post_id The post ID where the value is saved
     * @param array $field The field array containing all settings
     * @returns array
     */
    public static function svg_dimensions($value, int|string $post_id, array $field)
    {
        if (is_array($value) && $value['mime_type'] === 'image/svg+xml') {
            $dimensions = Svg::get_dimensions(get_attached_file($value['id']));

            if ($dimensions) {
                $value['width'] = $dimensions['width'];
                $value['height'] = $dimensions['height'];
            }
        }

        return $value;
    }
}