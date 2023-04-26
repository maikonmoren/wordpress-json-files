<?php 

namespace DynamicStaticLayout\Core\Generators;

use DynamicStaticLayout\Core\Traits\HasMetaTags;
use DynamicStaticLayout\Core\Traits\HasScripts;
use DynamicStaticLayout\Core\Traits\HasStyles;
use DynamicStaticLayout\Helpers\BufferHelper;
use DynamicStaticLayout\StoreGeneratedData;
use stdClass;
use WP_Post;
use WP_Term;

class EspecialTemplateGenerator extends PageTemplateGenerator{

    protected array $post_load_scripts = [];
    protected WP_Post $post;

    use HasStyles, HasScripts, HasMetaTags;

    public function setPost( WP_Post $post ){
        $this->post = $post;
        return $this;
    }

    public function getData(): array {
        $current = parent::getData();
        
        $current['scripts']             = $this->getScripts();
        $current['post_load_scripts']   = $this->post_load_scripts;
        $current['styles']              = $this->getStyles();
        $current['metas']               = $this->getMetaTags();
        $current['data']                = &$this->post && new stdClass;

        return $current;
    }

    protected function getScripts(){

        BufferHelper::getBufferOutputFromFunction(function(){
            wp_head();
            wp_footer();
        }, [], false );

        global $base_scripts;
        if( !is_array( $base_scripts ) )
            $base_scripts = [];

        return $base_scripts;
    }
    
    protected function _getType(): string {
        return "specials";
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
            $this->post->post_name .  
            '/data.json';
    }


    public function __destruct()
    {
        StoreGeneratedData::store( $this, $this );
    }
}