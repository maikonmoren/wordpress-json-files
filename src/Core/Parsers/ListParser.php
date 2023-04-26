<?php

use DynamicStaticLayout\Core\Parsers\ComponentsParser;

class ListParser {

    public static function parse( string $content = '' ): string {
        $matches = [];
        $matched = preg_match_all(
            '/(\{\{+\s?+list\:)(?<hash>[^\}]*?)(?:\s?+\}\})/', 
            $content,
            $matches, 
            PREG_PATTERN_ORDER
        );
        if( !$matched ) return $content;

        $paths = $matches['hash'];
        foreach( $matches[0] as $index => $tag ){

            $list = '';
            $file_path = DYNAMIC_STATIC_LAYOUT_GENERATED_PATH . "/lists/{$paths[$index]}/data.json";
            if( is_file( $file_path ) ){
                $contents = file_get_contents( $file_path );
                $data = json_decode( $contents, true );
                $items = @$data['list'] ?? [];
                if( @$data['chunk_size'] > 0 ){
                    $items = array_slice( $items, 0, $data['chunk_size'] );
                }
                foreach( $items as $item ){
                    $list .= ComponentsParser::parse( $data['component'], $item );
                }
            }
            $content = str_replace( $matches[0][$index], $list, $content );
        }

        return ComponentsParser::parse( $content );   
    }

}