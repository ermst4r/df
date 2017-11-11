<?php

namespace App\Console\Commands;

use App\DfCore\DfBs\Import\Adwords\Countries;
use Illuminate\Console\Command;

class CreateAdwordsCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create_adwords_countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the countries for adwords';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        Countries::importCountries();
        Countries::importLanguages();
        $this->comment("done, thats all follks :)");
    }
}
