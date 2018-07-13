@extends('adminlte::layouts.app')

@section('main-content')
  <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"> -->


	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-12 col-md-offset-0">
				<div class="panel panel-default">
					<div class="panel-heading">Reservas Pendientes </div>

					<div class="panel-body">
						

					
						
    <table id="datatable" class="table table-hover table-condensed cell-border display compact">
        <thead>
        <tr>
            <th>Id</th>
            <th>Huesped</th>
            <th>telefono</th>
            <th>Email</th>
            <th>Hotel</th>
            <th>T. Habitacion</th>
            <th>Precio</th>
            <th>Checkin</th>
            <th>Checkout</th>
            <th>hora</th>
          
        </tr>
        </thead>
         <tbody>
            <tr>

          @foreach ($reservas_pendientes as $reservas_pendiente)
              <th>{{$reservas_pendiente->id}}</th>
              <th>{{$reservas_pendiente->nombre_huesped}}</th>
              <th>{{$reservas_pendiente->telefono}}</th>
              <th>{{$reservas_pendiente->email}}</th>
              <th>{{$reservas_pendiente->nombre_hotel}}</th>
              <th>{{$reservas_pendiente->tipo_habitacion}}</th>
              <th>${{$reservas_pendiente->precio}}</th>
              <th>{{$reservas_pendiente->checkin}}</th>
              <th>{{$reservas_pendiente->checkout}}</th>
              <th>{{$reservas_pendiente->created_at}}</th>
            

              

              
            </tr>
            @endforeach
          </tbody>
    </table>

</div>
</div>
</div>
</div>
</div>
@endsection
