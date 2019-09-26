<!DOCTYPE html>
<html>
<head>
  <?php

include_once ('postman.php');

//configuracion de productos
  $product = array();
  $product_name = array();
  $product_price = array();
  $product_id = array();
  $product_status = array();

//variables de configuracion
  $configuration = array();
  $configuration_subtype_display = array();
  $configuration_display = array();
  $configuration_id = array();
  $configuration_subtype = array();
  $configuration_name = array();
  $configuration_extraprice = array();

//variables html 
  $tab = "&nbsp;&nbsp;&nbsp;&nbsp";

//variables de ayuda
  $aux_int2 = 0;
  $aux_string = array();

//variables css
  $estilo_configuraciones = "background:#FFF8DC;border:10px #DEB887 ridge;border-radius: 25px;";
  $estilo_botones = "background:#FFF8DC;border:10px gray ridge;border-radius: 25px;";
?>

  <title>App de Pedidos</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body>
<script type="text/javascript">
var contproducto=0;
var valorpedido=0;
var JSONcompleto = new Array();

$(document).ready(function(){
  //<carga menu
  $("#hrefmenu").click(function(){
    var id = $("#txtmenu").val();
    window.location.href = "v_pedido.php?cod="+id;
  });
  //carga menu>
  //<cargar pedido
  $("input[name=agregar_prod]").click(function(){
    var JSONproduto = new Object(); 
    var arrayconfiguraciones = new Array();
    var JSONconfiguracion = new Object();
    var valorproducto=0;
   
    var div_padre = $(this).parent();
    var id_prod = (div_padre.attr("id")).split("-")[1];
    var name_prod = div_padre.find("b[name=name_prod]").text();
    var price_prod = div_padre.find("b[name=price_prod]").text();
    $("#pedido").append('<ul><li name = "producto'+contproducto+'"><b>'+name_prod+'</b> - $<b>'+price_prod+'</b></li></ul>');
    valorproducto=valorproducto+parseInt(price_prod,10);
      //<carga configuraciones
    $("input[type=checkbox]:checked").each(function(){
      var obj_Padre = $(this).parent();
      var obj_padre_subtype = obj_Padre.parent();
      var subtype_conf = obj_padre_subtype.find("b[name=subtype_conf]").text();
      var  id_conf = ($(this).attr("id")).split("-")[1];

      var prueba = new Object();
      prueba.id_configuration = parseInt(id_conf);
      arrayconfiguraciones.push(prueba);
      var name_conf = obj_Padre.children("#conf_name").text();
      var price_conf = obj_Padre.children("#conf_price").text();
      $("li[name=producto"+contproducto+"]").append('<ul><li>'+subtype_conf+": "+name_conf+" - $"+price_conf+'</li></ul>');
      $(this).prop('checked', false);
      valorproducto=valorproducto+parseInt(price_conf,10);
    });
      //carga configuraciones>
    $("li[name=producto"+contproducto+"]").append("<ul><li><b>Total Acumulado: </b>$"+valorproducto+'</li></ul>');
    contproducto++;
    valorpedido = valorpedido + valorproducto;
    $("#total_pedido").remove();
    $("#pedido").append('<ul><li id = "total_pedido"><b>Total pedido: </b>'+valorpedido+'</b></li></ul>');
    $("#envio_pedido").remove();
    $("#pedido").append('<a class="btn btn-primary" id="envio_pedido" role="button">Realizar Pedido</a>');
    $("#salto").remove();
    $("#pedido").append('<p id = "salto"></p>');
    $("#refrescar").remove();
    $("#pedido").append('<a class="btn btn-primary" id="refrescar" role="button">Nuevo Pedido</a>');
    JSONproduto.annotations = "demo";
    JSONproduto.id_product = parseInt(id_prod);
    JSONproduto.quantity = 1;
    JSONproduto.configurations = arrayconfiguraciones;
    JSONcompleto.push(JSONproduto);

    $("#envio_pedido").click(function(){
     //<metodo post 
      var store_id ="store_id="+($("#info_store").text()).split("-")[0];
      var producto = "productos="+JSON.stringify(JSONcompleto);
      var totalpago = "total_pago="+valorpedido;
      var data = store_id+"&"+producto+"&"+totalpago;
      
      $.ajax({
        url: './postman.php',
        type: 'POST',
        data: data,
        async: true,
        success: function (data) {
          $("#pedido").append(data);
        },
      });
    //metodo post>
    });
    $("#refrescar").click(function(){
      window.location.reload();      
    });
  });
  //cargar producto >
  

});
</script>
<!--visual-->
<div class="container">
  <h1><b>APP de Pedidos</b></h1>
  <br><br><br>
  <input class="form-control" type="text" placeholder="Ingresa Store_Id" id = "txtmenu"><br>
  <a class="btn btn-primary" id='hrefmenu' role="button">menú</a><br> <br>

  <?php  if(isset( $_GET['cod']) && !empty($_GET['cod'])): 
  $menu = json_decode(getmenu($_GET['cod']));
  $store = json_decode(getstore($_GET['cod']));
  echo '<div class="row" style = "'.$estilo_botones.'">';
  echo "<div class=\"col-sm-4\" style = \"$estilo_configuraciones\">".$tab."<h4><b id='info_store'>". $store->data[0]->id."</b> - ". $store->data[0]->name. "</h4>";
  echo "</div>";
  echo "<div class=\"col-sm-8\" id=\"pedido\" style = \"$estilo_configuraciones\"><h4>Pedido:</h4>";
  echo "</div>";
  echo "</div>";

