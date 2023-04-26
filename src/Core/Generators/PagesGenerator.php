<?php 

namespace DynamicStaticLayout\Core\Generators;

use DynamicStaticLayout\Core\Contracts\Renderable;
use DynamicStaticLayout\Core\Parsers\ComponentsParser;
use DynamicStaticLayout\Core\Traits\HasScripts;
use DynamicStaticLayout\Core\Traits\HasStyles;
use ListParser;

class PagesGenerator extends TemplatePartGenerator implements Renderable{

    use HasScripts, HasStyles;

    protected static function getType(): string {
        return 'pages';
    }

    public function getData(): array{
        $current['content'] = $this->content;
        $current['scripts'] = $this->getScripts();
        $current['styles'] = $this->getStyles();

        return $current;
    }

    public function render(){
        $content = ComponentsParser::parse( $this->content );
        // $content = ListParser::parse( $content );
        echo $content;
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
}