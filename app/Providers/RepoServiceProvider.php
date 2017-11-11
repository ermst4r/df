<?php

namespace App\Providers;

use App\Entity\AdCampaignPreview;
use App\Entity\AdgroupPreview;
use App\Entity\AdsKeywordPreview;
use App\Entity\AdsPreview;
use App\Entity\AdwordsAd;
use App\Entity\AdwordsConfiguration;
use App\Entity\Adwordsfeed;
use App\Entity\AdwordsGoogleCountries;
use App\Entity\AdwordsGoogleLanguages;
use App\Entity\AdwordsKeyword;
use App\Entity\AdwordsRevision;
use App\Entity\AdwordsTarget;
use App\Entity\Bolads;
use App\Entity\BoladsPreview;
use App\Entity\Bolfeed;
use App\Entity\CategoryBol;
use App\Entity\CategoryChannel;
use App\Entity\CategoryFilter;
use App\Entity\Channel;
use App\Entity\ChannelCountry;
use App\Entity\ChannelCustomMapping;
use App\Entity\ChannelFeed;
use App\Entity\ChannelFeedMapping;
use App\Entity\ChannelMapping;
use App\Entity\ChannelType;
use App\Entity\CompositeMapping;
use App\Entity\Csvmapping;
use App\Entity\CustomMapping;
use App\Entity\Dflogger;
use App\Entity\Feed;
use App\Entity\FeedLog;
use App\Entity\FieldToMap;
use App\Entity\Repository\AdCampaignPreviewRepository;
use App\Entity\Repository\AdgroupPreviewRepository;
use App\Entity\Repository\AdsKeywordPreviewRepository;
use App\Entity\Repository\AdsPreviewRepository;
use App\Entity\Repository\AdwordsAdRepository;
use App\Entity\Repository\AdwordsConfigurationRepository;
use App\Entity\Repository\AdwordsfeedRepository;
use App\Entity\Repository\AdwordsGoogleCountriesRepository;
use App\Entity\Repository\AdwordsGoogleLanguagesRepository;
use App\Entity\Repository\AdwordsKeywordRepository;
use App\Entity\Repository\AdwordsRevisionRepository;
use App\Entity\Repository\AdwordsTargetRepository;
use App\Entity\Repository\BolAdsPreviewRepository;
use App\Entity\Repository\BolAdsRepository;
use App\Entity\Repository\BolFeedRepository;
use App\Entity\Repository\CategoryBolRepository;
use App\Entity\Repository\CategoryChannelRepository;
use App\Entity\Repository\CategoryFilterRepository;
use App\Entity\Repository\ChannelCountryRepository;
use App\Entity\Repository\ChannelCustomMappingRepository;
use App\Entity\Repository\ChannelFeedMappingRepository;
use App\Entity\Repository\ChannelFeedRepository;
use App\Entity\Repository\ChannelMappingRepository;
use App\Entity\Repository\ChannelRepository;
use App\Entity\Repository\ChannelTypeRepository;
use App\Entity\Repository\CompositeMappingRepository;
use App\Entity\Repository\Contract\iAdCampaignPreview;
use App\Entity\Repository\Contract\iAdgroupPreview;
use App\Entity\Repository\Contract\iAdsKeywordPreview;
use App\Entity\Repository\Contract\iAdsPreview;
use App\Entity\Repository\Contract\iAdwordsAd;
use App\Entity\Repository\Contract\iAdwordsConfiguration;
use App\Entity\Repository\Contract\iAdwordsfeed;
use App\Entity\Repository\Contract\iAdwordsGoogleCountries;
use App\Entity\Repository\Contract\iAdwordsGoogleLanguages;
use App\Entity\Repository\Contract\iAdwordsKeyword;
use App\Entity\Repository\Contract\iAdwordsRevision;
use App\Entity\Repository\Contract\iAdwordsTarget;
use App\Entity\Repository\Contract\iBolAds;
use App\Entity\Repository\Contract\iBolAdsPreview;
use App\Entity\Repository\Contract\iBolFeed;
use App\Entity\Repository\Contract\iCategoryBol;
use App\Entity\Repository\Contract\iCategoryChannel;
use App\Entity\Repository\Contract\iCategoryFilter;
use App\Entity\Repository\Contract\iChannel;
use App\Entity\Repository\Contract\iChannelCountry;
use App\Entity\Repository\Contract\iChannelCustomMapping;
use App\Entity\Repository\Contract\iChannelFeed;
use App\Entity\Repository\Contract\iChannelFeedMapping;
use App\Entity\Repository\Contract\iChannelMapping;
use App\Entity\Repository\Contract\iChannelType;
use App\Entity\Repository\Contract\iCompositeMapping;
use App\Entity\Repository\Contract\iCsvMapping;
use App\Entity\Repository\Contract\iCustomMapping;
use App\Entity\Repository\Contract\iDflogger;
use App\Entity\Repository\Contract\iFeed;
use App\Entity\Repository\Contract\iFeedLog;
use App\Entity\Repository\Contract\iFieldToMap;
use App\Entity\Repository\Contract\iRevision;
use App\Entity\Repository\Contract\iRule;
use App\Entity\Repository\Contract\iRuleAdwords;
use App\Entity\Repository\Contract\iRuleBol;
use App\Entity\Repository\Contract\iRuleChannel;
use App\Entity\Repository\Contract\iRuleCondition;
use App\Entity\Repository\Contract\iSpreadsheetHeader;
use App\Entity\Repository\Contract\iStore;
use App\Entity\Repository\Contract\iTaskLog;
use App\Entity\Repository\Contract\iXmlMapping;
use App\Entity\Repository\CsvMappingRepository;
use App\Entity\Repository\CustomMappingRepository;
use App\Entity\Repository\DfloggerRepository;
use App\Entity\Repository\FeedLogRepository;
use App\Entity\Repository\FeedRepository;
use App\Entity\Repository\FieldToMapRepository;
use App\Entity\Repository\RevisionRepository;
use App\Entity\Repository\RuleAdwordsRepository;
use App\Entity\Repository\RuleBolRepository;
use App\Entity\Repository\RuleConditionRepository;
use App\Entity\Repository\RuleRepository;
use App\Entity\Repository\RulesChannelRepository;
use App\Entity\Repository\SpreadsheetHeaderRepository;
use App\Entity\Repository\StoreRepository;
use App\Entity\Repository\TasklogRepository;
use App\Entity\Repository\XmlMappingRepository;
use App\Entity\Revision;
use App\Entity\Rule;
use App\Entity\RuleAdwords;
use App\Entity\RuleBol;
use App\Entity\RuleCondition;
use App\Entity\RulesChannel;
use App\Entity\SpreadsheetHeader;
use App\Entity\Store;
use App\Entity\Tasklog;
use App\Entity\Xmlmapping;
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
        $this->app->singleton(iFeed::class, function() {
            return new FeedRepository(new Feed());
        });

        $this->app->singleton(iStore::class, function() {
            return new StoreRepository(new Store());
        });

        $this->app->singleton(iFieldToMap::class, function() {
            return new FieldToMapRepository(new FieldToMap());
        });

        $this->app->singleton(iCsvMapping::class, function() {
            return new CsvMappingRepository(new Csvmapping());
        });

        $this->app->singleton(iXmlMapping::class, function() {
            return new XmlMappingRepository(new Xmlmapping());
        });

        $this->app->singleton(iCategoryFilter::class, function() {
            return new CategoryFilterRepository(new CategoryFilter());
        });

        $this->app->singleton(iRule::class, function() {
            return new RuleRepository(new Rule());
        });

        $this->app->singleton(iRuleCondition::class, function() {
            return new RuleConditionRepository(new RuleCondition());
        });

        $this->app->singleton(iCompositeMapping::class, function() {
            return new CompositeMappingRepository(new CompositeMapping());
        });

        $this->app->singleton(iSpreadsheetHeader::class, function() {
            return new SpreadsheetHeaderRepository(new SpreadsheetHeader());
        });

        $this->app->singleton(iRevision::class, function() {
            return new RevisionRepository(new Revision());
        });

        $this->app->singleton(iRevision::class, function() {
            return new RevisionRepository(new Revision());
        });

        $this->app->singleton(iFeedLog::class, function() {
            return new FeedLogRepository(new FeedLog());
        });

        $this->app->singleton(iChannelFeed::class, function() {
            return new ChannelFeedRepository(new ChannelFeed());
        });

        $this->app->singleton(iChannel::class, function() {
            return new ChannelRepository(new Channel());
        });


        $this->app->singleton(iChannelType::class, function() {
            return new ChannelTypeRepository(new ChannelType());
        });


        $this->app->singleton(iChannelCountry::class, function() {
            return new ChannelCountryRepository(new ChannelCountry());
        });


        $this->app->singleton(iChannelMapping::class, function() {
            return new ChannelMappingRepository(new ChannelMapping());
        });


        $this->app->singleton(iChannelFeed::class, function() {
            return new ChannelFeedRepository(new ChannelFeed());
        });


        $this->app->singleton(iChannelFeedMapping::class, function() {
            return new ChannelFeedMappingRepository(new ChannelFeedMapping());
        });

        $this->app->singleton(iChannelCustomMapping::class, function() {
            return new ChannelCustomMappingRepository(new ChannelCustomMapping());
        });

        $this->app->singleton(iAdwordsAd::class, function() {
            return new AdwordsAdRepository(new AdwordsAd());
        });

        $this->app->singleton(iAdwordsConfiguration::class, function() {
            return new AdwordsConfigurationRepository(new AdwordsConfiguration());
        });

        $this->app->singleton(iAdwordsfeed::class, function() {
            return new AdwordsfeedRepository(new Adwordsfeed());
        });

        $this->app->singleton(iAdwordsKeyword::class, function() {
            return new AdwordsKeywordRepository(new AdwordsKeyword());
        });

        $this->app->singleton(iAdwordsTarget::class, function() {
            return new AdwordsTargetRepository(new AdwordsTarget());
        });

        $this->app->singleton(iAdgroupPreview::class, function() {
            return new AdgroupPreviewRepository(new AdgroupPreview());
        });

        $this->app->singleton(iAdCampaignPreview::class, function() {
            return new AdCampaignPreviewRepository(new AdCampaignPreview());
        });


        $this->app->singleton(iAdsPreview::class, function() {
            return new AdsPreviewRepository(new AdsPreview());
        });

        $this->app->singleton(iRuleChannel::class, function() {
            return new RulesChannelRepository(new RulesChannel());
        });

        $this->app->singleton(iRuleAdwords::class, function() {
            return new RuleAdwordsRepository(new RuleAdwords());
        });

        $this->app->singleton(iAdwordsRevision::class, function() {
            return new AdwordsRevisionRepository(new AdwordsRevision());
        });

        $this->app->singleton(iAdsKeywordPreview::class, function() {
            return new AdsKeywordPreviewRepository(new AdsKeywordPreview());
        });

        $this->app->singleton(iAdwordsGoogleCountries::class, function() {
            return new AdwordsGoogleCountriesRepository(new AdwordsGoogleCountries());
        });

        $this->app->singleton(iAdwordsGoogleLanguages::class, function() {
            return new AdwordsGoogleLanguagesRepository(new AdwordsGoogleLanguages());
        });


        $this->app->singleton(iCategoryChannel::class, function() {
            return new CategoryChannelRepository(new CategoryChannel());
        });

        $this->app->singleton(iBolFeed::class, function() {
            return new BolFeedRepository(new Bolfeed());
        });

        $this->app->singleton(iRuleBol::class, function() {
            return new RuleBolRepository(new RuleBol());
        });

        $this->app->singleton(iCategoryBol::class, function() {
            return new CategoryBolRepository(new CategoryBol());
        });

        $this->app->singleton(iDflogger::class, function() {
            return new DfloggerRepository(new Dflogger());
        });

        $this->app->singleton(iTaskLog::class, function() {
            return new TasklogRepository(new Tasklog());
        });


        $this->app->singleton(iBolAds::class, function() {
            return new BolAdsRepository(new Bolads());
        });

        $this->app->singleton(iBolAdsPreview::class, function() {
            return new BolAdsPreviewRepository(new BoladsPreview());
        });

        $this->app->singleton(iCustomMapping::class, function() {
            return new CustomMappingRepository(new CustomMapping());
        });


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
