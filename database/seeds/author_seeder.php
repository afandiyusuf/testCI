<?php

use Illuminate\Database\Seeder;

class author_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('author')->insert([
			[
				"name"=>"yusuf afandi",
			]
        ]);
    }
}
