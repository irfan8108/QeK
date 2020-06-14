<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QeKController extends Controller
{
    
    public function quran(Request $request, $type = null){
    	switch ($type) {
    		case 'value':
    			# code...
    			break;
    		
    		default:
    			// FULL QURAN
    			$data = $this->fullQuran();
    			break;
    	}
    	return response(['status' => true, 'data' => $data]);
    }
	
    protected function fullQuran(){
		$data = \App\Chapter::whereStatus(true)->get();
		return $data;
    }

}