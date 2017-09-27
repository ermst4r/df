<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/**
 * Laravel default routes
 */
Auth::routes();


/**
 * Df Default routes
 */

Route::get('/', 'DfCore\IndexController@index')->name('index.index');
Route::group(['middleware' => ['auth']], function () {

    Route::get('/select.store', 'DfCore\StoreController@select_store')->name('store.select_store');
    Route::get('/setdefaultstore.{id}', 'DfCore\StoreController@defaultstore')->name('store.defaultstore');
    Route::get('/store.create/{id?}', 'DfCore\StoreController@create')->name('store.create');
    Route::get('/store.delete/{id?}', 'DfCore\StoreController@delete_store')->name('store.delete_store');
    Route::post('/store.post', 'DfCore\StoreController@poststore')->name('store.post');

});

Route::group(['middleware' => ['auth','select_store']], function () {
    Route::get('/dashboard', 'DfCore\IndexController@dashboard')->name('index.dashboard');


    /**
     * Logging routes
     */
    Route::get('/dfcore.logging', 'DfCore\Common\LoggingController@log_report')->name('common.log_report');
    Route::get('/dfcore.all_feed_log', 'DfCore\Common\LoggingController@all_feed_log')->name('common.all_feed_log');
    Route::get('/dfcore.completed_process', 'DfCore\Common\LoggingController@completed_process')->name('common.completed_process');


    /**
     * Import routes
     */
    Route::get('/import.selectfeed', 'DfCore\Feed\ImportController@select_feed')->name('import.selectfeed');
    Route::get('/import.browse_feed/{feed_id}', 'DfCore\Feed\ImportController@feed_browse')->name('import.browse_feed');
    Route::post('/ajax_preview_browse/{feed_id}', 'DfCore\Feed\ImportController@ajax_preview_browse')->name('import.ajax_preview_browse');
    Route::get('/import.manage_feeds', 'DfCore\Feed\ImportController@manage_feeds')->name('import.manage_feeds');
    Route::post('/import.post_feed', 'DfCore\Feed\ImportController@post_feed')->name('import.post_feed');
    Route::get('/import.mapping/{type?}/{id?}', 'DfCore\Feed\ImportController@mapping')->name('import.mapping');
    Route::get('/import.remove_feed/{id}', 'DfCore\Feed\ImportController@remove_feed')->name('import.remove_feed');
    Route::get('/import.ajax_update_feed/{id}/{job}', 'DfCore\Feed\ImportController@ajax_update_feed')->name('import.ajax_update_feed');
    Route::get('/import.mapping_complete/{id?}', 'DfCore\Feed\ImportController@mapping_complete')->name('import.mapping_complete');
    Route::post('/import.download_file', 'DfCore\Feed\ImportController@ajax_download_file')->name('import.ajax_download_file');
    Route::post('/import.postmapping', 'DfCore\Feed\ImportController@post_mapping')->name('import.post_mapping');
    Route::get('/import.composite_key/{id?}', 'DfCore\Feed\ImportController@composite_key')->name('import.composite_key');
    Route::post('/import.post_composite_key/{id?}', 'DfCore\Feed\ImportController@post_composite_key')->name('import.post_composite_key');

    /**
     * Filter routes
     */
    Route::get('/filter.categorize/{id?}', 'DfCore\Common\FilterController@categorize_feed')->name('filter.categorize_feed');
    Route::post('/filter.ajax_add_categorize/{id?}', 'DfCore\Common\FilterController@ajax_add_categorize')->name('filter.ajax_add_categorize');
    Route::post('/filter.ajax_save_category_filter/{id?}', 'DfCore\Common\FilterController@ajax_save_category_filter')->name('filter.ajax_save_category_filter');
    Route::post('/filter.ajax_remove_filter/{id?}', 'DfCore\Common\FilterController@ajax_remove_filter')->name('filter.ajax_remove_filter');
    Route::post('/filter.ajax_calculate_mapped/{id?}', 'DfCore\Common\FilterController@ajax_calculate_mapped')->name('filter.ajax_calculate_mapped');
    Route::post('/filter.ajax_categories/{id?}', 'DfCore\Common\FilterController@ajax_categories')->name('filter.ajax_categories');
    Route::post('/filter.ajax_browse_uncategorized/{id?}', 'DfCore\Common\FilterController@ajax_browse_uncategorized')->name('filter.ajax_browse_uncategorized');
    Route::get('/filter.browse_uncategorized/{id?}', 'DfCore\Common\FilterController@browse_uncategorized')->name('filter.browse_uncategorized');


    /**
     * Teaser routes
     */
    Route::post('/teaser.ajax_categorize_teaser/{id?}', 'DfCore\Feed\TeaserController@ajax_categorize_teaser')->name('teaser.ajax_categorize_teaser');
    Route::post('/teaser.ajax.autosuggest/{id?}', 'DfCore\Feed\TeaserController@ajax_suggester')->name('filter.ajax_suggester');

    /**
     * Create rules routes
     */
    Route::get('/rules.create_rule/{id}/{rule_id?}', 'DfCore\Common\RulesController@create_rules')->name('rules.create_rules');
    Route::get('/rules.remove_rule/{id}/{rule_id?}', 'DfCore\Common\RulesController@remove_rule')->name('rules.remove_rule');
    Route::post('/rules.ajax_get_form_type/{id}', 'DfCore\Common\RulesController@ajax_get_form_type')->name('rules.ajax_get_form_type');
    Route::post('/rules.ajax_delete_rule/{id}', 'DfCore\Common\RulesController@ajax_delete_rule')->name('rules.ajax_delete_rule');
    Route::post('/rules.ajax_save_draggable/{id}', 'DfCore\Common\RulesController@ajax_save_draggable')->name('rules.ajax_save_draggable');
    Route::post('/rules.post_rules', 'DfCore\Common\RulesController@post_rules')->name('rules.post_rules');
    Route::post('/rules.ajax_calculate_rules/{id}', 'DfCore\Common\RulesController@ajax_calculate_rules')->name('rules.ajax_calculate_rules');
    Route::get('/rules.ajax_getrule_esfields/{id}', 'DfCore\Common\RulesController@ajax_getrule_esfields')->name('rules.ajax_getrule_esfields');

    /**
     * Create Spreadsheet routes
     */
    Route::get('/spreadsheet.browse_feed/{id}', 'DfCore\Channel\SpreadSheetController@browse_feed')->name('spreadsheet.browse_feed');
    Route::post('/spreadsheet.ajax_browse_hot/{id}', 'DfCore\Channel\SpreadSheetController@ajax_browse_hot')->name('spreadsheet.ajax_browse_hot');
    Route::post('/spreadsheet.save_headers/{id}', 'DfCore\Channel\SpreadSheetController@save_headers')->name('spreadsheet.save_headers');
    Route::post('/spreadsheet.ajax_revision/{id}', 'DfCore\Channel\SpreadSheetController@ajax_revision')->name('spreadsheet.ajax_revision');
    Route::post('/spreadsheet.run_job/{id}', 'DfCore\Channel\SpreadSheetController@run_job')->name('spreadsheet.run_job');


    /**
     * Channel Routes
     */
    Route::get('/channel.channel_settings/{feed_id}/{channel_feed_id?}', 'DfCore\Channel\ChannelController@channel_settings')->name('channel.channel_settings');
    Route::get('/channel.manage_channels', 'DfCore\Channel\ChannelController@manage_channels')->name('channel.manage_channels');
    Route::get('/channel.start_wizard', 'DfCore\Channel\ChannelController@start_wizard')->name('channel.start_wizard');
    Route::post('/channel.ajax_get_channel/{feed_id}', 'DfCore\Channel\ChannelController@ajax_get_channel')->name('channel.ajax_get_channel');
    Route::post('/channel.ajax_get_channel_type/{feed_id}', 'DfCore\Channel\ChannelController@ajax_get_channel_type')->name('channel.ajax_get_channel_type');
    Route::post('/channel.post_channel_setting', 'DfCore\Channel\ChannelController@post_channel_setting')->name('channel.post_channel_setting');
    Route::post('/channel.post_channel_map_feed', 'DfCore\Channel\ChannelController@post_channel_map_feed')->name('channel.post_channel_map_feed');
    Route::get('/channel.finalize/{feed_id}/{channel_feed_id}/{channel_type_id}', 'DfCore\Channel\ChannelController@finalize')->name('channel.finalize');
    Route::get('/channel.ajax_get_field/{feed_id}/{channel_feed_id}/{channel_type_id}/{extra_field_counter?}', 'DfCore\Channel\ChannelController@ajax_get_field')->name('channel.ajax_get_field');
    Route::get('/remove_channel_feed/{channel_feed_id}/{feed_id}', 'DfCore\Channel\ChannelController@remove_channel_feed')->name('channel.remove_channel_feed');


    /**
     * Adwords route
     */
    Route::get('/adwords.start_wizard', 'DfCore\Adwords\AdwordsController@start_wizard')->name('adwords.start_wizard');
    Route::get('/adwords.settings/{feed_id}/{fk_adwords_feed_id?}', 'DfCore\Adwords\AdwordsController@adwords_settings')->name('adwords.adwords_settings');
    Route::get('/adwords.manage', 'DfCore\Adwords\AdwordsController@adwords_manage')->name('adwords.adwords_manage');
    Route::get('/adwords.adwords_spreadsheet_modus/{feed_id}/{fk_adwords_feed_id?}', 'DfCore\Adwords\AdwordsController@adwords_spreadsheet_modus')->name('adwords.adwords_spreadsheet_modus');
    Route::get('/adwords.backupads/{feed_id}/{fk_adwords_feed_id}/{parent_id}', 'DfCore\Adwords\AdwordsController@backup_ads')->name('adwords.backup_ads');
    Route::get('/adwords.adwords_preview/{feed_id}/{fk_adwords_feed_id}', 'DfCore\Adwords\AdwordsController@adwords_preview')->name('adwords.adwords_preview');
    Route::get('/adwords.adwords_preview_products/{fk_adwords_feed_id}/{fk_campaigns_preview_id}/{fk_adgroup_preview_id}', 'DfCore\Adwords\AdwordsController@adwords_preview_products')->name('adwords.adwords_preview_products');
    Route::get('/adwords.adwords_feed/{feed_id}/{fk_adwords_feed_id?}', 'DfCore\Adwords\AdwordsController@adwords_feed')->name('adwords.adwords_feed');
    Route::get('/adwords.remove_adwords_feed/{id}/{feed_id}', 'DfCore\Adwords\AdwordsController@remove_adwords_feed')->name('adwords.remove_adwords_feed');

    Route::post('/ajax_get_words_template/{feed_id}/{adwords_feed_id?}', 'DfCore\Adwords\AdwordsController@ajax_adwords_add')->name('adwords.ajax_adwords_add');
    Route::post('/ajax_get_keywords/{feed_id}', 'DfCore\Adwords\AdwordsController@ajax_keywords_add')->name('adwords.ajax_keywords_add');
    Route::post('/ajax_keywords_negative/{feed_id}', 'DfCore\Adwords\AdwordsController@ajax_keywords_negative')->name('adwords.ajax_keywords_negative');
    Route::post('/post_adwords_settings', 'DfCore\Adwords\AdwordsController@post_adwords_settings')->name('adwords.post_adwords_settings');
    Route::post('/adwords_es_fields', 'DfCore\Adwords\AdwordsController@adwords_es_fields')->name('adwords.adwords_es_fields');
    Route::post('/ajax_remove_adwords_items', 'DfCore\Adwords\AdwordsController@ajax_remove_adwords_items')->name('adwords.ajax_remove_adwords_items');
    Route::post('/post_adwords_backup', 'DfCore\Adwords\AdwordsController@post_adwords_backup')->name('adwords.post_adwords_backup');
    Route::post('/preview_ad', 'DfCore\Adwords\AdwordsController@preview_ad')->name('adwords.preview_ad');
    Route::post('/ajax_adwords_spreadsheet_hot', 'DfCore\Adwords\AdwordsController@ajax_adwords_spreadsheet_hot')->name('adwords.ajax_adwords_spreadsheet_hot');
    Route::post('/ajax_adwords_revision', 'DfCore\Adwords\AdwordsController@ajax_adwords_revision')->name('adwords.ajax_adwords_revision');
    Route::post('/ajax_get_campaigns', 'DfCore\Adwords\AdwordsController@ajax_get_campaigns')->name('adwords.ajax_get_campaigns');
    Route::post('/post_adwords_feed', 'DfCore\Adwords\AdwordsController@post_adwords_feed')->name('adwords.post_adwords_feed');

    /**
     * Bol.com routes
     */
    Route::get('/bol.settings/{feed_id}/{fk_bol_id?}', 'DfCore\Marketplaces\BolController@bol_settings')->name('bol.bol_settings');
    Route::get('/bol.build_bol_ad/{feed_id}/{fk_bol_id?}', 'DfCore\Marketplaces\BolController@build_bol_ad')->name('bol.build_bol_ad');
    Route::get('/bol.remove_bol_feed/{id}/{feed_id}', 'DfCore\Marketplaces\BolController@remove_bol_feed')->name('bol.remove_bol_feed');
    Route::post('/post_bol_feed', 'DfCore\Marketplaces\BolController@post_bol_feed')->name('bol.post_bol_feed');
    Route::post('/post_bol_ad', 'DfCore\Marketplaces\BolController@post_bol_ad')->name('bol.post_bol_ad');


});



