<?php

declare(strict_types=1);

namespace ContaoThemeManager\Tabs\Controller\ContentElement;

use Contao\BackendTemplate;
use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\Date;
use Contao\System;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(TabStartController::TYPE, category:'tabs')]
class TabStartController extends AbstractContentElementController
{
    public const TYPE = 'tabStart';

    private static array $tabGroups = [];

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        $container = System::getContainer();

        // Do not display template in backend
        if ($container->get('contao.routing.scope_matcher')->isBackendRequest($request))
        {
            $template = new BackendTemplate('be_wildcard');
            $template->title = $model->tabLabel;
        }
        else
        {
            // Initialize tab group cache if it does not exist
            if (null === self::getTabLabels($model->id))
            {
                $cols = ["tl_content.pid=?"];

                if (!$container->get('contao.security.token_checker')->isPreviewMode())
                {
                    $time   = Date::floorToMinute();
                    $cols[] = "tl_content.invisible='' 
                           AND (tl_content.start='' OR tl_content.start<='$time') 
                           AND (tl_content.stop='' OR tl_content.stop>'$time')";
                }

                $elements = ContentModel::findBy(
                    $cols,
                    [$model->pid],
                    ['order' => 'sorting ASC']
                );

                if (null !== $elements)
                {
                    $index     = -1;
                    $groupIds  = [];

                    // Collect tab labels (recursively)
                    while ($elements->next())
                    {
                        switch ($elements->type)
                        {
                            case 'tabStop':
                                $index--;
                                break;

                            case 'tabDivider':
                                self::$tabGroups[$groupIds[$index]]['tabs'][$elements->id] = $elements->tabLabel;
                                break;

                            case self::TYPE:
                                $index++;
                                $groupIds[$index] = $elements->id;

                                self::$tabGroups[$elements->id] = [
                                    'group' => $elements->id,
                                    'tabs' => [$elements->id => $elements->tabLabel]
                                ];
                        }
                    }
                }
            }

            if (null !== ($tabGroup = self::getTabLabels($model->id)))
            {
                $template->tabNavElements = $tabGroup;
            }
        }

        return $template->getResponse();
    }

    /**
     * Gets the previously set tabs
     */
    private function getTabLabels($id): ?array
    {
        if (array_key_exists($id, self::$tabGroups))
        {
            return self::$tabGroups[$id]['tabs'];
        }

        return null;
    }
}
