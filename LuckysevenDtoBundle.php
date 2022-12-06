<?php

namespace Luckyseven\Bundle\LuckysevenDtoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LuckysevenDtoBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
