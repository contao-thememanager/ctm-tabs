<?php

use ContaoThemeManager\Tabs\Controller\ContentElement\TabStartController;
use ContaoThemeManager\Tabs\Controller\ContentElement\TabStopController;

$GLOBALS['TL_DCA']['tl_content']['palettes'][TabStartController::TYPE] = '{type_legend},type;{tab_legend},tabLabel,tabGroup;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes'][TabStopController::TYPE]  = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['tabLabel'] = [
    'search'    => true,
    'inputType' => 'text',
    'eval'      => ['maxlength'=>64, 'mandatory' => true, 'tl_class'=>'w50'],
    'sql'       => "varchar(64) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['tabGroup'] = [
    'search'    => true,
    'inputType' => 'text',
    'eval'      => ['maxlength'=>64, 'tl_class'=>'w50'],
    'sql'       => "varchar(64) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['ContaoThemeManager\Tabs\Tabs', 'showJsLibraryHint'];
