<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SearchQueue;
use App\Models\Entities\Phone;
use App\Models\Entities\DateTime;
use App\Models\Entities\AadharCard;
use App\Models\Entities\PanCard;
use App\Models\Document;
use App\Models\DocumentData;
use App\Models\Category;
use App\Helpers\QueryBuilder;

class FullTextSearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $request;
    protected $queue_id;
    public function __construct($request,$qi)
    {
        $this->request=$request;
        $this->queue_id=$qi;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $query=QueryBuilder::getQuery($this->request);
        $queue=SearchQueue::find($this->queue_id);
        $keyword=$this->request['keyword'];
        $query->where('englishText','like','%'.$keyword.'%')->orWhere('hindiText','like','%'.$keyword.'%');
        $ids=$query->get()->pluck('id');
        $queue->document_datas()->sync($ids);
    }
}
