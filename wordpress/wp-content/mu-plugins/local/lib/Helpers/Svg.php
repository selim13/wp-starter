<?php

namespace Local\Helpers;

use DOMDocument;

class Svg
{
    /**
     * Returns svg file contents.
     *
     * @param string $path File attachment or path.
     * @param array $attrs Root svg element attributes.
     * @return string
     */
    public static function render(string $path, array $attrs = []): string
    {
        if (!file_exists($path)) {
            return sprintf('<!-- %s not found. -->', $path);
        }

        $svg = new DOMDocument();
        $svg->load($path);

        foreach ($attrs as $key => $value) {
            $svg->documentElement->setAttribute($key, $value);
        }

        return $svg->saveXML($svg->documentElement);
    }

    /**
     * Returns path to SVG image.
     *
     * @param array|string $image File attachment or path.
     * @return string
     */
    public static function get_path(array|string $image): string
    {
        if (is_array($image) && !empty($image['id'])) {
            $image = get_attached_file($image['id']);
            if (!$image) {
                return '';
            }

            return $image;
        }

        if (!is_string($image)) {
            return '';
        }

        return get_stylesheet_directory() . '/' . $image;
    }

    /**
     * Loads a svg file and returns it's dimensions.
     *
     * @param string $path SVG file path
     * @return array|null
     */
    public static function get_dimensions(string $path): ?array
    {
        $svg = @simplexml_load_file($path);
        if (!$svg) {
            return null;
        }

        $width = 0;
        $height = 0;

        $attributes = $svg->attributes();
        if (isset($attributes->width, $attributes->height)) {
            $width = floatval($attributes->width);
            $height = floatval($attributes->height);
        } elseif (isset($attributes->viewBox)) {
            $sizes = explode(' ', $attributes->viewBox);
            if (isset($sizes[2], $sizes[3])) {
                $width = floatval($sizes[2]);
                $height = floatval($sizes[3]);
            }
        } else {
            return null;
        }

        return [
            'width' => $width,
            'height' => $height
        ];
    }
}