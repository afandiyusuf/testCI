<?php
use Illuminate\Database\Seeder;

class PageComicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	  DB::table('pages')->insert([
			[
				"comic_id"=>1,
				"page_num"=>1,
				"image_name"=>'garuda_1_lightning cat.jpg',
				"panel_data"=> '{"id_page":6,"scaleFactor":0.3333333333333333,"initWidth":1920,"initHeight":1200,"panelData":[{"scaledX":24,"scaledY":18,"scaledWidth":242,"scaledHeight":206,"x":72,"y":54,"width":726,"height":618},{"scaledX":287,"scaledY":24,"scaledWidth":199,"scaledHeight":369,"x":861,"y":72,"width":597,"height":1107},{"scaledX":493,"scaledY":28,"scaledWidth":145,"scaledHeight":369,"x":1479,"y":84,"width":435,"height":1107},{"scaledX":25,"scaledY":228,"scaledWidth":240,"scaledHeight":161,"x":75,"y":684,"width":720,"height":483}]}',
				"total_panel"=> 3
			],
			[
				"comic_id"=>1,
				"page_num"=>2,
				"image_name"=>'garuda_1_lightning cat.jpg',
				"panel_data"=> '{"id_page":6,"scaleFactor":0.3333333333333333,"initWidth":1920,"initHeight":1200,"panelData":[{"scaledX":24,"scaledY":18,"scaledWidth":242,"scaledHeight":206,"x":72,"y":54,"width":726,"height":618},{"scaledX":287,"scaledY":24,"scaledWidth":199,"scaledHeight":369,"x":861,"y":72,"width":597,"height":1107},{"scaledX":493,"scaledY":28,"scaledWidth":145,"scaledHeight":369,"x":1479,"y":84,"width":435,"height":1107},{"scaledX":25,"scaledY":228,"scaledWidth":240,"scaledHeight":161,"x":75,"y":684,"width":720,"height":483}]}',
				"total_panel"=> 3
			]
		]);
    }
}
?>