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
            <th>Cod Resp</th> 
            <th>telefono</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Valor</th>
            <th>Email</th>
            <th>Estado</th>
            
                    
        </tr>
        </thead>
        <tbody>
            <tr>

          @foreach ($reservas_pendientes as $reservas_pendiente)
             
              <th>{{$reservas_pendiente->id}}</th>
              <th>{{$reservas_pendiente->response_code_pol}}</th> 
              <th>{{$reservas_pendiente->phone}}</th>
              <th>{{$reservas_pendiente->cc_holder}}</th>
              <th>{{$reservas_pendiente->description}}</th>
              <th>${{$reservas_pendiente->value}}</th>
              <th>{{$reservas_pendiente->email_buyer}}</th>
              <th>{{$reservas_pendiente->response_message_pol}}</th>
            </tr>
            @endforeach
          </tbody>

        <thead>
            <tr>
            <th>Fecha</th>
            <th>Moneda</th> 
            <th>Pais de compra</th>
            <th>Referencia de venta</th>
            <th>ip</th> 
            <th>Fecha Transaccion</th> 
            <th>Metodo de pago</th> 
            <th>Transaction ID</th> 
        </tr>
        </thead>
        <tbody>
            
               <th>{{$reservas_pendiente->date}}</th>
               <th>{{$reservas_pendiente->currency}}</th> 
               <th>{{$reservas_pendiente->shipping_country}}</th> 
               <th>{{$reservas_pendiente->reference_sale}}</th>
               <th>{{$reservas_pendiente->ip}}</th> 
               <th>{{$reservas_pendiente->transaction_date}}</th> 
               <th>{{$reservas_pendiente->payment_method_name}}</th> 
               <th>{{$reservas_pendiente->transaction_id}}</th> 
        </tbody>


          <thead>
            <tr>
            <th>additional_value</th>
            <th>test</th> 
            <th>cc_number</th>
            <th>error_code_bank</th>
            <th>billing_country</th> 
            <th>bank_referenced_name</th> 
            <th>Sign</th> 
            <th>error_message_bank</th> 
        </tr>
        </thead>
        <tbody>
            
               <th>{{$reservas_pendiente->additional_value}}</th>
               <th>{{$reservas_pendiente->test}}</th> 
               <th>{{$reservas_pendiente->cc_number}}</th> 
               <th>{{$reservas_pendiente->error_code_bank}}</th>
               <th>{{$reservas_pendiente->billing_country}}</th> 
               <th>{{$reservas_pendiente->bank_referenced_name}}</th> 
               <th>{{$reservas_pendiente->sign}}</th> 
               <th>{{$reservas_pendiente-> error_message_bank}}</th> 
        </tbody>
  
  
    </table>


      


</div>
</div>
</div>
</div>
</div>



		


@endsection
