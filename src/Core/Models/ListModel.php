<?php 


namespace DynamicStaticLayout\Core\Models;

use Closure;
use DynamicStaticLayout\Core\Contracts\Jsonable;
use DynamicStaticLayout\Core\Contracts\ListContract;

class ListModel implements ListContract, Jsonable {

    protected Closure $callable;
    protected array $args = [];
    protected int $chunk_size = -1;

    public function __construct( Closure $callable, array $args = [] ){
        $this->callable = $callable;
        $this->args = $args;
    }

    public function setHandlerArgs( array $args = [] ): self
    {
        $this->args = $args;
        return $this;
    }
    
    public function getHandlerArgs( ): array {
        return $this->args;
    }

    public function setChunkSize( int $size ): self{
        $this->chunk_size = $size;
        return $this;
    }

    public function getChunkSize(): int{
        return $this->chunk_size;
    }

    public function getHandlerPath(): string
    {
        return $this->handle_path;
    }

    public function getData(): array{
        return call_user_func_array( $this->callable, $this->args );
    }

    public function toJson(): string
    {
        return json_encode( [
            "list" => $this->getData(),
            "args" => $this->args,
            "chunk_size" => $this->chunk_size
        ] );
    }
}