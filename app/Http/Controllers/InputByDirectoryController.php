<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \File;
use App\Models\Comic;
use App\Models\Page;
use App\Models\Bundling;
use \Imagick;

class InputByDirectoryController extends Controller
{
	public $delimiter = "/";
    public function dvd_1()
    {

    	// $list = File::directories(storage_path('app'.$this->delimiter.'public'.$this->delimiter.'by_ftp'.$this->delimiter.'dvd_1'));
    	//  for($i =0;$i<count($list);$i++)
    	//  {

    	// 	$list[$i];
    		
    	// 	$datas = File::directories($list[$i]);
    	// 	$bundlingData = explode($this->delimiter,$list[$i]);
    	// 	$bundling = $bundlingData[count($bundlingData)-1];
    	// 	$bunDb = new Bundling();
    	// 	$bunDb->name = $bundling;
    	// 	$bunDb->save();
    	// 	for($j = 0;$j<count($datas);$j++)
    	// 	{
    	// 		$titleData = explode($this->delimiter,$datas[$j]);
    	// 		$title =  $titleData[count($titleData)-1];
    	// 		$title = strtolower($title);
    	// 		$title = ucwords($title);
    	// 		$files = File::files($datas[$j]);
    	// 		$comic = new Comic();
    	// 		$comic->title = $title;
    	// 		$comic->bundling_id = $bunDb->id;
    			
    	// 		if(count($files)>0){
	    // 			for($k=0;$k<count($files);$k++)
	    // 			{
	    // 				$replaced = str_replace(storage_path('app'.$this->delimiter.'public'),url('storage'),$files[$k]);
	    // 				$replaced = str_replace('\\','/',$replaced);
	    // 				if($k==0)
	    // 				{
	    // 					$comic->cover_url = $replaced;
	    // 					$comic->thumb_url = $replaced;
	    // 					$comic->save();
	    // 				}
	    // 				$page = new Page();
	    // 				$page->comic_id = $comic->id;
	    // 				$page->page_num = $k+1;
	    // 				$page->image_name = $replaced;
	    // 				$page->total_panel = 0;
	    // 				$page->panel_data = "";
	    // 				$page->save();
	    // 			}
    	// 		}
    	// 	}
    	// }

    }
    public function generateThumb(Request $request,Comic $comic)
    {
        $url = $comic->cover_url;
        $image = new Imagick();
        
        

        $replacedUrl = str_replace(url('storage'),storage_path('app/public'),$url);
        $arrExplode = explode("/",$replacedUrl);
        $fileName = $arrExplode[count($arrExplode)-1];
        $fileName = "thumb_".$fileName;
        $newDestination = "";
        for($i = 0;$i<count($arrExplode)-1;$i++)
        {
            $newDestination .= $arrExplode[$i]."/";
        }
        
        
        $urlDestination = str_replace(storage_path('app/public'), url('storage'), $newDestination);
        
        //get width & height
        $image->readImage($replacedUrl);
        $image->setImageDepth(24);
        $width = $image->getImageWidth();
        $height = $image->getImageHeight();
        //resize Width
        $compression_type = Imagick::COMPRESSION_NO;
        $image->setImageCompression($compression_type);
        $image->resizeImage(300,(300/$width)*$height,Imagick::FILTER_LANCZOS,1);
        $image->setImageFormat("jpg");
        $image->writeImage($newDestination.$fileName.".png");
        $com = new Comic();
        $com = $com->where('id',$comic->id);
        $com->update(["thumb_url"=>$urlDestination.$fileName.".png"]);

        return $urlDestination.$fileName;
    }
    public function dvd_2()
    {

    	
        // $list = File::directories(storage_path('app'.$this->delimiter.'public'.$this->delimiter.'by_ftp'.$this->delimiter.'dvd_2'));
        //  for($i =0;$i<count($list);$i++)
        //  {

        //     $list[$i];
            
        //     $datas = File::directories($list[$i]);
        //     $bundlingData = explode($this->delimiter,$list[$i]);
        //     $bundling = $bundlingData[count($bundlingData)-1];
        //     $bunDb = new Bundling();
        //     $bunDb->name = "Muffin Digital ".$bundling;
        //     $bunDb->save();
        //     for($j = 0;$j<count($datas);$j++)
        //     {
        //         $titleData = explode($this->delimiter,$datas[$j]);
        //         $title =  $titleData[count($titleData)-1];
        //         $title = strtolower($title);
        //         $title = ucwords($title);
        //         $files = File::files($datas[$j]);
        //         $comic = new Comic();
        //         $comic->title = $title;
        //         $comic->bundling_id = $bunDb->id;
                
        //         if(count($files)>0){
        //             for($k=0;$k<count($files);$k++)
        //             {
        //                 $replaced = str_replace(storage_path('app'.$this->delimiter.'public'),url('storage'),$files[$k]);
        //                 $replaced = str_replace('\\','/',$replaced);
        //                 if($k==0)
        //                 {
        //                     $comic->cover_url = $replaced;
        //                     $comic->thumb_url = $replaced;
        //                     $comic->save();
        //                 }
        //                 $page = new Page();
        //                 $page->comic_id = $comic->id;
        //                 $page->page_num = $k+1;
        //                 $page->image_name = $replaced;
        //                 $page->total_panel = 0;
        //                 $page->panel_data = "";
        //                 $page->save();
        //             }
        //         }
        //     }
        // }
    }
}
