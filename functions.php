<?php 
add_action('wp_enqueue_scripts', 'style_theme');
add_action('wp_footer', 'scripts_theme');
add_action('after_setup_theme', 'theme_register_nav_menu');
add_action('widgets_init', 'register_my_widgets');

add_filter('document_title_separator', 'my_sep');
function my_sep( $sep ) {
    $sep = ' | ';
    return $sep;
}

add_filter('the_content', 'test_content');
function test_content($content) {
    $content.= 'Thanks for reading';
    return $content;
}

// добавляем выдуманный action
add_action('my_action', 'action_function');
function action_function() {
    echo "Sponsored by Bart Simpson";
}

// добавляю shortcode
add_shortcode('my_short', 'short_function');
function short_function() {
    return "Test shortcode";
}

// добавляю iframe shortcode
add_shortcode( 'iframe', 'Generate_iframe' );

function Generate_iframe( $atts ) {
	$atts = shortcode_atts( array(
		'href'   => 'https://wp-kama.ru',
		'height' => '550px',
		'width'  => '600px',     
	), $atts );

	return '<iframe src="'. $atts['href'] .'" width="'. $atts['width'] .'" height="'. $atts['height'] .'"> <p>Your Browser does not support Iframes.</p></iframe>';
}
// использование: 
// [iframe href="http://www.exmaple.com" height="480" width="640"]

// подключаем стили
function style_theme() {
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_style('default', get_template_directory_uri() . '/assets/css/default.css' );
    wp_enqueue_style('layout', get_template_directory_uri() . '/assets/css/layout.css' );
    wp_enqueue_style('queries', get_template_directory_uri() . '/assets/css/media-queries.css' );
    wp_enqueue_style('fonts', get_template_directory_uri() . '/assets/css/fonts.css' );
}

// подключаем скрипты
// function scripts_theme() {
//     wp_enqueue_script('init', get_template_directory_uri() . '/assets/js/init.js');
//     wp_enqueue_script('doubletaptogo', get_template_directory_uri() . '/assets/js/doubletaptogo.js');
//     wp_enqueue_script('modernizr', get_template_directory_uri() . '/assets/js/modernizr.js');
//     wp_enqueue_script('core', get_template_directory_uri() .'/assets/js/core.js');
// }

// регистрируем верхнее и нижнее меню
function theme_register_nav_menu() {
	register_nav_menu( 'top', 'Меню в шапке' );
	register_nav_menu( 'footer', 'Меню в подвале' );
    // генерирует тайтлы для таба
    add_theme_support( 'title-tag' );
    // устанавливает миниатюрную картинку поста
    add_theme_support( 'post-thumbnails', array( 'post' ) );

    add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

    // добавляем новый размер миниатюры
    add_image_size( 'post_thumb', 1300, 500, true );

    // добавляет Read more ссылку
    add_filter( 'excerpt_more', 'new_excerpt_more' );
    function new_excerpt_more( $more ){
        global $post;
        return '<a href="'. get_permalink($post) . '"> Read more</a>';
    }

    // удаляет H2 из шаблона пагинации
    add_filter('navigation_markup_template', 'my_navigation_template', 10, 2 );
    function my_navigation_template( $template, $class ){
        /*
        Вид базового шаблона:
        <nav class="navigation %1$s" role="navigation">
            <h2 class="screen-reader-text">%2$s</h2>
            <div class="nav-links">%3$s</div>
        </nav>
        */

        return '
        <nav class="navigation %1$s" role="navigation">
            <div class="nav-links">%3$s</div>
        </nav>    
        ';
    }

    // выводим пагинацию
    the_posts_pagination( array(
        'end_size' => 2,
    ) ); 
}

// регистрируем сайдбар
function register_my_widgets(){
	register_sidebar( array(
		'name'          => 'Left Sidebar',
		'id'            => "left_sidebar",
		'description'   => 'Здесь можно редактировать сайдбар',
        'before_widget'  => '<div class="widget %2$s">',
		'after_widget'   => "</div>\n",
		'before_title'   => '<h5 class="widgettitle">',
		'after_title'    => "</h5>\n"
	) );
}
