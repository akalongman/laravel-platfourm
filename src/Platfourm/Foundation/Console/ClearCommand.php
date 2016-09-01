<?php

namespace Longman\Platfourm\Foundation\Console;

use Longman\Platfourm\Console\Command;

class ClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets all of compiled class files, cached views, cached configs etc.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('clear-compiled');
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');
        //$this->call('auth:clear-resets');
    }
}
