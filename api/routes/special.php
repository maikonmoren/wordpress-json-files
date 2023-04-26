<?php

add_action( 'rest_api_init', function () {
    register_rest_route( 'dynamic-static-layout', '/specials/(?P<slug>[^\/].*)', array(
      'methods' => 'GET',
      'permission_callback' => fn() => true,
      'callback' => function( WP_REST_Request $request ){

            $slug = $request->get_param('slug');

            $args = array(
                'name'        => $slug,
                'post_status' => 'publish',
                'numberposts' => 1,
                'post_type' => [ "page"],
                'no_found_rows' => true
            );


            $post = get_posts($args);
            

            if( !$post ) return new WP_REST_Response( null, 404 );


            require ABSPATH . "wp-load.php";
            
            /**
             * @var WP_post
             */
            $post = current( $post );
            do_action('save_especial', $post);
            
            $full_path = DYNAMIC_STATIC_LAYOUT_GENERATED_PATH . "/specials/{$slug}/data.json";

            if( is_file( $full_path ) ){
                $json = file_get_contents( $full_path );
                if( $data = json_decode( $json ) )
                    return $data;
            }
            
            return new WP_REST_Response( null, 404 );
        }
    ));
});
  