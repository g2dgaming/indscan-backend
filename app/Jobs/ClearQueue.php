<?php declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SearchQueue;
use DB;
class ClearQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $queue_id; 
    public function __construct($queue_id)
    {
        $this->queue_id=$queue_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $queue=SearchQueue::find($this->queue_id);
        if($queue){
            DB::table('dd_sq_pivot')->where('search_queue_id',$queue->id)->delete();
        }
    }
}
