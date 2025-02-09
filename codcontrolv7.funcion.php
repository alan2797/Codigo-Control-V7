#!/usr/bin/php
<?php
  /*
  *********************************
  *********************************
  Esta función esta discontinuada!!!!!
  La mantengo solo para ver como implementé esto la 1era vez
  Por favor vea README y la clase (class)
  *********************************
  *********************************

  ** Fuentes:
  http://www.impuestos.gob.bo/index.php?option=com_content&view=article&id=1564&Itemid=584
  http://www.impuestos.gob.bo/images/GACCT/FACTURACION/CodigoControlV7.pdf
  http://www.impuestos.gob.bo/images/GACCT/FACTURACION/5000CasosPruebaCCVer7.txt
  */

  // Source: http://codepad.org/vNjgkgAu
  class Verhoeff {
    public $d = null;
    public $inv = null;
    public $p = null;

    function __construct() {
      $this->d = json_decode('[[0,1,2,3,4,5,6,7,8,9],[1,2,3,4,0,6,7,8,9,5],[2,3,4,0,1,7,8,9,5,6],[3,4,0,1,2,8,9,5,6,7],[4,0,1,2,3,9,5,6,7,8],[5,9,8,7,6,0,4,3,2,1],[6,5,9,8,7,1,0,4,3,2],[7,6,5,9,8,2,1,0,4,3],[8,7,6,5,9,3,2,1,0,4],[9,8,7,6,5,4,3,2,1,0]]');
      $this->inv = json_decode('[0,4,3,2,1,5,6,7,8,9]');
      $this->p = json_decode('[[0,1,2,3,4,5,6,7,8,9],[1,5,7,6,2,8,3,0,9,4],[5,8,0,3,7,9,6,1,4,2],[8,9,1,6,0,4,3,5,2,7],[9,4,5,3,1,2,6,8,7,0],[4,2,8,6,5,7,3,9,0,1],[2,7,9,3,8,0,6,4,1,5],[7,0,4,6,9,1,3,2,5,8]]');
    }
    function Calculate($number, $iterations = 1) {
      $result = 0;
      $number = str_split(strrev($number), 1);
      foreach ($number as $key => $value) {
        $result = $this->d[$result][$this->p[($key + 1) % 8][$value]];
      }
      $result = strrev(implode('', $number)) . $this->inv[$result];
      if ($iterations > 1) {
        return $this->Calculate($result, --$iterations);
      }
      return $result;
    }
    function Check($number, $iterations = 1) {
      $result = 0;
      $number = str_split(strrev($number), 1);
      foreach ($number as $key => $value) {
        $result = $this->d[$result][$this->p[$key % 8][$value]];
      }
      if ($result == 0) {
        unset($number[0]);
        $result = strrev(implode('', $number));
        if ($iterations > 1) {
          return $this->Check($result, --$iterations);
        }
        return $result;
      }
      return false;
    }
  }
  class AllegedRC4 {
    public static function encode($msg, $key, $mode='hex') {
      $state = array();
      for ($i=0; $i<256; $i++) $state[] = $i;
      $x = $y = $i1 = $i2 = 0;
      $key_length = strlen($key);
      for ($i=0; $i<256; $i++) {
        $i2 = (ord($key[$i1])+$state[$i]+$i2) % 256;
        self::swap($state[$i], $state[$i2]);
        $i1 = ($i1+1) % $key_length;
      }
      $msg_length = strlen($msg);
      $msg_hex = '';
      for ($i=0; $i<$msg_length; $i++) {
        $x = ($x + 1) % 256;
        $y = ($state[$x] + $y) % 256;
        self::swap($state[$x], $state[$y]);
        $xi = ($state[$x] + $state[$y]) % 256;
        $r = ord($msg[$i]) ^ $state[$xi];
        $msg[$i] = chr($r);
        $msg_hex .= sprintf("%02X",$r);
      }
      return ($mode=='hex'?$msg_hex:$msg);
    }
    private static function swap(&$x, &$y) {
      $z = $x; $x = $y; $y = $z;
    }
  }
  function base64($n) {
    $d = array(
      '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 
      'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 
      'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 
      'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 
      'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 
      'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 
      'y', 'z', '+', '/' );
    $c = 1; $r = '';
    while ($c > 0) {
      $c = (int)($n / 64);
      $r = $d[$n%64] . $r;
      $n = $c;
    }
    return $r;
  }
  function CodigoControl ($codcontrol_autorizacion="", $codcontrol_nrofactura="", $codcontrol_nitci="", $codcontrol_fecha="", $codcontrol_monto="", $codcontrol_llave="") {
    $codigocontrol = "ERROR";
    if ($codcontrol_autorizacion == "" || $codcontrol_nrofactura == "" || $codcontrol_nitci == "" || $codcontrol_fecha == "" || $codcontrol_monto == "" || $codcontrol_llave == "") {
      $codigocontrol = "ERROR_SinDatos";
    } else {
      // Paso 1
      $Verhoeff=new Verhoeff();
      // En los otros pasos no usamos las variables originales pero si las nuevas
      // En ese sentido re usamos las variables originales para modificarlas
      $codcontrol_nrofactura = $Verhoeff->Calculate($codcontrol_nrofactura,2);
      $codcontrol_nitci = $Verhoeff->Calculate($codcontrol_nitci,2);
      $codcontrol_fecha = $Verhoeff->Calculate($codcontrol_fecha,2);
      $codcontrol_monto = $Verhoeff->Calculate($codcontrol_monto,2);
      $suma_verhoeff = $Verhoeff->Calculate(bcadd(bcadd(bcadd($codcontrol_nrofactura, $codcontrol_nitci), $codcontrol_fecha), $codcontrol_monto),5);
      $paso1 = substr($suma_verhoeff,strlen($suma_verhoeff)-5,strlen($suma_verhoeff));
      // Paso 2
      $contadigitos = array();
      $aadicionar = array();
      $indicestr = 0;
      foreach (str_split($paso1) as $d) {
        $contadigitos[] = $d + 1;
        $aadicionar[] = substr($codcontrol_llave, $indicestr, $d + 1);
        $indicestr += $d + 1;
      }
      // En los otros pasos no usamos las variables originales pero si las nuevas
      // En ese sentido re usamos las variables originales para modificarlas
      $codcontrol_autorizacion .= $aadicionar[0];
      $codcontrol_nrofactura .= $aadicionar[1];
      $codcontrol_nitci .= $aadicionar[2];
      $codcontrol_fecha .= $aadicionar[3];
      $codcontrol_monto .= $aadicionar[4];
      // Paso 3
      $paso3_str = $codcontrol_autorizacion.$codcontrol_nrofactura.$codcontrol_nitci.$codcontrol_fecha.$codcontrol_monto;
      $paso3_key = $codcontrol_llave.$paso1;
      $AllegedRC4 = new AllegedRC4();
      $paso3 = $AllegedRC4->encode($paso3_str, $paso3_key);
      // Paso 4
      $paso4 = 0;
      $sumparcial = array(0,0,0,0,0);
      $codif_length = strlen($paso3);
      for ($i=0; $i<$codif_length; $i++) {
        $paso4 += ord($paso3[$i]);
        $sumparcial[$i%5] += ord($paso3[$i]);
      } 
      // Paso 5
      $paso5 = 0;
      for ($i=0; $i<5; $i++)
        $paso5 += (int)(($paso4 * $sumparcial[$i]) / (1 + $paso1[$i]));
      $paso5 = base64($paso5);
      // Paso 6
      $paso6 = $AllegedRC4->encode($paso5, $codcontrol_llave.$paso1);
      $codigocontrol = implode("-", str_split($paso6,2));
      return $codigocontrol;
    }
  }
  /*echo "Codigo de Control para Facturacion en BOLIVIA Segun v7".PHP_EOL;
  echo "------------------------------------------------------".PHP_EOL;
  echo "Probando: ".CodigoControl(29040011007,1503,4189179011,20070702,2500,'9rCB7Sv4X29d)5k7N%3ab89p-3(5[A')."\n"; 
  $fichero = @fopen("5000CasosPruebaCCVer7.txt", "r");
  if ($fichero) {
    $contador = 1;
    while (($linea = fgets($fichero, 4096)) !== false) {
      list($autorizacion, $nrofactura, $nitci, $fecha, $monto, $llave, $verhoeff, $cadena, $sumatoria, $base64, $codigocontrol)=explode("|", $linea);
      if ($autorizacion > 0) {
        echo "[".str_pad($contador,5," ",STR_PAD_LEFT)."] ";
        echo str_pad($codigocontrol,16," ", STR_PAD_LEFT)." / ";
        $fecha = str_replace("/", "", $fecha);
        $monto = round(str_replace(",", ".", $monto),0);
        $newcodigo = CodigoControl($autorizacion,$nrofactura,$nitci,$fecha,$monto,$llave);
        echo str_pad($newcodigo,16," ", STR_PAD_LEFT)." [";
        if ($codigocontrol == $newcodigo ) { echo "  OK"; } else { echo "NOOK"; }
        echo "]\n";
        $contador ++;
      }
    }
    if (!feof($fichero)) {
      echo "Error: unexpected fgets() fail\n";
    }
    fclose($fichero);
  }*/

?>
