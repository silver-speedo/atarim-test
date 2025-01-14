<?php

namespace App\Contracts;

interface UrlShortenerContract
{
    public function encode(string $url): string;

    public function decode(string $url): string;
}
