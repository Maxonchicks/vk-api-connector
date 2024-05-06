<?php

namespace App\Console\Commands;

use App\VkAPI;
use Illuminate\Console\Command;

class VKParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vk:parse {vk_id_group}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to start parsing group';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $vk_id_group = $this->argument('vk_id_group');
        (new VkAPI)->callAPI($vk_id_group);
    }
}
