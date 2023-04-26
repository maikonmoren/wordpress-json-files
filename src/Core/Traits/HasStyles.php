<?php 

namespace DynamicStaticLayout\Core\Traits;

use DynamicStaticLayout\Core\Generators\StyleGenerator;

trait HasStyles{

    protected function getStyles(){

        global $wp_styles;
        
        $styles = [];
        foreach( $wp_styles->queue as $id )
            if( $id !== 'admin-bar' ){
                StyleGenerator::load( $id );
                $styles[] = $id;
            }
        
        return $styles;
    }   

}

