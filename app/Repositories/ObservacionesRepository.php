<?php
namespace App\Repositories;

use App\Models\Observaciones;
use Carbon\Carbon;

class ObservacionesRepository implements ObservacionesRepositoryInterface
{
protected $modelColumns;
   public function __construct()    
   {
    $this->modelColumns =(new Observaciones())->getFillable();
   }
   public function paginateAllByMonth(Carbon $date,int $cant){
    return Observaciones::whereMonth('fechaMovimiento',$date->month)->paginate($cant);
}
    public function getOne($id)
    {
        return Observaciones::where('idRegistro','=',$id)->first();
    }
    public function getLast()
    {
        return Observaciones::orderBy('idRegistro', 'desc')->first();
    }
    public function create(array $data){
        return Observaciones::create($data);
    }
}