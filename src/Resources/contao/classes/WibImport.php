<?php
/**
 * This file is part of Contao EstateManager.
 *
 * @link      https://www.contao-estatemanager.com/
 * @source    https://github.com/contao-estatemanager/project
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

namespace ContaoEstateManager\WibImport;


use Contao\File;
use Contao\FilesModel;
use Contao\Input;
use ContaoEstateManager\FilesHelper;

/**
 * Class WibImport
 * @package ContaoEstateManager\Project
 * @author  Fabian Ekert <fabian@oveleon.de>
 */
class WibImport
{
    /**
     * Download an WIB open immo file manually
     *
     * @param $context
     */
    public function manuallyDownloadOpenImmoFile($context): void
    {
        if ($context->interface->type === 'wib')
        {
            if (Input::get('downloadWibXml'))
            {
                $this->downloadOpenImmoFile($context);
            }

            $context->updateSyncTime = false;
        }
    }

    /**
     * Download an WIB open immo file
     *
     * @param $context
     */
    public function downloadOpenImmoFile($context): void
    {
        $objInterface = $context->interface;

        $syncTime = time();
        $syncUrl = html_entity_decode($objInterface->wibSyncUrl) . '&lastChange=' . $objInterface->lastSync;
        $fileName = 'export_' . $syncTime . '.xml';

        $content = $this->getFileContent($syncUrl);

        if (strpos($content, 'uebertragung') !== false)
        {
            File::putContent($context->importFolder->path . '/' . $fileName, $content);

            $objInterface->lastSync = $syncTime;
            $objInterface->save();

            $context->updateSyncTime = false;
        }
        else
        {
            //\Message::addInfo('The downloaded file was empty and has been skipped.');
        }
    }

    /**
     * Check if record need to be skipped
     *
     * @param $realEstate
     * @param $re
     * @param $contactPerson
     * @param $skip
     * @param $context
     */
    public function skipPartnerRecord($realEstate, &$re, &$contactPerson, &$skip, $context): void
    {
        if ($context->interface->type === 'wib')
        {
            $re['AUFTRAGSART'] = $this->getWibAuftragsart($realEstate, $context);

            if ($re['ANBIETER'] !== $context->interface->anbieternr)
            {
                if (in_array($re['AUFTRAGSART'], array('R', 'V', 'S', 'SB', 'Z', 'G')))
                {
                    $skip = true;
                }
            }
        }
    }

    /**
     * Return field "Auftragsart" from real estate xpath object
     *
     * @param $realEstate
     * @param $context
     *
     * @return string
     */
    protected function getWibAuftragsart($realEstate, $context): string
    {
        $groups = $realEstate->xpath('verwaltung_objekt');

        foreach ($groups as $group)
        {
            // Skip if condition dont match
            if ($context->getFieldData('user_defined_simplefield@feldname', $group) !== 'auftragsart')
            {
                continue;
            }

            $value = $context->getFieldData('user_defined_simplefield', $group);

            // Skip if value is not set
            if ($value === null)
            {
                continue;
            }

            return $value;
        }

        return '';
    }

    public function downloadImage($objFilesFolder, &$value, $tmpGroup, &$values, &$skip, $context): void
    {
        if ($context->interface->type === 'wib')
        {
            $format = current($tmpGroup->format);
            $check = next($tmpGroup->check);

            $fileName = $this->getValueFromStringUrl($value, 'imageId');

            $extension = $this->getExtension($format);

            // Skip image if no file extension could be determined
            if ($extension === false)
            {
                $skip = true;
                return;
            }

            $completeFileName = $fileName . $extension;

            $existingFile = FilesModel::findByPath($objFilesFolder->path . '/' . $context->uniqueProviderValue . '/' . $context->uniqueValue . '/' . $completeFileName);

            if ($existingFile !== null && $existingFile->hash === $check)
            {
                $values[] = $existingFile->uuid;
                $skip = true;
                return;
            }

            $this->downloadFile($value, $context->importFolder, $completeFileName);

            $fileSize = FilesHelper::fileSize($context->importFolder->path . '/tmp/' . $completeFileName);
            if ($fileSize > 3000000 || $fileSize === 0)
            {
                $skip = true;
                return;
            }

            $value = $completeFileName;
        }
    }

    protected function getValueFromStringUrl($url, $parameter)
    {
        $parts = parse_url($url);
        if (isset($parts['query']))
        {
            parse_str($parts['query'], $query);
            if (isset($query[$parameter]))
            {
                return $query[$parameter];
            }
        }

        return null;
    }

    protected function getExtension($format)
    {
        switch ($format)
        {
            case 'image/jpeg':
            case 'image/jpg':
                $extension = '.jpg';
                break;
            case 'image/png':
                $extension = '.png';
                break;
            case 'image/gif':
                $extension = '.gif';
                break;
            case 'application/pdf':
                $extension = '.pdf';
                break;
            case 'application/octet-stream':
                return false;
                break;
            default:
                if (strpos('/', $format) === false)
                {
                    $extension = '.' . strtolower($format);
                }
                else
                {
                    return false;
                }
        }

        return $extension;
    }

    protected function downloadFile($path, $targetDirectory, $fileName, $tmpFolder=true): void
    {
        $content = $this->getFileContent($path);

        File::putContent($targetDirectory->path . '/' . ($tmpFolder ? 'tmp/' : '') . $fileName, $content);
    }

    protected function getFileContent($path)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
}
