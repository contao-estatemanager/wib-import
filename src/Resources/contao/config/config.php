<?php
/**
 * This file is part of Contao EstateManager.
 *
 * @link      https://www.contao-estatemanager.com/
 * @source    https://github.com/contao-estatemanager/wib-import
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

// ESTATEMANAGER
$GLOBALS['TL_ESTATEMANAGER_ADDONS'][] = array('ContaoEstateManager\WibImport', 'AddonManager');

if(ContaoEstateManager\WibImport\AddonManager::valid()) {
    // HOOKS
    $GLOBALS['TL_HOOKS']['realEstateImportBeforeSync'][]       = array('ContaoEstateManager\WibImport\WibImport', 'manuallyDownloadOpenImmoFile');
    $GLOBALS['TL_HOOKS']['realEstateImportBeforeCronSync'][]   = array('ContaoEstateManager\WibImport\WibImport', 'downloadOpenImmoFile');
    $GLOBALS['TL_HOOKS']['realEstateImportPrePrepareRecord'][] = array('ContaoEstateManager\WibImport\WibImport', 'skipPartnerRecord');
    $GLOBALS['TL_HOOKS']['realEstateImportSaveImage'][]        = array('ContaoEstateManager\WibImport\WibImport', 'downloadImage');
}
