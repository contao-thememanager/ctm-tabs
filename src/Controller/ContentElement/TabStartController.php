<?php

declare(strict_types=1);

namespace ContaoThemeManager\Tabs\Controller\ContentElement;

use Contao\BackendTemplate;
use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\Date;
use Contao\StringUtil;
use Contao\System;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(TabStartController::TYPE, category:'tabs', template:'ce_tabStart')]
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
                    foreach ($elements as $element)
                    {
                        switch ($element->type)
                        {
                            case 'tabStop':
                                $index--;
                                break;

                            case 'tabDivider':
                                $this->setTabId($element);
                                self::$tabGroups[$groupIds[$index]]['tabs'][$element->id] = clone $element;
                                break;

                            case self::TYPE:
                                $index++;
                                $groupIds[$index] = $element->id;

                                $this->setTabId($element);

                                self::$tabGroups[$element->id] = [
                                    'group' => $element->id,
                                    'tabs' => [$element->id => clone $element]
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

    private function setTabId(ContentModel &$element): void
    {
        $element->tabId = (StringUtil::deserialize($element->cssID, true)[0] ?? null) ?: $element->id;
    }
}
