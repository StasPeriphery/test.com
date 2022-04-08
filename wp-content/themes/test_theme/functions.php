<?php
/**
 * test_theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package test_theme
 */

if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function test_theme_setup()
{
    /*
        * Make theme available for translation.
        * Translations can be filed in the /languages/ directory.
        * If you're building a theme based on test_theme, use a find and replace
        * to change 'test_theme' to the name of your theme in all the template files.
        */
    load_theme_textdomain('test_theme', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
        * Let WordPress manage the document title.
        * By adding theme support, we declare that this theme does not use a
        * hard-coded <title> tag in the document head, and expect WordPress to
        * provide it for us.
        */
    add_theme_support('title-tag');

    /*
        * Enable support for Post Thumbnails on posts and pages.
        *
        * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        */
    add_theme_support('post-thumbnails');

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(
        array(
            'menu-1' => esc_html__('Primary', 'test_theme'),
        )
    );

    /*
        * Switch default core markup for search form, comment form, and comments
        * to output valid HTML5.
        */
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    // Set up the WordPress core custom background feature.
    add_theme_support(
        'custom-background',
        apply_filters(
            'test_theme_custom_background_args',
            array(
                'default-color' => 'ffffff',
                'default-image' => '',
            )
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support(
        'custom-logo',
        array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        )
    );
}

add_action('after_setup_theme', 'test_theme_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function test_theme_content_width()
{
    $GLOBALS['content_width'] = apply_filters('test_theme_content_width', 640);
}

add_action('after_setup_theme', 'test_theme_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function test_theme_widgets_init()
{
    register_sidebar(
        array(
            'name' => esc_html__('Sidebar', 'test_theme'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here.', 'test_theme'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        )
    );
}

add_action('widgets_init', 'test_theme_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function test_theme_scripts()
{
    wp_enqueue_style('test_theme-style', get_stylesheet_uri(), array(), _S_VERSION);
    wp_style_add_data('test_theme-style', 'rtl', 'replace');

    wp_enqueue_script('test_theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'test_theme_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}


define('ASSETS', get_stylesheet_directory_uri() . '/assets/');


class SetRandPost
{

    private $countPosts;
    private $titlePost = 'Some Title post ';

//    private $;

    public function __construct($countPosts = 20)
    {
        $this->countPosts = $countPosts;
    }

    public function getPostContentsFromAPI()
    {
        return file_get_contents('http://loripsum.net/api/10/medium');
    }


    public function generateRandomString($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function setPostData()
    {
        $ar = [];

        for ($i = 1; $i <= $this->countPosts; $i++) {
            $ar[$i] = ["post_title" => $this->generateRandomString(rand(0, 15)),
                "post_content" => $this->getPostContentsFromAPI(),
                'post_status' => 'publish',
            ];
        }

        foreach ($ar as $key => $val) {
            $postId = wp_insert_post($val, true);
        }
        die();
    }


}


function load_more_posts()
{

    if ($_COOKIE['sort'] ){
        $posts = get_posts([
            'numberposts' => -1,
            'orderby'=> 'content',
            'order'=>'ASC',

        ]);

        $posts = sort_post_for_index_page($posts);

        $array_resp =[];


        for ($i =0; $i<get_option('posts_per_page'); $i++ ){
            $array_resp[$i] = $posts[(5 * $_POST['page']) + $i];
        }
        wp_die(json_encode($array_resp));
        die();
    }

        $posts = get_posts([
        'numberposts' => 5,
        'post_type' => 'post',
        'posts_per_page' => get_option('posts_per_page'),
        'offset' => (5 * $_POST['page'])

    ]);
    foreach ($posts as $el) {
        if (get_the_post_thumbnail_url($el->ID))
            $el->thumb = get_the_post_thumbnail_url($el->ID);
    }

    wp_die(json_encode($posts));
    die();
}

function sort_post_for_index_page($posts)
{

    if ($_COOKIE['sort'] == 'down') {
        usort($posts, function ($a, $b) {

            if (strlen($a->post_content) == strlen($b->post_content))
                return 0;
            return strlen($a->post_content) < strlen($b->post_content) ? -1 : 1;

        });
    }

    if ($_COOKIE['sort'] == 'up') {
        usort($posts, function ($a, $b) {

            if (strlen($a->post_content) == strlen($b->post_content))
                return 0;
            return strlen($a->post_content) > strlen($b->post_content) ? -1 : 1;

        });
    }
    return $posts;
}

add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');

function save_post_data()
{

    $data = $_POST['data'];
    if ($data == 'true')
        update_option('sort_post', '1', false);
    else
        delete_option('sort_post');

    wp_die(json_encode(['status' => 'ok']));
    die();
}

add_action('wp_ajax_save_post_data', 'save_post_data');
add_action('wp_ajax_nopriv_save_post_data', 'save_post_data');


if ($_GET['set_posts'] == 20) {

    $ss = new SetRandPost();

    $ss->setPostData();
    die();

}


add_action('admin_menu', 'true_top_menu_page', 25);

function true_top_menu_page()
{

    add_menu_page(
        'Настройки слайдера',
        'Sorting feature',
        'manage_options',
        'sort_page',
        'sort_page_page_callback',
        'dashicons-images-alt2',
        20
    );
}


function sort_page_page_callback()
{
    echo '<input type="checkbox" class="input-save-sort"'
        . (get_option('sort_post') ? 'checked' : '') .
        ' > <labl>sort posts</labl><button class="save-button">SAVE</button>';
}


add_action('admin_init', 'sort_page_fields');

function sort_page_fields()
{

    register_setting(
        'sort_page_settings',
        'sort_post',
        'absint'
    );

    add_settings_section(
        'data_settings_section_id',
        '',
        '',
        'sort_page'
    );

    add_settings_field(
        'sort_post',
        'SORT POST',
        'true_number_field',
        'sort_page',
        'data_settings_section_id',
        array(
            'label_for' => 'sort_post',
            'class' => 'class-field',
            'name' => 'sort_post',
        )
    );

}


add_action('wp_footer', 'scripts', 1);
add_action('wp_footer', 'local_script', 1);


function scripts()
{
    wp_enqueue_script('home_js', ASSETS . 'js/home.js', '', '', true);

}


add_action('admin_enqueue_scripts', 'add_js_custom_save', 1);

function add_js_custom_save($hook_suffix)
{
    if ($hook_suffix == 'toplevel_page_sort_page') {

        wp_enqueue_script('admin_save', get_stylesheet_directory_uri() . '/assets/js/admin/admin_save.js', [], '', true);
    }
}


function local_script()
{
    global $wp_query;

    wp_localize_script('home_js', 'myajax',
        [
            'url' => admin_url('admin-ajax.php'),
            'posts' => json_encode($wp_query->query_vars),
            'current_page' => get_query_var('paged') ? get_query_var('paged') : 1,
            'max_page' => $wp_query->max_num_pages
        ]
    );
}



