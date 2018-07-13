@extends('adminlte::layouts.app')

@section('main-content')
  <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"> -->

	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-12 col-md-offset-0">
				<div class="panel panel-default">
					<div class="panel-heading">Reservas Confirmadas </div>

					<div class="panel-body" >
						

					
						
    <table id="datatable" class="table table-hover table-condensed cell-border display compact">
        <thead>
        <tr>
     		    <th>id</th>
            <!-- <th>Cod Resp</th> -->
            <th>telefono</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Valor</th>
            <th>Email</th>
            <th>Estado</th>
            <!-- <th>Transaction ID</th> -->
            <!-- <th>Metodo de pago</th> -->
            <th>Fecha</th>
            <!-- <th>Moneda</th> -->
            <!-- <th>Pais de compra</th> -->
            <!-- <th>Referencia de venta</th> -->
            <!-- <th>ip</th> -->
            <!-- <th>Fecha Transaccion</th> -->
            <th>Acciones</th>
        </tr>
        </thead>
         <tbody>
            <tr>

          @foreach ($reservas_pendientes as $reservas_pendiente)
             
               <th>{{$reservas_pendiente->id}}</th>
              <!-- <th>{{$reservas_pendiente->response_code_pol}}</th> -->
              <th>{{$reservas_pendiente->phone}}</th>
              <th>{{$reservas_pendiente->cc_holder}}</th>
              <th>{{$reservas_pendiente->description}}</th>
              <th>${{$reservas_pendiente->value}}</th>
              <th>{{$reservas_pendiente->email_buyer}}</th>
               <th>{{$reservas_pendiente->response_message_pol}}</th>
              <!-- <th>{{$reservas_pendiente->transaction_id}}</th> -->
              <!-- <th>{{$reservas_pendiente->payment_method_name}}</th> -->
              <th>{{$reservas_pendiente->date}}</th>
              <!-- <th>{{$reservas_pendiente->currency}}</th> -->
              <!-- <th>{{$reservas_pendiente->shipping_country}}</th> -->
              <!-- <th>{{$reservas_pendiente->reference_sale}}</th> -->
              <!-- <th>{{$reservas_pendiente->ip}}</th> -->
              <!-- <th>{{$reservas_pendiente->transaction_date}}</th> -->
              <th>
                <a href="{{url('/bookingconfirmations/'.$reservas_pendiente->id)}}" class="btn btn-info">Ver mas...</a>
            </th>

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
