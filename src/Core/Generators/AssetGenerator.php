<?php

namespace DynamicStaticLayout\Core\Generators;

use DynamicStaticLayout\Core\Contracts\HasPath;
use DynamicStaticLayout\Core\Generators\JsonGenerator;
use DynamicStaticLayout\StoreGeneratedData;

abstract class AssetGenerator extends JsonGenerator implements HasPath{

    protected string $asset_id = '';
    protected string $signature = '';
    protected string $src = '';

    public function __construct( string $asset_id ){
        $this->asset_id = $asset_id;
        $this->sign();
    }

    public static function load( string $asset_id ){   
        return new static( $asset_id );
    }

    public function getData(): array {
        return [
            'src' => $this->getUrl(),
            'signature' => $this->signature
        ];
    }

    abstract protected function getUrl();

    final public function getContent(){
        try {
            $path = str_replace( home_url(), '', $this->getUrl() );

            if( strpos(  $path, "http" ) !== false )
                return file_get_contents( $path );

            $path = ABSPATH . $path;
            $path = str_replace( "//", "/", $path );

            if( is_dir( $path ) ) return '';
            
            return file_get_contents( $path );
        } catch (\Throwable $th) {
            log_plugin_error( $th );
        }
        return '-';
    }

    private function sign(){
        $this->signature = (string) $this->asset_id;
    }

    public function __destruct()
    {
        StoreGeneratedData::store( $this, $this );
    }
}