<?php

namespace DynamicStaticLayout\Core\Generators;

class StyleGenerator extends AssetGenerator{

    protected function getUrl(){
        global $wp_styles;

        $url = '';
        if( $data = @$wp_styles->registered[ $this->asset_id ] )
            if( $url = $data->src )
                if( strpos( $url, "http" ) === false )
                    $url = home_url( $url );

        return $url;
    }

    public function getPath()
    {
        return DYNAMIC_STATIC_LAYOUT_GENERATED_PATH .
            "/styles/{$this->asset_id}.json" ;
    }
}
