<?php

class BaseDAO extends DAOGeneric {

    /**
     * Lista las entidades que corresponden al dao
     * 
     * @param  VerQueTipoDeDatoEs $rs
     * 
     * @return string Nombre de la entidad
     */
    public function listar($rs, $entity = null) {
        try {
            $ret = array();

            if (!$entity && !$entity = $this->detectEntity()) {
                return false;
            }

            while (!$rs->EOF) {
                $ret[] = new $entity($rs->GetRowAssoc(false));
                $rs->MoveNext();
            }

            return $ret;
        } catch (Exception $exc) {
            if (isset($rs) && $rs != null) {
                $rs->Close();
            }
        }
    }

    /**
     * Detecta el nombre de la entidad
     *
     * Si se define $entityName con el nombre de la entidad, retorna
     * directamente el nombre de esa clase.
     *
     * Si no fue especificado $entityName intenta buscarla siguiendo 
     * el patrón NombreEntidadDAO.
     *
     * Con cualquiera de las dos formas en caso de no existir la clase
     * devuelve false.
     * 
     * @return string|false
     */
    private function detectEntity() {
        // Nos fijamos si $entityName está definido
        if (isset($this->entityName) && !empty($this->entityName)) {
            // Si la clase existe la retornamos
            if (class_exists($this->entityName)) {
                return $this->entityName;
            }
        }

        // Nombre del DAO
        $dao = get_class($this);
        // Le quitamos el DAO final para que quede el nombre de la entidad
        $entity = substr($dao, 0, -3);

        // Si existe la retornamos
        if (class_exists($entity)) {
            return $entity;
        }

        // Fallaron ambos casos
        return false;
    }

    /**
     * Ejecuta una sentencia
     * 
     * @param  string  $sql    sentencia sql
     * @param  array   $params array con los valores de la sentencia
     * @param  mixed   $id     variable que va a guardar el ID que cree
     * 
     * @return AveriguarQueTipoDeDatoEs
     */
    public function excecute($sql, $params = array(), &$id = false) {
        $sp = $this->db->PrepareSP($sql);

        foreach ($params as $k => $v) {
            $this->db->InParameter($sp, $v, $k);
        }

        if ($id !== false) {
            $this->db->OutParameter($sp, $id, 'id_factura');
        }

        return $this->db->Execute($sp);
    }

    /**
     * "Bindea" los valores de la consulta
     * 
     * @param  AveriguarQueTipoDeDatoEs $sp     $sp de la consulta
     * @param  array                    $params valores
     * 
     * @return void
     */
    public function params($sp, $params) {
        if ($params && is_array($params)) {
            foreach ($params as $k => $v) {
                $this->db->InParameter($sp, $v, $k);
            }
        }
    }

    /**
     * Devuelve el valor de la variable o null en caso de que no tenga nada
     * 
     * @param  mixed  $prop     valor a evaluar
     * @param  string $default  valor que retorna en caso de no tener datos la variable
     * 
     * @return mixed
     */
    public function getOrNull($prop, $default = 'NULL') {
        return (!empty($prop)) ? $prop : $default;
    }

}
