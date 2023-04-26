<?php 

namespace DynamicStaticLayout\Core\Generators;

use DynamicStaticLayout\Core\Traits\HasMetaTags;
use DynamicStaticLayout\Core\Traits\HasScripts;
use DynamicStaticLayout\Core\Traits\HasStyles;
use DynamicStaticLayout\StoreGeneratedData;
use stdClass;
use WP_Term;

class TermTemplateGenerator extends PageTemplateGenerator{

    protected array $post_load_scripts = [];
    protected WP_Term $term;

    use HasStyles, HasScripts, HasMetaTags;

    public function setTerm( WP_Term $term ){
        $this->term = $term;
        return $this;
    }

    public function getData(): array {
        $current = parent::getData();
        
        $current['scripts']             = $this->getScripts();
        $current['post_load_scripts']   = $this->post_load_scripts;
        $current['styles']              = $this->getStyles();
        $current['metas']               = $this->getMetaTags();
        $current['data']                = &$this->term ?? new stdClass;
        return $current;
    }


    protected function _getType(): string {
        return $this->term->taxonomy;
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
        return DYNAMIC_STATIC_LAYOUT_GENERATED_PATH . "/" . 
            $this->_getType() . '/' . 
            $this->term->slug .  
            '/data.json';
    }


    public function __destruct()
    {
        StoreGeneratedData::store( $this, $this );
    }
}