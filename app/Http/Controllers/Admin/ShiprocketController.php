<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ShiprocketService;

class ShiprocketController extends Controller
{
    //
    protected $shipService;

    public function __construct()
    {
        $this->shipService = new ShiprocketService;
    }


   public function index()
   {
        dd($this->shipService->getChannelId());
        
        $objProduct = [
            'name' => 'hiii',
            'sku' => 'ABC0125',
            'units' => '5',
            'length' => 7.0,
            'width' => 3.5,
            'height' => 7.4,
            'weight' => 6
        ];
       dd($this->shipService->createOrder("Surat",'2b0a2840-4ab2-11ed-895a-79a2641f98aa', $objProduct));
   }

   

}
