<?php declare(strict_types=1);

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

class SearchEntity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $request;
    protected $queue_id;
    protected $entity;
    public function __construct($request,$qi,$entity)
    {
        $this->request=$request;
        $this->queue_id=$qi;
        $this->entity=$entity;
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
        $query=QueryBuilder::getQuery($this->request);
        $classname=config('app.entities_query_builder.'.$this->entity);
        if(isset($this->request['max_search_limit'])){
            $limit=(int)$this->request['max_search_limit'];
        }
        else{
            $limit=(int)config('app.default_max_search_limit');
        }
        $ids=$query->whereHas($this->entity,function ($q)use($keyword,$classname){$q->where($classname::keyword_index,'like','%'.$keyword.'%');})->limit($limit)->get()->pluck('id');
        $queue->document_datas()->attach($ids);                         
    }
}
