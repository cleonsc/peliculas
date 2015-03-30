<?php

include_once(DAO_INTERFACE . 'IReporteDAO.php');
include_once('BaseDAO.php');

class ReporteDAO extends DAOGeneric implements IReporteDAO {
    function __construct($conexion) {
        $this->db = $conexion;
    }
    
    public function obtenerReporte(){
        $sql = "select * from reportes";
        $sp = $this->db->PrepareSP($sql);
        $rs = $this->db->Execute($sp);
        
        $reportes =  array();
        while (!$rs->EOF) {
            $reporte = new Reporte();
            $tr = $rs->GetRowAssoc(false);
            $reporte->setId_reporte($tr['id_reporte']);
            $reporte->setNumero_cliente($tr['numero_cliente']);
            $reporte->setRazon_social($tr['razon_social']);
            $reporte->setDireccion($tr['direccion']);
            $reporte->setDepartamento($tr['departamento']);
            $reporte->setTelefono($tr['telefono']);
            $reporte->setNombre_contacto($tr['nombre_contacto']);
            $reporte->setVisita_venta($tr['visita_venta']);
            $reporte->setLlamado_cobranza($tr['llamado_cobranza']); 
            $reporte->setDia_entrega($tr['dia_entrega']);
            $reporte->setDia_venta($tr['dia_venta']);
            $reporte->setDia_cobranza($tr['dia_cobranza']);
            
            $reportes[] = $reporte;
            $rs->MoveNext();

        }
        $rs->Close();
        return $reportes;
    }
    
    public function getReporteXId($id) {
        $sql = "select * from reportes where id_reporte = ". $id;
        $sp = $this->db->PrepareSP($sql);
        $rs = $this->db->Execute($sp);
        
        while (!$rs->EOF) {
            $reporte = new Reporte();
            $tr = $rs->GetRowAssoc(false);
            $reporte->setId_reporte($tr['id_reporte']);
            $reporte->setNumero_cliente($tr['numero_cliente']);
            $reporte->setRazon_social($tr['razon_social']);
            $reporte->setDireccion($tr['direccion']);
            $reporte->setDepartamento($tr['departamento']);
            $reporte->setTelefono($tr['telefono']);
            $reporte->setNombre_contacto($tr['nombre_contacto']);
            $reporte->setVisita_venta($tr['visita_venta']);
            $reporte->setLlamado_cobranza($tr['llamado_cobranza']); 
            $reporte->setDia_entrega($tr['dia_entrega']);
            $reporte->setDia_venta($tr['dia_venta']);
            $reporte->setDia_cobranza($tr['dia_cobranza']);
            
            $rs->MoveNext();

        }
        $rs->Close();
        return $reporte;
    }
    
   /* public function modificarCliente($id,$direccion,$departamento,$telefono,$nombreContacto,$visitaVenta,$diaEntrega,$diaVenta,$diaCobranza) {
        $sql ="UPDATE reportes SET direccion = '".$direccion."',departamento = '".$departamento ."',telefono = '".$telefono."',nombre_contacto = '".$nombreContacto .
                "',visita_venta = '".$visitaVenta ."',dia_entrega = '".$diaEntrega ."',dia_venta = '".$diaVenta . "',dia_cobranza = '".$diaCobranza ."' WHERE id_reporte = ".$id;
        $sp = $this->db->PrepareSP($sql);
        $rs = $this->db->Execute($sp);
    } */
    
   public function nuevoCliente($direccion,$departamento,$telefono,$nombreContacto,$visitaVenta,$diaEntrega,$diaVenta,$diaCobranza) {
       
       $sql = "INSERT INTO reportes (numero_cliente, razon_social,direccion,departamento,telefono,nombre_contacto,visita_venta,dia_entrega,dia_venta,dia_cobranza) VALUES
                ('".$direccion."','".$departamento."','".$telefono."','".$nombreContacto."','".$visitaVenta."','".$diaEntrega."','".$diaVenta."','".$diaCobranza."')";
       
       
       $sp = $this->db->PrepareSP($sql);
        $rs = $this->db->Execute($sp);
   }
   
   public function modificarCliente($datosCliente){
   		$sql = "DELETE FROM reportes WHERE numero_cliente = '" . $datosCliente[0]['numeroCliente'] ."'";
   		$sp = $this->db->PrepareSP($sql);
   		$rs = $this->db->Execute($sp);
   		
   		$sql = "INSERT INTO reportes (numero_cliente, razon_social,direccion,departamento,telefono,nombre_contacto,visita_venta,dia_entrega,dia_venta,dia_cobranza) VALUES
                ('".$datosCliente[0]['numeroCliente']."','".$datosCliente[0]['razonSocial']."','".$datosCliente[0]['direccion']."','".$datosCliente[0]['departamento']."','".$datosCliente[0]['telefono']."','".$datosCliente[0]['nombreContacto']."','".$datosCliente[0]['visitaVenta']."','".$datosCliente[0]['diaEntrega']."','".$datosCliente[0]['diaVenta']."','".$datosCliente[0]['diaCobranza']."')";
   		$sp = $this->db->PrepareSP($sql);
   		$rs = $this->db->Execute($sp);
                
                return true;
   }
}

