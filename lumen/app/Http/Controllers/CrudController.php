<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrudController extends Controller
{   
    // VALIDATE A REQUEST TO A PARTICULAR MODEL/OBJECT USING GIVEN RULES
    private function validation($request, $modelObj, $validationRules){
    	$validation = \Validator::make($request->data, $validationRules);
        if ($validation->fails()) {
            return ['status'=>false, 'error'=>$validation->errors()];
        } 

        return ['status'=>true];
    }
    
    /**
     * CRAETE NEW RECORD
     */
    protected function store($object, $request, $validationRules){
        // VALIDATE THE REQUEST USING GIVEN VALIDATION RULE
    	$validation = $this->validation($request, $object, $validationRules);
    	if(!$validation['status']){
            return response(['status' => false, 'message' => 'validation error', 'error' => $validation['error']], 500);
    	}

        // CREATE NEW MODEL OBJECT
        $modelObj = new $object;

        // VALIDATE & BIND THE REQUESTED ATTRIBUTES INTO NEW CREATED MODEL OBJECT
        $updatedObject = $this->bindFillablePropertiesIntoObject($request->data, $modelObj);

        // ATTEMPT TO CREATE NEW TABLE/OBJECT RECORD
        try{
            $updatedObject->save();
            // DATA RECORD CREATED
            return response(['status'=>true, 'message'=>'data successfully inserted', 'data'=>['id'=>$modelObj->id]], 200);
        }
        // FAILED TO CREATE NEW TABLE/OBJECT RECORD
        catch (\Illuminate\Database\QueryException $ex) {
            return response(['status'=>false, 'message'=>$ex->getMessage()], 500);
        }
    }

    /**
     * UPDATE EXISTING RECORD
     */
    protected function update($itemModel, $request, $validationRule){
        // VALIDATE THE REQUEST USING GIVEN VALIDATION RULE
        $validation = $this->validation($request, $itemModel, $validationRule);
        if(!$validation['status']){
            return $validation;
        }

        // FIND THE REQUEST RECORD
        $existingObject = $itemModel::find($request->data['id']);
        
        // VALIDATE THE REQUESTED ATTRIBUTES
        $updatedObject = $this->bindFillablePropertiesIntoObject($request->data, $existingObject);

        // UPDATE OBJECT/TABLE RECORD
        if($updatedObject->save()){
            return response(['status'=>true, 'message'=>'data successfully updated', 'data'=>['id'=>$updatedObject->id]], 200);
        }else{
            return response(['status'=>true, 'message'=>'Whoops, something went wrong? Please try after sometime'], 500);
        }
    }

    // protected function view($model, $withArr = []){
	protected function view($model){
		// return $model::with($withArr)->orderBy('id', 'desc')->get();
		return $model::orderBy('id', 'desc')->get();
    }

    protected function filter($model, $cmd){
		// PREPARE ORDER_BY
    	$orderBy = is_null($cmd->orderBy) ? 'desc' : $cmd->orderBy;
    	
    	// PREPARE WHERE ARRAY
    	$whereArr = [];
    	foreach ($cmd->where as $key => $value) {
    		if(!is_null($value))
    			$whereArr[] = [$key, $value];
    	}

		return $model::where($whereArr)->orderBy('id', $orderBy)->get();
    }

    /**
     * DELETE EXISTING RECORD
     * @param $softDelete will removed the record for temporary
     * SoftDeleted Records can be restored later
     */
    protected function delete($item, $softDelete = true){
    	if($item->delete()){
    		return back()->with('success', 'Successfully deleted.');
    	}else{
    		return back()->with('error', 'Whoops, something went wrong? please try after sometime.');
    	}
    }

    /**
     * VALIDATE A REQUEST
     * WITH FILLABLES MODEL PROPERTIES
     * @return UPDATED MODEL OBJECT
     */
    private function bindFillablePropertiesIntoObject($requestData, $modelObj){
        // ITERATE THROUGH ALL REQUESTED DATA
        foreach ($requestData as $key => $value) {
            // BIND ONLY AVAILABLE FILLABLE ATTRIBUTES
            if(in_array($key, $modelObj->getFillable())){
                // BIND VALUE AS JSON_STRING, IF VALUE IS AN ARRAY
                $modelObj->$key = is_array($value) ? json_encode($value) : $value;
            }
        }
        return $modelObj;
    }

}
