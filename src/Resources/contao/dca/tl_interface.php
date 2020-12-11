<?php
/**
 * This file is part of Contao EstateManager.
 *
 * @link      https://www.contao-estatemanager.com/
 * @source    https://github.com/contao-estatemanager/wib-import
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

if(ContaoEstateManager\WibImport\AddonManager::valid()) {

    // Add type option
    $GLOBALS['TL_DCA']['tl_interface']['fields']['type']['options'][] = 'wib';

    // Add fields
    $GLOBALS['TL_DCA']['tl_interface']['fields']['wibSyncUrl'] = array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_interface']['wibSyncUrl'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'long'),
        'sql'                     => "varchar(255) NOT NULL default ''"
    );

    // Add palettes
    $GLOBALS['TL_DCA']['tl_interface']['palettes']['wib'] = '{title_legend},title,type;{oi_field_legend},provider,anbieternr,uniqueField,importPath,filesPath,filesPathContactPerson;{related_records_legend},contactPersonActions,contactPersonUniqueField,importThirdPartyRecords;{expert_legend},skipRecords,dontPublishRecords;{sync_legend},autoSync,deleteFilesOlderThen,wibSyncUrl,filesPerSync';
}
