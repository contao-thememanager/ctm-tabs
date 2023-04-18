<?php

declare(strict_types=1);

namespace ContaoThemeManager\Tabs;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoThemeManagerTabs extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
