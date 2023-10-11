<?php

namespace App\Movie\Search\Transformer;

interface TransformerInterface
{
    public function transform(mixed $value): mixed;
}
