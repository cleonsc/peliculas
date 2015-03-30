<?php


interface IReporteService {
    public function getReporte();
    public function getReporteXId($id);
    public function modificarCliente($datosCliente);
    public function nuevoCliente($direccion,$departamento,$telefono,$nombreContacto,$visitaVenta,$diaEntrega,$diaVenta,$diaCobranza);
}
