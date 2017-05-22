<?php

use Illuminate\Database\Seeder;

class comic_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comic_categories')->insert([
			[
				"category"=>"romance",
			],
			[
				"category"=>"action",
			],
			[
				"category"=>"horror",
			]
        ]);

		DB::table('comics')->insert([
		     [
		     	"title"=>"jaka sembung 1 ",
		     	"comic_categories_id"=>1,
		     	"cover_url"=>"1.jpg",
		     	"description"=> "ini adalah deskripsi",
		     	"author_id" => "1",
		     	"comic_tags"=>"",
		     	"custom_prop"=>1,
		     ],
		     [
		     	"title"=>"jaka sembung 2 ",
		     	"comic_categories_id"=>1,
		     	"cover_url"=>"1.jpg",
		     	"description"=> "ini adalah deskripsi",
		     	"author_id" => "1",
		     	"comic_tags"=>"",
		     	"custom_prop"=>1,
		     ],
		     [
		     	"title"=>"jaka sembung 3 ",
		     	"comic_categories_id"=>1,
		     	"cover_url"=>"1.jpg",
		     	"description"=> "ini adalah deskripsi",
		     	"author_id" => "1",
		     	"comic_tags"=>"",
		     	"custom_prop"=>1,
		     ],
		     [
		     	"title"=>"rambo 1 ",
		     	"comic_categories_id"=>2,
		     	"cover_url"=>"1.jpg",
		     	"description"=> "ini adalah deskripsi",
		     	"author_id" => "1",
		     	"comic_tags"=>"",
		     	"custom_prop"=>1,
		     ],
		     [
		     	"title"=>"rambo 2 ",
		     	"comic_categories_id"=>2,
		     	"cover_url"=>"1.jpg",
		     	"description"=> "ini adalah deskripsi",
		     	"author_id" => "1",
		     	"comic_tags"=>"",
		     	"custom_prop"=>1,
		     ],
		     [
		     	"title"=>"rambo 3 ",
		     	"comic_categories_id"=>2,
		     	"cover_url"=>"1.jpg",
		     	"description"=> "ini adalah deskripsi",
		     	"author_id" => "1",
		     	"comic_tags"=>"",
		     	"custom_prop"=>1,
		     ],
		     [
		     	"title"=>"balada tanggal tua 1 ",
		     	"comic_categories_id"=>3,
		     	"cover_url"=>"1.jpg",
		     	"description"=> "ini adalah deskripsi",
		     	"author_id" => "1",
		     	"comic_tags"=>"",
		     	"custom_prop"=>1,
		     ],
		     [
		     	"title"=>"balada tanggal tua 2 ",
		     	"comic_categories_id"=>3,
		     	"cover_url"=>"1.jpg",
		     	"description"=> "ini adalah deskripsi",
		     	"author_id" => "1",
		     	"comic_tags"=>"",
		     	"custom_prop"=>1,
		     ],
		     [
		     	"title"=>"balada tanggal tua 3 ",
		     	"comic_categories_id"=>3,
		     	"cover_url"=>"1.jpg",
		     	"description"=> "ini adalah deskripsi",
		     	"author_id" => "1",
		     	"comic_tags"=>"",
		     	"custom_prop"=>1,
		     ],
		    
		]);
    }
}
