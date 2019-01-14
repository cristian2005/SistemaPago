<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Sistema de pago</title>
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <link rel="icon" type="image/png" href="https://www.comunitypowerdom.org/wp-content/uploads/2018/12/LOGO-CARLOS-2.png">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>
</head>
<body>

	<header>
						<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #e3f2fd;">
							<a class="navbar-brand" href="https://www.comunitypowerdom.org/">
								<img src="https://www.comunitypowerdom.org/wp-content/uploads/2018/12/LOGO-CARLOS-2.png" width="60" height="60" alt="logo de comunitypowerdom" >
							</a>
							<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						    <span class="navbar-toggler-icon"></span>
						  </button>

							<div class="collapse navbar-collapse" id="navbarSupportedContent">
										<ul class="navbar-nav ml-auto">
											<li class="nav-item">
												<a class="nav-link" href="https://www.comunitypowerdom.org/">Inicio</a>
											</li>

											<li class="nav-item">
												<a class="nav-link" href="https://www.comunitypowerdom.org/eventos/">Eventos</a>
											</li class="nav-item">

											<li class="nav-item">
												<a class="nav-link" href="https://www.comunitypowerdom.org/my-account/">Mi cuenta</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="https://www.comunitypowerdom.org/blog/">Blog</a>
											</li>
										</ul>
							</div>

							</nav>
	</header>

	<?php
define("Regular",100);
define("Premium",200);
define("Eventual",150);
$id_iv=0;
	include("conexion.php");
	Conexion::getInstancia();

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT");


#region Abonar
if (isset($_GET["abono"])) {
    $consu=Conexion::Ejecutar("insert into wpcp_pagos(`Id_usuario`, `Abono`) values(".$_GET["id_pagou"].",".$_GET["abono"].")");
    if ($consu) {
      echo "<script>window.location='?id=".$_GET["id"]."&iv=".$_GET["id_iv"]."';</script>";
    }
    else
  {
    echo "<script>window.location='https://www.comunitypowerdom.org/my-account/';alert('Ocurrió un error inesperado');</script>";
  }
  exit();
}
#end region



