<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Error\Debugger;
use Cake\Routing\Router;
use Cake\Datasource\ConnectionManager;

class PartsController extends AppController {
    public function index(){
        $url = Router::reverse($this->request, false);
        $session = $this->request->session();
        $session->write('url',substr($url,4));
        $specs = TableRegistry::get('Specs');
        $stocks = TableRegistry::get('Stocks');
        $page = $this->request->query['page'];
        if($page == null)
            $page = 0;

        if(strcmp($this->request->query['type'],'fuzzy')==0){
            $text = $this->request->query['word'];
            $data =$this->Parts->find('all',[
                'conditions'=>['name like'=> "%$text%"],
                'limit'=>6,
                'offset'=>$page*6
            ])->contain(['Stocks']);
            if($data->count() == 0){
                $data =$this->Parts->find('all',[
                    'conditions'=>['model_number like'=> "%$text%"],
                    'limit'=>6,
                    'offset'=>$page * 6
                ])->contain(['Stocks']);
            }
            $total = $data->count();
            //debug($data->first());
        }
        else if(strcmp($this->request->query['type'],'detailed')==0){
            $text1 = $this->request->query['text1'];
            $text2 = $this->request->query['text2'];
            $text3 = $this->request->query['text3'];
            $data = array();

            $data_by_name =$this->Parts->find('all',['conditions'=>['name'=> $text1]])->contain(['Specs'=>['Spec_types']]);
            
            foreach($data_by_name as $obj){
                if(strcmp($obj->spec->VALUE,$text2) == 0 && strcmp($obj->spec->spec_type->UNIT,$text3) == 0){
                    $entity = $this->Parts->find('all',[
                        'conditions'=>['parts_id'=> $obj->PARTS_ID]   
                    ])->contain(['Stocks'])->first();
                    if($entity != null)
                    $data += array($obj->PARTS_ID => $entity);
                }
           }     
           $total = count($data);
        }
        else if(strcmp($this->request->query['type'],'drawer')==0){
            $text = $this->request->query['word'];
            $data = $this->Parts->find('all',[
                'conditions'=>['drawer_id like'=> "%$text%"],
                'limit' => 6,
                'offset'=> $page*6
            ])->contain(['Stocks']);
            $total = $data->count();
        }


        $spec_by_part = array();
        
        
        foreach($data as $obj){
            $spec = $specs->find('all',['conditions'=>['parts_id'=>$obj->PARTS_ID]])->contain(['Spec_types']);
            $spec->enableHydration(false);
            $spec = $spec->toArray();
            
            $spec_by_part+=array($obj->PARTS_ID => $spec);
        }
        
        if(floor(intval($page)/10) == 0){
            if((intval($page)+1 >= ceil($total/6)) && (intval($page) <= 0)){
                $this->set('back',substr($url,0,-1).intval(0));
                $this->set('next',substr($url,0,-1).intval($page));
            }
            else if(intval($page) <= 0){
                $this->set('back',substr($url,0,-1).intval(0));
                $this->set('next',substr($url,0,-1).intval($page+1));
            }
            else if(intval($page)+1 >= ceil($total/6)){
                $this->set('back',substr($url,0,-1).intval($page-1));
                $this->set('next',substr($url,0,-1).intval($page));
            }
            else{
                $this->set('back',substr($url,0,-1).intval($page-1));
                $this->set('next',substr($url,0,-1).intval($page+1));
            }
        }
        else{
            $this->set('back',substr($url,0,-2).intval($page-1));
            $this->set('next',substr($url,0,-2).intval($page+1));
        }
        
        $this->set('total',$total);
        $this->set('page',$page);
        $this->set('data',$data);
        $this->set('spec',$spec_by_part);
    }

