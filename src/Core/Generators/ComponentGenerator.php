<?php 

namespace DynamicStaticLayout\Core\Generators;

use DynamicStaticLayout\Core\Contracts\Component;
use DynamicStaticLayout\Core\Contracts\Renderable;
use DynamicStaticLayout\Core\Parsers\ComponentsParser;
use DynamicStaticLayout\Core\Traits\HasTag;

class ComponentGenerator extends TemplatePartGenerator implements Component, Renderable{

    use HasTag;
    protected string $content = '';
    protected string $path = '';

    public function __construct( string $content, string $path ){
        $data = $this->parseVars( $content );
        $this->content = $content;
        $this->path = $path;
        $this->vars = $data["vars"];
        $this->content = $data["content" ];
        $this->setTag( "{{cmp:{$path}}}" );
    }

    final public function getParsedContent( $data = [] ){
        $content = ComponentsParser::parse( $this->getData()['content'] );
        $content = $this->replaceVars( $content, $this->vars, $data );
        return $content;   
    }

    final protected function replaceVars( string $content = '', array $vars = [], $data = [] ){
        
        if( count( $vars ) === 0 ) return $content;

        foreach( $vars as $tag => $vars ):
            foreach( $vars as $var ):
                $props = array_values( explode( '.', $var ) );
                $type = array_shift( $props );
                
                if( empty( $props ) ) continue;

                if( $type === 'post' && !$data ){
                    global $post;
                    if( empty( $post ) ) continue;
                    $generator = ( new PostJsonGenerator( $post ) );
                    $data = $generator->getData();

                    if( !is_file( $generator->getPath() ) ){
                        do_action( 'save_post', $post->ID, $post, true );
                    }
                    if( is_file( $generator->getPath() ) ){
                        $aux = file_get_contents( $generator->getPath() );
                        $aux = json_decode( $aux, true);
                        if( $aux ){
                            $data = $aux;
                        }
                        unset( $aux );
                    }
                }
                
                $current = null;
                foreach( $props as $key ){
                    if( $current ) {
                        $current = @$current[$key];
                        continue;
                    }
                    $current = @$data[$key];
                }
                if( !is_string( $current ) ) $current = '';

                $pos = strpos($content, $tag);
                if ($pos === false) {
                    continue;
                }
                $content = substr( $content, 0, $pos ) . $current . substr( $content, $pos + strlen( $tag ) );
            endforeach;
        endforeach;

        return $content;
    }

    final protected static function parseVars( string $content ){
        $matches = [];
        $open = "/(\{\{+\s?+cmp\-var\:)(?<vars>[^\}]*?)(?:\s?+\}\})/";
        preg_match_all(
            $open,
            $content,
            $matches,
            PREG_PATTERN_ORDER
        );
        $vars = [];
        foreach( $matches[0] as $index => $tag ){
            if( !@$vars[$tag] ) $var[$tag] = [];
            $vars[$tag][] = $matches['vars'][$index];
        }
        return [
            'vars' => $vars,
            'content' => $content,
        ];
    }

    public function getData(): array{
        return [
            "content" => $this->content,
            "tag" => $this->getTag(),
            "vars" => $this->vars
        ];
    }

    protected static function getType(): string {
        return 'components';
    }

    public function getPath()
    {
        $slug = explode("/", $this->path );
        $slug = end( $slug );
        return DYNAMIC_STATIC_LAYOUT_GENERATED_PATH . "/" . 
            $this->getType() . '/' . 
            $slug .  
            '/data.json';
    }

    public function render(){
        echo $this->getParsedContent();
    }
}