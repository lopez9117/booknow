<!DOCTYPE HTML>
<html>
<head>

   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>prueba</title>

  <!-- Latest compiled and minified CSS -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">




</head>
<body>

<div class="container">




      <form  class="bookform" id="register">

        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        
     <div class="col-md-6">
        <div class="form-group">
          <label for="">Hotel id</label>
          <input type="text" class="form-control" value="1991619"   name="hotel_id" id="hotel_id">
        </div>
        <div class="form-group">
          <label for="">Nombre Hotel</label>
          <input type="text" class="form-control" value="Soy Local"   name="nombre_hotel">
        </div>
       
       <div class="form-group">
          <label for="">Checkin</label>
          <input type="text" class="form-control" value="2018-06-29"  name="checkin" >
        </div>
         <div class="form-group">
          <label for="">Tipo_habitacion</label>
          <input type="text" class="form-control" value="Apartamento Deluxe"   name="tipo_habitacion">
        </div>

        </div>

      <div class="col-md-6">
        
         <div class="form-group">
          <label for="">Dirección</label>
          <input type="text" class="form-control" value="Carrera 34 8a-24, El Poblado, 050022 Medellín, Colombia"   name="direccion">
        </div>
        <div class="form-group">
          <label for="">Descripcion</label>
          <input type="text" class="form-control" value="Está en nuestra selección para Medellín.El Soy Local tiene barbacoa y vistas al jardín y está situado en el barrio El Poblado de Medellín, a 300 metros del parque Lleras Las habitaciones están equipadas con aire acondicionado, TV de pantalla plana con canales por cable, cafetera y baño privado con ducha o bañera. Algunas disponen de zona de estar. Además, las habitaciones superiores incluyen bañera de hidromasaje La recepción abre las 24 horas El Soy Local se encuentra a 700 metros del parque El Poblado y a 3,6 km del Pueblito Paisa. El aeropuerto más cercano es el Olaya Herrera, a 3 km del establecimiento.    El Poblado es una opción genial para los viajeros interesados en el ocio nocturno, la comida y la comida local"   name="descripcion">
        </div>
     

          <div class="form-group">
          <label for="">checkout</label>
          <input type="text" class="form-control" value="2018-06-30"  name="checkout">
        </div>
        <div class="form-group">
          <label for="">Precio</label>
          <input type="text" class="form-control" value="198000"   name="precio">
        </div>
     </div>


<div class="col-md-6">
        <div class="form-group">
          <label for="">Email</label>
          <input type="email" class="form-control" value=""  id="email" name="email">
        </div>
        <div class="form-group">
          <label for="">Nombre</label>
          <input type="text" class="form-control" value=""   name="nombre">
        </div>
  </div>
        
     <div class="col-md-6">
         <div class="form-group">
          <label for="">Telefono</label>
          <input type="number" class="form-control" value="" id="telefono"   name="telefono">
        </div>
        <div class="form-group">
          <label for="">Nombre Huesped</label>
          <input type="text" class="form-control" value=""   name="nombre_huesped">
        </div>
        
          <button type="submit" id="pedir_comercio" class="btn btn-primary btn-lg">Pagar</button>
    </div>
     

      </form>


