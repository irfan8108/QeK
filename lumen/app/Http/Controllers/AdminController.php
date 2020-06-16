<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends CrudController
{
    // CREATING THE RESOURCE ACCORDINGLY
    public function create(Request $request, $modelName){
    	switch ($modelName) {
        	case 'Chapter':
        		$validation = ['name'=>'required', 'surahs'=>'required|integer|min:1|max:99'];
        		break;

    		case 'Surah':
    			$validation = ['chapter_id' => 'required|integer|exists:chapters,id', 'name' => 'required', 'aayahs' => 'required|integer|min:3|max:999', 'rukuh' => 'required|integer|min:1|max:25', 'revealed_at' => 'required|in:Mecca,Medina'];
                break;
			
			case 'Aayah':
        		$validation = ['chapter_id' => 'required|integer|exists:chapters,id', 'surah_id' => 'required|integer|exists:surahs,id', 'name' => 'required'];
        		break;

    		case 'Language':
        		$validation = ['name' => 'required|unique:language,name'];
        		break;

    		case 'Translation':
        		$validation = ['language_id' => 'required|integer|exists:languages,id', 'type_id' => 'required|integer', 'type' => 'required|in:chapter,surah,aayah', 'content' => 'required'];
        		break;

        	default:
        		$validation = [];
        		break;
        }
        return $this->store('\App\\'.$modelName, $request, $validation);
    }

    // UPDATING SPECIFIC RESOURCES
    public function modify(Request $request, $modelName){
    	$table = strtolower($modelName).'s';
    	$validation = ['id' => "required|integer|exists:$table,id"];
        return $this->update('\App\\'.$modelName, $request, $validation);
    }

    // RECYCLING SPECIFIC RESOURCE
    public function remove(Request $request, $modelName){
    	return $this->recycleBin('\App\\'.$modelName, $request);
    }

    // RESTORING FROM RECYCLE/TRASH
    public function restore(Request $request, $modelName){
    	$restorableModel = ['Aayah'];
    	if(in_array($modelName, $restorableModel))
        	return $this->restoreRecycleBin('\App\\'.$modelName, $request);
        return response(['status' => false, 'message' => 'You can not restore '.$modelName.' Item'], 401);
    }
    
}
