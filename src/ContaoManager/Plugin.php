<?php

declare(strict_types=1);

namespace ContaoThemeManager\Tabs\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use ContaoThemeManager\Tabs\ContaoThemeManagerTabs;
use ContaoThemeManager\Core\ContaoThemeManagerCore;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoThemeManagerTabs::class)
                ->setLoadAfter([
                    ContaoCoreBundle::class,
                    ContaoThemeManagerCore::class,
                ])
                ->setReplace(['ctm-tabs']),
        ];
    }
}
