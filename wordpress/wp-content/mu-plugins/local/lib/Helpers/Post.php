<?php

namespace Local\Helpers;

class Post
{
    public static function get_id_by_name(string $name, string $type): ?int
    {
        global $wpdb;
        $query = $wpdb->prepare(
            'SELECT ID FROM '
            . $wpdb->posts
            . ' WHERE post_title = %s AND post_type = %s AND post_status = "publish" ORDER BY ID DESC LIMIT 1',
            $name,
            $type
        );
        $result = $wpdb->get_results($query);
        if (isset($result[0])) {
            return $result[0]->ID;
        }

        return null;
    }
}