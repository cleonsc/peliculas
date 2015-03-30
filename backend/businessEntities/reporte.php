<?php

class Reporte {
    
    private $id_reporte;
    
    private $numero_cliente;
    
    private $razon_social;
    
    private $direccion;
    
    private $departamento;
    
    private $telefono;
    
    private $nombre_contacto;
    
    private $visita_venta;
    
    private $llamado_cobranza;
    
    private $dia_entrega;
    
    private $dia_venta;
    
    private $dia_cobranza;
    
    function getId_reporte() {
        return $this->id_reporte;
    }

    function getNumero_cliente() {
        return $this->numero_cliente;
    }

    function getRazon_social() {
        return $this->razon_social;
    }

    function getDireccion() {
        return $this->direccion;
    }

    function getDepartamento() {
        return $this->departamento;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getNombre_contacto() {
        return $this->nombre_contacto;
    }

    function getVisita_venta() {
        return $this->visita_venta;
    }

    function getLlamado_cobranza() {
        return $this->llamado_cobranza;
    }

    function getDia_entrega() {
        return $this->dia_entrega;
    }

    function getDia_venta() {
        return $this->dia_venta;
    }

    function getDia_cobranza() {
        return $this->dia_cobranza;
    }

    function setId_reporte($id_reporte) {
        $this->id_reporte = $id_reporte;
    }

    function setNumero_cliente($numero_cliente) {
        $this->numero_cliente = $numero_cliente;
    }

    function setRazon_social($razon_social) {
        $this->razon_social = $razon_social;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setNombre_contacto($nombre_contacto) {
        $this->nombre_contacto = $nombre_contacto;
    }

    function setVisita_venta($visita_venta) {
        $this->visita_venta = $visita_venta;
    }

    function setLlamado_cobranza($llamado_cobranza) {
        $this->llamado_cobranza = $llamado_cobranza;
    }

    function setDia_entrega($dia_entrega) {
        $this->dia_entrega = $dia_entrega;
    }

    function setDia_venta($dia_venta) {
        $this->dia_venta = $dia_venta;
    }

    function setDia_cobranza($dia_cobranza) {
        $this->dia_cobranza = $dia_cobranza;
    }


    
}

