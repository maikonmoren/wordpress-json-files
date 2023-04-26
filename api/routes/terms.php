<?php

use DynamicStaticLayout\Helpers\BufferHelper;

add_action( 'rest_api_init', function () {
    register_rest_route( 'dynamic-static-layout', '/terms/(?P<taxonomy>[^\/].*)/(?P<slug>[^\/].*)', array(
      'methods' => 'GET',
      'permission_callback' => fn() => true,
      'callback' => function( WP_REST_Request $request ){

            $taxonomy = $request->get_param('taxonomy');
            $slug = $request->get_param('slug');

            $term = get_term_by('slug', $slug, $taxonomy);

            if( !$term )
              return new WP_REST_Response( "{$taxonomy} {$slug} not found", 404 );
              
            $full_path = DYNAMIC_STATIC_LAYOUT_GENERATED_PATH . "/terms/{$taxonomy}/{$slug}/data.json";

            if( is_file( $full_path )  ) @unlink( $full_path );

            do_action( 'saved_term', $term->term_id, $term->term_taxonomy_id, $taxonomy, true );

            if( is_file( $full_path ) ){
                $json = file_get_contents( $full_path );
                if( $data = json_decode( $json ) )
                    return $data;
            }

            return new WP_REST_Response( 404 );
        }
    ));
});
  