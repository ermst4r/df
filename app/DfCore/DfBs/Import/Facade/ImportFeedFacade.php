<?php


namespace App\DfCore\DfBs\Import\Facade;
use App\DfCore\DfBs\Enum\ImportStatus;
use App\DfCore\DfBs\FileWriter\FeedWriter;
use App\DfCore\DfBs\Import\Csv\CsvReaderFacade;
use App\DfCore\DfBs\Import\Mapping\DetectFieldType;
use App\DfCore\DfBs\Import\Mapping\MappedVisibleFieldsFacade;
use App\DfCore\DfBs\Import\Mapping\MappingFactory;
use App\DfCore\DfBs\Import\Mapping\MappingValidator;
use App\DfCore\DfBs\Import\Mapping\ProductId;
use App\DfCore\DfBs\Import\Remote\RemoteFileService;
use App\DfCore\DfBs\Import\Xml\CustomXmlParser\Parsefeed;
use App\DfCore\DfBs\Import\Xml\XmlReaderFacade;
use App\DfCore\DfBs\Log\FeedlogFacade;
use App\Entity\Repository\FeedRepository;
use App\Entity\Repository\XmlMappingRepository;
use App\Entity\Xmlmapping;
use App\Entity\Repository\CompositeMappingRepository;
use App\Entity\Repository\CsvMappingRepository;
use App\DfCore\DfBs\Enum\ESImportType;
use App\DfCore\DfBs\Enum\ImportType;
use App\DfCore\DfBs\Enum\LogStates;
use App\ElasticSearch\DynamicFeedRepository;
use App\Entity\CompositeMapping;
use App\Entity\Csvmapping;
use Carbon\Carbon;

class ImportFeedFacade
{


    /**
     * Set the bulk op for the feed.
     * @var array
     */
    private $bulk_types = [
        ESImportType::DELETE,
        ESImportType::INDEX
    ];



    /**
     * Import CSV data into ES
     * @param $feed_args
     * @param DynamicFeedRepository $DynamicFeedRepository
     */
    public function importCsvFeed($feed_args,DynamicFeedRepository $DynamicFeedRepository,$refresh=false,$custom_mapping=[])
    {
        $inserts =   $this->prepareCsvImportFacade($feed_args,$custom_mapping);
        $DynamicFeedRepository->createDynamicMapping($inserts['detect_field_type']);
        $this->insertBulkData($DynamicFeedRepository,$feed_args,$inserts['inserts'],$refresh);
    }


    /**
     * @param $fields
     * @param $custom_mapping
     * @return mixed
     */
    private function applyCustomMapping($fields,$custom_mapping)
    {
        foreach($custom_mapping as $custom) {
            $fields[$custom] = "";
        }
        return $fields;
    }


    /**
     * Import XML data into ES
     * @param $feed_args
     * @param DynamicFeedRepository $DynamicFeedRepository
     * @throws mixed
     */
    public function importXmlFeed($feed_args, DynamicFeedRepository $DynamicFeedRepository,$refresh=false,$custom_mapping=[])
    {

        if(is_null($feed_args['prepend_nodes'])) {
            $xml_args = $this->prepareXmlImportFacade($feed_args,$custom_mapping);
        } else {
            $xml_args =  $this->preparecustomXmlImport($feed_args,$custom_mapping);
        }



        if(count($xml_args) > 1000) {

            throw new \Exception('This feed is in bad condition....');
        }
        /**
         * create the schema for the ES index
         */
        $DynamicFeedRepository->createDynamicMapping($xml_args['detect_field_type']);
        /**
         * Insert data into ES
         */

        $this->insertBulkData($DynamicFeedRepository,$feed_args,$xml_args['inserts'],$refresh);

    }

    public function doImport($feed_id,DynamicFeedRepository $DynamicFeedRepository, FeedRepository $feed,$feed_args,$refresh=false,$custom_mapping=[])
    {
        /**
         * Prepare the XML file
         * Or the CSV file And insert them into ES
         */
        $feed->createFeed(['fetched_records'=>0  ],  $feed_id);

        switch($feed_args['get_feed']->feed_type) {
            case ImportType::CSV:
            case ImportType::TXT:
                $this->importCsvFeed($feed_args,$DynamicFeedRepository,$refresh,$custom_mapping);
            break;
            case ImportType::XML:

                $this->importXmlFeed($feed_args,$DynamicFeedRepository,$refresh,$custom_mapping);
            break;
        }
        $records_inserted = $DynamicFeedRepository->countRecords();
        $feed->createFeed(
            [
                'feed_status'=>ImportStatus::IMPORTED,
                'next_update'=>Carbon::now()->tz(DFBULDER_TIMEZONE)->addSeconds($feed_args['get_feed']->update_interval),
                'feed_updated'=>Carbon::now()->tz(DFBULDER_TIMEZONE),
                'fetched_records'=>$records_inserted['count']
            ],
            $feed_id);
    }

