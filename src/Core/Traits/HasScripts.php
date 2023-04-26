<?php 

namespace DynamicStaticLayout\Core\Traits;

use DynamicStaticLayout\Core\Generators\ScriptGenerator;
use DynamicStaticLayout\Helpers\BufferHelper;

trait HasScripts{

    protected function getScripts(){
        global $wp_scripts;
        
        $scripts = [];
        foreach( $wp_scripts->queue as $id )
            if( $id !== 'admin-bar' ){
                ScriptGenerator::load( $id );
                $scripts[] = $id;   
            }

        return $scripts;
      
    }
}

