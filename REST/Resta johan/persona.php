<?php

use LDAP\Result;

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
    $v->addRules('apellido', 'Apellido', array('required' => true) );
    $v->addRules('id_tipo_documento', 'Tipo de documento', array('required' => true) );
    $v->addRules('telefono', 'Telefono', array('required' => true) );
    $v->addRules('numero_documento', 'Numero documento', array('required' => true) );
    $v->addRules('id_cargo', 'Cargo', array('required' => true) );

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
    $v->addRules('apellido', 'Apellido', array('required' => true) );
    $v->addRules('id_tipo_documento', 'Tipo de documento', array('required' => true) );
    $v->addRules('telefono', 'Telefono', array('required' => true) );
    $v->addRules('numero_documento', 'Numero documento', array('required' => true) );
    $v->addRules('id_cargo', 'Cargo', array('required' => true) );
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
    $sql = "SELECT persona.id_persona,
    tipo_documento.id_tipo_documento,
    cargo.id_cargo,
    tipo_documento.nombre as tipo_identificacion,
    cargo.nombre as cargo,
    persona.nombre,
    persona.apellido,
    persona.numero_documento,
    CONCAT_WS(' ',persona.nombre,persona.apellido) as nombre_completo,
    persona.email,
    persona.telefono
    from persona
     join tipo_documento on persona.id_tipo_documento=tipo_documento.id_tipo_documento
     join cargo on persona.id_cargo=cargo.id_cargo WHERE  persona.visible=1";
    $data = $this->db->select_all($sql);

    $result = array();
    $result['msg']='ok';
    $result['data']=$data;
    $result['error']=false;
    echo json_encode($result);
  }

  function get_tipos_documentos(){
    //$this->validar();
    $sql = "SELECT * FROM tipo_documento WHERE visible=1";
    $data = $this->db->select_all($sql);

    $result = array();
    $result['msg']='ok';
    $result['data']=$data;
    $result['error']=false;
    echo json_encode($result);
  }

  
  function get_cargos(){
    //$this->validar();
    $sql = "SELECT * FROM cargo WHERE visible=1";
    $data = $this->db->select_all($sql);

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
    unset($insert['id']); // SE ELIMINA LA ID

    $insert['visible'] = 1;
    $insert['fechar'] = date("y-m-d h:i:s");
    $this->db->insert("persona",$insert);

    $result = array();
    $result['msg']='Registro exitoso';
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

    $this->db->update("persona",$update,array('id_persona'=>$idu));

    $result = array();
    $result['msg']='Registro actualizado';
    $result['data']=$idu;
    $result['error']=false;
    echo json_encode($idu);
  }


  function eliminar(){
    
    $ide = $_POST['id'];
    $update = array();
    $update['visible']=2;
    $this->db->update("persona",$update,array('id_persona'=>$ide)); 

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