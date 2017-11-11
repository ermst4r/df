<?php namespace App\DfCore\DfBs\Import\Remote;
ini_set('memory_limit', '-1');
/**
 * Class RemoteFileService
 * @package App\DfCore\DfBs\Import\Remote
 */
class RemoteFileService
{


    /**
     *
     */
    public static function generateStorageDirs()
    {


//        dd(self::getFileStorageFolder());
        if(!file_exists(DOWNLOAD_FOLDER)) {
            mkdir(DOWNLOAD_FOLDER);
        }

        if(!file_exists(XML_STORAGE_FOLDER)) {
            mkdir(XML_STORAGE_FOLDER);
        }


        if(!file_exists(CSV_STORAGE_FOLDER)) {
            mkdir(CSV_STORAGE_FOLDER);
        }

        if(!file_exists(CHANNEL_STORAGE_FOLDER)) {
            mkdir(CHANNEL_STORAGE_FOLDER);
        }

    }

    /**
     * Show us the general download folder
     * @return string
     */
    public static function getFileStorageFolder()
    {
        self::generateStorageDirs();
        return DOWNLOAD_FOLDER;
    }


    /**
     * @return string
     */
    public static function getCategoryStorageFolder()
    {
        return public_path().'/'.CATEGORY_STORAGE_FOLDER;

    }

    /**
     * @return string
     */
    public static function getAdwordsStorageFolder()
    {
        return public_path().'/'.ADWORDS_STORAGE_FOLDER;

    }

    /**
     * Generate the location where to save the file
     * @param $type
     * @param $file_name
     * @return string
     */
    public static function generateSavePath($type,$file_name)
    {
        ($type == 'txt' ? $type = 'csv' : '');
        return self::getFileStorageFolder().'/'.$type.'/'.$file_name.'.'.$type;
    }


    /**
     * Let us check if a remote file exists
     * @param $url
     * @return bool
     */
    public  static function checkRemoteFileExist($url)
    {

        $curlInit = curl_init($url);
        curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curlInit, CURLOPT_HEADER, true);
        curl_setopt($curlInit, CURLOPT_NOBODY, true);
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curlInit);
        curl_close($curlInit);

        if ($response) return true;
        return false;
    }


    /**
     * Download the file to the server
     * and save it to a directory
     * @param $file_url
     * @param $save_to
     */
    public static function downloadFileWithCurl($file_url, $save_to)
    {



        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_URL, $file_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $file_content = curl_exec($ch);
        curl_close($ch);


        $downloaded_file = fopen($save_to, 'w');
        fwrite($downloaded_file, $file_content);
        fclose($downloaded_file);
    }

}