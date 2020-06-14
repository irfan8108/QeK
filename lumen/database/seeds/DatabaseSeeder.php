<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

        \DB::table('qek')->insert(
		    ['properties' => json_encode($QeK)]
		);

        // $this->call('UsersTableSeeder');
    }
}
