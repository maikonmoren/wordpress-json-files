<?php

namespace DynamicStaticLayout\Core\Contracts;

interface TemplatePart {
    public static function getTemplatePart( string $name, string $slug, array $args );
}