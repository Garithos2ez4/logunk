<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observaciones extends Model {

    public $timestaps = false;

    protected $table = 'RegistroProducto';

    protected $guarded=['idRegistro'] ;
}