    public function info(){
        $url = Router::reverse($this->request, false);
        $session = $this->request->session();
        $session->write('url',substr($url,4));

        $key = $this->request->query['name']; 
        $obj = $this->Parts->find('all',['conditions'=>['parts_id'=> $key]])->contain(['Stocks'])->first();//,'Specs'=>['Spec_types']]);//$this->Parts->findById($key);
        
        $makers = TableRegistry::get('Makers');
        $maker = $makers->find('all',['conditions'=>['maker_id'=>$obj->MAKER_ID]])->first(); 
        $this->set('maker',$maker);

        $datasheets = TableRegistry::get('Data_sheets');
        $datasheet = $datasheets->find('all',['conditions'=>['parts_id'=>$key]])->first(); 
        if($datasheet == null){
            $this->set('is_exist',false);
        }
        else{
            $this->set('is_exist',true);
            $filename = $datasheet->FILE_PATH;
            $this->set('filename',$filename);
        }

        $relational_parts = TableRegistry::get('Relational_parts');
        $relational_part = $relational_parts->find('all',['conditions'=>['Relational_parts.parts_id'=>$key]])->contain(['Parts']);
        $relation = array();
        foreach ($relational_part as $object):
            $entity = $this->Parts->find('all',['conditions'=>['parts_id'=> $object->parts['PARTS_ID']]])->contain(['Stocks'])->first();
            if($entity != null){
                $relation += array($object->parts['PARTS_ID'] => $object);
            }
        endforeach;

        if($relation == null)
            $this->set('is_exist_relation',false);
        else{
            $this->set('is_exist_relation',true);
            $this->set('relation',$relation);
        }

        $specs = TableRegistry::get('Specs');
        $spec_by_part = array();
        $spec = $specs->find('all',['conditions'=>['parts_id'=>$obj->PARTS_ID]])->contain(['Spec_types']);
        $spec->enableHydration(false);
        $spec = $spec->toArray();
        $spec_by_part+=array($obj->PARTS_ID => $spec);

        $purchases = TableRegistry::get('Purchases');
        $purchase_by_part = array();
        $purchase = $purchases->find('all',['conditions'=>['parts_id'=>$obj->PARTS_ID]])->contain(['Shops']);
        $purchase->enableHydration(false);
        $purchase = $purchase->toArray();
        $purchase_by_part+=array($obj->PARTS_ID => $purchase);
        
        $this->set('purchase',$purchase_by_part);
        $this->set('spec',$spec_by_part);
        $this->set('obj',$obj);
        $this->set('key',$key);
    }

