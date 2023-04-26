<?php

use DynamicStaticLayout\Core\Generators\TermJsonGenerator;
use DynamicStaticLayout\StoreGeneratedData;

return function( int $term, int $tt_id, string $taxonomy, WP_Term $deleted_term, array $object_ids ){
    try {
            $generator = new TermJsonGenerator( $deleted_term );
            StoreGeneratedData::delete( pathinfo( $generator->getPath(), PATHINFO_DIRNAME ) );
    } catch (\Throwable $th) { log_plugin_error( 'delete_term-01', $th ); }

    try{
        $generator = new TermJsonGenerator( $deleted_term, true );
        StoreGeneratedData::delete( pathinfo( $generator->getPath(), PATHINFO_DIRNAME ) );
    } catch (\Throwable $th) {
        log_plugin_error( 'delete_term-01', $th );
    }
};