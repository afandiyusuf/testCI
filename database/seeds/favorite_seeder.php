<?php

use Illuminate\Database\Seeder;

class favorite_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('favorite')->insert([
        	[
			'user_id' => 11,
			'comic_id' => 1
			],
			[
			'user_id' => 11,
			'comic_id' => 2
			],
			[
			'user_id' => 11,
			'comic_id' => 5
			]
		]);
    }
}
