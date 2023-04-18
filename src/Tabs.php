<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Tabs;

use Contao\Backend;
use Contao\ContentModel;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\DataContainer;
use Contao\Input;
use Contao\Message;
use Contao\System;

class Tabs extends Backend
{
    const NAME_SM_CONFIG = 'tm-config';

    private string $rootDir;

    public function __construct()
    {
        parent::__construct();
        System::loadLanguageFile('tl_thememanager_settings');
        $this->rootDir = System::getContainer()->getParameter('kernel.project_dir');
    }

    public function showJsLibraryHint(DataContainer $dc): void
    {
        if ($_POST || Input::get('act') != 'edit')
        {
            return;
        }

        $security = System::getContainer()->get('security.helper');

        // Return if the user cannot access the layout module (see #6190)
        if (!$security->isGranted(ContaoCorePermissions::USER_CAN_ACCESS_MODULE, 'themes') || !$security->isGranted(ContaoCorePermissions::USER_CAN_ACCESS_LAYOUTS))
        {
            return;
        }

        $objCte = ContentModel::findByPk($dc->id);

        if ($objCte === null)
        {
            return;
        }

        switch ($objCte->type)
        {
            case 'tabStart':
                Message::addInfo(sprintf(($GLOBALS['TL_LANG']['tl_thememanager_settings']['includeCtmTemplate'] ?? null), 'js_ctm_tabs'));
                break;
        }
    }
}
