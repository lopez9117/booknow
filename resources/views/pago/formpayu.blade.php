<!DOCTYPE html>
<html >
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
   
      <form  class="formPayu" style="display: none">

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


       <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"> 
  </script>

  <script
  src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.10.0/js/md5.min.js"
   crossorigin="anonymous"> 
  </script>
</body>
</html>
