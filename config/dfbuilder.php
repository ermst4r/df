<?php

/**
 * Define your main website url.
 */
define('DFBUILDER_MAIN_WEBSITE_URL','http://dfbuilder.dev/');

/**
 * Your company name
 */
define('COMPANY_NAME','DFbuilder.com');
/**
 * Your slug
 */
define('TITLE_SLUG','Export your feeds rapidly');


define('DFBUILDER_MAIN_ID_FIELD','product_id');


/**
 * Dfbuilder channel folder.
 */
define('DFBUILDER_CHANNEL_FOLDER','channel');

/**
 * Define global download folder
 */
define('DOWNLOAD_FOLDER',public_path().'/download');
/**
 * Define the xml storage folder
 */
define('XML_STORAGE_FOLDER',DOWNLOAD_FOLDER.'/'.'xml');

/**
 * Define the csv storage folder
 */
define('CSV_STORAGE_FOLDER',DOWNLOAD_FOLDER.'/'.'csv');


/**
 * Define the csv storage folder
 */
define('CHANNEL_STORAGE_FOLDER',DOWNLOAD_FOLDER.'/'.DFBUILDER_CHANNEL_FOLDER);


/**
 * Define the category storage folder.
 */
define('CATEGORY_STORAGE_FOLDER','/'.'categories');

/**
 * Define the storage folder for adwords
 */
define('ADWORDS_STORAGE_FOLDER','/'.'google_adwords');


/**
 * Define the documents to bulk in Elasticsearch
 */

define('DFBUILDER_MAX_ES_BULK_COUNT',5000);


/**
 * Define the filename prefix for outputting the file..
 */
define('DFBUILDER_FILE_PREFIX','dfbuilder');


/**
 * Define the type name for ES
 * All the docs will be stored in the type products
 */

define('DFBUILDER_ES_TYPE','products');


/**
 * In what timezone do we live?
 */

define('DFBULDER_TIMEZONE','Europe/Amsterdam');


/**
 * Define the default product id field
 */
define('DFBUILDER_DEFAULT_ID_NAME','product_id');


/**
 * The default limit for ES
 */
define('DFBUILDER_DEFAULT_ES_LIMIT',1000);


/**
 * Channels will be cronned and consumed from an external source
 * Please define over here the channel consumer url
 */
define('DFBUILDER_CHANNEL_CONSUMER_URL','http://dfbuilder.com/api.php');

/**
 * Please define here the your default country
 * 1 = Netherlands
 * 2 = Belgium
 */
define('DFBUILDER_DEFAULT_COUNTRY',1);


/**
 * Define the Channel finalize mapping levensthein algorithm grade (def = 70);
 *
 */

define('DFBUILDER_CHANNEL_FINALIZE_SENSITIVITY',70);

/**
 * Version DF builder
 */
define('DFBUILDER_VERSION','RC 2.0');


/**
 * Don't touch me, default system settings...
 */

return array(

    /*
   |--------------------------------------------------------------------------
   | ES TMP TABLE Fields to map
   |--------------------------------------------------------------------------
   |
   | Let us define here, which fields we want to use for mapping.
   | If you want to add more fields, you can do it in the system.
   |
   */

    'fields_to_map'=>[
        'product_id','description','product_name','image_url','category','price','extra_image','availability','brand',
        'color','condition','country','currency','delivery_period','ean','gender','isbn','product_url','material'
        ,'mpn','old_price','product_subcategory','product_thirdcategory','product_fourth_category','product_type',
        'shipping_country','shipping_service','size','sku','stock'
    ],


/*
   |--------------------------------------------------------------------------
   | ES  TABLE Settings
   |--------------------------------------------------------------------------
   |
   | Over here we define the settings for the tmp es field
   |
   |
   */

    /**
     * Meta fields for the ES index for the temp table
     */
    'es_meta_fields' => [
    ],



    /**
     * Remove meta ES fields
     */
    'remove_es_meta_fields' => ['has_category_filter','has_rule_filter','rule_id','rule_ids','rule_filters'],


    /**
     * The required fields to map
     */
    'required_fields_to_map'=>
    [
        'product_name','image_url','price','product_url','product_id'
    ],

    /**
     * Exclude fields what the user isn't allow to modify
     * For example in the spreadsheet.
     */
    'es_live_to_exclude' =>
    [
        'generated-id',
        'last_updated',
        'feed_id',
        'rule_id',
        'has_category_filter',
        'has_rule_filter',
        'generated_id',
        'category_meta',
        'meta_shop_category_name',
        'meta_internal_cat_id',
        'rule_filters',
        'rule_ids'

    ]



);




