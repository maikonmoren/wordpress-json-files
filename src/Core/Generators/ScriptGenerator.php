<?php

namespace DynamicStaticLayout\Core\Generators;

class ScriptGenerator extends AssetGenerator{


    protected function getUrl(){
        global $wp_scripts;
        
        $url = '';
        if( $data = @$wp_scripts->registered[ $this->asset_id ] )
            if( $url = $data->src )
                if( strpos( $url, "http" ) === false )
                    home_url( $url );

        return $url;
    }



    public function getPath()
    {
        return DYNAMIC_STATIC_LAYOUT_GENERATED_PATH .
            "/scripts/{$this->asset_id}.json" ;
    }
}
