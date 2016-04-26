<?php
require_once("../../../_class/class.asset.php");
$objAsset = new Asset();
$asset = $objAsset->getAsset($_GET['id_asset']);
$tanks = json_decode($asset[0]['fuel'],true);
$t1 = $tanks["t1"];
$t2 = $tanks["t2"];
$t3 = $tanks["t3"];

function calc_fuel($vol,$t){
  $gps_vol = $vol;
  $idx = NULL;
  for($i=0;$i<count($t);$i++){
    if($gps_vol >= $t[$i]['vol'] && $gps_vol <= $t[$i+1]['vol']){
	  $idx = $i;
	  break;
    }   
  }
  $dif_vol = $t[$idx+1]['vol']-$t[$idx]['vol'];
  $ind_comb = number_format((($t[$idx+1]['lts']-$t[$idx]['lts'])/($t[$idx+1]['vol']-$t[$idx]['vol'])),2);
  $lts = $t[$idx]['lts']+($ind_comb*($gps_vol-$t[$idx]['vol']));
  return $lts;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link rel="stylesheet" href="../../_lib/fawesome/css/font-awesome.min.css">

<script>
$(document).on("ready",function(){ 
  $('.onClickAddRow').on("click",function(){ 
    $("#tbl_fuel_"+$(this).attr("rel")+" > tbody:last").append('<tr><td class="idx" align="center">N0</td><td><input type="text" id="lst_lts[]" name="lst_lts[]" /></td><td align="center"><input type="text" id="lst_v[]" name="lst_v[]" /></td><td align="center"><a href="javscript:void(0)" class="onClickRemoveRow remove" rel="'+$(this).attr("rel")+'"><i class="fa fa-times"></i></a></td></tr>');
    update_table($(this).attr("rel"));
  });
  
  /* TABS */
  $("a.onClickTab").on("click",function(e){ 
    $(".tab").hide();
	$(".tabs ul li").removeClass('active');
	$(this).parent().addClass('active');
	$("#"+$(this).attr("rel")).fadeIn();
  });
  /* EOF TABS*/
  
  $(document).on("click","a.onClickSave",function(e){ save_table(); });
  
  $(document).on("click","a.onClickRemoveRow",function(e){ 
    $(this).parent().parent().remove();
	update_table($(this).attr("rel"));
  });
  
  
});

function update_table(id){
  var cont = 0;
  $("#tbl_fuel_"+id+" tbody tr td.idx").each(function() {
    $(this).html("N"+cont);
	cont++;
  });
}

function save_table(){ 
  var data_1 = [];
  var data_2 = [];
  var data_3 = [];
   $("#tbl_fuel_1 > tbody tr").each(function(){
	 var matrix = { lts: $(this).children().next().children().val(), vol :  $(this).children().next().next().children().val() };
	 if(matrix.lts!="" && matrix.vol!="") data_1.push(matrix);
   });
   
   $("#tbl_fuel_2 > tbody tr").each(function(){
	 var matrix = { lts: $(this).children().next().children().val(), vol :  $(this).children().next().next().children().val() };
	 if(matrix.lts!="" && matrix.vol!="") data_2.push(matrix);
   });
   
   $("#tbl_fuel_3 > tbody tr").each(function(){
	 var matrix = { lts: $(this).children().next().children().val(), vol :  $(this).children().next().next().children().val() };
	 if(matrix.lts!="" && matrix.vol!="") data_3.push(matrix);
   });
   
   $.post("../../_ctrl/ctrl.client.php", { exec: "insert_calib", t1 : data_1, t2 : data_2, t3: data_3, id_asset: <?php echo $_GET['id_asset']; ?> },function(r){
     alert("Calibracion Guardada con Exito");
     }); 
}
</script>
<style>
body { margin:0; padding:0; font-family:"Arial", Gadget, sans-serif; font-weight:normal; width:500px; }
#modal { width:500px; font-size:12px;}
#modal .tabs { background-color:#e4e4e4; }
#modal .tabs ul { margin:0; padding:0; list-style:none; }
#modal .tabs ul li { float:left; border-right:#d6d6d6 solid 1px; border-bottom:#e1e1e1 solid 1px;}
#modal .tabs ul li.active { background-color:#fff; border-bottom:#fff solid 1px;}
#modal .tabs ul li.active a { color:#4d4d4d; display: inline-block;  } 
#modal .tabs ul li a { display:inline-block; height:30px; text-align:center; text-decoration:none; padding-top:15px; color:#7f7f7f;  padding-left:45px; padding-right:45px;}
#modal .tab_container { height:400px; border:#ddd solid 1px; overflow-y:scroll; }
#modal .tab_container .tab { padding:15px; display:none; }
#modal .tab_container .tab.active { display:block; }
#modal .actions { background-color:#eeeeee; border-top:#cbcbcb solid 1px; box-shadow:#f8f8f8 -1px -1px 0px; height:50px; }
#modal .actions ul { margin:0; margin-right:15px; padding:0; list-style:none; }
#modal .actions ul li { float: right; margin-top:10px; }
#modal .actions ul li a { background-color:#000; color:#fff; height:22px; padding-top:8px; display:inline-block; padding-left:20px; padding-right:20px; border-radius:3px; }
.clear { clear:both;}

#modal { width:500px; }
#modal h1 { margin:0; padding:0; background-color:#1b1e26; color:#fff; font-weight:100; font-size:15px; text-align:center; padding:8px; }
#modal form fieldset { border:none; padding-left:25px; margin-top:20px; }
#modal form fieldset p { margin-bottom:5px; margin-top:5px;  }
#modal form fieldset p.save { margin-left:130px; margin-top:10px; }
#modal form fieldset p.save a { background-color:#000; color:#fff; min-width:100px; text-align:center; padding:5px; display:inline-block;  }
#modal form fieldset p select { border-color: #d9d9d9; border-radius: 2px;padding:6px 12px; height:28px; }
#modal form fieldset p label { float:left; width:130px; display:inline-block; padding-top:3px; color:#393E48; }
#modal form fieldset p select.contacto { border-color: #d9d9d9; border-radius: 2px; width:120px; border:1px solid #cccccc; padding:6px 12px; height:28px; margin-right:10px; margin-top:4px; }
#modal form fieldset p input[type=text] { border:#ccc solid 1px; width:300px; float:left; padding:8px; }
#modal form fieldset p input[type=text].medium { border:#ccc solid 1px; width:150px; float:left; padding:8px; }
#modal form fieldset p input[type=password] { border:#ccc solid 1px; width:300px; float:left; padding:8px; }
a.onClickAddRow { background-color:#000; color:#fff; text-align:center; margin-top:5px; display:inline-block; width:25px; height:18px; border-radius:3px; padding-top:7px; }

#modal form fieldset p textarea { border:#ccc solid 1px; width:300px; float:left; padding:8px; }

#modal form fieldset .left { float:left;}
table.result { background-color:#fff; width:98%; border:#e4e4e4 solid 1px; border-right:none;}
table.result thead tr th { border-right:#59646e solid 1px; padding:8px 15px; color:#fff; font-size:10px; background:#333;}
table.result thead tr th.order { cursor:pointer;background:url(../_img/bck_sort.png) right no-repeat #333;  background-position-y: 0;  }
table.result thead tr th.asc {  background-position-y: -120px; }
table.result thead tr th.desc {  background-position-y: -60px; }
table.result tbody tr td { border-right:#e6e6e6 solid 1px; padding:3px 7px;}
table.result tbody tr.odd { background-color:#f6f6f6; }
table.result tbody tr.even { background-color:#fff; }
table.result tbody tr:hover { background-color:#fdf7e8;}
table.result tbody tr td a.remove { color:#333; padding:3px; text-align:center; width:18px; display:inline-block; border-radius: 2px; }
table.result tbody tr td a.remove:hover { background-color:#333; color:#fff; }
</style>
</head>

<body>

<div id="modal">
  <h1>Calibraci&oacute;n de Tanques</h1>
  <div class="tabs">
    <ul>
      <li class="active"><a href="#" class="onClickTab" rel="tanque_1">Tanque 1</a></li>
      <li><a href="#" class="onClickTab" rel="tanque_2">Tanque 2</a></li>
      <li><a href="#" class="onClickTab" rel="tanque_3">Tanque 3</a></li>
    </ul>
    <br class="clear" />
  </div>
  <div class="tab_container">
    <!-- T1 -->
    <div id="tanque_1" class="tab active">
      <table id="tbl_fuel_1" border="0" width="100%" cellpadding="0" cellspacing="0" class="result">
        <thead>
          <tr>
            <th>No.</th>
            <th>Litros en Tanque 1</th>
            <th>Voltaje</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
	 <?php
		  if($t1!=NULL){
			for($i=0;$i<count($t1);$i++){
		    echo '<tr>
				    <td class="idx" align="center">N'.$i.'</td>
					<td align="center"><input type="text" id="lst_lts[]" name="lst_lts[]" value="'.$t1[$i]['lts'].'" /></td>
					<td align="center"><input type="text" id="lst_v[]" name="lst_v[]" value="'.$t1[$i]['vol'].'" /></td>
					<td align="center"><a href="javscript:void(0)" class="onClickRemoveRow remove" rel="1"><i class="fa fa-times"></i></a></td>
				  </tr>';
			}
		  }else{
		    echo '<tr><td class="idx" align="center">N0</td><td><input type="text" id="lts[]" name="lts[]" class="lts" /></td><td align="center"><input type="text" id="vol[]" name="vol[]" class="vol" /></td><td align="center"><a href="javascript:void(0)" class="onClickRemoveRow remove" rel="1"><i class="fa fa-times"></i></a></td></tr>';
		  }
		  ?>        </tbody>
      </table>
      <a href="#" class="onClickAddRow" rel="1"><i class="fa fa-plus"></i></a>
    </div>
    <!-- T2 -->
    <div id="tanque_2" class="tab">
      <table id="tbl_fuel_2" border="0" width="100%" cellpadding="0" cellspacing="0" class="result">
        <thead>
          <tr>
            <th>No.</th>
            <th>Litros en Tanque 2</th>
            <th>Voltaje</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
		  if($t2!=NULL){
			for($i=0;$i<count($t2);$i++){
		    echo '<tr>
				    <td class="idx" align="center">N'.$i.'</td>
					<td align="center"><input type="text" id="lst_lts[]" name="lst_lts[]" value="'.$t2[$i]['lts'].'" /></td>
					<td align="center"><input type="text" id="lst_v[]" name="lst_v[]" value="'.$t2[$i]['vol'].'" /></td>
					<td align="center"><a href="javscript:void(0)" class="onClickRemoveRow remove" rel="2"><i class="fa fa-times"></i></a></td>
				  </tr>';
			}
		  }else{
		    echo '<tr><td class="idx" align="center">N0</td><td><input type="text" id="lts[]" name="lts[]" class="lts" /></td><td align="center"><input type="text" id="vol[]" name="vol[]" class="vol" /></td><td align="center"><a href="javascript:void(0)" class="onClickRemoveRow remove" rel="2"><i class="fa fa-times"></i></a></td></tr>';
		  }
		  ?>
        </tbody>
      </table>
      <a href="#" class="onClickAddRow" rel="2"><i class="fa fa-plus"></i></a>
    </div>
    <!-- T3 -->
    <div id="tanque_3" class="tab">
      <table id="tbl_fuel_3" border="0" width="100%" cellpadding="0" cellspacing="0" class="result">
        <thead>
          <tr>
            <th>No.</th>
            <th>Litros en Tanque 3</th>
            <th>Voltaje</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
<?php
		  if($t3!=NULL){
			for($i=0;$i<count($t3);$i++){
		    echo '<tr>
				    <td class="idx" align="center">N'.$i.'</td>
					<td align="center"><input type="text" id="lst_lts[]" name="lst_lts[]" value="'.$t3[$i]['lts'].'" /></td>
					<td align="center"><input type="text" id="lst_v[]" name="lst_v[]" value="'.$t3[$i]['vol'].'" /></td>
					<td align="center"><a href="javscript:void(0)" class="onClickRemoveRow remove" rel="3"><i class="fa fa-times"></i></a></td>
				  </tr>';
			}
		  }else{
		    echo '<tr><td class="idx" align="center">N0</td><td><input type="text" id="lts[]" name="lts[]" class="lts" /></td><td align="center"><input type="text" id="vol[]" name="vol[]" class="vol" /></td><td align="center"><a href="javascript:void(0)" class="onClickRemoveRow remove" rel="3"><i class="fa fa-times"></i></a></td></tr>';
		  }
		  ?>        </tbody>
      </table>
      <a href="#" class="onClickAddRow" rel="3"><i class="fa fa-plus fa-lg"></i></a>
    </div>
  </div>
  
  <div class="actions">
    <ul>
      <li><a href="#" class="onClickSave">Guardar</a></li>
    </ul>
    <br class="clear" />
  </div>
  
</div>

</body>
</html>
