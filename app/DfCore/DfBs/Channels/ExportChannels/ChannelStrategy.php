<?php
namespace App\DfCore\DfBs\Channels\ExportChannels;
use App\DfCore\DfBs\Enum\ChannelRegistery;
use App\ElasticSearch\ESChannel;

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

class ChannelStrategy
{


    /**
     * @param $feed_data | raw feed data
     * @param $mapping_template | the channel mapping template
     * @param array $custom_fields | | custom fields added by the user
     * @return array
     */

    public static function loadChannels($feed_data,$mapping_template, $custom_fields = [],$channel_feed_id)
    {


        return [
            ChannelRegistery::ZANOX_NL => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Zanox($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::ADCROWD_NL => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Adcrowd($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::TD_NL => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\TradeDoubler($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::ADFORM_NL => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Adform($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::ADPS_NL => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\ADPS($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::ADROLL_NL => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\AddRoll($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::AFF4YOU_NL => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Aff4you($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::AFFWINDOW => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\AffiliateWindow($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::AFFILINET => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Affilinet($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::AWIN => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Awin($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::BAZAARVOICE => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Bazaavoice($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::BESLIST => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Beslist($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::BIANO => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Biano($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::BINGADS => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Bingads($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::BOEKTIEK => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Boetiek($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::CENEO => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Ceneo($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::CHOOZEN => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Choozen($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::CIAO => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Ciao($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::CLANG => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Clang($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::CRITEO => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Criteo($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::DAISYCON => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Daisycon($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::DELTA_PROJECTS => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Deltaprojects($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::DOUBLECLICK => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Doubleclick($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::EPOQ => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Epoq($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::GOOGLESHOPPING => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\GoogleShopping($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::FACEBOOK => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Facebook($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::FASHA => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Fasha($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::FASHIONCHICK => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Fashionchick($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::FRENDZ => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Frendz($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::FRUUGO => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Fruugo($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::GOOGLE_DISPLAY_ADS => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\GoogleDisplayAds($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::HARDWARE_INFO => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Hardwareinfo($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::KIESKEURIG => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Kieskeurig($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::KIYOH => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Kiyoh($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::KLEDING => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Kleding($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::KOOPKEUS => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Koopkeus($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::LEGUIDE => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Leguide($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::LOGSICSALE => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Logicsale($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::LOOTJESTREKKEN => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Lootjestrekken($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::MINTO => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Minto($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::MONETATE => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Monetate($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::MYBESTBRANDS => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Mybestbrands($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::MYHOMESHOPPING => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Myhomeshopping($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::OOSHOPPING => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\OOShopping($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::PRIJSVERGELIJK => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Prijsvergelijk($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::PROMODEALS => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Promodeals($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::PUBLITAS => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Publitias($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::SCOUPZ => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Scoupz($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::SELLVATION => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Sellvation($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::Shopalike => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Shopalike($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::SHOPDICHTBIJ => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Shopdichtbij($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::SHOPMANIA => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Shopmania($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::SHOPR => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Shopr($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::SIZMEK => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Sizmek($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::SMARTLY => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Smartly($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::SOCIOMANTIC => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Sociomantic($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::SOOQR => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Sooqr($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::SPARTOO => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Spartoo($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::STADNL => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Stadnl($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::STOCKBASE => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Stockbase($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::STYLEFRUITS => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Stylefruits($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::STYLELOUNGE => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Stylelounge($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::STYLIGTH => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Styligth($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::TNA => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\TNA($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::TOBEDRESSED => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Tobedressed($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::TRADETRACKER => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Tradetracker($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::TWEAKERS => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Tweakers($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::TWENGA => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Twenga($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::VERGELIJK => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Vergelijk($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::VIAFIXIT => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Viafixit($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::VINDKLEDING => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Vindkleding($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::DEVOORDEELGROUP => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Devoordeelgroup($feed_data,$mapping_template,$custom_fields,$channel_feed_id),
            ChannelRegistery::WEBGAINS => new  \App\DfCore\DfBs\Channels\ExportChannels\NL\Webgains($feed_data,$mapping_template,$custom_fields,$channel_feed_id)

        ];
    }



}