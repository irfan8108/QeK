<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surahs', 'status'
    ];

    protected $hidden = [
        'status', 'updated_by', 'created_at', 'updated_at'
    ];
    
    /**
     * Join the Related Surahs According to Chapter.
     */
    public function surah()
    {
        return $this->hasMany('App\Surah');
    }

    /**
     * Join the Related Aayahs According to Surhas.
     */
    public function aayah()
    {
        return $this->hasManyThrough(
            'App\Aayah',
            'App\Surah',
            'chapter_id', // Foreign key on surah table
            'surah_id',   // Foreign key on aayah table
            'id',         // Local key on chapter table 
            'id'          // Local key on surah table
        )->where('aayahs.status', true);
    }

    /**
     * Join the Surah Translation
     */
    public function surahTranslation()
    {
        return $this->hasManyThrough(
            'App\Translation',
            'App\Surah',
            'chapter_id', // Foreign key on surah table
            'type_id',    // Foreign key on translation table
            'id',         // Local key on chapter table 
            'id'          // Local key on surah table
        )->where(['translations.type' => 'surah', 'translations.status' => true]);
    }

    /**
     * Join the Aayah Translations
     */
    public function aayahTranslation()
    {
        return $this->hasManyThrough(
            'App\Translation',
            'App\Aayah',
            'chapter_id', // Foreign key on surah table
            'type_id',    // Foreign key on translation table
            'id',         // Local key on chapter table 
            'id'          // Local key on surah table
        )->where(['translations.type' => 'aayah', 'translations.status' => true]);
    }

    /**
     * Join the Chapter Translation
     */
    public function chapterTranslation()
    {
        return $this->hasMany('App\Translation', 'id', 'type_id')->where(['translations.type' => 'chapter']);;
    }

}
