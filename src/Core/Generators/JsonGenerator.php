<?php 

namespace DynamicStaticLayout\Core\Generators;

use DynamicStaticLayout\Core\Contracts\Jsonable;

abstract class JsonGenerator implements Jsonable{


    abstract public function getData();

    final public function toJson()
    {
        return json_encode( $this->getData(), JSON_PRETTY_PRINT );
    }
}