    /**
     * refresh the index
     * @param $index_name
     */
    public function refreshIndex($index_name, DynamicFeedRepository $DynamicFeedRepository )
    {

        if($DynamicFeedRepository->client->indices()->exists(['index'=>$index_name])) {
            $DynamicFeedRepository->deleteIndex();
        }
    }


    /**
     * Wrapp all functions into a single method
     * @param $feed_id
     * @return array
     */
    public function initializeFeed($feed_id, FeedRepository $feed,$get_feed)
    {

        $xml = new XmlMappingRepository(new Xmlmapping());
        $csv = new CsvMappingRepository(new Csvmapping());
        $CompositeMapping = new CompositeMappingRepository(new CompositeMapping());
        $composite_mappings = $CompositeMapping->getCompositeMapping($feed_id);
        $xmlreader = null;
        $csvreader = null;
        $feed_id = $get_feed->id;
        $feed_type = $get_feed->feed_type;
        $feed_xml_root_node = $get_feed->xml_root_node;
        $prepend_nodes = $get_feed->prepend_nodes;
        /**
         * File handling
         */
        $status =  RemoteFileService::checkRemoteFileExist($get_feed->feed_url);
        $file_name = RemoteFileService::generateSavePath($feed_type,$feed_id);
        RemoteFileService::downloadFileWithCurl($get_feed->feed_url,$file_name);
        $file_saved = file_exists($file_name);


        if(strlen($get_feed->feed_custom_parser) >0  || !is_null($get_feed->feed_custom_parser)) {
            $parse_feed = new Parsefeed($file_name,$get_feed->feed_custom_parser);
            $parse_feed->writeNewFeedData();
            $xml->removeMapping($feed_id);
            $xml->createXmlMapping([['xml_map_name'=>$parse_feed->getIdField(),'mapped_xml_name'=>'product_id','fk_feed_id'=>$feed_id]]);
        }

        $detect_feed_type = FeedWriter::detectFeedType($file_name);

        /**
         * csv or xml?
         */
        if($feed_type == ImportType::CSV || $feed_type == ImportType::TXT) {
            $csvreader = new CsvReaderFacade($file_name);
        }  else {
            $xmlreader = new XmlReaderFacade($file_name,$feed_xml_root_node);
        }


        $mapping_info = MappingFactory::setMapping($file_name,$get_feed);

        $mapped_fields_from_user = MappedVisibleFieldsFacade::getFeedFieldsFromMapping($get_feed,$mapping_info['workable_data']);

        if(!$status) {
            FeedlogFacade::addAlert($feed_id,trans('messages.general_notifications_lbl2'),LogStates::CRITICAL);

        }

        return compact('xml','csv','get_feed','file_name','file_saved' ,'xmlreader'
            ,'csvreader','mapped_fields_from_user','mapping_info','composite_mappings','status',
            'feed','prepend_nodes','feed_xml_root_node','feed_id','detect_feed_type');
    }


    /**
     * Prepare the csv node before inserting into the database.
     * @param $feed_args
     * @return array
     */

    public function prepareCsvImportFacade($feed_args,$custom_mapping=[])
    {

        $csv_header = $feed_args['csvreader']->showCsvMapping();
        $inserts = [];
        $detect_field_type  = [];
        $teller = 0;
        foreach($feed_args['csvreader']->getAllCsvRows() as $csv_rows) {
            $prepare_csv_node = [];
            foreach($csv_header as $csv_keys => $csv_values) {
                $prepare_csv_node[MappingValidator::formatMapping($csv_values)] = $csv_rows[$csv_keys];
                if(!isset($field_type[$csv_values])) {
                    $field_type[$csv_values] = null;
                }
                $detect_field_type[$csv_values] = (DetectFieldType::isfloat($csv_rows[$csv_keys]) ? $field_type[$csv_values] +=1 : $field_type[$csv_values]+=0 );
            }
            $prepare_csv_node = $this->applyProductIds($prepare_csv_node,$feed_args);
            $prepare_csv_node = $this->applyCustomMapping($prepare_csv_node,$custom_mapping);
            $inserts[] = $this->applyImportFilters($prepare_csv_node,$feed_args['mapped_fields_from_user']);
            $teller++;
        }

        $detect_field_type = DetectFieldType::calculateToMarkAsFloat($detect_field_type,$teller);
        return ['inserts'=>$inserts,'detect_field_type'=>$detect_field_type];

    }


