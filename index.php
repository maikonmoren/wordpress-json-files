<?php
/**
 * @package Plugin
 */
/*
Plugin Name: Static HTML generator
Plugin URI: 
Text Domain: Static HTML generator
Domain Path: /plugins/static-html-generator
Description: Static HTML generator.
Version: 0.1.0
Author: 
License: MIT
Text Domain: Static HTML generator
*/

require_once __DIR__ . "/src/autoload.php";
require_once __DIR__ . "/api/index.php";

define( "DYNAMIC_STATIC_LAYOUT_PATH", __DIR__ );
define( "DYNAMIC_STATIC_LAYOUT_GENERATED_PATH", __DIR__ . DIRECTORY_SEPARATOR . "generated");

add_action('init', function(){

  $base = __DIR__ . '/src/hooks';
  $actions = "{$base}/actions";
  $filters = "{$base}/filters";

  if( is_dir( $actions ) ){
    foreach( scandir($actions ) as $action ){
      if( in_array( $action, ['..', '.' ] ) ) continue;
      if( !is_dir( "{$base}/actions/{$action}" ) ) continue;
      foreach( scandir( "{$base}/actions/{$action}" ) as $file ){
        if( in_array( $file, ['..', '.' ] ) ) continue;
        add_action( $action, require_once( "{$base}/actions/{$action}/{$file}" ), 10, 10 );
      }
    }
  }

  if( is_dir( $filters ) ){
    foreach( scandir($filters ) as $filter ){
      if( in_array( $filter, ['..', '.' ] ) ) continue;
      if( !is_dir( "{$base}/filters/{$filter}" ) ) continue;
      foreach( scandir( "{$base}/filters/{$filter}" ) as $file ){
        if( in_array( $file, ['..', '.' ] ) ) continue;
        add_filter( $filter, require_once( "{$base}/filters/{$filter}/{$file}" ), 10, 10 );
      }
    }
  }

});

function debug( string $file, $content = [] ){
  file_put_contents( __DIR__ . "/" . $file, json_encode( $content, JSON_PRETTY_PRINT ) ); 
}

function log_plugin_error( string $file, $content = [] ){

  if( $content instanceof Throwable ){
    /**
     * @var Throwable $content
     */
    $content = $content->getMessage() . PHP_EOL . $content->getTraceAsString();
  }

  if( is_string( $content ) ){
    $content = "[ " . date('Y-m-d H:i:s') . " ] => " . $content;
    $content .= PHP_EOL . PHP_EOL;
    return file_put_contents( __DIR__ . "/logs/" . $file, $content, FILE_APPEND ); 
  }

  file_put_contents( 
    __DIR__ . "/logs/" . uniqid( "{$file}-" ), 
    json_encode( $content, JSON_PRETTY_PRINT ) 
  );
}

