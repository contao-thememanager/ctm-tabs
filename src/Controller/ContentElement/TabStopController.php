<?php

declare(strict_types=1);

namespace ContaoThemeManager\Tabs\Controller\ContentElement;

use Contao\BackendTemplate;
use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\System;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(TabStopController::TYPE, category:'tabs', template:'ce_tabStop')]
class TabStopController extends AbstractContentElementController
{
    public const TYPE = 'tabStop';

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        // Do not display template in backend
        if (System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
        {
            $template = new BackendTemplate('be_wildcard');
        }

        return $template->getResponse();
    }
}
