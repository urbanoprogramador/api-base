<?php 

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

trait ApiResponser{
	private function sussessResponse($data,$code){
        return response()->json(["data"=>$data],$code);
    }
    protected function errorResponse($message,$code){
        return response()->json(['error'=>$message,'code'=>$code],$code);
    }
    protected function showAll( Collection $collection ,$code=200, $tranfor=null){
        if($collection->isEmpty()){
            return $this->sussessResponse(['data'=>$collection],$code);
        }
        return $this->sussessResponse($collection,$code);
    }
    protected function showOne(Model $instance ,$code=200){
        return $this->sussessResponse($instance,$code);
    }
    protected function showArray($array,$code=200){
        return $this->sussessResponse($array,$code);
    }
}