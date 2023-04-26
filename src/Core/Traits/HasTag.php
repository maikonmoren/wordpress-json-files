<?php 

namespace DynamicStaticLayout\Core\Traits;

trait HasTag{

    public function setTag( string $tag ){
        $this->tag = $tag;
    }

    public function getTag(): string {
        return $this->tag;
    }

    public function displayTag(){
        echo $this->tag;
        return $this;
    }
}