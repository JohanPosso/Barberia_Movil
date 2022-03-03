<?php
header('Access-Control-Allow-Origin: *'); 
header('Content-Type: application/json;charset=utf-8');
/**
 * Servicios externos para la aplicacion
 */
include_once ("externos.php");
include_once ("php/validation.php");

class formulario extends Base {
  
  function validar_registrar(){
    $v = new Validation($_POST);
    $v->addRules('nombre', 'Nombre', array('required' => true) );

        $result = $v->validate();

        if ($result['messages'] == "") {//No hay errores de validacion
            return true;
        } else { //Errores de validación
            $r['error'] = true;
            $r['msg'] = $result['messages'];
            $r['bad_fields'] = $result['bad_fields'];
            $r['errors'] = $result['errors'];
            echo json_encode($r);
            exit(0);
        }
        return true;
  }


   function validar_modificar(){
    $v = new Validation($_POST);
    $v->addRules('nombre', 'Nombre', array('required' => true) );
    $v->addRules('id', 'ID', array('required' => true,'integer'=>true) );

        $result = $v->validate();

        if ($result['messages'] == "") {//No hay errores de validacion
            return true;
        } else { //Errores de validación
            $r['error'] = true;
            $r['msg'] = $result['messages'];
            $r['bad_fields'] = $result['bad_fields'];
            $r['errors'] = $result['errors'];
            echo json_encode($r);
            exit(0);
        }
        return true;
  }

   function validar_eliminar(){
    $v = new Validation($_POST);
    
    $v->addRules('id', 'ID', array('required' => true,'integer'=>true) );

        $result = $v->validate();

        if ($result['messages'] == "") {//No hay errores de validacion
            return true;
        } else { //Errores de validación
            $r['error'] = true;
            $r['msg'] = $result['messages'];
            $r['bad_fields'] = $result['bad_fields'];
            $r['errors'] = $result['errors'];
            echo json_encode($r);
            exit(0);
        }
        return true;
  }


  // PERMITE LISTAR LOS ELEMENTOS DE LA BASE DE DATOS
  function listar(){
    //$this->validar();
    $sql = "SELECT * FROM reserva WHERE visible=1";
    $data = $this->db->select_all($sql);

    $result = array();
    $result['msg']='ok';
    $result['data']=$data;
    $result['error']=false;
    echo json_encode($result);
  }


   function get_informacion(){
    //$this->validar();
    $ide = $_GET['id'];
    $sql = "SELECT * FROM servicios WHERE id_reserva='$ide'";
    $data = $this->db->select_row($sql);

    $result = array();
    $result['msg']='ok';
    $result['data']=$data;
    $result['error']=false;
    echo json_encode($result);
  }

  // PERMITE REGISTRAR LOS ELEMENTO A LA BASE DE DATOS
  function registrar(){
    $this->validar_registrar();
    $insert = $_POST;
    $insert['visible']=1;
    $insert['fechar']=date("Y-m-d H:i:s");

    $this->db->insert("servicios",$insert);

    $result = array();
    $result['msg']='Registro registrado';
    $result['data']= $this->db->last_insert_id();
    $result['error']=false;
    echo json_encode($result);
  }

  // PERMITE REGISTRAR LOS ELEMENTO A LA BASE DE DATOS
  function actualizar(){
    $this->validar_modificar(); 
    $idu = $_POST['id'];
    $update = $_POST;
    unset($update['id']); // SE ELIMINA LA ID

    $this->db->update("reserva",$update,array('id_reserva'=>$idu));

    $result = array();
    $result['msg']='Registro actualizado';
    $result['data']=$idu;
    $result['error']=false;
    echo json_encode($result);
  }


  function eliminar(){
     $this->validar_eliminar(); 
    $ide = $_POST['id'];
    $update = array();
    $update['visible']=2;
    $this->db->update("reserva",$update,array('id_reserva'=>$ide));

    $result = array();
    $result['msg']='Registro eliminado';
    $result['data']=$ide;
    $result['error']=false;
    echo json_encode($result);
  }

}

// Recibir por GET
$accion  = $_GET['accion'];
$formulario = new formulario();
$formulario->$accion();


?>