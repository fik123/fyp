<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class kiki extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kiki:pening';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'hilangkan kiki punye pening';

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
     * @return mixed
     */
    public function handle()
    {
        $oldmask = umask(0);
        mkdir('public/cim', 0777, true);
        echo "Menu folder created successfully"."\n";
        umask($oldmask);
    }
}
