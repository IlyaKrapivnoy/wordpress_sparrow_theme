<?php 
add_action('wp_enqueue_scripts', 'style_theme');
add_action('wp_footer', 'scripts_theme');
add_action('after_setup_theme', 'theme_register_nav_menu');
add_action('widgets_init', 'register_my_widgets');

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
    // добавляем новый размер миниатюры
    add_image_size( 'post_thumb', 1300, 500, true );

    // добавляет Read more ссылку
    add_filter( 'excerpt_more', 'new_excerpt_more' );
    function new_excerpt_more( $more ){
        global $post;
        return '<a href="'. get_permalink($post) . '"> Read more</a>';
    }
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
