<?php 
add_action('wp_enqueue_scripts', 'style_theme');
add_action('wp_footer', 'scripts_theme');
add_action('after_setup_theme', 'theme_register_nav_menu');
add_action('widgets_init', 'register_my_widgets');

add_action( 'init', 'register_post_types' );
function register_post_types(){
	register_post_type( 'portfolio', [
		'label'  => null,
		'labels' => [
			'name'               => 'Portfolio', // основное название для типа записи
			'singular_name'      => 'Portfolio', // название для одной записи этого типа
			'add_new'            => 'Добавить portfolio', // для добавления новой записи
			'add_new_item'       => 'Добавление portfolio', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование portfolio', // для редактирования типа записи
			'new_item'           => 'Новое portfolio', // текст новой записи
			'view_item'          => 'Смотреть portfolio', // для просмотра записи этого типа.
			'search_items'       => 'Искать portfolio', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Portfolio', // название меню
		],
		'description'         => 'This is my portfolio',
		'public'              => true,
		'publicly_queryable'  => true, // зависит от public
		'exclude_from_search' => true, // зависит от public
		'show_ui'             => true, // зависит от public
		'show_in_nav_menus'   => true, // зависит от public
		'show_in_menu'        => true, // показывать ли в меню адмнки
		'show_in_admin_bar'   => true, // зависит от show_in_menu
		'show_in_rest'        => true, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => 4,
		'menu_icon'           => null,
		//'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false,
		'supports'            => [ 'title', 'editor', 'author', 'thumbnail', 'excerpt' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => [],
		'has_archive'         => false,
		'rewrite'             => true,
		'query_var'           => true,
	] );
}

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

    add_theme_support( 'post-formats', array( 'video', 'gallery' ) );

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
