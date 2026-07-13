<?php

namespace Models;

use JsonSerializable;
use Override;

class Model implements JsonSerializable
{
    public function __construct(public readonly int $id) 
    {}

    #[Override]
    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
        ];
    }
}