    public function add($key=null,$word=null){
        $purchases = TableRegistry::get('Purchases');
        $shops = TableRegistry::get('Shops');
        $datasheets = TableRegistry::get('Data_sheets');
        $makers = TableRegistry::get('Makers');
        $stocks = TableRegistry::get('Stocks');
        $parts_types = TableRegistry::get('Parts_types');
        $specs = TableRegistry::get('Specs');
        $spec_types = TableRegistry::get('Spec_types');
        $parts_name_prefixes = TableRegistry::get('Parts_name_prefixs');
        
        $new_rated_num = 0;
        $flag = true;
        $new_flag = false;
        if($this->request->is('post')){
            $status = $this->request->data['status'];
            $key = $this->request->data['word'];
            if($this->Parts->find('all',['conditions'=>['name'=>$key]])->first() != null){
            $parts_type_id_placeholder = $this->Parts->find('all',['conditions'=>['name'=>$key]])->first()->PARTS_TYPE_ID;
            $this->set('parts_type_name',$parts_types->find('all',['conditions'=>['parts_type_id'=>$parts_type_id_placeholder]])->first()->PARTS_TYPE_NAME);
            }
            else{
                $new_flag = true;
                $parts_name_prefix = $parts_name_prefixes->find();
                $prefix_array = array();
                foreach($parts_name_prefix as $p):
                    array_push($prefix_array,$p->PREFIX);
                endforeach;
                $this->set('prefix_array',$prefix_array);
            }
            $this->set('new_flag',$new_flag);

            $parts_query = $this->Parts->find('all',['order'=>'Parts.parts_id + 0']);
            $i = 1;
            foreach($parts_query as $parts_obj):
                if((int)$parts_obj->PARTS_ID != $i)
                    break;
                $i++;
            endforeach;
            $this->set('new_id',$i);
        }

        if($key == null)
            $flag = false;
        $data =$this->Parts->find('all',['conditions'=>['name'=> $key]])->contain(['Specs'=>['Spec_types']]);
        $spec_array = array();

        foreach($data as $obj):
            $new_flag = true;
            if(count($spec_array) == 0)
                array_push($spec_array,array(
                    'spec_type_id'=>$obj->spec->spec_type->SPEC_TYPE_ID,
                    'spec_type'=>$obj->spec->spec_type->SPEC_NAME,
                    'unit'=>$obj->spec->spec_type->UNIT
                ));
            

            foreach($spec_array as $array):
                if(strcmp($obj->spec->spec_type->SPEC_NAME,$array['spec_type']) == 0)
                    $new_flag = false;
            endforeach;

            if($new_flag)
            array_push($spec_array,array(
                'spec_type_id'=>$obj->spec->spec_type->SPEC_TYPE_ID,
                'spec_type'=>$obj->spec->spec_type->SPEC_NAME,
                'unit'=>$obj->spec->spec_type->UNIT
            ));
        endforeach;

        $this->set('spec_array',$spec_array);

        $part = $this->Parts->newEntity();
        
        if($this->request->is('post') && strcmp($this->request->data['status'],'input2')==0){
            $id = $this->request->data['id'];
            $name = $this->request->data['name'];
            $model_number = $this->request->data['model_number'];
            $number = $this->request->data['number'];
            $place = $this->request->data['place'];
            $shop_name = $this->request->data['shop_name'];
            $min_num = $this->request->data['min_num'];
            $set_num = $this->request->data['set_num'];
            $unit_price = $this->request->data['unit_price'];
            $shop_model_number = $this->request->data['shop_model_number'];
            $maker_name = $this->request->data['maker'];
            $parts_type = $this->request->data['parts_type'];

            //debug($makers->find('all',['conditions'=>['MAKER_NAME'=>$maker_name]])->first());
            if($makers->find('all',['conditions'=>['MAKER_NAME'=>$maker_name]])->first() == null){
                $maker_id = $makers->find('all',['order'=>['maker_id'=>'DESC']])->first()->MAKER_ID+1;
                $maker_entity = $makers->newEntity();
                $maker_entity->MAKER_ID = $maker_id;
                $maker_entity->MAKER_NAME = $maker_name;
                if(!$makers->save($maker_entity)){
                    $this->Flash->error(__('Unable to add the maker.'));
                }
            }
            else{
                $maker_id = $makers->find('all',['conditions'=>['MAKER_NAME'=>$maker_name]])->first()->MAKER_ID;
            }

            $parts_type_id = $parts_types->find('all',['conditions'=>['PARTS_TYPE_NAME'=>$parts_type]])->first()->PARTS_TYPE_ID;

            $parts_entity = $this->Parts->newEntity();
            $parts_entity->PARTS_ID = $id;
            $parts_entity->NAME = $name;
            $parts_entity->MODEL_NUMBER = $model_number;
            $parts_entity->PARTS_TYPE_ID = $parts_type_id;
            $parts_entity->MAKER_ID = $maker_id;
            if($new_flag)
                $parts_entity->PARTS_NAME_PREFIX_ID = $this->request->data['prefix'];
            else{
                $parts_entity->PARTS_NAME_PREFIX_ID = $this->Parts->find('all',['conditions'=>['name'=>$key]])->first()->PARTS_NAME_PREFIX_ID;
            }

            if(!$this->Parts->save($parts_entity)){
                $this->Flash->error(__('Unable to add the parts.'));
            }
           
            $stock_entity = $stocks->newEntity();
            $stock_entity->STOCK_ID=$stocks->find('all',['order'=>['stock_id'=>'DESC']])->first()->STOCK_ID+1;
            $stock_entity->PART_ID = $id;
            $stock_entity->STOCK_NUM = $number;
            $stock_entity->DRAWER_ID = $place;
            if(!$stocks->save($stock_entity)){
                $this->Flash->error(__('Unable to add the stocks.'));
                return $this->redirect('/users');
            }
            

            $new_spec = array();
            foreach($spec_array as $array):
            //debug($this->request->data[$array['spec_type_id'].'rated']);
            //debug($this->request->data[$array['spec_type_id'].'unit']);
            if(strcmp($this->request->data[$array['spec_type_id'].'rated'],'') != 0){
                $specs_entity = $specs->newEntity();
                $specs_entity->SPEC_ID = $specs->find('all',['order'=>['spec_id'=>'DESC']])->first()->SPEC_ID+1;
                $specs_entity->VALUE=$this->request->data[$array['spec_type_id'].'rated'];
                if($spec_types->find('all',['conditions'=>['SPEC_NAME'=>$array['spec_type']]])->count() == 0){
                    $specs_entity->SPEC_TYPE_ID= $spec_types->find('all',['order'=>['spec_type_id'=>'DESC']])->first()->SPEC_TYPE_ID+1;
                    $spec_types_entity = $spec_types->newEntity();
                    $spec_types_entity->SPEC_TYPE_ID = $spec_types->find('all',['order'=>['spec_type_id'=>'DESC']])->first()->SPEC_TYPE_ID+1;
                    $spec_types_entity->SPEC_NAME = $array['spec_type'];
                    $spec_types_entity->UNIT = $array['spec_type_id'].'unit';
                    debug($spec_types_entity);
                    if(!$spec_types->save($spec_types_entity)){
                        $this->Flash->error(__('Unable to add the spec_types.'));
                    }
                }
                else
                    $specs_entity->SPEC_TYPE_ID= $spec_types->find('all',['conditions'=>['spec_name'=>$array['spec_type']]])->first()->SPEC_TYPE_ID;
                
                //$specs_entity->UNIT = $this->request->data[$array['spec_type_id'].'unit'];
                $specs_entity->PARTS_ID = $id;

                if(!$specs->save($specs_entity)){
                    $this->Flash->error(__('Unable to add the specs.'));
                }
            }
            endforeach;

            $new_spec_name = $this->request->data['new_spec_name'];
            $new_spec_value = $this->request->data['new_spec_value'];
            $new_spec_unit = $this->request->data['new_spec_unit'];
            for($i = 0;$i < count($new_spec_name);$i++){
                if(strcmp($new_spec_name[$i],'') != 0 && strcmp($new_spec_value[$i],'') != 0){
                    $specs_entity = $specs->newEntity();
                    $specs_entity->SPEC_ID = $specs->find('all',['order'=>['spec_id'=>'DESC']])->first()->SPEC_ID+1;
                    $specs_entity->VALUE = $new_spec_value[$i];
                    if($spec_types->find('all',['conditions'=>['SPEC_NAME'=>$new_spec_name[$i]]])->count() == 0){
                        $specs_entity->SPEC_TYPE_ID= $spec_types->find('all',['order'=>['spec_type_id'=>'DESC']])->first()->SPEC_TYPE_ID+1;
                        $spec_types_entity = $spec_types->newEntity();
                        $spec_types_entity->SPEC_TYPE_ID = $spec_types->find('all',['order'=>['spec_type_id'=>'DESC']])->first()->SPEC_TYPE_ID+1;
                        $spec_types_entity->SPEC_NAME = $new_spec_name[$i];
                        $spec_types_entity->UNIT = $new_spec_unit[$i];
                        if(!$spec_types->save($spec_types_entity)){
                            $this->Flash->error(__('Unable to add the spec_types.'));
                        }
                    }
                    else
                        $specs_entity->SPEC_TYPE_ID= $spec_types->find('all',['conditions'=>['spec_name'=>$new_spec_name[$i]]])->first()->SPEC_TYPE_ID;
                    
                    //$specs_entity->UNIT = $this->request->data[$array['spec_type_id'].'unit'];
                    $specs_entity->PARTS_ID = $id;
    
                    if(!$specs->save($specs_entity)){
                        $this->Flash->error(__('Unable to add the specs.'));
                    }
                }
            }
            

            $shops_entity = $shops->newEntity();
            if($shops->find('all',['conditions'=>['SHOP_NAME'=>$shop_name]])->first() == null){
                $shop_id = $shops->find('all',['order'=>['shop_id'=>'DESC']])->first()->SHOP_ID+1;
                $shops_entity->SHOP_ID = $shop_id;
                $shops_entity->SHOP_NAME = $shop_name;
                if(!$shops->save($shops_entity)){
                    $this->Flash->error(__('Unable to add the shops.'));
                }
            }
            else
                $shop_id = $shops->find('all',['conditions'=>['SHOP_NAME'=>$shop_name]])->first()->SHOP_ID;  

            $purchases_entity = $purchases->newEntity();
            $purchases_entity->PURCHASE_ID=$purchases->find('all',['order'=>['purchase_id'=>'DESC']])->first()->PURCHASE_ID+1;
            $purchases_entity->MIN_NUM = $min_num;
            $purchases_entity->SET_NUM = $set_num;
            $purchases_entity->UNIT_PRICE=$unit_price;
            $purchases_entity->SHOP_MODEL_NUMBER =  $shop_model_number;
            $purchases_entity->SHOP_ID = $shop_id;
            $purchases_entity->PARTS_ID = $id;
            if(!$purchases->save($purchases_entity)){
                $this->Flash->error(__('Unable to add the shops.'));
            }

            $file = $this->request->data['datasheet'];
            debug($file);
            $filename = $file['name'];
            $uploaded_file = $file['tmp_name'];
            $filetype = $file['type'];

            //if($datasheets->find('all',['conditions'=>['FILE_PATH'=>$filename]])->count() == 0){
                if(move_uploaded_file($uploaded_file,'C:\xampp\htdocs\pms\webroot\datasheets/'.$filename))
                    debug('成功');
                else
                    debug('失敗');
                $datasheet_entity = $datasheets->newEntity();
                $datasheet_entity->DATA_SHEET_ID = $datasheets->find('all',['order'=>['data_sheet_id'=>'DESC']])->first()->DATA_SHEET_ID + 1;
                $datasheet_entity->FILE_PATH = $filename;
                $datasheet_entity->PARTS_ID = $id;
                if(!$datasheets->save($datasheet_entity)){
                    $this->Flash->error(__('Unable to add the datasheets.'));
                }
            //}
        }


        $this->set('key',$key);
        $this->set('part',$part);
        $this->set('flag',$flag);
        $this->set('new_rated_num',$new_rated_num);
    }