    /**
     * @param $prepare_xml_node
     * @return array
     */
    private function detectFieldType($prepare_xml_node)
    {
        $detect_field_type = [];
        foreach(array_keys($prepare_xml_node) as $save_keys) {
            if(!isset($field_type[$save_keys])) {
                $field_type[$save_keys] = null;
            }

            $detect_field_type[$save_keys] = (DetectFieldType::isfloat($prepare_xml_node[$save_keys]) ? $field_type[$save_keys]+=1 : $field_type[$save_keys]+=0 );
            $xml_map_keys[$save_keys] = true;

        }
        return $detect_field_type;
    }


    /**
     * Prepare the custom xml import
     * @param $feed_args
     * @return array
     */
    private  function  preparecustomXmlImport($feed_args,$custom_mapping=[])
    {

        $inserts = [];
        $xml_map_keys = [];
        $detect_field_type  = [];
        $rows = $feed_args['xmlreader']->streamingNode();
        $nodes = $feed_args['xmlreader']->transformCustomXmlToArray($rows,$feed_args);
        $teller = 0;
        foreach($nodes as $row) {

            $prepare_xml_node = $feed_args['xmlreader']->prepareCustomXml($row,$feed_args);
            $prepare_xml_node = $this->applyProductIds($prepare_xml_node,$feed_args);
            if(count($prepare_xml_node) == 0 ) {
                continue;
            }
            $detect_field_type = $this->detectFieldType($prepare_xml_node);
            $prepare_xml_node = $this->applyCustomMapping($prepare_xml_node,$custom_mapping);
            $inserts[] = $this->applyImportFilters($prepare_xml_node,$feed_args['mapped_fields_from_user']);

            $teller++;
        }


        $detect_field_type = DetectFieldType::calculateToMarkAsFloat($detect_field_type,$teller);
        return compact('inserts','xml_map_keys' ,'detect_field_type');

    }



    /**
     * Prepare the xml feed for insert
     * @param $feed_args
     * @return array
     */

    private function prepareXmlImportFacade($feed_args,$custom_mapping=[])
    {
        $inserts = [];
        $xml_map_keys = [];
        $teller = 0;
        $detect_field_type  = [];

        while($node = $feed_args['xmlreader']->streamingNode()  ) {

            $prepare_xml_node = $feed_args['xmlreader']->prepareXmlNodeForInsertIntoDatabase($node,$feed_args);
            $prepare_xml_node = $this->applyProductIds($prepare_xml_node,$feed_args);
            if(count($prepare_xml_node) == 0 ) {
                continue;
            }


            // prepare the xml keys
            // we know that xml keys can be different per line,
            // lets walk to all the keys and add them into the array
            $detect_field_type = $this->detectFieldType($prepare_xml_node);
            $prepare_xml_node = $this->applyCustomMapping($prepare_xml_node,$custom_mapping);

            $inserts[] = $this->applyImportFilters($prepare_xml_node,$feed_args['mapped_fields_from_user']);
            $teller++;
        }


        $detect_field_type = DetectFieldType::calculateToMarkAsFloat($detect_field_type,$teller);

        return compact('inserts','xml_map_keys' ,'detect_field_type');

    }


    /**
     * Insert / DELETE  documents into ES.
     * @param DynamicFeedRepository $dynamicFeedRepository
     * @param $feed_args
     * @param $inserts
     */
    private function insertBulkData(DynamicFeedRepository $dynamicFeedRepository,$feed_args,$inserts,$refresh=false)
    {

        /**
         * add some extra meta data to the es index
         */

        $extra_meta_data_for_es = [
            'feed_id'=>$feed_args['get_feed']->id,
        ];
        $extra_meta_data_for_es  = array_merge(config('dfbuilder.es_meta_fields'),$extra_meta_data_for_es);

        foreach ($this->bulk_types as $bulk) {

            $dynamicFeedRepository->insertBulkData($inserts,$extra_meta_data_for_es,$bulk,false,$refresh);

        }

    }


    /**
     * Apply filters
     *
     * @param $import_data
     * @return mixed
     */
    private function applyImportFilters($import_data,$mapped_fields_from_user)
    {
        /**
         * Your custom filters here..
         */
        //$import_data = FilterFactory::loadFilter(ImportFilters::RemoveHtmlTags,$import_data,$mapped_fields_from_user);
        return $import_data;
    }



    private function applyProductIds($import_data,$feed_args)
    {

        if(count($feed_args['composite_mappings']) > 0 ) {
            return ProductId::generateCompositeKey($import_data,$feed_args['composite_mappings']);
        } else {

            return ProductId::generateNormalId($import_data,$feed_args['mapped_fields_from_user']);
        }
    }







}