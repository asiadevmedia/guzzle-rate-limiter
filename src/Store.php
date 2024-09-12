<?php

namespace Asiadevmedia\GuzzleRateLimiter;

interface Store
{
    public function get(): array;

    public function push(int $timestamp, int $limit);
}
