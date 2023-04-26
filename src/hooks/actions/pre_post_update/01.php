<?php

use DynamicStaticLayout\Core\Generators\PostJsonGenerator;
use DynamicStaticLayout\StoreGeneratedData;

return function( int $post_ID, array $data ){
    try {
        $post = get_post( $post_ID );
        if( $post->post_name == @$data['post_name'] ) return;
        $generator = new PostJsonGenerator( $post );
        StoreGeneratedData::delete( $generator->getPath() );

        $generator = new PostJsonGenerator( $post, true );
        StoreGeneratedData::delete( $generator->getPath() );
    } catch (\Throwable $th) {
        log_plugin_error('pre_post_update-01.log', $th );
    }
};