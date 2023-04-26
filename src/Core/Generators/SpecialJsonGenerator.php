<?php 

namespace DynamicStaticLayout\Core\Generators;

use DynamicStaticLayout\Core\Contracts\HasPath;
use DynamicStaticLayout\Core\Traits\HasStyles;
use WP_Post;

class SpecialJsonGenerator extends JsonGenerator implements HasPath{

    use HasStyles; 
    protected bool $save_id_data = false;

    final public function __construct( WP_Post $post, bool $save_id_data = false ){
        $this->post = $post;
        $this->save_id_data = $save_id_data;
    }

    public function getData(){

        $data = [
            'name' => $this->post->post_title,
            'slug' => $this->post->post_name,
            'id' => $this->post->ID
        ];

        return $data;

   
    }

    public function getPath(){
        return DYNAMIC_STATIC_LAYOUT_GENERATED_PATH . 
            "/" .
            'special'.
            "/" .
            ( $this->save_id_data ? $this->post->ID : $this->post->post_name ) .
            "/" .
            "data.json";
    }

}
