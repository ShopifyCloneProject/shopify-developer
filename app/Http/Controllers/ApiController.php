<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ApiResponser;
use Illuminate\Http\Request;
use Config;

class ApiController extends Controller
{
    protected $defaultGuzzleParams;
    use ApiResponser;
    
    public function __construct()
    {
    	$this->defaultGuzzleParams = collect([
			"defaults"=>[
				"headers"=>["Content-Type"=> "application/json" ]
			],
			'verify' => Config::get('GUZZLE_VERIFY')
		]);
    }
}