//categorias
  ?>
  <br><br>
  <ul class="nav nav-tabs">
    <?php 
  for ($i=0; $i < count($menu->data->categories); $i++) { 
    echo "<li><a data-toggle=\"tab\"
    href=".'#'.$i.">".$menu->data->categories[$i]->name."</a></li>"; 
    # code...
  }
    ?>
  </ul>

  <div class="tab-content">

<?php 
  //categorias
  for ($i=0; $i < count($menu->data->categories); $i++) { 
    echo "<div id=".$i." class=\"tab-pane fade\">";
       //unir en $product todos los datos a usar de configuraciones
      //productos
      $product= array();
      $prueba = array("1",1,1);
      for ($j=0; $j < count($menu->data->categories[$i]->products); $j++) {
        
        $display_order = $menu->data->categories[$i]->products[$j]->display_order;
        $id = $menu->data->categories[$i]->products[$j]->id;
        $name = $menu->data->categories[$i]->products[$j]->name;
        $current_price = $menu->data->categories[$i]->products[$j]->current_price;
        $status = $menu->data->categories[$i]->products[$j]->status;
        $product[$j] = $display_order . "|" . $id . "|" . $name . "|" . $current_price . "|" . $status;
        
      }

    //ordenar productos
      for ($j=0; $j < count($menu->data->categories[$i]->products); $j++) {
        $aux_string=explode("|", $product[$j]);
        $product_id[$j] = $aux_string[1];
        $product_name[$j] = $aux_string[2];
        $product_price[$j] = $aux_string[3];
        $product_status[$j] = $aux_string[4];
      }
    //imprimir productos
    for ($j=0; $j < count($menu->data->categories[$i]->products); $j++) {
      
        if ($product_status[$j] == 1){
      echo '<div class="row" style = "'.$estilo_botones.'" id ="divprod-'.$product_id[$j].'">';
      echo '<br>'.$tab.'<input class="btn btn-primary" type="button" value="Agregar" name="agregar_prod"><h3>'
      .$tab.'<b name = "name_prod">'.$product_name[$j].'</b>'.$tab."-".$tab.'$<b name = "price_prod">'.$product_price[$j]."</b></h3>";
      //unir en $configuration todos los datos a usar de configuraciones
      //configuraciones
      for ($k=0 ; $k < count($menu->data->categories[$i]->products[$j]->configurations); $k++) {
          $subtype_display=$menu->data->categories[$i]->products[$j]->configurations[$k]->subtype_display_order;
          $display=$menu->data->categories[$i]->products[$j]->configurations[$k]->display_order;
          $subtype=$menu->data->categories[$i]->products[$j]->configurations[$k]->sub_type_name;
          $name=$menu->data->categories[$i]->products[$j]->configurations[$k]->name;
          $extra_price=$menu->data->categories[$i]->products[$j]->configurations[$k]->extra_price;
          $id = $menu->data->categories[$i]->products[$j]->configurations[$k]->id;
          $configuration[$k]= $display . "|". $subtype_display . "|". $subtype . "|" . $name . "|" . $extra_price . "|". $id;
      }
        sort($configuration);
      for ($k=0 ; $k < count($menu->data->categories[$i]->products[$j]->configurations); $k++) {
          $aux_string = explode("|", $configuration[$k]);
          $configuration[$k] = $aux_string[1]."|".$aux_string[2]."|".$aux_string[3]."|".$aux_string[4] ."|" . $aux_string[5];
      }
         sort($configuration);
      for ($k=0 ; $k < count($menu->data->categories[$i]->products[$j]->configurations); $k++) {
          $aux_string = explode("|", $configuration[$k]);
          $configuration[$k] = $aux_string[1]."|".$aux_string[2]."|".$aux_string[3]."|".$aux_string[4];
      }
      //desunir $configuration con todos los datos a usar de configuraciones y asignarlos
      for ($k=0 ; $k < count($menu->data->categories[$i]->products[$j]->configurations); $k++) {
          $aux_string=explode("|", $configuration[$k]);

          $configuration_subtype[$k] = $aux_string[0];
          $configuration_name[$k] = $aux_string[1];
          $configuration_extraprice[$k] =  $aux_string[2];
          $configuration_id[$k] = $aux_string[3];
      }
      //imprimir las configuraciones en el html 
      for ($k=0; $k < count($menu->data->categories[$i]->products[$j]->configurations); $k++) {
          if ($k!=0){
            if (strcasecmp ($configuration_subtype[$k],$configuration_subtype[$k-1]) != 0) {
              echo "</div>";
              echo "<div class=\"col-sm-4\" style = \"$estilo_configuraciones\" name=\"div_subtype_conf\">"."<h4><b name=\"subtype_conf\">".$configuration_subtype[$k]."</b></h4>";
            }   
          }else{
            echo "<div class=\"col-sm-4\" style = \"$estilo_configuraciones\" name=\"div_subtype_conf\">"."<h4><b name=\"subtype_conf\">".$configuration_subtype[$k]."</b></h4>";
          }
          echo '<label class="checkbox-inline"><br><input type="checkbox" class="checkbox" id ="conf-'.$configuration_id[$k].'" >'.
          $tab.'<b id="conf_name">'.$configuration_name[$k].'</b> - $<b id=conf_price>'.$configuration_extraprice[$k].'</b></label><br>';
        }
        //configurariones
        if(count($configuration_subtype)!=0){
         echo "</div>";
        }
      $configuration_subtype=array();
      echo "</div>";
        $configuration = array();
        $configuration_subtype = array();
        $configuration_name = array();
        $configuration_extraprice = array();
      }
    }
    echo "</div>";
  }
 ?>

  </div>
<?php  endif  ?>
</div>

</body>
</html>