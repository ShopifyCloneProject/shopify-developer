<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Traits\ApiResponser;

use App\Models\VariantMedium;
use App\Models\ProductMedium;

class FullCreateMedia implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ApiResponser;

    private $status;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $this->callImageConvert($this->status);
        $objProductMedias = ProductMedium::get(); 
        if($this->status)
        {
            $objProductMedias = ProductMedium::whereBetween('updated_at', [now()->subMinutes(50), now()])->get();
        }
        if($objProductMedias->IsNotEmpty())
        {
            foreach($objProductMedias as $key=>$objProductMedia)
            {
                if(file_exists(storage_path("app/public/$objProductMedia->client_id/images/$objProductMedia->product_id/$objProductMedia->src")))
                {
                    $this->ConvertImage($objProductMedia->product_id, $objProductMedia->src,$objProductMedia->id);
                }
            }
        }
        //  variants product media

        $objVariantProductMedias = VariantMedium::get(); 
        if($this->status)
        {
            $objVariantProductMedias = VariantMedium::whereBetween('updated_at', [now()->subMinutes(50), now()])->get();
        }

        if($objVariantProductMedias->IsNotEmpty())
        {
            foreach($objVariantProductMedias as $key=>$objVariantProductMedia)
            {
                if(file_exists(storage_path("app/public/$objVariantProductMedia->client_id/images/$objVariantProductMedia->product_id/$objVariantProductMedia->src")))
                {
                    $this->ConvertImage($objVariantProductMedia->product_id, $objVariantProductMedia->src,$objVariantProductMedia->id);
                }
            }
        }
    }
}
