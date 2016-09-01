<?php

namespace Longman\Platfourm\Foundation\Console;

use Longman\Platfourm\Console\Command;

class CompileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches and optimizes everything';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('clear-compiled');
        $this->call('optimize', ['--force' => true]);
        $this->call('config:cache');
        $this->call('route:cache');
    }
}
