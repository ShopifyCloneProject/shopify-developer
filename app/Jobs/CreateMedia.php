<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Traits\ApiResponser;

class CreateMedia implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ApiResponser;

    Private $product_id;
    Private $product_name;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product_id, $product_name)
    {
        $this->product_id = $product_id;
        $this->product_name = $product_name;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->ConvertImage($this->product_id, $this->product_name);
    }
}
