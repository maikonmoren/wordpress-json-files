<?php 

namespace DynamicStaticLayout\Core\Generators;

use DynamicStaticLayout\Core\Contracts\HasPath;
use DynamicStaticLayout\Core\Traits\HasStyles;
use WP_Term;

class TermJsonGenerator extends JsonGenerator implements HasPath{

    use HasStyles;
    protected WP_Term $term;
    protected bool $save_id_data = false;

    final public function __construct( WP_Term $term, bool $save_id_data = false ){
        $this->term = $term;
        $this->save_id_data = $save_id_data;
    }

    public function getData(): array{

        $data = [
            'name' => $this->term->name,
            'slug' => $this->term->slug,
            'id' => $this->term->term_id
        ];

        return $data;
    }

    public function getPath(){
        return DYNAMIC_STATIC_LAYOUT_GENERATED_PATH . 
            "/" .
            'terms' .
            "/".
            $this->term->taxonomy. 
            "/" . 
            ( $this->save_id_data ? $this->term->term_id : $this->term->slug ) .
            "/" .
            "data.json";
    }

}