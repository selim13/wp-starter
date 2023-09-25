<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 */

use Local\AssetsManifest;
use Local\Options;
use Twig\Extra\Html\HtmlExtension;

Timber\Timber::init();

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = ['templates', 'views'];

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class Site extends Timber\Site
{
  private AssetsManifest $assetsManifest;

  /** Add timber support. */
  public function __construct()
  {
    add_action('after_setup_theme', [$this, 'theme_supports']);
    add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    add_filter('timber/context', [$this, 'add_to_context']);
    add_filter('timber/twig', [$this, 'add_to_twig']);

    add_action(
      'acf/init',
      function () {
        $files = glob(get_stylesheet_directory() . '/acf/*.php');
        foreach ($files as $file) {
          $field_group = require_once($file);

          if (is_array($field_group)) {
            register_extended_field_group($field_group);
          }
        }
      }
    );

    parent::__construct();
  }


  /**
   * Registers the theme assets.
   *
   * @return void
   * @throws Exception
   */
  public function enqueue_scripts(): void
  {
    wp_enqueue_script(
      'index.js',
      get_stylesheet_directory_uri() . '/assets/index.js',
      [],
      null,
      true
    );

    // Provides js scripts with data from backend in wpParams object
    wp_localize_script(
      'index.js',
      'wpParams',
      [
        'restURL' => esc_url_raw(rest_url()),
        'restNonce' => wp_create_nonce('wp_rest'),
      ]
    );

    wp_enqueue_style(
      'index.css',
      get_stylesheet_directory_uri() . '/assets/index.css',
      false,
      null
    );

    wp_deregister_script('wp-embed');
    wp_dequeue_style('wp-block-library');

    // remove jquery for anonymous users as we don't use it for frontend
    if (!is_user_logged_in()) {
      wp_dequeue_script('jquery');
      wp_deregister_script('jquery');
    }
  }


  public function add_to_context(array $context): array
  {
    $context['menu'] = Timber::get_menu('primary_menu');
    $context['year'] = date('Y');
    $context['site'] = $this;
    return $context;
  }

  public function theme_supports()
  {
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support(
      'html5',
      [
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
      ]
    );
    add_theme_support('menus');
    register_nav_menus(
      [
        'primary_menu' => 'Главное меню',
      ]
    );
  }


  public function add_to_twig($twig)
  {
    $twig->addExtension(new HtmlExtension());
    $twig->addExtension(new Twig\Extension\StringLoaderExtension());
    return $twig;
  }

}

new Site();
