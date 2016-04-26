<?php 
function eventCodeStatus($eventCode,$sos){
      $blue = "2079EC";
      $red = "D82830";
      $green = "1cf100";
      $gris = "818181";
      $orange ="EA7C06";
      $yellow = "EAE035";
       switch ($eventCode) {
           case '23':
             $event_name = "Reporte de posición";
             $event_color = $blue;
             $event_icon = '<i class="fa fa-map-marker" style="color:#'.$event_color.'"></i>';
             $event_icon_alert = "";
             break;
           case '1':
             $event_name = "SOS";
             $event_color = $red;
             $event_icon = '<i class="fa fa-warning " data-toggle="tooltip" data-placement="top" style=" color:#'.$event_color. '" title="'.$event_name.'"></i>';
             $event_icon_alert = '<i class="fa fa-warning " data-toggle="tooltip" data-placement="top" style=" color:#'.$event_color. '" title="'.$event_name.'"></i>';
             break;
             case '2':
             $event_name = "Encendido de Motor";
             $event_color = $green;
             $event_icon = '<i class="fa fa-warning " data-toggle="tooltip" data-placement="top" style=" color:#'.$event_color. '" title="'.$event_name.'"></i>';
             $event_icon_alert = '<i class="fa fa-warning" data-toggle="tooltip" data-placement="top" style=" color:#'.$event_color. '" title="'.$event_name.'"></i>';
             
             break;
             case '3':
             $event_name = "Apagado de Motor";
             $event_color = $gris;
             $event_icon = '<i class="fa fa-warning " data-toggle="tooltip" data-placement="top" style=" color:#'.$event_color. '" title="'.$event_name.'"></i>';
             $event_icon_alert = '<i class="fa fa-warning " data-toggle="tooltip" data-placement="top" style=" color:#'.$event_color. '" title="'.$event_name.'"></i>';
             
             break;
             case '4':
             $event_name = "Exceso de Velocidad";
             $event_color = $yellow;
             $event_icon = '<i class="fa fa-warning " data-toggle="tooltip" data-placement="top" style=" color:#'.$event_color. '" title="'.$event_name.'"></i>';
             $event_icon_alert = '<i class="fa fa-warning " data-toggle="tooltip" data-placement="top" style=" color:#'.$event_color. '" title="'.$event_name.'"></i>';
             
             break;
             case '6':
             $event_name = "Desconexión de Equipo";
             $event_color = $red;
             $event_icon = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_outlet|16|'.$event_color.'|fff">';
             $event_icon_alert = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_outlet|16|'.$event_color.'|fff">';
             break;
             case '7':
             $event_name = "Conexión de Equipo ";
             $event_color = $blue;
             $event_icon = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_outlet|16|'.$event_color.'|fff">';
             $event_icon_alert = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_outlet|16|'.$event_color.'|fff">';
             
             break;
             case '19':
             $event_name = "Reporte de posición";
             $event_color = $gris;
             $event_icon = '<i class="fa fa-map-marker" style="color:#'.$event_color.'"></i>';
             $event_icon_alert = "";
             break;
             case '20':
             $event_name = "Reporte de posición";
             $event_color = $gris;
             $event_icon = '<i class="fa fa-map-marker" style="color:#'.$event_color.'"></i>';
             $event_icon_alert = "";
             break;
             case '21':
             $event_name = "Reporte de posición";
             $event_color = $blue;
             $event_icon = '<i class="fa fa-map-marker" style="color:#'.$event_color.'"></i>';
             $event_icon_alert = "";
             break;
             case '22':
             $event_name = "Reporte de posición";
             $event_color = $orange;
             $event_icon = '<i class="fa fa-map-marker" style="color:#'.$event_color.'"></i>';
             $event_icon_alert = "";
             break; 
             case '25':
             $event_name = "Resumen de Marcha ";
             $event_color = $blue;
             $event_icon = '<i class="icon-key" style="color:#'.$event_color. '" title="'.$event_name.'"></i>';
             $event_icon_alert = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|car-dealer|16|'.$event_color.'|fff">';
             
             break;
             case '26':
             $event_name = "Detiene la Marcha";
             $event_color = $gris;
             $event_icon = '<i class="icon-key" style="  color:#'.$event_color. ' " title="'.$event_name.'"></i>';
             $event_icon_alert = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|car-dealer|16|'.$event_color.'|fff">';
             
             break;
             case '31':
             $event_name = "1 Minuto Detenido";
             $event_color = $blue;
             $event_icon = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_stopwatch|16|'.$event_color.'|fff">';
                   $event_icon_alert = ' ';             
             break;
             case '32':
             $event_name = "3 Minutos Detenido";
             $event_color = $red;
             $event_icon = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_stopwatch|16|'.$event_color.'|fff">';
                   $event_icon_alert = ' ';             
             break;
             case '33':
             $event_name = "10 Minutos Detenido";
             $event_color = $red;
             $event_icon = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_stopwatch|16|'.$event_color.'|fff">';
                   $event_icon_alert = ' ';             
             break;
             case '34':
             $event_name = "15 Minutos Detenido";
             $event_color = $red;
             $event_icon = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_stopwatch|16|'.$event_color.'|fff">';
             $event_icon_alert = ' ';
             
             break;
             case '36':
             $event_name = "60 Minutos Detenido";
             $event_color = $yellow;
             $event_icon = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_stopwatch|16|'.$event_color.'|fff">';
             $event_icon_alert = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_stopwatch|16|'.$event_color.'|fff">';
             
             break;
             case '37':
             $event_name = "90 Minutos Detenido";
             $event_color = $yellow;
             $event_icon = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_stopwatch|16|'.$event_color.'|fff">';
             $event_icon_alert = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_stopwatch|16|'.$event_color.'|fff">';
             
             break;
             case '38':
             $event_name = "2 Horas Detenido";
             $event_color = $orange;
             $event_icon = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_stopwatch|16|'.$event_color.'|fff">';
             $event_icon_alert = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_stopwatch|16|'.$event_color.'|fff">';
             
             break;
             case '39':
             $event_name = "3 Horas Detenido";
             $event_color = $orange;
             $event_icon = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_stopwatch|16|'.$event_color.'|fff">';
             $event_icon_alert = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_stopwatch|16|'.$event_color.'|fff">';
             
             break;
             case '40':
             $event_name = "Bateria Baja";
             $event_color = $yellow;

             $event_icon = '<i class="fa fa-warning " data-toggle="tooltip" data-placement="top" style=" color:'.$event_color. '" title="'.$event_name.'"></i>';
             $event_icon_alert = '<i class="fa fa-warning " data-toggle="tooltip" data-placement="top" style=" color:'.$event_color. '" title="'.$event_name.'"></i>';
             
             break;
             case '56':
             $event_name = "Apertura de Puertas";
             $event_color = $orange;
             $event_icon = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_lock|16|'.$event_color.'|fff">';
             $event_icon_alert = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_lock|16|'.$event_color.'|fff">';
             
             break;
             case '57':
             $event_name = "Cierre de Puertas";
             $event_color = $blue;
             $event_icon = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_lock|16|'.$event_color.'|fff">';
             $event_icon_alert = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_lock|16|'.$event_color.'|fff">';
             
             break;
             case '58':
             $event_name = "Apertura de tapon de combustible";
             $event_color = $orange;
             $event_icon = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_lock|16|'.$event_color.'|fff">';
             $event_icon_alert = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_lock|16|'.$event_color.'|fff">';
             
             break;
             case '59':
             $event_name = "Cierre de tapon de combustible";
             $event_color = $blue;
             $event_icon = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_lock|16|'.$event_color.'|fff">';
             $event_icon_alert = '<img class="icon-list" data-placement="top" style="width:18px" title="'.$event_name.'" src="https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&amp;chld=|14|000|glyphish_lock|16|'.$event_color.'|fff">';
             
             break;
              
           
           default:
              $event_name = "Reporte de posición";
              $event_icon = " ";
              $event_color = " ";
              $event_icon_alert ="";
             break;
         }
         if($sos==1){
             $event_name = "SOS";
             $event_color = $red;
             $event_icon = '<i class="fa fa-warning " data-toggle="tooltip" data-placement="top" style=" color:#'.$event_color. '" title="'.$event_name.'"></i>';
             $event_icon_alert = '<i class="fa fa-warning " data-toggle="tooltip" data-placement="top" style=" color:#'.$event_color. '" title="'.$event_name.'"></i>';
             
         } 
         $event = array("icono" =>$event_icon,"nombre"=>$event_name,"color"=>$event_color,"alert"=>$event_icon_alert);
         return $event;
}
?>