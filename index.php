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
	?>
	<div class="container">
		<h1 style="text-align: center;">Weekly payment</h1>
		<br><br>
		<div class="row">
			<div class="col-6">
				<div class="d-flex flex-row bd-highlight mb-3">
  <div class="p-2 bd-highlight"><h3>LV:</h3></div>
  <div class="p-2 bd-highlight">
  	<select class="form-control">
  		<option>ajsdjkajsdjka</option>
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
  	<select class="form-control">
  		<option>ajsdjkajsdjka</option>
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
} );
</script>
</body>

</html>