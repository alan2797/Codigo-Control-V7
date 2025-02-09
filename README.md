# codcontrolv7

Generador de Código de Control para facturación en Bolivia. Libreria basada en la especificación v.7 del SIN.

<br />
Clase para Generación de Código de Control para Facturas<br />
Segun especificaciones v7 del Servicio de Impuestos Nacionales de Bolivia<br />
Copyright (c) 2015-2015 Carlos Anibarro Zelaya<br />
CAnibarro(at)WhiteSith(dot)com<br />

## Fuentes:

[http://www.impuestos.gob.bo/index.php?option=com_content&view=article&id=1564&Itemid=584](http://www.impuestos.gob.bo/index.php?option=com_content&view=article&id=1564&Itemid=584)<br />
[http://www.impuestos.gob.bo/images/GACCT/FACTURACION/CodigoControlV7.pdf](http://www.impuestos.gob.bo/images/GACCT/FACTURACION/CodigoControlV7.pdf)<br />
[http://www.impuestos.gob.bo/images/GACCT/FACTURACION/5000CasosPruebaCCVer7.txt](http://www.impuestos.gob.bo/images/GACCT/FACTURACION/5000CasosPruebaCCVer7.txt)<br />
[https://www.impuestos.gob.bo/ckeditor/plugins/imageuploader/uploads/356aea02e.pdf](https://www.impuestos.gob.bo/ckeditor/plugins/imageuploader/uploads/356aea02e.pdf)<br />
<br />
<br />

## Requisitos:<br />

php 5.3+<br />
libreria bcmath instalada en php<br />

## Uso:

```
   require_once("codcontrolv7.class.php");
   /*
   * @param string $autorizacion: nro de autorizacion de la factura
   * @param string $nrofactura: nro de factura
   * @param string $nitci: nit o ci del cliente
   * @param string $fecha: fecha de la transaccion (Ymd) e.g. para el 10/08/2010: 20100810
   * @param string $monto: monto de la transaccion sin decimales
   * @param string $llave: llave de dosificacion
   * @return string: codigo de control generado
   */
   $CodigoControl = new CodigoControl();
   $codigocontrol = $CodigoControl->generar($autorizacion, $nrofactura, $nitci, $fecha, $monto, $llave);
```

Hay un fichero con 5000 casos de uso.<br />
Así mismo, el programa prueba.clase.php evaluará y generará el código de control y lo comparará con el resultado
que da el Servicio de Impuestos Nacionales.

Para ejecutar en Linux/Unix:

```
chmod +x prueba.clase.php
./prueba.clase.php
```

o bien:

```
/usr/bin/php -q prueba.clase.php
```

dependiendo de la ruta en la que tenga instalado PHP

Para ejecutar en Windows:<br />
Ubiquese en el path/ruta/directorio en el que tiene la clase
Luego, llame a PHP y ejecute el programa:

```
c:\php\php.exe -q prueba.clase.php
```

## Versiones

1.0 primera version lanzada by VLADIMIR ALAN

## Licencia

Esta era una implementación privada, hecha para un sistema que diseñé.<br />
Incluye código, implementaciones, ideas y sugerencias de varias personas y genios
que tuvieron la paciencia de revisar cada sistema de encriptación. <br />
Yo solo encontré lo que necesitaba, lo junté y me aseguré de que
funcionará correctamente. :)<br />
Libero el código, para fines investigativos y de posibles mejoras bajo la licencia
GPL v3 incluida en el archivo comprimido.<br />
