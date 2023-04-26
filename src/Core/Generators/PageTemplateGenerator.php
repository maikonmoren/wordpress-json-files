<?php 

namespace DynamicStaticLayout\Core\Generators;

use DynamicStaticLayout\Core\Traits\HasMetaTags;
use DynamicStaticLayout\Core\Traits\HasScripts;
use DynamicStaticLayout\Core\Traits\HasStyles;
use DynamicStaticLayout\StoreGeneratedData;

class PageTemplateGenerator extends ComponentGenerator{

    protected array $post_load_scripts = [];

    use HasStyles, HasScripts, HasMetaTags;

    public function getData(): array {
        $current = parent::getData();
        $current['scripts']             = $this->getScripts();
        $current['post_load_scripts']   = $this->post_load_scripts;
        $current['styles']              = $this->getStyles();
        $current['metas']               = $this->getMetaTags();

        return $current;
    }

    protected static function getType(): string {
        return 'page-templates';
    }

    public function addPostLoadScript( string $src, $attrs = [] ){
        $attrs['src'] = $src;
        $this->post_load_scripts[] = $attrs;
        return $this;
    }

    public function getMetaVal( $meta ): string {
        $vars = $this->parseVars($meta)['vars'];
        return parent::replaceVars( $meta, $vars );
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


    public function __destruct()
    {
        StoreGeneratedData::store( $this, $this );
    }
}