<?php

namespace Local\Helpers;

class Str
{
    /**
     * Determine if a given string contains a given substring.
     *
     * @param string $haystack
     * @param string|string[] $needles
     * @return bool
     */
    public static function contains(string $haystack, array|string $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if ($needle !== '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param string $haystack
     * @param string|string[] $needles
     * @return bool
     */
    public static function ends_with(string $haystack, array|string $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if ($needle !== '' && substr($haystack, -strlen($needle)) === (string)$needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param string $haystack
     * @param string|string[] $needles
     * @return bool
     */
    public static function starts_with(string $haystack, array|string $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if ((string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns plural form
     *
     * @param int $count
     * @param array $forms
     * @return string
     */
    public static function plural(int $count, array $forms): string
    {
        $abs = abs($count);
        $cases = [2, 0, 1, 1, 1, 2];
        return $forms[($abs % 100 > 4 && $abs % 100 < 20) ? 2 : $cases[min($abs % 10, 5)]];
    }

    public static function translitirate(string $title): array|string|null
    {
        global $wpdb;

        $iso9_table = [
            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Ѓ' => 'G',
            'Ґ' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'YO',
            'Є' => 'YE',
            'Ж' => 'ZH',
            'З' => 'Z',
            'Ѕ' => 'Z',
            'И' => 'I',
            'Й' => 'J',
            'Ј' => 'J',
            'І' => 'I',
            'Ї' => 'YI',
            'К' => 'K',
            'Ќ' => 'K',
            'Л' => 'L',
            'Љ' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'Њ' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ў' => 'U',
            'Ф' => 'F',
            'Х' => 'H',
            'Ц' => 'TS',
            'Ч' => 'CH',
            'Џ' => 'DH',
            'Ш' => 'SH',
            'Щ' => 'SHH',
            'Ъ' => '',
            'Ы' => 'Y',
            'Ь' => '',
            'Э' => 'E',
            'Ю' => 'YU',
            'Я' => 'YA',
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'ѓ' => 'g',
            'ґ' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'yo',
            'є' => 'ye',
            'ж' => 'zh',
            'з' => 'z',
            'ѕ' => 'z',
            'и' => 'i',
            'й' => 'j',
            'ј' => 'j',
            'і' => 'i',
            'ї' => 'yi',
            'к' => 'k',
            'ќ' => 'k',
            'л' => 'l',
            'љ' => 'l',
            'м' => 'm',
            'н' => 'n',
            'њ' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ў' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'ts',
            'ч' => 'ch',
            'џ' => 'dh',
            'ш' => 'sh',
            'щ' => 'shh',
            'ъ' => '',
            'ы' => 'y',
            'ь' => '',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',
        ];
        $geo2lat = [
            'ა' => 'a',
            'ბ' => 'b',
            'გ' => 'g',
            'დ' => 'd',
            'ე' => 'e',
            'ვ' => 'v',
            'ზ' => 'z',
            'თ' => 'th',
            'ი' => 'i',
            'კ' => 'k',
            'ლ' => 'l',
            'მ' => 'm',
            'ნ' => 'n',
            'ო' => 'o',
            'პ' => 'p',
            'ჟ' => 'zh',
            'რ' => 'r',
            'ს' => 's',
            'ტ' => 't',
            'უ' => 'u',
            'ფ' => 'ph',
            'ქ' => 'q',
            'ღ' => 'gh',
            'ყ' => 'qh',
            'შ' => 'sh',
            'ჩ' => 'ch',
            'ც' => 'ts',
            'ძ' => 'dz',
            'წ' => 'ts',
            'ჭ' => 'tch',
            'ხ' => 'kh',
            'ჯ' => 'j',
            'ჰ' => 'h',
        ];
        $iso9_table = array_merge($iso9_table, $geo2lat);

        $locale = get_locale();
        switch ($locale) {
            case 'bg_BG':
                $iso9_table['Щ'] = 'SHT';
                $iso9_table['щ'] = 'sht';
                $iso9_table['Ъ'] = 'A';
                $iso9_table['ъ'] = 'a';
                break;
            case 'uk':
            case 'uk_ua':
            case 'uk_UA':
                $iso9_table['И'] = 'Y';
                $iso9_table['и'] = 'y';
                break;
        }

        $title = strtr($title, $iso9_table);
        if (function_exists('iconv')) {
            $title = iconv('UTF-8', 'UTF-8//TRANSLIT//IGNORE', $title);
        }
        $title = preg_replace("/[^A-Za-z0-9'_\-\.]/", '-', $title);
        $title = preg_replace('/\-+/', '-', $title);
        $title = preg_replace('/^-+/', '', $title);
        $title = preg_replace('/-+$/', '', $title);

        /*
        $is_term = false;
        $backtrace = debug_backtrace();
        foreach ( $backtrace as $backtrace_entry ) {
            if ( $backtrace_entry['function'] == 'wp_insert_term' ) {
                $is_term = true;
                break;
            }
        }

        $term_query = $wpdb->prepare("SELECT slug FROM {$wpdb->terms} WHERE name = '%s'", $title);
        $term = $is_term ? $wpdb->get_var($term_query) : '';
        if ( empty($term) ) {
            $title = strtr($title, apply_filters('ctl_table', $iso9_table));
            if (function_exists('iconv')){
                $title = iconv('UTF-8', 'UTF-8//TRANSLIT//IGNORE', $title);
            }
            $title = preg_replace("/[^A-Za-z0-9'_\-\.]/", '-', $title);
            $title = preg_replace('/\-+/', '-', $title);
            $title = preg_replace('/^-+/', '', $title);
            $title = preg_replace('/-+$/', '', $title);
        } else {
            $title = $term;
        }
*/
        return $title;
    }
}
