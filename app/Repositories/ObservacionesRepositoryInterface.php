<?php
namespace App\Repositories;

use Carbon\Carbon;

interface ObservacionesRepositoryInterface
{
    public function paginateAllByMonth(Carbon $date, int $cant);
    public function getOne($id);
    public function getLast();
    public function create(array $data);    
    
}