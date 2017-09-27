<?php
/**
 * Created by PhpStorm.
 * User: erm
 * Date: 17-04-17
 * Time: 18:33
 */

namespace App\DfCore\DfBs\Rules\Wizard;

use App\DfCore\DfBs\Enum\UrlKey;
use App\Entity\Feed;
use App\Entity\Repository\FeedRepository;

class ChannelWizard
{
    /**
     * Determine what navigation we can load
     * @param $url_key
     * @return array
     */
    public static function getNavigation($url_key,$params = [])
    {
        switch($url_key) {

            case UrlKey::CHANNEL_FEED:

                $feed = new FeedRepository( new Feed());
                $get_feed = $feed->getFeed($params['feed_id']);
                $channel_type_id = (isset($params['channel_type_id']) ?  $params['channel_type_id'] : 0);
                $channel_feed_id = (isset($params['channel_feed_id']) ?  $params['channel_feed_id'] : 0);
                return [
                    ['label'=>trans('messages.channel_breadcrumb_lbl1'),'route'=>route('channel.channel_settings',['id'=>$get_feed->id,'channel_feed_id'=>$channel_feed_id,'url_key'=>UrlKey::CHANNEL_FEED,'channel_type_id'=>$channel_type_id]),'route_name'=>'channel.channel_settings'],
                    ['label'=>trans('messages.channel_breadcrumb_lbl2'),'route'=>route('filter.categorize_feed',['id'=>$get_feed->id,'url_key'=>UrlKey::CHANNEL_FEED,'channel_feed_id'=>$channel_feed_id,'channel_type_id'=>$channel_type_id]),'route_name'=>'filter.categorize_feed'],
                    ['label'=>trans('messages.channel_breadcrumb_lbl3'),'route'=>route('rules.create_rules',['id'=>$get_feed->id,'rule_id'=>0,'url_key'=>UrlKey::CHANNEL_FEED,'channel_feed_id'=>$channel_feed_id,'channel_type_id'=>$channel_type_id]),'route_name'=>'rules.create_rules'],
                    ['label'=>trans('messages.channel_breadcrumb_lbl4'),'route'=>route('channel.finalize',['feed_id'=>$get_feed->id,'channel_feed_id'=>$channel_feed_id,'url_key'=>UrlKey::CHANNEL_FEED,'channel_type_id'=>$channel_type_id]),'route_name'=>'channel.finalize'],
                    ['label'=>trans('messages.channel_breadcrumb_lbl5'),'route'=>route('spreadsheet.browse_feed',['id'=>$get_feed->id,'url_key'=>UrlKey::CHANNEL_FEED,'channel_feed_id'=>$channel_feed_id,'channel_type_id'=>$channel_type_id]),'route_name'=>'spreadsheet.browse_feed'],
                ];
            break;


            /**
             * Adwords
             */
            case UrlKey::ADWORDS:
                return [
                    ['label'=>trans('messages.adwords_wizard_lbl5'),'route'=>route('adwords.adwords_feed',['feed_id'=>$params['feed_id'],'fk_adwords_feed_id'=>$params['fk_adwords_feed_id'],'url_key'=>UrlKey::ADWORDS]),'route_name'=>'adwords.adwords_feed'],
                    ['label'=>trans('messages.adwords_wizard_lbl1'),'route'=>route('adwords.adwords_settings',['feed_id'=>$params['feed_id'],'fk_adwords_feed_id'=>$params['fk_adwords_feed_id'],'url_key'=>UrlKey::ADWORDS]),'route_name'=>'adwords.adwords_settings'],
                    ['label'=>trans('messages.adwords_wizard_lbl2'),'route'=>route('rules.create_rules',['id'=>$params['feed_id'],'rule_id'=>0,'adwords_feed_id'=>$params['fk_adwords_feed_id'],'url_key'=>UrlKey::ADWORDS]),'route_name'=>'rules.create_rules'],
                    ['label'=>trans('messages.adwords_wizard_lbl3'),'route'=>route('adwords.adwords_preview',['feed_id'=>$params['feed_id'],'fk_adwords_feed_id'=>$params['fk_adwords_feed_id'],'url_key'=>UrlKey::ADWORDS]),'route_name'=>'adwords.adwords_preview'],


                ];
            break;


            /**
             * Bol.com
             */
            case UrlKey::BOL:

                return [
                    ['label'=>trans('messages.bol_lbl_1'),'route'=>route('bol.bol_settings',['feed_id'=>$params['feed_id'],'bol_id'=>$params['bol_id']]),'route_name'=>'bol.bol_settings'],
                    ['label'=>trans('messages.adwords_wizard_lbl2'),'route'=>route('rules.create_rules',['id'=>$params['feed_id'],'rule_id'=>0,'bol_id'=>$params['bol_id'],'url_key'=>UrlKey::BOL]),'route_name'=>'rules.create_rules'],
                    ['label'=>trans('messages.bol_lbl_16'),'route'=>route('bol.build_bol_ad',['id'=>$params['feed_id'],'url_key'=>UrlKey::BOL,'fk_bol_id'=>$params['bol_id']]),'route_name'=>'bol.build_bol_ad'],
                ];
            break;


            default:
                return [];
        }
    }
}