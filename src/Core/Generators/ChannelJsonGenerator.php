<?php 

namespace DynamicStaticLayout\Core\Generators;

use DynamicStaticLayout\Core\Contracts\HasPath;
use DynamicStaticLayout\Core\Traits\HasStyles;
use WP_Post;


class ChannelJsonGenerator extends JsonGenerator implements HasPath{

    use HasStyles;
    protected WP_Post $post;

    final public function __construct( WP_Post $post){
        $this->post = $post;
    }

    public function getData(): array{

        $data = [
            'slug' => $this->post->post_name,
        ];

        return $data;
    }

    public function getPath(){
       
        return DYNAMIC_STATIC_LAYOUT_GENERATED_PATH . "/" . 
        'publicitarios'. '/' . 
        $this->user->user_nicename.  
        '/data.json';
}
    }

