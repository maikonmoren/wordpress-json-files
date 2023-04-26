<?php

use DynamicStaticLayout\Core\Generators\ColumnistTemplateGenerator;

add_action( 'rest_api_init', function () {
    register_rest_route( 'dynamic-static-layout', '/columnists/(?P<slug>[^\/].*)', array(
      'methods' => 'GET',
      'permission_callback' => fn() => true,
      'callback' => function( WP_REST_Request $request ){

            $slug = $request->get_param('slug');

            $user = get_user_by('slug',$slug);

            if( !$user ) return new WP_REST_Response( null, 404 );

            require ABSPATH . "wp-load.php";
            
            /**
             * @var WP_User
             */
          
            do_action('saved_columnist', $user);
        
            $full_path = DYNAMIC_STATIC_LAYOUT_GENERATED_PATH . "/columnist/{$slug}/data.json";
           
            if( is_file( $full_path ) ){
                $json = file_get_contents( $full_path );
                if( $data = json_decode( $json ) )
                    return $data;
            }
            
            return new WP_REST_Response( null, 404 );
        }
    ));
});
  