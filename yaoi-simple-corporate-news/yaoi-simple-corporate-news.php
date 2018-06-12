<?php
/*
  Plugin Name: Simple-Corporate-News-JP
  Description: タイトルのみのニュース表示関数を提供するプラグイン
  Version:     0.1.0
  Author:      Yuki AOI
*/

add_action( 'init', 'create_post_type_news' );
function create_post_type_news() {
    
    $labels = array(
        'name'               => 'ニュース',
        'singular_name'      => 'ニュース',
        'add_new'            => '新規',
        'add_new_item'       => '新規ニュース',
        'edit_item'          => 'ニュース編集',
        'new_item'           => 'ホットニュース',
        'all_items'          => 'ニュース一覧',
        'view_item'          => 'ニュース閲覧',
        'search_items'       => 'ニュース検索',
        'not_found'          => 'ニュースが見つかりません',
        'not_found_in_trash' => '削除済みのニュースです',
        'parent_item_colon'  => '',
        'menu_name'          => 'ニュース'
    );
    
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'news' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'author')
    );
    register_post_type( 'news', $args );
}

function the_corporate_news($arg = null) {
    switch (true) {
        case empty($arg):
            the_corporate_news_array();
            break;
        case is_array($arg):
            the_corporate_news_array($arg);
            break;
        case is_numeric($arg):
            the_corporate_news_array(array(
                'numberposts' => $arg - 0,
            ));
            break;
        default:
            the_corporate_news_array();
            break;
    }
}
function the_corporate_news_array(array $args = array()) {
    $args = $args + array(
        'numberposts' => 3,
        'post_type' => 'news'
    );
    $posts = get_posts( $args );

    echo '<div id="news-wrapper"><div id="news-inner">', "\n";
    echo '<div id="news"><p class="gtc f16 bld fwhite">NEWS</p></div>', "\n";
    echo '<dl id="article" class="f16">', "\n";

    if ( $posts ) {
        foreach ( $posts as $post ) {
            $date = date('Y.m.d', strtotime($post->post_date));
            $title = htmlspecialchars($post->post_title);
            if (strlen($post->post_content) > 0) {
                $title = sprintf('<a href="%s">%s</a>', get_permalink($post->ID), $title);
            }
            echo sprintf('<dt>%s</dt><dd>%s</dd>', $date, $title), "\n";
            //echo $date, $title, "\n";
        } 
    } else {
        echo '<dt>', '0000.00.00', '</dt>', "\n";
        echo '<dd>', 'NEWS機能は一時停止しています。', '</dd>', "\n";
    }
    echo '</dl>', "\n";
    echo '</div></div>', "\n";
}