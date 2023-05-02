<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ApiResponser;

class ImageConvert extends Command
{
    use ApiResponser;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:imageconvert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert image';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       $this->callImageConvert();
    }

    
}
