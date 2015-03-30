<?php

class clienteDTO {
    
    private $idReporte;
    
    private $numeroCliente;
    
    private $razonSocial;
    
    private $direccion;
    
    private $departamento;
    
    private $telefono;
    
    private $nombreContacto;
    
    private $visitaVenta;
    
    private $diaEntrega;
    
    private $diaVenta;
    
    private $diaCobranza;
    
    
    public function __construct() {
        
    }

    
    public function getIdReporte() {
        return $this->idReporte;
    }

    public function setIdReporte($idReporte) {
        $this->idReporte = $idReporte;
    }

        
    public function getNumeroCliente() {
        return $this->numeroCliente;
    }

    public function getRazonSocial() {
        return $this->razonSocial;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getDepartamento() {
        return $this->departamento;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getNombreContacto() {
        return $this->nombreContacto;
    }

    public function getVisitaVenta() {
        return $this->visitaVenta;
    }

    public function getDiaEntrega() {
        return $this->diaEntrega;
    }

    public function getDiaVenta() {
        return $this->diaVenta;
    }

    public function getDiaCobranza() {
        return $this->diaCobranza;
    }

    public function setNumeroCliente($numeroCliente) {
        $this->numeroCliente = $numeroCliente;
    }

    public function setRazonSocial($razonSocial) {
        $this->razonSocial = $razonSocial;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setNombreContacto($nombreContacto) {
        $this->nombreContacto = $nombreContacto;
    }

    public function setVisitaVenta($visitaVenta) {
        $this->visitaVenta = $visitaVenta;
    }

    public function setDiaEntrega($diaEntrega) {
        $this->diaEntrega = $diaEntrega;
    }

    public function setDiaVenta($diaVenta) {
        $this->diaVenta = $diaVenta;
    }

    public function setDiaCobranza($diaCobranza) {
        $this->diaCobranza = $diaCobranza;
    }


}

