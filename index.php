<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Metodo de pago</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>
</head>
<body>
	<?php
	include("conexion.php");
	Conexion::getInstancia();
//Obtener datos del usuario
 function Get_User()
 {
  $temp=Conexion::consulta("select concat(wpcp_pc_users.name,' ', wpcp_pc_users.surname) as Miembro,categories
  from wpcp_pc_users where id=".$_GET["id"]);
  $categoria=Conexion::consulta("select wpcp_terms.name from wpcp_terms  where term_id=".unserialize($temp[0]->categories)[0]);
  $User=array("Miembro"=>$temp[0]->Miembro,"Categoria"=>$categoria[0]->name);
  return $User;
 }
 //End datos usuario

$User=Get_User();
  $IV=Conexion::consulta("select concat(wpcp_pc_users.name,' ', wpcp_pc_users.surname) as Miembro,wpcp_pc_users.id 
from wpcp_pc_users inner join wpcp_pc_user_meta on wpcp_pc_users.id=wpcp_pc_user_meta.user_id
where  meta_key='codigo-del-referido' and substring(categories,15,2)=31 OR substring(categories,15,2)=30 and wpcp_pc_user_meta.meta_value in(
select wpcp_pc_user_meta.meta_value from wpcp_pc_user_meta
where user_id=".$_GET["id"]." and meta_key='Codigo');");
  $membresia=Conexion::consulta("select meta_value as membresia from wpcp_pc_user_meta where meta_key='membresia' and user_id in(select wpcp_pc_user_meta.user_id
from wpcp_pc_users inner join wpcp_pc_user_meta on wpcp_pc_users.id=wpcp_pc_user_meta.user_id
where   meta_key='codigo-del-referido'  and wpcp_pc_user_meta.meta_value in(
select wpcp_pc_user_meta.meta_value from wpcp_pc_user_meta
where user_id=".$_GET["id"]." and meta_key='Codigo'));");

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
	?>
 

	<div class="container">
		<h1 style="text-align: center;">Weekly payment</h1>
		<br><br>
		<div class="row">
			<div class="col-6">
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
where user_id=".$_GET["id"]." and meta_key='codigo-del-referido');");
        echo "<option disabled selected value='".$moderador[0]->id."'>".$moderador[0]->Miembro."</option>";
      }
      ?>
  	</select>
  </div>
</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
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
  echo "<script>alert('Este tipo de usuario no es válido en esta página');window.location='./';</script>";
  exit();
}
      ?>
      <option <?php echo (isset($_GET["iv"])||$User["Categoria"]=="Inscriptor")?"":"selected";?>  disabled>Seleccionar IV</option>
  	</select>
  </div>
</div>
			</div>
			
		</div>
	<table id="tabla" class="table table-striped">
  <thead>
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
   </tr>
   <?php }}?>
    <?php
      if (isset($membresia) && $User["Categoria"]=="Inscriptor") {
      for ($i=0; $i <count($membresia) ; $i++) { 
        
     ?>
   <tr>
    <td><?php echo $IV[$i]->Miembro;?></td>
    <td><?php echo $membresia[$i]->membresia;?></td>
    <td><a class="btn  btn-info btn-sm btn-">Pagar</a></td>
    <td><a class="btn  btn-info btn-sm btn-">Pagar</a></td>
    <td><a class="btn  btn-info btn-sm btn-">Pagar</a></td>
    <td><a class="btn  btn-info btn-sm btn-">Pagar</a></td>
    <td><a class="btn  btn-info btn-sm btn-">Pagar</a></td>
    <td></td>
   </tr>
   <?php }}?>
  </tbody>
</table>
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
      window.location='/Sistemapago?id=<?php echo $_GET['id']?>'+'&iv='+this.value;
     });
} );
</script>

<!-- Modal pago -->
<div class="modal" tabindex="-1" id="modalpago" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Cierre del modal pago -->
</body>

</html>