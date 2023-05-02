<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\VariantMedium;
use App\Models\ProductMedium;
use Illuminate\Support\Facades\Storage;
use File;

class DownloadProductImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $productMedias = ProductMedium::where('source', 1)->get();
        foreach($productMedias as $key => $media){
            if($media->cdn_url != ''){
                $image = file_get_contents($media->cdn_url);
               // $name = substr($media->cdn_url, strrpos($media->cdn_url, '/') + 1);
               // $name = substr($name, 0, strpos($name, '?'));
                $refrence_id = mt_rand( 1000, 9999);
                $name = time().$refrence_id.'.png';
               // $exists = Storage::disk('public')->exists("images/$media->product_id/$name");
                //if(!$exists){
                    Storage::disk('public')->put("$media->client_id/images/$media->product_id/$name", $image, 'public');
                    ProductMedium::where('id', $media->id)->update(['source' => 0, 'src' =>  $name]);
             //   }
            }
        }

        $variantMedias = VariantMedium::where('source', 1)->get();
        foreach($variantMedias as $key => $media){
            if($media->cdn_url != ''){
                $image = file_get_contents($media->cdn_url);
               // $name = substr($media->cdn_url, strrpos($media->cdn_url, '/') + 1);
               // $name = substr($name, 0, strpos($name, '?'));

                $refrence_id = mt_rand(1000, 9999);
                $name = time().$refrence_id.'.png';
               // $exists = Storage::disk('public')->exists("images/$media->product_id/$name");
               // if(!$exists){
                    Storage::disk('public')->put("$media->client_id/images/$media->product_id/$name", $image, 'public');
                    VariantMedium::where('id', $media->id)->update(['source' => 0, 'src' =>  $name]);
                // }
            }
        }
    }
}
