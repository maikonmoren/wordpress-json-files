<?php 

namespace DynamicStaticLayout\Core\Parsers;

use DynamicStaticLayout\Core\Generators\ComponentGenerator;
use ListParser;

class ComponentsParser {
    public static function parse( string $content = '', array $data = [] ): string {
        $matches = [];
        $matched = preg_match_all(
            '/(\{\{+\s?+cmp\:)(?<path>[^\}]*?)(?:\s?+\}\})/', 
            $content,
            $matches, 
            PREG_PATTERN_ORDER
        );
        if( !$matched ) return $content;

        $paths = $matches['path'];
        foreach( $matches[0] as $index => $tag ){
            $component = ComponentGenerator::getTemplatePart( $paths[$index] );
            $content = str_replace( $matches[0][$index], $component->getParsedContent( $data ), $content );
        }

        return ListParser::parse( $content );   
    }
}