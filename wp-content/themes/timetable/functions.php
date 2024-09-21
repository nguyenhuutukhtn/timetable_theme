<?php

function enqueue_timetable_styles() {
    wp_enqueue_style( 'timetable-style', get_template_directory_uri() . '/css/timetable-style.css' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_timetable_styles' );

function enqueue_custom_scripts() {
    wp_enqueue_script( 'brand-switcher', get_template_directory_uri() . '/js/brand-switcher.js', array(), null, true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_scripts' );
