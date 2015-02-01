<?php
add_filter( 'wpmu_blogs_columns', 'do_get_id' );
add_action( 'manage_sites_custom_column', 'do_add_columns', 10, 2 );
add_action( 'manage_blogs_custom_column', 'do_add_columns', 10, 2 );

function do_add_columns( $column_name, $blog_id ) {
    if ( 'blog_id' === $column_name )
        echo $blog_id;
    return $column_name;
}

function do_get_id( $columns ) {
    $columns['blog_id'] = 'ID';
    return $columns;
}
