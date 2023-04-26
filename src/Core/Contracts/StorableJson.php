<?php 

namespace DynamicStaticLayout\Core\Contracts;

interface StorableJson {
    public static function store( HasPath $path, Jsonable $contents );
}