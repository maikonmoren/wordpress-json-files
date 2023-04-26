<?php 

namespace DynamicStaticLayout\Core\Generators;

use DynamicStaticLayout\Core\Contracts\HasPath;
use WP_Query;
use WP_Term;

final class TermPostListJsonGenerator extends JsonGenerator implements HasPath{

    protected WP_Term $term;
    protected array $cached_posts;

    final public function __construct( WP_Term $term ){
        $this->term = $term;
    }

    public function getData():array {

        if( !isset( $this->cached_posts ) ){
            $query = new WP_Query([
                'posts_per_page' => -1,
                'tax_query' => [
                    'terms' => $this->term->term_id,
                ]
            ]);
            if( $query->have_posts() ){
                while( $query->have_posts() ){
                    $query->the_post();
                    $this->cached_posts[] = get_the_ID();
                }
                wp_reset_query();
            }else{
                $this->cached_posts = [];
            }
        }

        return $this->cached_posts;
    }

    public function getPath(){
        return DYNAMIC_STATIC_LAYOUT_GENERATED_PATH . 
            "/" .
            'terms' .
            "/" . 
            $this->term->term_id .
            "/" .
            'posts' .
            "/" .
            "full-list.json";
    }

}