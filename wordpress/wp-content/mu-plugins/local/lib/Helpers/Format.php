<?php

namespace Local\Helpers;

class Format
{
    /**
     * Prepares phone number to be inserted into href/
     * e.g.: +7 (903) 000-00-00 -> +79030000000
     *
     * @param string $phone
     * @return string
     */
    public static function phone_href(string $phone): string
    {
        return 'tel:' . preg_replace('/(?!\+)\D+/', '', trim($phone));
    }

    /**
     * Returns video id from the youtube video url.
     *
     * @param string $url
     * @return string|null
     */
    public static function youtube_id(string $url): ?string
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $res);
        return $res['v'];
    }

    /**
     * Formats price.
     *
     * @param string $price
     * @param string $currency
     * @return string
     */
    public static function price(string $price, string $currency = '₽'): string
    {
        if (!is_numeric($price)) {
            return $price;
        }

        $price = floatval($price);
        return number_format($price, 0, ',', '&nbsp') . '&nbsp' . $currency;
    }

    /**
     * Formats price without spaces.
     *
     * @param string $price
     * @param string $currency
     * @return string
     */
    public static function short_price(string $price, string $currency = '₽'): string
    {
        if (!is_numeric($price)) {
            return $price;
        }

        $price = floatval($price);
        return number_format($price, 0, ',', '') . $currency;
    }
}