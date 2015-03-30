<?php

include_once(SERVICE_INTERFACE . 'IReporteService.php');


class reporteService implements IReporteService{
    private $reporteDAO;

    public function __construct(){
        $this->reporteDAO = DAOFactory::getDAO('reporte');
    }
    
    public function getReporte(){
        return $this->reporteDAO->obtenerReporte();
    }
    
    public function getReporteXId($id) {
        return $this->reporteDAO->getReporteXId($id);
        
    }

    
    public function nuevoCliente($direccion,$departamento,$telefono,$nombreContacto,$visitaVenta,$diaEntrega,$diaVenta,$diaCobranza) {
        $this->reporteDAO->nuevoCliente($direccion,$departamento,$telefono,$nombreContacto,$visitaVenta,$diaEntrega,$diaVenta,$diaCobranza);
    }
    
    public function modificarCliente($datosCliente){
    	return $this->reporteDAO->modificarCliente($datosCliente);
    }
}

