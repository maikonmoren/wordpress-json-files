<?php 

namespace DynamicStaticLayout\Core\Generators;

use DynamicStaticLayout\Core\Contracts\HasPath;
use DynamicStaticLayout\Core\Contracts\TemplatePart;
use DynamicStaticLayout\Core\Generators\JsonGenerator;
use DynamicStaticLayout\StoreGeneratedData;

abstract class TemplatePartGenerator extends JsonGenerator implements TemplatePart, HasPath{
    

    abstract protected static function getType():string;

    public function __construct( string $content, string $path ){
        $this->content = $content;
        $this->path = $path;
    }
    
    final protected static function getClass(): string{
        return static::class;
    }
    
    final public static function getTemplatePart( string $slug, string $name = null, array $args = [] ):static {
        ob_start( );
        get_template_part( $slug, $name, $args );
        if( strpos('components/', $slug ) === 0 )
            $slug = str_replace( 'components/', '', $slug );
        $path = [ $slug ];
        if( $name ) $path[] = $name;

        $class = self::getClass();
        return new $class( ob_get_clean() ?? '', join( '-', $path ) );
    }

    public function __destruct(){
        StoreGeneratedData::store( $this, $this );
    }
}