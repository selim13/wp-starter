<?php

namespace Local;

use WP_Block_Editor_Context;
use WP_Block_Type_Registry;
use WP_Post;

/**
 * Gutenberg configuration.
 *
 * @package Local
 */
class Gutenberg
{
    public static function wp_init()
    {
        add_filter(
            'use_block_editor_for_post_type',
            [self::class, 'use_block_editor_for_post_type'],
            10,
            2
        );
        add_filter(
            'use_block_editor_for_post',
            [self::class, 'use_block_editor_for_post'],
            10,
            2
        );
        add_filter(
            'allowed_block_types_all',
            [self::class, 'exclude_block_types'],
            10,
            2
        );
        add_filter(
            'render_block',
            [self::class, 'render_block'],
            10,
            2
        );
    }

    /**
     * Disables the Gutenberg editor all post types
     * except listed in $gutenberg_supported_types.
     *
     * @param bool $can_edit Whether to use the Gutenberg editor.
     * @param string $post_type Name of WordPress post type.
     * @return bool $can_edit
     */
    public static function use_block_editor_for_post_type(bool $can_edit, string $post_type): bool
    {
        $gutenberg_supported_types = ['post'];

        if (!in_array($post_type, $gutenberg_supported_types, true)) {
            $can_edit = false;
        }

        return $can_edit;
    }

    /**
     * Reenables Gutenberg for specific templates.
     *
     * @param bool $can_edit Whether the post can be edited or not
     * @param WP_Post $post The post being checked
     * @return bool
     */
    public static function use_block_editor_for_post(bool $can_edit, WP_Post $post): bool
    {
        if (
            $post->post_type === 'page'
            && get_page_template_slug($post->ID) === 'templates/block-editor-gutenberg.php'
        ) {
            $can_edit = true;
        }

        return $can_edit;
    }

    /**
     * Blacklists some blocks from the editor.
     *
     * @param bool|string[] $allowed_blocks Array of block type slugs, or boolean to enable/disable all.
     * @param WP_Block_Editor_Context $block_editor_context The current block editor context.
     * @return bool|string[]
     */
    public static function exclude_block_types(
        array|bool $allowed_blocks,
        WP_Block_Editor_Context $block_editor_context
    ): array|bool {
        $excluded_blocks = [
            'core/legacy-widget',
            'core/loginout',
            'core/page-list',
            'core/post-content',
            'core/post-date',
            'core/post-excerpt',
            'core/post-featured-image',
            'core/post-terms',
            'core/post-title',
            'core/post-template',
            'core/query',
            'core/query-pagination',
            'core/query-pagination-next',
            'core/query-pagination-numbers',
            'core/query-pagination-previous',
            'core/query-title',
            'core/site-tagline',
            'core/site-logo',
            'core/site-title',
//            'core/paragraph',
//            'core/image',
//            'core/heading',
//            'core/gallery',
//            'core/list',
//            'core/quote',
            'core/shortcode',
            'core/archives',
            'core/audio',
            'core/button',
            'core/buttons',
            'core/calendar',
            'core/categories',
            'core/code',
//            'core/columns',
            'core/column',
            'core/cover',
//            'core/embed',
            'core/file',
            'core/group',
            'core/freeform',
            'core/html',
            'core/media-text',
            'core/latest-comments',
            'core/latest-posts',
            'core/missing',
            'core/more',
            'core/nextpage',
            'core/preformatted',
            'core/pullquote',
            'core/rss',
            'core/search',
//            'core/separator',
            'core/block',
            'core/social-links',
            'core/social-link',
//            'core/spacer',
            'core/subhead',
//            'core/table',
            'core/tag-cloud',
            'core/text-columns',
            'core/verse',
//            'core/video',
            'yoast-seo/breadcrumbs'
        ];

        $block_registry = WP_Block_Type_Registry::get_instance();
        $all_blocks = array_keys($block_registry->get_all_registered());
        $allowed_blocks = array_values(array_diff($all_blocks, $excluded_blocks));

        return $allowed_blocks;
    }

    /**
     * Alters block's markup.
     *
     * @param string $block_content
     * @param array $block
     * @return string
     */
    public static function render_block(string $block_content, array $block): string
    {
        switch ($block['blockName']) {
            case 'core/table':
                // make responsive table
                $return = '<div class="wp-block-table-wrap">
                                <div class="wp-block-table-mobile-container">';
                $return .= $block_content;
                $return .= '</div></div>';
                return $return;
            default:
        }

        return $block_content;
    }
}
