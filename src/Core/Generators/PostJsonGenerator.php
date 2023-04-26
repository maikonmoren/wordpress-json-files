<?php 

namespace DynamicStaticLayout\Core\Generators;

use DynamicStaticLayout\Core\Contracts\HasPath;
use WP_Post;

class PostJsonGenerator extends JsonGenerator implements HasPath{

        
    protected bool $save_id_data = false;

    final public function __construct( WP_Post $post, bool $save_id_data = false ){
        $this->post = $post;
        $this->save_id_data = $save_id_data;
    }

    public function getData(){

        $templates = [
            "single-{$this->post->post_type}-{$this->post->post_name}.php",
            "single-{$this->post->post_type}.php",
            "single.php",
            "singular.php", 
            "index.php"
        ];

        if( $this->post->post_type === "page" ){
            $templates = [
                "page-{$this->post->post_name}.php",
                "page-{$this->post->ID}.php",
                "page.php",
                "singular.php",
                "index.php"
            ];
        }
        if($this->post->post_type === "article_columnist"){
            $templates = [
                "article_columnist.php",
            ];
        }
        
        if($this->post->post_name === "especiais-publicitarios"){
            $templates = [
                "page-especiais-publicitarios.php",
            ];
        }

        $template = locate_template( $templates, false, true ) ?? '';

        $template = explode( "/", $template );
        $template = end( $template );
        $template = str_replace( ".php", "", $template );

        $data = json_decode( json_encode( $this->post ), true );
        $data['link'] = str_replace( home_url(), '', get_post_permalink( $this->post->ID ) );
        $data['path'] = str_replace( home_url(), '', get_post_permalink( $this->post->ID ) );
        $data['permalink'] = get_post_permalink( $this->post->ID );
        $data['slug'] = $this->post->name;
        $data['template'] = $template;
        $data['post_content'] = do_blocks(  $this->post->post_content  );
        $data['tags'] = get_the_terms( $this->post, 'post_tag' ) ?? [];
        $data['categories'] = get_the_terms( $this->post, 'category' ) ?? [];
        $data['post_excerpt'] = get_field('subtitle', $this->post->ID);

        $postDate = date_create($this->post->post_date);
        $postModified = date_create($this->post->post_modified);
        $interval = date_diff($postDate, $postModified);

        $get_date = ($interval->format('%i') == 0) ? get_the_date("d/m/Y - H\hi", $this->post ) : get_the_date("d/m/Y - H\hi", $this->post ) . ' - Atualizada em: ' . get_the_modified_date("d/m/Y - H\hi", $this->post );
        $data['date_formated'] = $get_date;


        $data['author'] = [
            "id" => $this->post->post_author,
            "name" => get_the_author_meta( "nickname", $this->post->post_author ),
            "email" => get_the_author_meta( "email", $this->post->post_author ),
        ];

        $thumb_id = get_post_thumbnail_id( $this->post );
        $helper = function( ?int $thumb_id, string $size ){
            if( !$thumb_id ) return false;

            $data = array_map( fn( $d ) => current( $d ), get_post_meta( $thumb_id ) );
            return array_merge(
                $data,
                [ "url" => get_the_post_thumbnail_url( $this->post, $size ) ]
            );
        };

        $thumbnails = get_field('main_image', $this->post->ID);
        $image_credits = get_field('image_credits', $this->post->ID);

        $url = '';
        if(isset($thumbnails['url']) && !empty($thumbnails['url'])){
            $alt = (isset($thumbnails['caption']) && !empty($thumbnails['caption'])) ? $thumbnails['caption'] : (isset($thumbnails['alt']) && !empty($thumbnails['alt']) ? $thumbnails['alt'] : '');
            $url = '<img class="object-contain w-full" src="' . $thumbnails['url'] . '" alt="'. $alt .'" />';
        } elseif(isset($thumbnails) && (isset($thumbnails['url']) && empty($thumbnails['url']))) {
            $src = wp_get_attachment_image_src($thumbnails);
            
            $alt = get_post_meta( $thumbnails, '_wp_attachment_image_alt', true);
            $image_credits = get_post_meta( $this->post->ID, 'image_credits', true);
            $url = '<img class="object-contain w-full" src="' .  $src[0] . '" alt="'. $alt .'" />';
        }

        $cats = get_the_category();
        $content = "conteudo";
        foreach ($cats as $cat) {
          if ($cat->slug === "especiais-publicitarios") {
            $content = "especiais";
          }
        }

        $in_image_conf = json_encode([
            "sizes" => [
              [320, 50],
              [300, 50],
              [798, 90],
            ],
            'size_mapping' => [
              [
                'minWidth' => 0,
                'minHeight' => 0,
                'sizes' => [
                  [320, 50],
                  [300, 50],
                ]
              ],
              [
                'minWidth' => 1000,
                'minHeight' => 0,
                'sizes' => [
                  [798, 90],
                ]
              ],
            ],
            "targeting" => [
              'format' => ['in_image']
            ],
            'content' => $content
          ]);
          
       $url .= `
       <div class="absolute bottom-0 left-0 w-full bg-white md:hidden">
       <div class="adSlot__container bg-white flex justify-center overflow-hidden">
         <div class="adSlot m-auto" style='min-width: 300px; display:none;' data-no-margin='1' data-config='$in_image_conf' data-closable='1'>
         </div>
       </div>
     </div>`;


        $data['thumbnails'] = [
            'thumbnail' => $helper( $thumb_id, 'thumbnail', ),
            'medium' => [
                "_wp_attachment_image_alt" => isset($alt) && !empty($alt) ? $alt : '',
                "url" => $url,
                "image_credits" => isset($image_credits) && !empty($image_credits) ? ' ' . $image_credits . '' : ''
            ],
            'large' => $helper( $thumb_id, 'large', ),
            'full' => $helper( $thumb_id, 'full', ),
        ];
    
        return $data;
    }

    public function getPath(){
        return DYNAMIC_STATIC_LAYOUT_GENERATED_PATH . 
            "/" .
            'posts'.
            "/" .
            ( $this->save_id_data ? $this->post->ID : $this->post->post_name ) .
            "/" .
            "data.json";
    }

}
