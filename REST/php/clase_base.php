<?php

class Base {

    protected $db;
    protected $dbsql;
    protected $dbda;
    protected $dbe;
    protected $usuario_activo;

    public function __construct() {
        $this->db = $GLOBALS['db'];
        $this->dbsql = $GLOBALS['dbsql'];
        $this->dbda = $GLOBALS['dbda'];
        $this->dbe = $GLOBALS['dbe'];
    }

}

//Para compactibilidad con formularios antiguos
class clase_base extends Base { 
    
}

?>