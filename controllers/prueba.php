<?php

require_once 'libs/controller.php';

require_once 'vendor/tcpdf/tcpdf.php'; 

class Prueba extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->view->mensaje = "";
    }

    function render()
    {
        if (!isset($_SESSION)) {
            session_start();

        }

        $id_user = $_SESSION['iduser'];
        $data           = $this->model->getData();
        print_r($data);
        
        $this->view->render('prueba/index');

    }

    function pass($param = null)
    {
        $pass = $param[0];
        
        $passenc        = password_hash($pass, PASSWORD_DEFAULT);
        
        print $passenc;
    }

    function salir()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        session_destroy();
        $this->render();
    }

    function funpdf()
    {
        // Crear una nueva instancia de TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Configurar el documento
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Tu Nombre');
        $pdf->SetTitle('Título del PDF');
        $pdf->SetSubject('Asunto');
        $pdf->SetKeywords('TCPDF, PDF, ejemplo');

        // Configurar la cabecera y pie de página
        $pdf->setHeaderData('', 0, 'Cabecera', 'Generado por TCPDF');
        $pdf->setFooterData();

        // Configurar las fuentes
        $pdf->SetFont('helvetica', '', 12);

        // Añadir una página
        $pdf->AddPage();

        // Escribir contenido en el PDF
        $html = '
            <div class="row">
                <div class="col-12">
                            <div class="card glass">    
                        <div class="card-body">
                            <strong>
                                    </strong><table class="table" border="2">
                                <tbody><tr class="align-middle text-center">
                                    <th style="padding:0px;" class="col-2">Formato</th>
                                    <th style="padding:0px;" rowspan="3"><br><br> ENTREGA DE ELEMENTOS DE PROTECCION PERSONAL (E.P.P) </th>
                                    <th style="padding:0px;" rowspan="3" class="col-2"><img src="https://sigotusalud.si18.com.co/public/images/si18.png" alt="" width="60%"></th>
                                    
                                </tr>
                                <tr class="align-middle text-center">
                                    <td style="padding:0px;">Código <br> STB-FOR-004</td>
                                </tr>
                                <tr class="align-middle text-center">
                                    <td style="padding:0px;">Versión: 5</td>
                                </tr>
                            </tbody></table>   
                        </div>
                    </div><br>
                    <div class="card glass">
                        <div class="card-header">
                            Detalles del acta de entrega E.P.P
                        </div>
                        <div class="card-body">
                            <div style="text-align: justify;">
                                <p>El Proceso del Sistema de Gestión Integral (SGI) del Sistema integrado de operación de transporte <b> SI18SU</b>     en concordancia con lo evaluado en la matriz de identificación de riesgos de la organización , realiza la entrega de los Elementos  de Protección  Personal (EPP) que requiere EL COLABORADOR identificado en este formato para el cabal cumplimiento de sus funciones en el cargo que él ha registrado en este mismo formato, con el fin de que el COLABORADOR pueda ejercer todas sus funciones dentro de un marco de seguridad integral, resaltando, mediante el presente documento, la importancia del mantenimiento y uso adecuado de tales elementos.</p>
                                <p>En virtud de lo anterior, se deja constancia que los Elementos de Protección Personal (EPP) que se entregan al COLABORADOR y que se relacionan más adelante, fueron adquiridos por Sistema integrado de operación de transporte <b> SI18SU</b>    ratificando con ello que son de propiedad de la organización y su y su destinación será exclusivamente para labores propias de la organización. Se encuentra entonces totalmente prohibido para EL COLABORADOR utilizar estos elementos de trabajo para cualquier gestión personal o diferente a las exigencias del cargo desempeñado.</p>
                                <p><b>DERECHOS Y OBLIGACIONES. Ley 9 de 1979. Artículo 85. Todos los trabajadores están obligados a:</b><br>
                                <b>a)</b> Cumplir las disposiciones de la presente ley y sus reglamentaciones, así como con las normas del reglamento de Medicina, Higiene y Seguridad que se establezca. <br>
                                <b>b)</b> Usar y mantener adecuadamente los dispositivos para control de riesgos y equipos de protección personal y conservar en orden y aseo los lugares de trabajo. <br>
                                Decreto 1295 de 1994 del Ministerio de Gobierno. Sanciones. Artículo 91. b) Para el trabajador: El grave incumplimiento por parte del trabajador de las instrucciones, reglamentos y determinaciones de prevención de riesgos, adoptados en forma general o específica, y que se encuentren dentro de los programas de salud ocupacional de la respectiva empresa, que le hayan comunicado por escrito, facultan al COLABORADOR para la terminación del vínculo o relación laboral por justa causa. <br>
                                Decreto 1072 de 2015 del Ministerio del Trabajo:  Artículo 2.2.4.6.10. Responsabilidades de los trabajadores, literal 3 “Cumplir las normas, reglamentos e instrucciones del Sistema de Gestión de la Seguridad y Salud en el Trabajo de la empresa”.</p>
                                <p>Yo <b> VICTOR  ALEXANDER  ARTEAGA  CORDOBA</b> identificado con cédula de ciudadanía No.<b> 1085929701</b> con fecha de ingreso del día  <b> 2022-09-01 00:00:00.000</b> dejo constancia que he recibido los elementos de protección personal para el desempeño de mis responsabilidades en el cargo de <b> ESPECIALISTA TIC</b>   de acuerdo con lo señalado en la Ley 9 de 1979 y el Decreto 1295 de 1994. Del mismo modo, manifiesto que los elementos de protección personal recibidos  serán destinados para uso exclusivo de las labores contratadas, de acuerdo con lo señalado en el artículo 85 de la Ley 9 de 1979, los cuales serán devueltos en el momento de retiro.</p>
                                <p>Así mismo, declaro que conozco y me haré responsable de la plena aplicación de la política institucional sobre elementos de protección personal, manifestando desde ya que me hago responsable por cualquier situación de la cual pueda derivarse algún perjuicio a mí, como COLABORADOR, ante el desconocimiento o aplicación deficiente de la misma. Dejo constancia de que he recibido los elementos que se relacionan a continuación:</p>
                                <p>OBLIGACIONES ADICIONALES- EL COLABORADOR se comprometa a: <br>
                                <b>1.</b> Dar un uso adecuado a los elementos y equipos descritos, teniendo en cuenta sus calidades y características.<br>
                                <b>2.</b> Mientras las herramientas de trabajo se encuentren bajo la responsabilidad del COLABORADOR y esta sufra daño o perdida por negligencia o culpa del COLABORADOR, este se compromete a la inmediata reparación o adquisición, previo concepto del Sistema de Gestión Integral (SGI). (Artículo 58 numeral 3 del Código sustantivo del trabajo). <br>
                                <b>3.</b> Si el COLABORADOR se desvincula de la organización, por cualquier causa deberá devolver las herramientas de trabajo a la organización, en las mismas condiciones que lo recibió salvo el deterioro normal de las cosas. (Artículo 58 numeral 3 del Código sustantivo del trabajo). <br>
                                <b>4.</b> A utilizar los equipos única y exclusivamente para desarrollar las labores correspondientes a su cargo.<br>
                                <b>5.</b> A custodiar, conservar y dar el uso convenido a los equipos, respondiendo hasta por la culpa levísima. <br>
                                <b>6.</b> A dar aviso inmediato a la organización respecto al hurto, extravío, avería o en general a cualquier otro daño que se presente en los equipos, en caso de ocurrencia de estas circunstancias se debe tramitar el denuncio ante la autoridad correspondiente. <br>
                                <b>7.</b> A no prestar, entregar o gravar los equipos a cualquier título. <br>
                                <b>8.</b> A restituir los equipos a la organización o al colaborador que ésta le indique, cuando se le requiera y en el mismo estado en que le fue entregado, salvo por el deterioro natural derivado de su uso legítimo. <br>
                                <b>9.</b> A cumplir con las instrucciones dadas en cuanto al adecuado manejo y utilización del equipo. <br>
                                <b>10.</b> A responder por los daños que por el uso del equipo se le causen a terceros.<br>
                                <b>11.</b> A mantener los equipos en perfecto estado de funcionamiento.</p>
                                <p><b>AUTORIZACIÓN DE DESCUENTO:</b> Se deja constancia que el COLABORADOR, por medio de este documento, autoriza al EMPLEADOR para que deduzca de su sueldo, liquidación de acreencias laborales, bonificaciones, indemnizaciones, etc., que le llegaren a corresponder como consecuencia de la relación laboral, el valor correspondiente por el arreglo o la reposición parcial o total de los equipos.</p>
                                <p><b>INCUMPLIMIENTO:</b> En caso de que el COLABORADOR utilice las herramientas de trabajo para actividades diferentes de las correspondientes a la organización, o le dé una destinación diferente dentro de la jornada laboral de la organización, o incumpla lo aquí establecido aun por la primera vez, la empresa podrá terminar el contrato de trabajo con justa causa cuando considere que la conducta en que ha incurrido EL COLABORADOR es grave.</p>
                                <p>Sistema integrado de operación de transporte <b> SI18SU</b>   y EL COLABORADOR manifiestan que la firma del presente documento constituye constancia con respecto al recibo de los Elementos de Protección Personal en perfectas condiciones de funcionamiento y aptos para las funciones que se van a desempeñar con los mismos, asumiendo desde este momento todas las obligaciones relacionadas en este escrito.</p>
                                <p>EL COLABORADOR  manifiesta que firma el presente documento en señal de que ha leído las obligaciones, que las acepta y se compromete a aplicar los principios en ella establecidos, pues de lo contrario procederán las estipulaciones del Decreto Ley 2351 de 1965, artículo 7º literal a.</p>
                                <p><i>En consecuencia de aceptación, lo firmamos ante testigos, en dos ejemplares del mismo tenor en Bogotá, el día <b> 10</b> del mes <b> 09</b> del año <b> 2024</b>.</i></p>
                            </div><br>
                            <div class="table-responsive">
                                <table class="table table-bordered table_border">
                                    <tbody><tr class="table_border"> 
                                        <td style="background-color: #1fa81f;" class="table_border">Entrega:</td>
                                        <td><b> VICTOR  ALEXANDER  ARTEAGA  CORDOBA</b></td>
                                        <td style="background-color: #1fa81f;" class="table_border">Cédula:</td>
                                        <td><b> 1085929701</b></td>
                                        <td style="background-color: #1fa81f;" class="table_border">Cargo:</td>
                                        <td><b> ESPECIALISTA TIC</b></td>
                                        <td style="background-color: #1fa81f;" class="table_border">Firma:</td>
                                        <td><img src="https://sigotusalud.si18.com.co/resources/delivery/firma21085929701_10092024-080025.jpg" alt="" width="100px"></td>
                                    </tr>
                                    <tr class="table_border">
                                        <td style="background-color: #1fa81f;" class="table_border">Recibe:</td>
                                        <td><b> VICTOR  ALEXANDER  ARTEAGA  CORDOBA</b></td>
                                        <td style="background-color: #1fa81f;" class="table_border">Cédula:</td>
                                        <td><b> 1085929701</b></td>
                                        <td style="background-color: #1fa81f;" class="table_border">Cargo:</td>
                                        <td><b> ESPECIALISTA TIC</b></td>
                                        <td style="background-color: #1fa81f;" class="table_border">Firma:</td>
                                        <td><img src="https://sigotusalud.si18.com.co/resources/delivery/firma1085929701_10092024-080025.jpg" alt="" width="100px"></td>
                                    </tr>
                                </tbody></table><br>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table_border">
                                    <thead>
                                        <tr class="align-middle text-center">
                                            <th scope="col">Id</th>
                                            <th scope="col">Nombre EPP</th>
                                            <th scope="col">Clasificación</th>
                                            <th scope="col">Referencia</th>
                                            <th scope="col">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                                                <tr>
                                            <td class="align-middle text-center">21</td>
                                            <td>Guante de seguridad de R. Mecánico (Sandy)</td>
                                            <td class="align-middle text-center">PROTECCION MANOS</td>
                                            <td class="align-middle text-center">ref prot manos</td>
                                            <td class="align-middle text-center">1</td>
                                        </tr>
                                                                </tbody>
                                </table>
                            </div>    
                                
                                <div class="row">
                                    <div class="col">
                                        <label for="firma">Firma quien recibe:</label><br>
                                        <img src="https://sigotusalud.si18.com.co/resources/delivery/firma1085929701_10092024-080025.jpg" alt=""> 
                                    </div>
                                    <div class="col">
                                        <label for="firma">Firma quien entrega:</label><br>
                                        <img src="https://sigotusalud.si18.com.co/resources/delivery/firma21085929701_10092024-080025.jpg" alt=""> 
                                    </div>
                                
                                    <br> 
                                </div>
                                                    <a class="btn btn-primary btn-sm" href="https://sigotusalud.si18.com.co/deliveryitems/renderListDeliveryUsers/">
                                    Atras
                                </a> 
                                
                        </div>
                    </div>
                </div>
            </div>
        ';
        $pdf->writeHTML($html, true, false, true, false, '');

        // Salida del PDF (puedes guardar o mostrar el PDF)
        $pdf->Output('ejemplo.pdf', 'I');  // 'I' para mostrar, 'D' para descargar

    }
}
