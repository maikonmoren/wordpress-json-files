<?php

spl_autoload_register( function( $class ){
    $class = explode('\\',  $class);
    $class = end($class);

    $recursive = function( string $dir ) use ( &$recursive, $class ){
        if( is_file( "{$dir}/{$class}.php" ) )
            return include_once "{$dir}/{$class}.php";
        foreach( scandir( $dir ) as $child ){
            if( in_array( $child, [ ".", ".." ] ) ) continue; 
            $path = "{$dir}/{$child}";

            if( is_dir( $path ) )  $recursive( $path );
        }
    };

    return $recursive( __DIR__ );
});