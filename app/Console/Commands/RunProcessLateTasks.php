<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessLateTasks;

class RunProcessLateTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-process-late-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ProcessLateTasks::dispatch();
        $this->info('ProcessLateTasks job dispatched!');
    }
}
