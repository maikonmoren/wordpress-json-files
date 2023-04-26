<?php 

namespace DynamicStaticLayout\Core\Generators;

use Closure;
use DynamicStaticLayout\Core\Contracts\HasPath;
use DynamicStaticLayout\Core\Contracts\ListContract;
use DynamicStaticLayout\Core\Contracts\Signed;
use DynamicStaticLayout\Core\Traits\HasTag;
use DynamicStaticLayout\StoreGeneratedData;

class ListGenerator extends JsonGenerator implements HasPath, Signed{

    use HasTag;
    protected Closure $callable;
    protected ListContract $list;
    protected string $component;
    protected string $signature;
    protected string $handler_path;
    protected int $chunk_size = -1;

    public function __construct( string $component, string $handler_path )
    {
        $this->handler_path = $handler_path;
        $this->list = require get_theme_file_path( $handler_path );
        $this->component = ComponentGenerator::getTemplatePart( $component )->getTag();
    }

    public function sign( string $uniq = '' ): self
    {
        $id = $uniq . json_encode( $this->list->getHandlerArgs() ) . $this->component . $this->handler_path;
        $this->signature = substr( sha1( $id ), 0, 10);
        $this->setTag("{{list:{$this->signature}}}");
        return $this;
    }

    public function setHandlerArgs( $args = [] ){
        $this->list->setHandlerArgs( $args );
        return $this;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public static function listOf( string $component, string $handler_path ){
        return new static( $component, $handler_path );
    }

    public function setChunkSize( int $size ){
        $this->list->setChunkSize( $size );
        return $this;
    }

    protected static function getType(): string {
        return 'lists';
    }

    public function isListNotEmpty(){
        $data = $this->list->getData();
        return is_array( $data ) && count( $data ) > 0;
    }

    public function getData(): array
    {
        return [
            'list' => $this->list->getData(),
            'chunk_size' => $this->list->getChunkSize(),
            'handler_path' => $this->handler_path,
            'component' => $this->component,
        ];
    }

    public function getPath()
    {
        if( !isset( $this->signature ) ) $this->sign();

        return DYNAMIC_STATIC_LAYOUT_GENERATED_PATH . "/" . 
            $this->getType() . '/' . 
            $this->signature .  
            '/data.json';
    }

    public function store(){
        try {
            //code...
            StoreGeneratedData::store( $this, $this );
        } catch (\Throwable $th) {
            //throw $th;
            log_plugin_error( 'list-generator-storage-fail.log', $th );
        }
        return $this;
    }
    
    public function __destruct(){
        $this->store();
    }
}