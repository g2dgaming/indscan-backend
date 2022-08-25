<?php declare(strict_types=1);

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
use App\Helpers\QueryBuilder;
use App\Jobs\StartSearch;
use App\Jobs\SearchEntity;
use App\Jobs\FullTextSearch;
use App\Jobs\ClearQueue;
use DB;
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
        //ClearQueue::dispatch($this->queue_id)->delay(config('app.queue_clear_time'));
        if(isset($request['max_search_limit'])){
            $limit=(int)$request['max_search_limit'];
        }
        else{
            $limit=(int)config('app.default_max_search_limit');
        }
        if(isset($request['aadhar_number'])){
            $ids=AadharCard::where('uid','like','%'.$request['aadhar_number'].'%')->limit($limit)->get()->pluck('document_data_id');
            $queue->document_datas()->attach($ids);
            $queue->is_active=0;
            $queue->save();
            return;
        }
        else if(isset($request['pan_number'])){
            $ids=PanCard::where('pan_number','like','%'.$request['pan_number'].'%')->limit($limit)->get()->pluck('document_data_id');
            $queue->document_datas()->attach($ids);
            $queue->is_active=0;
            $queue->save();
            return;
        }
        else{
            //Get Query From Query Builder
            $keyword=$request['keyword'];
            $query=QueryBuilder::getQuery($request);
            //After applying all filters and gettings ids;
            $ids=[];
            if(isset($request['entity'])){
                $entity=$request['entity'];
                if(isset((config('app.entities_query_builder'))[$entity])){
                    $classname=config('app.entities_query_builder.'.$entity);
                    $ids=$query->whereHas($entity,function ($q)use($keyword,$classname){$q->where($classname::keyword_index,'like','%'.$keyword.'%');})->limit($limit)->get()->pluck('id');
                    $queue->document_datas()->attach($ids);
                    $queue->is_active=0;
                    $queue->save();
                }
            } 
            else{
                //Raw String Implemenation
                $query->whereHas('document',function($q)use ($keyword){
                    return $q->where('name','like','%'.$keyword.'%')->orWhere('notes','like','%'.$keyword.'%');
                });
                $ids=$query->limit($limit)->get()->pluck('id');
                //Adding Document name or notes matches to queue
                $queue->document_datas()->attach($ids);
                //Dispatching Score Jobs
                if(isset($request['score'])){
                    $score_entities=$this->request['score'];
                    foreach($score_entities as $key=>$value){
                        if($value == 1){
                            DB::table('search_queue_activity')->insert([
                                'search_queue_id'=>$queue->id,
                                'operation'=>$key,
                                'is_active'=>1
                            ]);
                            SearchEntity::dispatch($request,$this->queue_id,$key);
                        }
                    }
                    foreach($score_entities as $key=>$value){
                        if($value == 0){
                            DB::table('search_queue_activity')->insert([
                                'search_queue_id'=>$queue->id,
                                'operation'=>$key,
                                'is_active'=>1
                            ]);
                            SearchEntity::dispatch($request,$this->queue_id,$key);
                        }
                    }
                }
                DB::table('search_queue_activity')->insert([
                    'search_queue_id'=>$queue->id,
                    'operation'=>'fullText',
                    'is_active'=>1
                ]);
                FullTextSearch::dispatch($request,$this->queue_id);
            }
        }
    }
}
