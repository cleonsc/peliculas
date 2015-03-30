<?php

interface IReporteDAO {
    public function obtenerReporte();
    public function getReporteXId($id);
    public function modificarCliente($datosCliente);
}

