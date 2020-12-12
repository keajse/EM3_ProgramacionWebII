<?php namespace App\Models;

use CodeIgniter\Model;

class ModeloAnimales extends Model{

    protected $table='animales';
    protected $primaryKey='id';

    protected $allowedFields = array('nameanimal','ageanimal','time','description','foodanimal','typeanimal','photoanimal');

}