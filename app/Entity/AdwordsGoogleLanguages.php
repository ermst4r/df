<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class AdwordsGoogleLanguages extends Model
{
    protected $table = 'adwords_google_languages';
    protected $fillable = ['language_name','language_code','criteria_id'];
}