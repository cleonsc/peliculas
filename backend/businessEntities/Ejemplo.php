<?php

class Autorizacion {

    private $id_autorizacion;
    private $id_parte;
    private $c_usuario;
    private $id_estado;
    private $f_autorizacion;
    
    public $usuario;
    public $estado;
    
    const PENDIENTE = 0;
    const APROBADO  = 1;
    const RECHAZADO = 2;
    const VENCIDO   = 3;
    

    public function __construct() {
        
    }

    public function getId_autorizacion() {
        return $this->id_autorizacion;
    }

    public function setId_autorizacion($id_autorizacion) {
        $this->id_autorizacion = $id_autorizacion;
    }

        public function getId_parte() {
        return $this->id_parte;
    }

    public function setId_parte($id_parte) {
        $this->id_parte = $id_parte;
    }
    public function getC_usuario() {
        return $this->c_usuario;
    }

    public function setC_usuario($c_usuario) {
        $this->c_usuario = $c_usuario;
    }

    
    public function getId_estado() {
        return $this->id_estado;
    }

    public function setId_estado($id_estado) {
        $this->id_estado = $id_estado;
    }

    public function getF_autorizacion() {
        return $this->f_autorizacion;
    }

    public function setF_autorizacion($f_autorizacion) {
        $this->f_autorizacion = $f_autorizacion;
    }
    
    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }
    
    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }





}

?>
