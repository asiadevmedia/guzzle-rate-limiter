<?php

namespace Asiadevmedia\GuzzleRateLimiter;

interface Deferrer
{
    public function getCurrentTime(): int;

    public function sleep(int $milliseconds);
}
