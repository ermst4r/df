<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class AdwordsGoogleCountries extends Model
{
    protected $table = 'adwords_google_countries';
    protected $fillable = ['criteria_id','country_name','country_code'];
}