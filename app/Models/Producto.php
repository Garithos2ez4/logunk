<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Producto extends Model
{
    public $timestamps = false;
 
    protected $table = 'Producto';
    
    protected $primaryKey = 'idProducto';

    protected $guarded = ['idProducto'];
    
    protected $fillable = ['idProducto',
                            'idMarca',
                            'idGrupo',
                            'nombreProducto',
                            'codigoProducto',
                            'UPC',
                            'partNumber',
                            'numeroSerie',
                            'modelo',
                            'precioDolar',
                            'gananciaExtra',
                            'garantia',
                            'descripcionProducto',
                            'imagenProducto1',
                            'imagenProducto2',
                            'imagenProducto3',
                            'imagenProducto4',
                            'estadoProductoWeb',
                            'stockMin',
                            'slugProducto',
                            'url_local',
                            'url_youtube'
                            ];

    
    protected $hidden = [
        
    ];

    
    protected $casts = [
        'idProducto' => 'int',
        'idMarca' => 'int',
        'idGrupo' => 'int',
        'stockMin' => 'int',
        'precioDolar' => 'float',
        'gananciaExtra' => 'float'
    ];
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($producto) {
            $producto->slugProducto = Str::slug($producto->nombreProducto);
        });

        static::updating(function ($producto) {
            $producto->slugProducto = Str::slug($producto->nombreProducto);
        });
    }
    
    public function Publicacion()
    {
        return $this->hasMany(Publicacion::class, 'idProducto', 'idProducto');
    }

    public function Caracteristicas_Producto()
    {
        return $this->hasMany(Caracteristicas_Producto::class, 'idProducto', 'idProducto');
    }
    
    public function DetalleComprobante()
    {
        return $this->hasMany(DetalleComprobante::class, 'idProducto', 'idProducto');
    }

    public function MarcaProducto()
    {
        return $this->belongsTo(MarcaProducto::class,'idMarca','idMarca');
    }
    
    public function GrupoProducto()
    {
        return $this->belongsTo(GrupoProducto::class, 'idGrupo', 'idGrupoProducto');
    }
    
    public function Inventario()
    {
        return $this->hasMany(Inventario::class, 'idProducto', 'idProducto');
    }
    
    public function Inventario_Proveedor()
    {
        return $this->belongsTo(Inventario_Proveedor::class, 'idProducto', 'idProducto');
    }
    
    public function publicImages(){
        
            $default = asset('storage/noimagen.webp');
    
            $imagen1 = $this->imagenProducto1 ? asset('storage/'.$this->imagenProducto1) : $default;
            $imagen2 = $this->imagenProducto2 ? asset('storage/'.$this->imagenProducto2) : $default;
            $imagen3 = $this->imagenProducto3 ? asset('storage/'.$this->imagenProducto3) : $default;
            $imagen4 = $this->imagenProducto4 ? asset('storage/'.$this->imagenProducto4) : $default;
    
            $images = [$imagen1, $imagen2, $imagen3, $imagen4];
            
    
        return $images;
    }
    public function registros() {
        return $this->hasMany(RegistroProducto::class, 'idProducto', 'idProducto');
    }
    
    public function estadoColor(){
        if ($this->estadoProductoWeb == 'DISPONIBLE') {
            return 'text-success';
        } elseif ($this->estadoProductoWeb == 'OFERTA') {
            return 'text-danger';
        } else {
            return 'text-danger text-decoration-line-through';
        }
    }
    
    public function displayImg($img){
        if($img == "asset('storage/images/noimagen.webp')"){
            return "d-none";
        }else{
            return "";
        }
    }
}