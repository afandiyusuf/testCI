<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class Comment_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$current_time = Carbon::now();
        DB::table('comments')->insert([
        	[
				"comic_id"=>1,
				"user_id"=>11,
				"parent_id"=>0,
				"comment"=>"Hebat gan",
				"created_at"=>$current_time
			],
			[
				"comic_id"=>1,
				"user_id"=>10,
				"parent_id"=>0,
				"comment"=>"Komiknya keren gan",
				"created_at"=>$current_time
			],
			[
				"comic_id"=>1,
				"user_id"=>12,
				"parent_id"=>0,
				"comment"=>"Iya keren parah",
				"created_at"=>$current_time
			],
			[
				"comic_id"=>1,
				"user_id"=>10,
				"parent_id"=>0,
				"comment"=>"Harus update tiap hari komik ini!!",
				"created_at"=>$current_time
			],
			[
				"comic_id"=>1,
				"user_id"=>12,
				"parent_id"=>0,
				"comment"=>"Lah komiknya kan udah tamat, gimana updatenya",
				"created_at"=>$current_time
			],
			[
				"comic_id"=>1,
				"user_id"=>9,
				"parent_id"=>0,
				"comment"=>"Tsadeest",
				"created_at"=>$current_time
			],
			[
				"comic_id"=>1,
				"user_id"=>12,
				"parent_id"=>0,
				"comment"=>"Kok tokohnya kayak gitu kaka",
				"created_at"=>$current_time
			],
			[
				"comic_id"=>2,
				"user_id"=>10,
				"parent_id"=>0,
				"comment"=>"Kok tokohnya kayak gitu kaka",
				"created_at"=>$current_time
			],
			[
				"comic_id"=>2,
				"user_id"=>9,
				"parent_id"=>0,
				"comment"=>"Kok tokohnya kayak gitu kaka",
				"created_at"=>$current_time
			],
			
        ]);
         DB::table('comments')->insert([
			[
				"user_id"=>11,
				"comic_id"=>1,
				"parent_id"=>1,
				"comment"=>"Iya keren bingits",
				"created_at"=>$current_time
			],
			[
				"user_id"=>10,
				"comic_id"=>1,
				"parent_id"=>1,
				"comment"=>"Wajib update tiap hari nih",
				"created_at"=>$current_time
			],
			[
				"user_id"=>12,
				"comic_id"=>1,
				"parent_id"=>1,
				"comment"=>"Bener banget wajib",
				"created_at"=>$current_time
			],
			[
				"user_id"=>10,
				"comic_id"=>1,
				"parent_id"=>1,
				"comment"=>"Keren gila pokoknya",
				"created_at"=>$current_time
			]
        ]);

    }
}
