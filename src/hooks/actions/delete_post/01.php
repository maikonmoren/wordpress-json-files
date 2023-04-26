<?php

use DynamicStaticLayout\Core\Generators\PostJsonGenerator;
use DynamicStaticLayout\StoreGeneratedData;

return function( int $post_ID, WP_Post $post ){
    try {
        $generator = new PostJsonGenerator( $post );
        StoreGeneratedData::delete( pathinfo( $generator->getPath(), PATHINFO_DIRNAME ) );

        $generator = new PostJsonGenerator( $post, true );
        StoreGeneratedData::delete( pathinfo( $generator->getPath(), PATHINFO_DIRNAME ) );
    } catch (\Throwable $th) {
        log_plugin_error('delte_post-01.log', $th );
    }
};