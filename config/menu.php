<?php
return array(
    'dashboard' => array('route' => 'index.dashboard', 'translation_key' => 'messages.menu_lbl4', 'fa-icon'=>'fa fa-dashboard',
        'active_class'=>['index.dashboard'],
        'children' => array()),

    /**
     * Feed import and children
     */
    'selectfeed' => array('route' => 'import.selectfeed', 'translation_key' => 'messages.menu_lbl11', 'fa-icon'=>'fa fa-feed',
                    'active_class'=>['import.mapping','import.selectfeed','import.manage_feeds','import.composite_key'],
        'children' => [
                    array(
            'import'=>['route'=>'import.selectfeed','translation_key'=>'messages.menu_lbl12','fa-icon'=>'fa fa-angle-right','active_class'=>['import.mapping','import.selectfeed','import.composite_key']],
            'manage'=>['route'=>'import.manage_feeds','translation_key'=>'messages.menu_lbl13','fa-icon'=>'fa fa-angle-right','active_class'=>['import.manage_feeds']]
        )
        ]),






    /**
     * Channel
     */

    'channel' => array('route' => 'channel.start_wizard', 'translation_key' => 'messages.menu_lbl15', 'fa-icon'=>'fa fa-globe',
        'active_class'=>['channel.channel_settings','channel.start_wizard','channel.finalize','channel.ajax_get_field','channel.remove_channel_feed','spreadsheet.browse_feed','channel.manage_channels'],
        'children' => [
            array(
                'common'=>['route'=>'channel.start_wizard','translation_key'=>'messages.menu_lbl16','fa-icon'=>'fa fa-angle-right','active_class'=>
                    ['channel.channel_settings','channel.start_wizard','channel.finalize','channel.ajax_get_field','channel.remove_channel_feed','spreadsheet.browse_feed']],

                'manage'=>['route'=>'channel.manage_channels','translation_key'=>'messages.menu_lbl17','fa-icon'=>'fa fa-angle-right','active_class'=>
                    ['channel.manage_channels']],
            )
        ]),



    'adwords' => array('route' => 'adwords.start_wizard', 'translation_key' => 'messages.menu_lbl18', 'fa-icon'=>'fa fa-google',
        'active_class'=>['adwords.start_wizard','adwords.adwords_settings','adwords.adwords_manage','adwords.adwords_spreadsheet_modus','adwords.backup_ads'
            ,'adwords.adwords_preview','adwords.adwords_preview_products','adwords.adwords_feed','adwords.remove_adwords_feed'],
        'children' => [
            array(
                'common'=>['route'=>'adwords.start_wizard','translation_key'=>'messages.menu_lbl16','fa-icon'=>'fa fa-angle-right','active_class'=>
                    ['adwords.start_wizard','adwords.adwords_settings','adwords.adwords_spreadsheet_modus','adwords.backup_ads'
                        ,'adwords.adwords_preview','adwords.adwords_preview_products','adwords.adwords_feed','adwords.remove_adwords_feed','adwords.start_wizard']],

                'manage'=>['route'=>'adwords.adwords_manage','translation_key'=>'messages.menu_lbl17','fa-icon'=>'fa fa-angle-right','active_class'=>
                    ['adwords.adwords_manage']],
            )
        ]),



    /**
     * Logging
     */
    'logging' => array('route' => 'common.log_report', 'translation_key' => 'messages.menu_lbl14', 'fa-icon'=>'fa fa-warning',
        'active_class'=>['common.all_feed_log','common.log_report','common.completed_process'],
        'children' => [
            array(
                'common'=>['route'=>'common.log_report','translation_key'=>'messages.log_lbl_15','fa-icon'=>'fa fa-angle-right','active_class'=>['common.log_report']],
                'allfeed'=>['route'=>'common.all_feed_log','translation_key'=>'messages.log_lbl_16','fa-icon'=>'fa fa-angle-right','active_class'=>['common.all_feed_log']],
                'completed_process'=>['route'=>'common.completed_process','translation_key'=>'messages.dashboard_lbl_17','fa-icon'=>'fa fa-angle-right','active_class'=>['common.completed_process']],
            )
    ]),


/**
 * Uncomment below to enable modules
 */

//    'categories' => array('route' => 'categorytable.create', 'translation_key' => 'messages.extra_module_menu_lbl1', 'fa-icon'=>'fa fa-plug',
//        'active_class'=>['categorytable.create'],
//        'children' => array()),





);