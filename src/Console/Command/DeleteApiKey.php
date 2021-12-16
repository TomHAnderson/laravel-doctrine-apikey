<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use Illuminate\Console\Command;

final class DeleteApiKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apikey:delete {apiKeyName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Soft delete an ApiKey';

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
        // $drip->send(User::find($this->argument('user')));
    }
}
