<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
    
	protected function buildFailedValidationResponse(Request $request, array $errors)
	{
  		return response(["success"=> false , "message" => $errors],401);
	}



	// The is Used for Testing Purpose only
	public function test(){
		// dd(phpinfo());
		$QeK = [
        	'chapter' => 30,
        	'surah' => 114,
        	'aayah' => 6666,
        	'about' => [
        		'book' => "Quran is the words of ALLAH (God), The Most Gracious and Most Merciful. Literal words of ALLAH (الله‎) communicated to His last Prophet and Messenger, Prophet MUHAMMAD (peace be upon him / صلى الله عليه وسلم), through Angel Jibraeel (عليه السلام) also known as Gabriel.",
                'app' => [
                    "web" => "Fully Featured Quran (The Pure Book) Web App",
                    "mobile" => [
                        "ios" => null,
                        "android" => null
                    ]
                ]
        	],
        ];
        echo(json_encode($QeK));
	}

}