//Obtener datos del usuario
 function Get_User()
 {
  $temp=Conexion::consulta("select concat(wpcp_pc_users.name,' ', wpcp_pc_users.surname) as Miembro,categories,id
  from wpcp_pc_users where wp_user_id=".$_GET["id"]);
  $categoria=Conexion::consulta("select wpcp_terms.name from wpcp_terms  where term_id=".unserialize($temp[0]->categories)[0]);
  $User=array("Miembro"=>$temp[0]->Miembro,"Categoria"=>$categoria[0]->name,"id"=>$temp[0]->id);
  return $User;
 }
 //End datos usuario
$User=Get_User();
  $IV=Conexion::consulta("
select concat(wpcp_pc_users.name,' ', wpcp_pc_users.surname) as Miembro,wpcp_pc_users.id
from wpcp_pc_users inner join wpcp_pc_user_meta on wpcp_pc_users.id=wpcp_pc_user_meta.user_id
where  meta_key='codigo-del-referido' and case when substring(categories,15,2)=31 then substring(categories,15,2)=31  else substring(categories,15,2)=30 end and wpcp_pc_user_meta.meta_value in(
select wpcp_pc_user_meta.meta_value from wpcp_pc_user_meta
where user_id=".$User['id']." and meta_key='Codigo');");
  $membresia=Conexion::consulta("select meta_value as membresia from wpcp_pc_user_meta where meta_key='membresia' and user_id in(select wpcp_pc_user_meta.user_id
from wpcp_pc_users inner join wpcp_pc_user_meta on wpcp_pc_users.id=wpcp_pc_user_meta.user_id
where   meta_key='codigo-del-referido'  and wpcp_pc_user_meta.meta_value in(
select wpcp_pc_user_meta.meta_value from wpcp_pc_user_meta
where user_id=".$User['id']." and meta_key='Codigo'));");

  if(isset($_GET["iv"]))
  {
    $membresia_iv=Conexion::consulta("select meta_value as membresia from wpcp_pc_user_meta where meta_key='membresia' and user_id in(select wpcp_pc_user_meta.user_id
from wpcp_pc_users inner join wpcp_pc_user_meta on wpcp_pc_users.id=wpcp_pc_user_meta.user_id
where   meta_key='codigo-del-referido' and wpcp_pc_user_meta.meta_value in(
select wpcp_pc_user_meta.meta_value from wpcp_pc_user_meta
where user_id=".$_GET["iv"]." and meta_key='Codigo'));");
    $miembro_iv=Conexion::consulta("select concat(wpcp_pc_users.name,' ', wpcp_pc_users.surname) as Miembro,wpcp_pc_users.id
from wpcp_pc_users inner join wpcp_pc_user_meta on wpcp_pc_users.id=wpcp_pc_user_meta.user_id
where   meta_key='codigo-del-referido' and wpcp_pc_user_meta.meta_value in(
select wpcp_pc_user_meta.meta_value from wpcp_pc_user_meta
where user_id=".$_GET["iv"]." and meta_key='Codigo');");
  }

//Function que organiza las celdas
  function Organizar_Celdas($membresia,$total,$contador,$html,$id_user)
  {
    if($total>=$membresia)
          {
             for ($i=1; $i <=5-$contador ; $i++) {
            $html.='<td></td>';
            }
            $html.='<td><span>Pagado<span></td>';

          }
        else
          {
            $resto=$membresia-$total;
      $html.= '<td><a data-toggle="modal" data-id="'.$id_user.'" data-abonado="'.$resto.'" onclick="AdministrarPago(event);" style="color:white;" data-target="#modalpago" class="btn btn-info btn-sm btn-">Pagar</a></td>';
            //Agregando fila vacias
            for ($i=1; $i <5-$contador ; $i++) {
              $html.='<td></td>';
            }
            $html.='<td><span>'.$resto.'<span></td>';
          }
          return $html;
  }

  //Obteniendo los pagos
  function Get_Pagos($id,$membresia)
  {
    $html='';
    $pagos=Conexion::consulta("select * from wpcp_pagos where Id_usuario=".$id);
    $total=0;
    $contador=0;
    if ($pagos) {
      ////Asigando los pagos que ha hecho
      foreach ($pagos as  $value) {
       $contador++;
       $html.= '<td><span data-toggle="tooltip" data-placement="top" title="'.date("d/m/y",strtotime($value->Fecha)).'">'.$value->Abono.'</span></td>';
       $total=$total+$value->Abono;
      }

      //Asignando el boton de pagar y sus celdas vacias
      switch ($membresia) {
        case 'Regular':
$html=Organizar_Celdas(Regular,$total,$contador,$html,$id);
          break;
        case 'Premium':
$html=Organizar_Celdas(Premium,$total,$contador,$html,$id);

          break;
          case 'Eventual':
$html=Organizar_Celdas(Eventual,$total,$contador,$html,$id);
          break;
        default:

          break;
      }
    }
     else
     {
       switch ($membresia) {
        case 'Regular':
$resto=Regular;
          break;
        case 'Premium':
$resto=Premium;
          break;
          case 'Eventual':
$resto=Eventual;
          break;
        default:

          break;
      }
      $html='<td><a data-toggle="modal" data-id="'.$id.'" data-abonado="'.$resto.'" onclick="AdministrarPago(event);" style="color:white;" data-target="#modalpago" class="btn btn-info btn-sm btn-">Pagar</a></td>
 <td></td><td></td><td></td><td></td> <td><span>'.$resto.'</span></td>
      ';
     }
      return $html;
  }

	?>
	<div class="container-fluid" style="background-image: url(https://www.comunitypowerdom.org/wp-content/uploads/2018/10/img-20.jpg) !important;">
			<div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center" >
	  <div class="col-md-5 p-lg-5 mx-auto my-5">
	    <h1 class=" font-weight-normal" style="font-size:50px;"><b>Sistema de pago</b></h1>
	  </div>
	</div>
</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<h2 class="text-center p-2">Historial de pago</h2>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-2">

			</div>
			<div class="col-4">
				<div class="d-flex flex-row bd-highlight mb-3">
  <div class="p-2 bd-highlight"><h3>LV:</h3></div>
  <div class="p-2 bd-highlight">
  	<select  class="form-control">
      <?php if ($User["Categoria"]=="Moderador") { ?>
  		<option><?php echo $User["Miembro"]?></option>
      <?php }
      else
      {
        $moderador=Conexion::consulta("select concat(wpcp_pc_users.name,' ', wpcp_pc_users.surname) as Miembro,wpcp_pc_users.id
from wpcp_pc_users inner join wpcp_pc_user_meta on wpcp_pc_users.id=wpcp_pc_user_meta.user_id
where meta_key='Codigo' and wpcp_pc_user_meta.meta_value in(
select wpcp_pc_user_meta.meta_value from wpcp_pc_user_meta
where user_id=".$User['id']." and meta_key='codigo-del-referido');");
        echo "<option disabled selected value='".$moderador[0]->id."'>".$moderador[0]->Miembro."</option>";
      }
      ?>
  	</select>
  </div>
</div>
			</div>
			<div class="col-2">

			</div>

			<div class="col-4">
				<div class="d-flex flex-row bd-highlight mb-3">
  <div class="p-2 bd-highlight"><h3>IV:</h3></div>
  <div class="p-2 bd-highlight">
  	<select id="iv"  class="form-control">
     <?php
if ($User["Categoria"]=="Moderador") {
    for ($i=0; $i <count($IV) ; $i++) {
       ?>
      <option <?php if(isset($_GET["iv"])){ if($_GET["iv"]==$IV[$i]->id) echo "selected";} ?> value="<?php echo $IV[$i]->id;?>"><?php echo $IV[$i]->Miembro;?></option>

      <?php }} else if($User["Categoria"]=="Inscriptor") {?>
        <option disabled selected><?php echo $User["Miembro"]?></option>
  		<?php
      }
else
{
  echo "<script>alert('Este tipo de usuario no es válido en esta página');window.location='https://www.comunitypowerdom.org/my-account/';</script>";
  exit();
}
      ?>
      <option <?php echo (isset($_GET["iv"])||$User["Categoria"]=="Inscriptor")?"":"selected";?>  disabled>Seleccionar IV</option>
  	</select>
  </div>
</div>
			</div>

		</div>
</div>
<br>
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
	<table id="tabla" class="table table-striped">
  <thead class="bg-info">
    <tr>
      <th scope="col">Miembros</th>
      <th scope="col">Tarjeta</th>
      <th scope="col">Pago1</th>
      <th scope="col">Pago2</th>
      <th scope="col">Pago3</th>
      <th scope="col">Pago4</th>
      <th scope="col">Pago5</th>
<th>Restante</th>
    </tr>
  </thead>
  <tbody>
     <?php
      if (isset($membresia_iv)) {
      for ($i=0; $i <count($membresia_iv) ; $i++) {

     ?>
   <tr>
    <td><?php echo $miembro_iv[$i]->Miembro?></td>
    <td><?php echo $membresia_iv[$i]->membresia?></td>
    <?php echo Get_Pagos($miembro_iv[$i]->id,$membresia_iv[$i]->membresia); $id_iv=$_GET["iv"];?>

   </tr>
   <?php }}?>
    <?php
      if (isset($membresia) && $User["Categoria"]=="Inscriptor") {
      for ($i=0; $i <count($membresia) ; $i++) {

     ?>
   <tr>
    <td><?php echo $IV[$i]->Miembro;?></td>
    <td><?php echo $membresia[$i]->membresia;?></td>
    <?php echo Get_Pagos($IV[$i]->id,$membresia[$i]->membresia); $id_iv=$_GET["id"];?>

   </tr>
   <?php }}?>
  </tbody>
</table>
	</div>
	</div>
	</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
<script type="text/javascript">
  $(document).ready( function () {
     $('#tabla').DataTable( {
        "language": {
           "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
     "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
        }
    } );
     $('#iv').change(function(){
      window.location='/SistemaPago?id=<?php echo $_GET['id']?>'+'&iv='+this.value;
     });
     $('#todo').click(function(){
      this.href="?id=<?php echo $_GET['id']?>&id_pagou="+id+"&abono="+abonado+"&id_iv=<?php echo (isset($_GET['iv']))?$_GET["iv"]:'';?>";
     });
      $('#abonar').click(function(){
      this.href="?id=<?php echo $_GET['id']?>&id_pagou="+id+"&abono="+$('#txtabono').val()+"&id_iv=<?php echo (isset($_GET['iv']))?$_GET["iv"]:'';?>";
     });
      $('[data-toggle="tooltip"]').tooltip();
} );
  var id=0,abonado=0;
  function AdministrarPago(e)
  {
     id=e.target.dataset.id;
     abonado=e.target.dataset.abonado;
  }
</script>

<!-- Modal pago -->
<div class="modal" tabindex="-1" id="modalpago" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Abonar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-inline">
  <div class="form-group mb-2">

    <input type="text" id="txtabono" placeholder="Monto a bonar" class="form-control" >
  </div>
  <div class="form-group mx-sm-3 mb-2">
  <a style="color:white;" id="abonar" class="btn btn-primary ">Abonar</a>

  </div>
  <a style="color:white;" id="todo" class="btn btn-success mb-2">Pagar todo</a>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Cierre del modal pago -->
</body>

</html>
