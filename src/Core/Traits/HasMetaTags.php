<?php 

namespace DynamicStaticLayout\Core\Traits;

trait HasMetaTags{

    protected array $meta_tags = [];

    public function addMetaTag( string $property, array $attrs,  bool $replace = true ): self {

        if( !$replace )
            if( @$this->meta_tags[ $property ] )
                return $this;

        $this->meta_tags[$property] = [];
        foreach( $attrs as $prop => $value ){
            if( !is_string( $prop ) || !is_string( $value ) ) continue;
            $this->meta_tags[$property][$prop] = $value;
        }

        add_action('dynamice_static_layout_meta_tags', function() use ( $property ){
            $meta = $this->meta_tags[$property];
            $attrs = '';
            foreach( $meta as $attr => $val ):
                $val = $this->getMetaVal( $val );
                $attrs .= "{$attr}='{$val}'";
            endforeach;
            echo "<meta property='{$property}' {$attrs} />";
        });
        return $this;
    }   

    protected function getMetaTags(){
        return $this->meta_tags;
    }

    abstract public function getMetaVal( string $val ): string;

}

