<?php 

namespace DynamicStaticLayout\Core\Generators;

use DynamicStaticLayout\Core\Contracts\HasPath;
use DynamicStaticLayout\Core\Traits\HasStyles;
use WP_User;

class ColumnistJsonGenerator extends JsonGenerator implements HasPath{

    use HasStyles;
    protected WP_User $user;

    final public function __construct( WP_User $user){
        $this->user = $user;
    }

    public function getData(): array{

        $current = parent::getData();
        
        $current['scripts']             = $this->getScripts();
        $current['post_load_scripts']   = $this->post_load_scripts;
        $current['styles']              = $this->getStyles();
        $current['metas']               = $this->getMetaTags();
        $current['data']                = &$this->user && new stdClass;

        return $current;    
        
    }

    public function getPath(){
       
        return DYNAMIC_STATIC_LAYOUT_GENERATED_PATH . "/" . 
        'columnist'. '/' . 
        $this->user->user_nicename.  
        '/data.json';
}
    }