    public function use(){
        $session = $this->request->session();
        $url = $session->read('url');
        
        if($this->request->is('post')){
            $connection = ConnectionManager::get('default');
            try{
                $connection->begin();
                $id = $this->request->data['id'];
                $number = $this->request->data['num'];

                $stocks = TableRegistry::get('Stocks');
                $stock = $stocks->find()->where(['Stocks.part_id'=>$id])->first();
                if($stock->STOCK_NUM >= $number)
                    $stock->STOCK_NUM -= $number;
                else
                    $stock->STOCK_NUM = 0;
                $stocks->save($stock);
                $connection->commit();
            }catch(Exception $e){
                $connection->rollback();
                Log::write('debug',$e->getMessage());
            }
        }
        return $this->redirect($url);
    }

    public function purchase(){
        $session = $this->request->session();
        $url = $session->read('url');
        
        if($this->request->is('post')){
            $connection = ConnectionManager::get('default');
            try{
                $connection->begin();
                $id = $this->request->data['id'];
                $number = $this->request->data['num'];

                $stocks = TableRegistry::get('Stocks');
                $stock = $stocks->find()->where(['Stocks.part_id'=>$id])->first();
                $stock->STOCK_NUM += $number;
                $stocks->save($stock);
                $connection->commit();
            }catch(Exception $e){
                $connection->rollback();
                Log::write('debug',$e->getMessage());
            }
        }
        return $this->redirect($url);
    }

    public function sql(){
        $this->set('result','result');
        if($this->request->is('post')){
            if(strcmp($this->Auth->user('role'),'admin') == 0){
                $sql = $this->request->data['script'];
                try{
                    $connection = ConnectionManager::get('default');
                    $result = $connection->execute($sql)->fetchAll('assoc');
                    $this->set('result',$result);
                }catch(\PDOException $e){
                    $this->set('result',"構文または通信に問題があります");
                }
            }
            else{
                $this->set('result',"管理者以外実行できません");
            }
        }
    }
}