<!-- 
/*==============================================================
/*==============================================================
/===============================================================
/*==============================================================
/*==============================================================
 FORMULARIO PAGAR CON PAYU
=================================================================*/ --> 


      <form  class="formPayu"  style="display: none">

                  <input name="merchantId"      type="hidden"  value="">
                  <input name="accountId"       type="hidden"  value="">
                  <input name="description"     type="hidden"  value="">
                  <input name="referenceCode"   type="hidden"  value="">
                  <input name="amount"          type="hidden"  value="">
                  <input name="tax"             type="hidden"  value="0">
                  <input name="taxReturnBase"   type="hidden"  value="0">
                  <input name="currency"        type="hidden"  value="COP">
                  <input name="lgn"             type="hidden"  value="es">            
                  <input name="confirmationUrl" type="hidden"  value=""> 
                  <input name="responseUrl"     type="hidden"  value="">
                  <input name="declinedResponseUrl"  type="hidden"  value="">
                  <input name="displayShippingInformation"   value="NO ">
                  <input name="test"            type="hidden"  value="">
                  <input name="signature"       type="hidden"  value=""> 
                  <input name="buyerFullName"   type="hidden"  value=""> 
                  <input name="buyerEmail"      type="hidden"  value=""> 
                  <input name="telephone"       type="hidden"  value=""> 


                  <input name="Submit"   type="submit"  value="PAGAR">
      </form>

             <!-- <button class="btn btn-info pull-right"   >pedir info comercio</button>  -->

  </div>
  <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"> 
  </script>

 <script
  src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.10.0/js/md5.js"
   crossorigin="anonymous"> 
  </script>
  




    <script type="text/javascript">

      $.ajaxSetup({
      headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });


        $('#pedir_comercio').on('click', function(){                  
        $('#register').submit(function(){
         var hotel_id = $('input:text[name=hotel_id]').val();
         var nombre_hotel = $('input:text[name=nombre_hotel]').val();
         var checkin = $('input:text[name=checkin]').val();
         var checkout = $('input:text[name=checkout]').val();
         var tipo_habitacion = $('input:text[name=tipo_habitacion]').val();
         var direccion = $('input:text[name=direccion]').val();
         var descripcion = $('input:text[name=descripcion]').val();
         var precio = $('input:text[name=precio]').val();
         var email = $('#email').val();
         var nombre = $('input:text[name=nombre]').val();
         var telefono = $('#telefono').val();
         var nombre_huesped = $('input:text[name=nombre_huesped]').val();

         var dataString = "hotel_id="+hotel_id+"&nombre_hotel="+nombre_hotel+"&checkin="+checkin+"&checkout="+checkout+"&tipo_habitacion="+tipo_habitacion+"&direccion="+direccion+"&descripcion="+descripcion+"&precio="+precio+"&email="+email+"&nombre="+nombre+"&telefono="+telefono+"&nombre_huesped="+nombre_huesped;

         $.ajax({
            type: "POST",
            url: "/booking",
            data: dataString,
            
           });
     
        });     
        });


         $('#pedir_comercio').on('click', function(){  

         $.get("{{ URL::to('/commerce') }}",function(data){
                             
                var merchantId        = data[0].merchantIdPayu;
                var accountId         = data[0].accountIdPayu;
                var apiKey            = data[0].apiKeyPayu;
                var modo              = data[0].modoPayu;
                var referenceCode     = (Number(Math.ceil(Math.random()*1000000000))+$('input:text[name=precio]').val());
                var descripcion       = $('input:text[name=nombre_hotel]').val()+'-' + $('input:text[name=tipo_habitacion]').val();
                var amount            = $('input:text[name=precio]').val();
                var currency          = "COP";
                var rutaOculta        = "http://booknow.io.co.ve/";
                var rutaconfirmacion  = "http://api.reserveahora.com/api/v1/confirmation";
                var signature         = md5(apiKey+'~'+merchantId+'~'+referenceCode+'~'+amount+'~'+currency);
                var buyerFullName     = $('input:text[name=nombre]').val();
                var buyerEmail        = $('input:text[name=email]').val();
                var telephone         = $('input:text[name=telefono]').val();

                console.log(merchantId);
                console.log(accountId);
                console.log(apiKey);
                console.log(modo);
                console.log(descripcion);
                console.log(referenceCode);
                console.log(rutaconfirmacion);                
                console.log(amount);
                console.log(currency);
                console.log(rutaOculta);
                console.log(signature);

                if (modo == "sandbox") {
                          var url = "https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/";
                          var tet = 1;
                 }else{
                   
                          var url = "https://checkout.payulatam.com/ppp-web-gateway-payu/";
                          var test = 0;
                }

               // console.log( $('#merchantId').val(data[0].merchantIdPayu));
               // console.log($('#accountId').val(data[0].accountIdPayu));
               $('.formPayu').attr('method','POST');
               $('.formPayu').attr('action',url);
               $(".formPayu input[name='merchantId']").attr("value",merchantId);
               $(".formPayu input[name='accountId']").attr("value",accountId);
               $(".formPayu input[name='description']").attr("value", descripcion);
               $(".formPayu input[name='referenceCode']").attr("value",referenceCode);
               $(".formPayu input[name='amount']").attr("value",amount);
               $(".formPayu input[name='responseUrl']").attr("value", rutaOculta);
               $(".formPayu input[name='confirmationUrl']").attr("value",rutaconfirmacion);
               $(".formPayu input[name='declinedResponseUrl']").attr("value", rutaOculta);
               $(".formPayu input[name='test']").attr("value", test);
               $(".formPayu input[name='signature']").attr("value", signature);
               $(".formPayu input[name='buyerFullName']").attr("value", buyerFullName);
               $(".formPayu input[name='buyerEmail']").attr("value", buyerEmail);
               $(".formPayu input[name='telephone']").attr("value", telephone );
               $(".formPayu input[name='Submit']").click();

             });

});


       




    </script>
<!-- 
<script
    const URL =  'https://http://786afe54.ngrok.io/commerce'; 
   fetch(URL)
   .then(response => .json())
   .then(response => {
       response.forEach(e => {
           console.log(comercio);
       });
   });

   </script> -->


<!-- Latest compiled and minified JavaScript -->
         <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


</body>
</html>