<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendQuiz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:send {quiz}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send a quiz to pusher, mostly for testing';

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
        event(new \App\Events\DisplayQuiz($this->argument('quiz')));
    }
}
