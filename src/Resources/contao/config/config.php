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

use ContaoEstateManager\WibImport\AddonManager;
use ContaoEstateManager\WibImport\WibImport;

if(AddonManager::valid()) {
    // HOOKS
    $GLOBALS['CEM_HOOKS']['realEstateImportBeforeSync'][]       = array(WibImport::class, 'manuallyDownloadOpenImmoFile');
    $GLOBALS['CEM_HOOKS']['realEstateImportBeforeCronSync'][]   = array(WibImport::class, 'downloadOpenImmoFile');
    $GLOBALS['CEM_HOOKS']['realEstateImportPrePrepareRecord'][] = array(WibImport::class, 'skipPartnerRecord');
    $GLOBALS['CEM_HOOKS']['realEstateImportSaveImage'][]        = array(WibImport::class, 'downloadImage');
}
