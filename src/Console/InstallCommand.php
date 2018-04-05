<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/3/19
 */

namespace Limen\Laravel\Jobs\Console;

use Illuminate\Console\Command;
use Limen\Laravel\Jobs\Database\DbSeeder;

class InstallCommand extends Command
{
    protected $name = 'jobs:install';

    protected $description = 'Install Jobs package';

    public function handle()
    {
        $this->call('migrate');
    }
}