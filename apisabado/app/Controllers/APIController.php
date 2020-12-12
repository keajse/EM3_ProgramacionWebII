<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class APIController extends ResourceController
{
    protected $modelName = 'App\Models\ModeloAnimales';
    protected $format    = 'json';

    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    // ...

    public function buscarAnimal($id)
    {
       $busqueda=$this->model->find($id);

       if ($busqueda) {
           return $this->respond($busqueda);
       } else {
           $mensaje=array("mensaje"=>"Error encontrando el id","estado"=>false);
           return $this->respond(json_encode($mensaje),400);
       }
       
    }

    public function insertar()
    {

        // 1. Recibir los datos desde el cliente

        $nameanimal = $this->request->getPost("nameanimal");
        $ageanimal = $this->request->getPost("ageanimal");
        $time = $this->request->getPost("time");
        $foodanimal = $this->request->getPost("foodanimal");
        $description = $this->request->getPost("description");
        $typeanimal = $this->request->getPost("typeanimal");
        $photoanimal = $this->request->getPost("photoanimal");

        //2. Organizar los datos que llegan de las vistas
        // en un arreglo asociativo 
        //(las claves deben ser iguales a los campos o atributos de la tabla en BD)
        $datosEnvio = array(
            "nameanimal" => $nameanimal,
            "ageanimal" => $ageanimal,
            "time" => $time,
            "foodanimal" => $foodanimal,
            "description" => $description,
            "typeanimal" => $typeanimal,
            "photoanimal" => $photoanimal
        );

        //3. Utilizar el atributo this-> validate del controlador para validar datos
        if ($this->validate('animalPOST')) {
            $id = $this->model->insert($datosEnvio);

            return $this->respond($this->model->find($id));
        } else {
            $validation = \Config\Services::validation();
            return $this->respond($validation->getErrors());
        }
    }

    public function eliminar($id)
    {

        $busqueda=$this->model->find($id);

        $consulta=$this->model->where('id',$id)->delete();
        $filasAfectadas=$consulta->connID->affected_row;

        if ($busqueda) {
            $mensaje=array("mensaje"=>"usuario eliminado","estado"=>true);
            return $this->respond(json_encode($mensaje));
        } else {
            $mensaje=array("mensaje"=>"El id, no existe","estado"=>false);
            return $this->respond(json_encode($mensaje));
        }
        

    }

    public function actualizar($id)
    {
        //1. Recibir los datos desde el cliente
        $datosPeticion=$this->request->getRawInput();
        

        //2.  Depurar el arreglo
        $nameanimal= $datosPeticion["nameanimal"];        
        $description = $datosPeticion["description"];       
        
        //3. Organizar el formato
        $datosEnvio = array(
            
            "nameanimal" => $nameanimal,            
            "description" => $description           
           
        );
        
        //4. Ejecutar la validación
        if ($this->validate('animalPUT')) 
        {

            try
            {
                $this->model->update($id,$datosEnvio);

                return $this->respond($this->model->find($id));
            }
            catch(\Exception $error)
            {

                echo($error->getMessage());

            }
           
            
        } 
        else 
        {
            $validation = \Config\Services::validation();
            return $this->respond($validation->getErrors());
        }
        

    }
}
