<?php
/**
 *  This file is part of Dfbuilder.
 *
 *     Dfbuilder is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     Dfbuilder is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with Dfbuilder.  If not, see <http://www.gnu.org/licenses/>
 */
namespace App\DfCore\DfBs\FileWriter;

use App\DfCore\DfBs\Log\DfbuilderLogger;
use App\DfCore\DfBs\Log\LoggerFacade;
use File;
use App\DfCore\DfBs\Import\Remote\RemoteFileService;
use League\Csv\Writer;

class FeedWriter
{



    /**
     * WriteFeed constructor.
     * @param $to_file_name
     */
    public function __construct()
    {
        RemoteFileService::generateStorageDirs();
    }


    /**
     * @param $feed_type
     * @param $feed_id
     * @return bool
     */
    public static function removeTmpFeedFile($feed_type,$feed_id)
    {

        $filename  = DOWNLOAD_FOLDER.'/'.$feed_type.'/'.$feed_id.'.'.$feed_type;
        $file_exists = file_exists($filename);
        if($file_exists) {
            unlink($filename);
        }
        return $file_exists;
    }


    /**
     * @param $file_name
     * @return array
     */
    public static function detectFeedType($file_name)
    {

        $file_name = file_get_contents($file_name,null,null,0,1000);
        /**
         * Set default namespace for rss feed
         */
        if(
            strpos($file_name ,'rss') !== false
            && strpos($file_name ,'channel') !== false
            &&  strpos($file_name ,'item') !== false) {
            return [
                'xml_root_node'=>'rss',
                'prepend_nodes'=>'channel.item',
                'namespace'=>'g'
                ];

        }

        /**
         * Set default namespaces for atom feed
         */
        if(
             strpos($file_name ,'http://base.google.com/ns/1.0') !== false
             && strpos($file_name ,'http://www.w3.org/2005/Atom"') !== false
        ) {
            return [
                'xml_root_node'=>'entry',
                'namespace'=>'g',
            ];

        }


        return [];
    }


    /**
     * @param $content
     * @param $channel_feed_id
     * @param string $format
     * @return mixed
     */
    public function writeFile($content,$channel_feed_id,$format='xml')
    {
        $file_name = $this->generateFileName($channel_feed_id,$format);
        $full_file_name = CHANNEL_STORAGE_FOLDER.'/'.$file_name;

        /**
         * Remove the file if it already exists
         */
        if(File::exists($full_file_name)) {
            File::delete($full_file_name);
        }

        /**
         * Write the file
         */
        $file_written =  File::put($full_file_name,$content);
        if($file_written === false) {
            LoggerFacade::addAlert("Could not write channel feed. Perhaps some issues with the permissions? ");
        }
        return $file_written;
    }


    /**
     * Generate the file name
     * @param $channel_feed_id
     * @param string $file_extension
     * @return string
     */
    public function generateFileName($channel_feed_id,$file_extension='xml')
    {
        return DFBUILDER_FILE_PREFIX.'_'.base64_encode($channel_feed_id.'_dfbuilder4ever').'.'.$file_extension;
    }

}