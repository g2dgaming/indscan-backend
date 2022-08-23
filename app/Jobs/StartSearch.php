<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use App\Models\SearchQueue;
use App\Models\Entities\Phone;
use App\Models\Entities\DateTime;
use App\Models\Entities\AadharCard;
use App\Models\Entities\PanCard;
use App\Models\Document;
use App\Models\DocumentData;
use App\Models\Category;
use App\Jobs\StartSearch;
use App\Jobs\SearchEntities;

use Log;
class StartSearch implements ShouldQueue
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
        $request=$this->request;
        $queue=SearchQueue::find($this->queue_id);
        if(isset($request['aadhar_number'])){
            $ids=AadharCard::where('uid','like','%'.$request['aadhar_number'].'%')->get()->pluck('document_data_id');
            $queue->document_datas()->sync($ids);
            return;

        }
        else if(isset($request['pan_number'])){
            $ids=PanCard::where('pan_number','like','%'.$request['pan_number'].'%')->get()->pluck('document_data_id');
            $queue->document_datas()->sync($ids);
            return;
        }
        else{
            $keyword=$request['keyword'];
            if(isset($request['category'])){
                $category=Category::where('name',$request['category'])->first();
                $query=$category->document_data();
            }
            //After applying all filters and gettings ids;
            $ids=[];
            if(!isset($query)){
                $query=DocumentData::query();
            }
            if(isset($request['entity'])){
                $entity=$request['entity'];
                if(isset((config('app.entities_query_builder'))[$entity])){
                    $classname=config('app.entities_query_builder.'.$entity);
                    $ids=$query->whereHas($entity,function ($q)use($keyword,$classname){$q->where($classname::keyword_index,'like','%'.$keyword.'%');})->get()->pluck('id');
                    $queue->document_datas()->sync($ids);
                    return;
                }
            } 
            else{
                //Raw String Implemenation
                $query->whereHas('document',function($q)use ($keyword){
                    return $q->where('name','like','%'.$keyword.'%')->orWhere('notes','like','%'.$keyword.'%');
                });
                //Dispatching Score Jobs
               if(isset($request['score'])){
                    SearchEntities::dispatch($request,$queue_id);
                }
                //$query->orWhere('englishText','like','%'.$keyword.'%')->orWhere('hindiText','like','%'.$keyword.'%');
            }
        }
    }
}
