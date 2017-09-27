<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->bindStores();
        $this->bindFeed();
        $this->bindFieldToMap();
        $this->bindCsvMapping();
        $this->bindXmlMapping();
        $this->bindCategoryFilter();
        $this->bindCategory();
        $this->bindRules();
        $this->bindRuleConditions();
        $this->bindCompositeMapping();
        $this->bindSpreadsheetHeaders();
        $this->bindRevision();
        $this->bindFeedLog();
        $this->bindChannelFeed();
        $this->bindChannel();
        $this->bindChannelType();
        $this->bindChannelCountry();
        $this->bindChannelMapping();
        $this->bindChannelFeed();
        $this->bindChannelFeedMapping();
        $this->bindChannelCustomMapping();
        $this->bindAdwordsAd();
        $this->bindAdwordsConfiguration();
        $this->bindAdwordsConfiguration();
        $this->bindAdwordsFeed();
        $this->bindAdwordsKeyword();
        $this->bindAdwordsTarget();
        $this->bindAdgroupPreview();
        $this->bindAdCampaignPreview();
        $this->bindAdsPreview();
        $this->bindRuleChannel();
        $this->bindRuleAdwords();
        $this->bindAdwordsRevision();
        $this->bindAdKeywordPreview();
        $this->bindAdwordsCountries();
        $this->bindAdwordsLanguages();
        $this->bindCategoryChannel();
        $this->bindBolFeed();
        $this->bindRuleBol();
        $this->bindCategoryBol();
        $this->bindDfLogger();
        $this->bindTaskLog();
        $this->bindBolAds();
        $this->bindBolAdsPreview();
        $this->bindCustomMapping();

    }




    public function bindCustomMapping()
    {
        $this->app->bind('App\Entity\Repository\Contract\iCustomMapping', 'App\Entity\Repository\CustomMappingRepository');
    }

    public function bindBolAdsPreview()
    {
        $this->app->bind('App\Entity\Repository\Contract\iBolAdsPreview', 'App\Entity\Repository\BolAdsPreviewRepository');
    }

    public function bindBolAds()
    {
        $this->app->bind('App\Entity\Repository\Contract\iBolAds', 'App\Entity\Repository\BolAdsRepository');
    }


    public function bindTaskLog()
    {
        $this->app->bind('App\Entity\Repository\Contract\iTaskLog', 'App\Entity\Repository\TasklogRepository');
    }

    public function bindDfLogger()
    {
        $this->app->bind('App\Entity\Repository\Contract\iDflogger', 'App\Entity\Repository\DfloggerRepository');
    }


    public function bindCategoryBol()
    {
        $this->app->bind('App\Entity\Repository\Contract\iCategoryBol', 'App\Entity\Repository\CategoryBolRepository');
    }


    public function bindRuleBol()
    {
        $this->app->bind('App\Entity\Repository\Contract\iRuleBol', 'App\Entity\Repository\RuleBolRepository');
    }


    public function bindBolFeed()
    {
        $this->app->bind('App\Entity\Repository\Contract\iBolFeed', 'App\Entity\Repository\BolFeedRepository');
    }

    public function bindCategoryChannel()
    {
        $this->app->bind('App\Entity\Repository\Contract\iCategoryChannel', 'App\Entity\Repository\CategoryChannelRepository');
    }

    public function bindAdwordsLanguages()
    {
        $this->app->bind('App\Entity\Repository\Contract\iAdwordsGoogleLanguages', 'App\Entity\Repository\AdwordsGoogleLanguagesRepository');
    }


    public function bindAdwordsCountries()
    {
        $this->app->bind('App\Entity\Repository\Contract\iAdwordsGoogleCountries', 'App\Entity\Repository\AdwordsGoogleCountriesRepository');
    }

    public function bindAdKeywordPreview()
    {
        $this->app->bind('App\Entity\Repository\Contract\iAdsKeywordPreview', 'App\Entity\Repository\AdsKeywordPreviewRepository');
    }


    public function bindAdwordsRevision()
    {
        $this->app->bind('App\Entity\Repository\Contract\iAdwordsRevision', 'App\Entity\Repository\AdwordsRevisionRepository');
    }


    public function bindRuleAdwords()
    {
        $this->app->bind('App\Entity\Repository\Contract\iRuleAdwords', 'App\Entity\Repository\RuleAdwordsRepository');
    }



    public function bindRuleChannel()
    {
        $this->app->bind('App\Entity\Repository\Contract\iRuleChannel', 'App\Entity\Repository\RulesChannelRepository');
    }


    public function bindAdsPreview()
    {
        $this->app->bind('App\Entity\Repository\Contract\iAdsPreview', 'App\Entity\Repository\AdsPreviewRepository');
    }

    public function bindAdCampaignPreview()
    {
        $this->app->bind('App\Entity\Repository\Contract\iAdCampaignPreview', 'App\Entity\Repository\AdCampaignPreviewRepository');
    }


    public function bindAdgroupPreview()
    {
        $this->app->bind('App\Entity\Repository\Contract\iAdgroupPreview', 'App\Entity\Repository\AdgroupPreviewRepository');
    }



    public function bindAdwordsTarget()
    {
        $this->app->bind('App\Entity\Repository\Contract\iAdwordsTarget', 'App\Entity\Repository\AdwordsTargetRepository');
    }



    public function bindAdwordsKeyword()
    {
        $this->app->bind('App\Entity\Repository\Contract\iAdwordsKeyword', 'App\Entity\Repository\AdwordsKeywordRepository');
    }



    public function bindAdwordsFeed()
    {
        $this->app->bind('App\Entity\Repository\Contract\iAdwordsfeed', 'App\Entity\Repository\AdwordsfeedRepository');
    }


    public function bindAdwordsConfiguration()
    {
        $this->app->bind('App\Entity\Repository\Contract\iAdwordsConfiguration', 'App\Entity\Repository\AdwordsConfigurationRepository');
    }



    public function bindAdwordsAd()
    {
        $this->app->bind('App\Entity\Repository\Contract\iAdwordsAd', 'App\Entity\Repository\AdwordsAdRepository');
    }


    public function bindStores()
    {
        $this->app->bind('App\Entity\Repository\Contract\iStore', 'App\Entity\Repository\StoreRepository');
    }



    public function bindFeed()
    {
        $this->app->bind('App\Entity\Repository\Contract\iFeed', 'App\Entity\Repository\FeedRepository');
    }

    public function bindFieldToMap()
    {
        $this->app->bind('App\Entity\Repository\Contract\iFieldToMap', 'App\Entity\Repository\FieldToMapRepository');
    }


    public function bindCsvMapping()
    {
        $this->app->bind('App\Entity\Repository\Contract\iCsvMapping', 'App\Entity\Repository\CsvMappingRepository');
    }

   public function bindXmlMapping()
    {
        $this->app->bind('App\Entity\Repository\Contract\iXmlMapping', 'App\Entity\Repository\XmlMappingRepository');
    }


    public function bindCategoryFilter()
    {
        $this->app->bind('App\Entity\Repository\Contract\iCategoryFilter', 'App\Entity\Repository\CategoryFilterRepository');
    }

    public function bindCategory()
    {
        $this->app->bind('App\Entity\Repository\Contract\iCategory', 'App\Entity\Repository\CategoryRepository');
    }

    public function bindRules()
    {
        $this->app->bind('App\Entity\Repository\Contract\iRule', 'App\Entity\Repository\RuleRepository');
    }

    public function bindRuleConditions()
    {
        $this->app->bind('App\Entity\Repository\Contract\iRuleCondition', 'App\Entity\Repository\RuleConditionRepository');
    }

    public function bindCompositeMapping()
    {
        $this->app->bind('App\Entity\Repository\Contract\iCompositeMapping', 'App\Entity\Repository\CompositeMappingRepository');
    }

    public function bindSpreadsheetHeaders()
    {
        $this->app->bind('App\Entity\Repository\Contract\iSpreadsheetHeader', 'App\Entity\Repository\SpreadsheetHeaderRepository');
    }


    public function bindRevision()
    {
        $this->app->bind('App\Entity\Repository\Contract\iRevision', 'App\Entity\Repository\RevisionRepository');
    }

    public function bindFeedLog()
    {
        $this->app->bind('App\Entity\Repository\Contract\iFeedLog', 'App\Entity\Repository\FeedLogRepository');
    }


    public function bindChannelFeed()
    {
        $this->app->bind('App\Entity\Repository\Contract\iChannelFeed', 'App\Entity\Repository\ChannelFeedRepository');
    }

    public function bindChannel()
    {
        $this->app->bind('App\Entity\Repository\Contract\iChannel', 'App\Entity\Repository\ChannelRepository');
    }



    public function bindChannelType()
    {
        $this->app->bind('App\Entity\Repository\Contract\iChannelType', 'App\Entity\Repository\ChannelTypeRepository');
    }

    public function bindChannelCountry()
    {
        $this->app->bind('App\Entity\Repository\Contract\iChannelCountry', 'App\Entity\Repository\ChannelCountryRepository');
    }


    public function bindChannelMapping()
    {
        $this->app->bind('App\Entity\Repository\Contract\iChannelMapping', 'App\Entity\Repository\ChannelMappingRepository');
    }


    public function bindChannelFeedMapping()
    {
        $this->app->bind('App\Entity\Repository\Contract\iChannelFeedMapping', 'App\Entity\Repository\ChannelFeedMappingRepository');
    }

    public function bindChannelCustomMapping()
    {
        $this->app->bind('App\Entity\Repository\Contract\iChannelCustomMapping', 'App\Entity\Repository\ChannelCustomMappingRepository');
    }







}
