<?php

namespace App\Movie\Search\Provider;

interface ProviderInterface
{
    public function getOne(string $value): mixed;
}
