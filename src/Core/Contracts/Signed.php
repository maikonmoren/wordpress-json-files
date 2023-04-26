<?php 

namespace DynamicStaticLayout\Core\Contracts;

interface Signed{
    public function sign( string $uniq = '' ): self;
    public function getSignature(): string;
}