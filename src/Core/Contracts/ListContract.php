<?php 

namespace DynamicStaticLayout\Core\Contracts;


interface ListContract extends Jsonable{

    public function getHandlerPath(): string;
    public function setChunkSize( int $size ): self;
    public function getChunkSize(): int;
    
    public function getData(): array;
    
    public function setHandlerArgs( array $args = [] ): self;
    public function getHandlerArgs( ): array;
}