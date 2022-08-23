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

class SearchEntities implements ShouldQueue
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
        $keyword=$this->request['keyword'];
        $queue=SearchQueue::find($this->queue_id);
        $score_entities=$this->request['score'];
        foreach($score_entities as $key=>$value){
            if($value == 1){
                $classname=config('app.entities_query_builder.'.$key);
                $ids=$classname::where($classname::keyword_index,'like','%'.$keyword.'%')->get()->pluck('document_data_id')->toArray();
                $queue->document_datas()->sync($ids);
            }
        }
        foreach($score_entities as $key=>$value){
            if($value == 0){
                $classname=config('app.entities_query_builder.'.$key);
                $ids=$classname::where($classname::keyword_index,'like','%'.$keyword.'%')->get()->pluck('document_data_id')->toArray();
                $queue->document_datas()->sync($ids);
            }
        }                             
    }
}
