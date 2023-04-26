<?php 

namespace DynamicStaticLayout\Helpers;

class BufferHelper {


    public static function getBufferOutputFromFunction( callable $callable, array $args = [], bool $print = true ){
 
        $content = '';

        ob_start();
        call_user_func_array( $callable, $args );
        $content = ob_get_clean();

        if( $print ) echo $content;

        return $content;
    }
}