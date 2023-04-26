<?php 
namespace DynamicStaticLayout;

use DynamicStaticLayout\Core\Contracts\HasPath;
use DynamicStaticLayout\Core\Contracts\Jsonable;
use DynamicStaticLayout\Core\Contracts\StorableJson;
use Exception;

class StoreGeneratedData implements StorableJson {

    public static function store( HasPath $generator, Jsonable $contents ){
        $dir = self::generatePath( $generator );
        if( !is_writable( $dir ) ) throw new Exception( "unable to write file: " . $generator->getPath()  );      
        file_put_contents( $generator->getPath(), $contents->toJson() );
    }

    public static function delete( string $dir ){
        
        if (!file_exists($dir)) {
            return true;
        }
    
        if (!is_dir($dir)) {
            return unlink($dir);
        }
    
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
    
            if (!self::delete($dir . DIRECTORY_SEPARATOR . $item)) return false;
        }
    
        return rmdir($dir);
    }

    public static function generatePath( HasPath $generator ){
        $dir = pathinfo( $generator->getPath(), PATHINFO_DIRNAME );

        if( function_exists( 'get_current_site' ) ){
            $dir = get_current_site()->id . DIRECTORY_SEPARATOR . $dir;
        }
        if( !is_dir( $dir ) ) mkdir( $dir, 0777, true );
        return $dir;
    }

    public static function remoteStore(){

    }
}