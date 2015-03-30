<?php

class UtilsService {

    /**
     * Devuelve el tiempo en milisegundos
     * @return float
     */
    public static function microtimeFloat() {
        list($useg, $seg) = explode(" ", microtime());
        return ((float) $useg + (float) $seg);
    }

    /**
     * Recorre un array y devuelve todas las claves del mismo que contengan un array como value
     * @param array $arr
     * @return array o FALSE si no se encontrÃ³ ningun array
     */
    public static function findArrayInArray(array $arr) {
        $result = false;
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $result[] = $key;
            }
        }
        return $result;
    }

    /**
     * Devuelve un string truncado al tamaÃ±o especificado
     * @param string $string
     * @param int $len
     * @return string
     */
    public static function shortenText($string, $len) {
        if (strlen($string) > $len) {
            return substr($string, 0, $len) . '...';
        } else {
            return $string;
        }
    }

    /**
     * Genera la botonera de paginado en base a los parÃ¡metros
     * @param int $pagina
     * @param int $cant_registros
     * @param int $total_registros
     * @return string
     */
    public static function getPaginacion($pagina, $cant_registros, $total_registros) {

        $cant_paginas = 9;
        $total_paginas = ceil($total_registros / $cant_registros);
        $inicio = ($pagina - 1) * $cant_registros;


        //Calculo las pÃ¡ginas a mostrar
        $intervalo = ceil(($cant_paginas / 2) - 1);
        $pag_desde = $pagina - $intervalo;
        $pag_hasta = $pagina + $intervalo;

        if ($pag_desde < 1) {
            $pag_hasta -= ($pag_desde - 1);
            $pag_desde = 1;
        }

        if ($pag_hasta > $total_paginas) {
            $pag_desde -= ($pag_hasta - $total_paginas);
            $pag_hasta = $total_paginas;
            if ($pag_desde < 1) {
                $pag_desde = 1;
            }
        }


        //Armo la botonera con los indices de paginacion
        $btn_paginacion = '';

        if ($total_paginas > 1) {
            $btn_paginacion = '<ul id="paginador">' . "\n";

            if ($pagina > 1) {
                $btn_paginacion .= '<li class="pagina anterior" onclick="general.go_to(' . ($pagina - 1) . ')"><div id="anterior"></div></li>' . "\n";
            }

            if ($pagina > 10) {
                $btn_paginacion .= '<li class="pagina" title="-10" onclick="general.go_to(' . ($pagina - 10) . (isset($criterio)?$criterio:null) . ')">...</li>' . "\n";
            }

            for ($i = $pag_desde; $i <= $pag_hasta; $i++) {
                if ($pagina == $i) {
                    $btn_paginacion .= '<li class="pagina actual">' . $pagina . '</li>' . "\n";
                } else {
                    $btn_paginacion .= '<li class="pagina" onclick="general.go_to(' . $i . (isset($criterio)?$criterio:null) . ')">' . $i . '</li>' . "\n";
                }
            }

            if ($pag_hasta < ($total_paginas - 10)) {
                $btn_paginacion .= '<li class="pagina" title="+10" onclick="general.go_to(' . ($pagina + 10) . (isset($criterio)?$criterio:null) . ')">...</li>' . "\n";
            }

            if ($pagina < $total_paginas) {
                $btn_paginacion .= '<li class="pagina posterior" onclick="general.go_to(' . ($pagina + 1) . ')"><div id="posterior"></div></li>' . "\n";
            }

            if (($cant_registros + $inicio) > $total_registros) {
                $fin_registros = $total_registros;
            } else {
                $fin_registros = $cant_registros + $inicio;
            }
            $btn_paginacion .= '</ul></div><div class="form-row"><div id="total_registros" style="text-align:center; color:#9E222C; font-size:0.75em; font-weight: bold;" title="Registros visibles de total de registros.">' . ($inicio + 1) . ' <span style="color:#000; font-weight:normal">a</span> ' . $fin_registros . ' <span style="color:#000; font-weight:normal">de</span> ' . $total_registros . '</div>' . "\n";
            $btn_paginacion .= '<input type="hidden" id="totPaginas" name="totPaginas" value="' . $total_paginas . '" />';
        }

        return $btn_paginacion;
    }

    public static function esSubgerenteOSuperior($estructura) {

        return preg_match('/0{5,}$/', $estructura);
    }

    public static function tengoFuncion($nombre) {

        if (isset($_SESSION['funciones'])) {

            foreach ($_SESSION['funciones'] as $funcion) {

                if (in_array(strtoupper($nombre), $funcion) || in_array(strtolower($nombre), $funcion)) {

                    return true;
                }
            }

            return false;
        } else {

            return false;
        }
    }


}

?>
