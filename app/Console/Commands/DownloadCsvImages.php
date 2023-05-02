<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\Traits\ApiResponser;


class DownloadCsvImages extends Command
{
    use ApiResponser;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:downloadcsvimages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download product images import by csv file.';

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
        $this->importImage();
    }
}
