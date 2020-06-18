<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QeKController extends Controller
{
    private $languages = [];
    private $quran = [];

    public function __construct(){
        // SET THE LANGUAGES
        $this->languages = $this->bindLanguages();
    }

    // BIND ACTIVE LANGUAGES
    private function bindLanguages(){
        $data = \App\Language::whereStatus(true)->get()->toArray();
        foreach ($data as $key => $value) {
            $temp[$value['id']] = $value['name'];
        }
        return $temp;
    }

    // GET THE QURAN ACCORDING TO REQUEST / TYPE
    public function quran(){
        $data = $this->fullQuran();
        return response(['status' => true, 'data' => $data]);
    }
	
    // GET THE REQUEST QURAN CHAPTER    
    public function specificChapter($chapter_id){
        if(!is_null($chapter_id)){
            $data = \App\Chapter::whereStatus(true)->where('id', $chapter_id)->with('surah')->with('aayah')->with('chapterTranslation', 'surahTranslation', 'aayahTranslation')->get();
            if(!empty($data)){
                $this->quran = $this->serealizeData($data);
            }
        }
        return $this->quran;   
    }

    // GET THE COMPLETE QURAN
    protected function fullQuran(){
		
        // FETCH COMPLETE QURAN DATA
        $data = \App\Chapter::whereStatus(true)->with('surah')->with('aayah')->with('chapterTranslation', 'surahTranslation', 'aayahTranslation')->get();

        // SEREALIZE & BIND THE DATA
        if(!empty($data)){
            $this->quran = $this->serealizeData($data);
            $this->quran['total_chapters'] = count($data);
        }

		return $this->quran;
    }

    private function serealizeData($data){
        foreach ($data as $key => $value) {
            // CHAPTER INTIAL DETAILS
            $this->quran[$key]['id'] = $value['id'];
            $this->quran[$key]['name'] = $value['name'];
            // BIND CHAPTER TRANSLATION 
            $this->quran[$key]['translations'] = $this->bindTranslations($value, 'chapter', $value['id']);

            // BIND SURAH WITH TRANSLATION
            foreach ($value['surah'] as $s_key => $s_value) {
                // SURAH INITIAL DETAILS
                $this->quran[$key]['surahs'][$s_key] = ['id' => $s_value['id'], 'name' => $s_value['name'], 'rukus' => $s_value['ruku']];
                // SURAH TRANSLATIONS
                $this->quran[$key]['surahs'][$s_key]['translation'] = $this->bindTranslations($value->toArray(), 'surah', $s_value['id']);
            }

            // BIND AAYAH WITH TRANSLATION
            foreach ($value['aayah'] as $a_key => $a_value) {
                // AAYAH INITIAL DETAILS
                $this->quran[$key]['surahs'][$s_key]['aayahs'][$a_key] = ['id' =>$a_value['id'], 'name' => $a_value['name'], 'sajda' => $s_value['has_sajdah'] ? 'Yes' : 'No'];
                // AAYAH TRANSLATIONS
                $this->quran[$key]['surahs'][$s_key]['aayahs'][$a_key]['translation'] = $this->bindTranslations($value->toArray(), 'aayah', $a_value['id']);
            }
        }
        return $this->quran;
    }

    // BIND THE QURAN TRANSLATIONS
    private function bindTranslations($data, $type, $type_id){
        $translations = [];
        // CHECK IF TRANSLATIONS AVAILABLE
        if(!empty($data[$type.'_translation'])){
            // BIND INDIVIDUAL TRANSLATION
            foreach ($data[$type.'_translation'] as $key => $value) {
                if($value['type_id'] ==  $type_id){
                    $translations[strtolower($this->languages[$value['id']])] = $value['content'];
                }
            }
        }
        return $translations;
    }

}