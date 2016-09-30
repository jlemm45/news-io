<?php

namespace App\Console\Commands;

use App\Article;
use App\Notifications\ArticlesAdded;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Services\Slack;

class CheckTodaysArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks how many articles were added today';

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
        $count = Article::where('created_at', '>=',  Carbon::today())->count();
        (new Slack())->send('Today\'s Articles', 'Count', $count);
    }
}
