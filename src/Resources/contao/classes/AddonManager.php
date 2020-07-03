<?php
/**
 * This file is part of Contao EstateManager.
 *
 * @link      https://www.contao-estatemanager.com/
 * @source    https://github.com/contao-estatemanager/wib-import
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

namespace ContaoEstateManager\WibImport;

use Contao\Config;
use Contao\Environment;
use ContaoEstateManager\EstateManager;

class AddonManager
{
    /**
     * Bundle name
     * @var string
     */
    public static $bundle = 'EstateManagerWibImport';

    /**
     * Package
     * @var string
     */
    public static $package = 'contao-estatemanager/wib-import';

    /**
     * Addon config key
     * @var string
     */
    public static $key  = 'addon_wib_import_license';

    /**
     * Is initialized
     * @var boolean
     */
    public static $initialized  = false;

    /**
     * Is valid
     * @var boolean
     */
    public static $valid  = false;

    /**
     * Licenses
     * @var array
     */
    private static $licenses = [
        '3d92dec581fd2ecf197725fe6659f699',
        'b73499d07d28af907851f731bf1428df',
        '3601eea846c87d47081a2e31f120a868',
        '905fff2122722f270158be01b5192543',
        'e126cb8b64881a555c30131bcb2e9e83',
        'c48cf6b796ed3376c85bf1986c1cbdba',
        'd71400d6974e5c6de4e88aeb52742526',
        '3e952b43fd5e408f6792a2f3347c5322',
        'b9b9fd02945480183279d2c799f3605a',
        'f0d2516c177ced7f871429efd07d7c54',
        '82cd09a3ad8e53d515338ac977b38377',
        '51716e5c849638641d3bbf3fcdaacf93',
        '5205a6e1acc44dbec441eee4ed892108',
        'c226736deba8e6e8cdfc2f7ada7b9694',
        '463e70ee7a51116f631a4050aaf05828',
        'f09c356c94d91243a01384556be7b3ca',
        'd353b0bf52faa68c1dc8952bc8046dc3',
        '4a7b9897114e9c596d52dbaa08793423',
        '2f87ba4046067f7c488fad4efd5fa5aa',
        '3a7de41820578fb55a68e03ace438dc3',
        '965ea414ba59cffd5475a2c9d7f0e991',
        'e506dc7fc1d9ed3de4e652cb6f610bd8',
        '07036cf37f711bffa0873b68cdd11bbd',
        'ae16e0e9a55ccf33595d5b4e8fe33b9f',
        'c083c7c910b40dcf7720baed3137166a',
        '2e03e7f30c8fa79a50cec449c9ef390c',
        'f3cd7d8ee9118a96dbf767daed343176',
        'b0fa09aba357429dac60ea155d28de75',
        'ab5da6bebf02fbafbc1b79fc05eec8dd',
        'fa832819e5cef8a527a79bc664376302',
        'b70a0df14494b233636e1ca56412090c',
        'a0a9df589b09398b57476e4c2510bff7',
        '6a6ff0e2da06a5f5fdff42184ae20267',
        'ec214c2200996b5db4dd8445f63335fc',
        '0a2205e303c6f39ada20d76d65400b00',
        '760a2c8b9cbab8533f7711eddb19ffad',
        'bc47729f0be584eb8e57a4562bb4fa17',
        'd9642c8e57d80b7b23ba56bc14f761cd',
        '9289b2515417c5f36291541289ef6888',
        '2872b79c6568458f0dab17a6d9ec5623',
        '8fe6d72ea8b3ae4ede20f5292ccf7492',
        'ec840ab28cf8948697ed9a24c88c6b4e',
        'c2a3ca5549307a1122ca57562ac248d2',
        '7d0a10e13d931d934bc9bd9a557a1e85',
        'ebe10008e49cf10d56f01e0a750fa0be',
        '68d4966d47350a5ab1c23b66baf81a32',
        'fbbf05d757c86332b5e2df4241349253',
        '897d4f3bfd14220e178589f70da2e43b',
        '0a9e3c576108c4f3b57c589d5a730caf',
    ];

    public static function getLicenses()
    {
        return static::$licenses;
    }

    public static function valid()
    {
        if(strpos(Environment::get('requestUri'), '/contao/install') !== false)
        {
            return true;
        }

        if (static::$initialized === false)
        {
            static::$valid = EstateManager::checkLicenses(Config::get(static::$key), static::$licenses, static::$key);
            static::$initialized = true;
        }

        return static::$valid;
    }

}
