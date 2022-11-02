<?php
/*---------------------------------------------------------
Plugin Name: Tripvisite 
Author: carlosramosweb
Author URI: https://criacaocriativa.com/plugins/
Donate link: https://donate.criacaocriativa.com/
Description: Plugin que ler uma API da Tripadvisor para comentários e grava os dados em uma tabela personalizada no WordPress. Também exibi os dados gravados via shortcodes [tripvisite]. Com ajuda do plugin de CRON (WP Crontrol) para executar periodicamente.
Text Domain: wp-tripvisite
Domain Path: /languages/
Version: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 
------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Criação dos menus iniciais
function wp_tripvisite_register_plugin_action_links_settings( $links ) {
	$action_links = array(
		'settings' => '<a href="' . esc_url(admin_url( 'tools.php?page=crontrol_admin_manage_page' )) . '" title="'.__( 'Configurar Cron', 'wp-tripvisite' ).'">'.__( 'Configurar Cron', 'wp-tripvisite' ).'</a>',
		'donate' => '<a href="' . esc_url("https://donate.criacaocriativa.com") . '" target="_blank" title="'.__( 'Doação', 'wp-tripvisite' ).'">'.__( 'Doação', 'wp-tripvisite' ).'</a>');
	return array_merge( $action_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wp_tripvisite_register_plugin_action_links_settings' );

add_action( 'admin_menu', 'register_wp_tripvisite_submenu_page', 70 );
function register_wp_tripvisite_submenu_page() {
    add_submenu_page( 'edit.php?post_type=tripvisite', 'Configurações', 'Configurações', 'edit_posts', 'tripvisite-settings', 'wp_tripvisite_settings_callback' ); 
}

// Criação do Post Type
function wp_tripvisite_setup_post_type() {
    $args = array(
        'public'        => true,
        'label'         => __( 'Tripvisite', 'wp-tripvisite' ),
        'description'   => __( 'Comentários do site Tripvisite.', 'wp-tripvisite' ),
        'menu_icon'     => 'dashicons-format-status',
        'supports'      => array( 'title', 'excerpt', 'thumbnail' ), // , 'custom-fields',
        'taxonomies'    => array( 'rating' ),
        'capability_type'   => 'post',
        'menu_position' => 20,
        'show_in_rest'  => true,
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,        
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'rewrite'               => array( 'slug' => 'tripvisite', 'with_front' => false ),
    );
    register_post_type( 'tripvisite', $args );
}
add_action( 'init', 'wp_tripvisite_setup_post_type' );

// Criação da categoria
function wp_tripvisite_create_taxonomies() {
    $labels = array(
        'name'              => __( 'Rating', 'wp-tripvisite' ),
        'singular_name'     => __( 'Rating', 'wp-tripvisite' ),
        'menu_name'         => __( 'Rating', 'wp-tripvisite' ),
    );
 
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'rating' ),
    );
 
    register_taxonomy( 'rating', array( 'tripvisite' ), $args );
}
//add_action( 'init', 'wp_tripvisite_create_taxonomies', 0 );

// Criação do fields personalizado
function wp_tripvisite_register_meta_boxes() {
    add_meta_box( 'metabox_tripvisite', __( 'Extras', 'wp-tripvisite' ), 'wp_tripvisite_meta_boxes_callback', 'tripvisite', 'normal',  'high' );
}

function wp_tripvisite_meta_boxes_callback( $post ) {
    @include_once( plugin_dir_path( __FILE__ ) . './tripvisite-meta-boxes.php' );
}
add_action( 'add_meta_boxes', 'wp_tripvisite_register_meta_boxes' );

// Inclui uma página para configuração
@include_once( plugin_dir_path( __FILE__ ) . './tripvisite-settings.php' );

// Inclui o bloco de código responsável para pegar e gravar os comentários
@include_once( plugin_dir_path( __FILE__ ) . './tripvisite-curl-cron.php' );

// Inclui um shortcode no sistema wp
@include_once( plugin_dir_path( __FILE__ ) . './tripvisite-shortcode.php' );

// Botão para pesquisar comentários manualmente
if( isset( $_POST['action_tripvisite_cron'] ) ) {
    add_action('init', 'wp_action_tripvisite_curl_cron_callback');
}

// Salva os campos extras do post type personalizados
function wp_save_tripvisite_meta_boxes( $post_id ) {
    $fields = [
        'wp_tripvisite_title',
        'wp_tripvisite_location_id',
        'wp_tripvisite_rating',
        'wp_tripvisite_name',
        'wp_tripvisite_username',
        'wp_tripvisite_lang',
        'wp_tripvisite_url',
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
     }
}
add_action( 'save_post', 'wp_save_tripvisite_meta_boxes' );

//
function wp_tripvisite_columns_list($columns) {
    unset($columns['title']);
    unset($columns['tags']);
    unset($columns['date']);
    
   return array_merge ( $columns, array ( 
        'thumbnail' => __ ( 'Avatar', 'wp-tripvisite' ),
        'title' => __ ( 'ID', 'wp-tripvisite' ),
        'wp_tripvisite_location_id' => __ ( 'Location ID', 'wp-tripvisite' ),
        'wp_tripvisite_title' => __ ( 'Título', 'wp-tripvisite' ),
        'wp_tripvisite_name' => __ ( 'Nome', 'wp-tripvisite' ),
        'wp_tripvisite_rating' => __ ( 'Rating', 'wp-tripvisite' ),
        'wp_tripvisite_url' => __ ( 'URL', 'wp-tripvisite' ),
        'wp_tripvisite_date' => __ ( 'Publicado em', 'wp-tripvisite' ),
   ) );

}
add_filter( 'manage_tripvisite_posts_columns', 'wp_tripvisite_columns_list' );

//
function wp_tripvisite_table_content( $column_name, $post_id ) {
    
    if( $column_name == 'thumbnail' ) {
        $featured_img_url = get_the_post_thumbnail_url($post_id, 'thumbnail');
        echo '<a href="'.esc_url(admin_url( 'post.php?post='.$post_id.'&action=edit')).'" style="display: inline-block;" title="'.__( 'Clique para editar', 'wp-tripvisite' ).'"><img src="'. esc_url($featured_img_url) .'" style=" width:50px; border-radius: 50%;"></a>';
    }
    if( $column_name == 'wp_tripvisite_location_id' ) {
        $wp_tripvisite_location_id = esc_attr( get_post_meta( $post_id, 'wp_tripvisite_location_id', true ) );
        echo $wp_tripvisite_location_id;
    }
    if( $column_name == 'wp_tripvisite_title' ) {
        $wp_tripvisite_title = esc_attr( get_post_meta( $post_id, 'wp_tripvisite_title', true ) );
        echo $wp_tripvisite_title;
    }
    if( $column_name == 'wp_tripvisite_name' ) {
        $wp_tripvisite_name = esc_attr( get_post_meta( $post_id, 'wp_tripvisite_name', true ) );
        echo $wp_tripvisite_name;
    }
    if( $column_name == 'wp_tripvisite_url' ) {
        $wp_tripvisite_url = esc_url( get_post_meta( $post_id, 'wp_tripvisite_url', true ) );
        echo '<a href="'.$wp_tripvisite_url.'" target="_blank">Visualizar</a>';
    }
    if( $column_name == 'wp_tripvisite_rating' ) {
        $wp_tripvisite_rating = esc_attr( get_post_meta( $post_id, 'wp_tripvisite_rating', true ) );
        echo $wp_tripvisite_rating . " Estrela(s)";
    }
    if( $column_name == 'wp_tripvisite_date' ) {
        $wp_tripvisite_date = date_i18n( get_option( 'date_format' ), strtotime( esc_attr( get_post_meta( $post_id, 'wp_tripvisite_date', true ) ) ) );
        echo $wp_tripvisite_date;
    }

}
add_action( 'manage_posts_custom_column', 'wp_tripvisite_table_content', 10, 2);
