<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class DeployCommand extends Command
{
    protected $signature = 'logicdir:deploy {env=production}';
    protected $description = 'Trigger the local deployment sequence';

    public function handle()
    {
        $env = $this->argument('env');
        $this->info("Starting LogicDir deployment to: {$env}");

        if (!$this->confirm("Are you sure you want to deploy to {$env}?", true)) {
            return;
        }

        $this->info("Step 1/3: Running local preparation...");
        $result = Process::run("bash deploy.sh {$env}");

        if ($result->failed()) {
            $this->error("Preparation failed: " . $result->error());
            return 1;
        }

        $this->line($result->output());
        $this->info("Step 2/3: Package created.");
        
        $this->info("Step 3/3: Deployment ready. Please upload the package and run remote script.");
    }
}
