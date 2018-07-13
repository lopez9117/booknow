<?php

namespace App\Http\Controllers;

// use Goutte\Client;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\City;
use App\Bookinkdetail;
use App\Comercio;
use DB;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;


class ScrapingController extends Controller
{
    //

   


    public function example(Client $client)
    {

    		// $crawler = $client->request( 'GET','https://www.booking.com/hotel/co/calle-10-express.es.html?label=gen173nr-1FCAsoMkINc3VpdGVzLXJlY3Jlb0gKWARoMogBAZgBCsIBA3gxMcgBDNgBAegBAfgBA5ICAXmoAgM;sid=bc3e43896557080384f6fc1969225d5e;all_sr_blocks=176446704_109834274_0_0_0;bshb=2;checkin=2018-05-07;checkout=2018-05-24;dest_id=-592318;dest_type=city;dist=0;dotd_fb=1;group_adults=2;group_children=0;hapos=1;highlighted_blocks=176446704_109834274_0_0_0;hpos=1;no_rooms=1;room1=A%2CA;sb_price_type=total;srepoch=1525731828;srfid=60dcc3547c69412b7d90f061a11dff5d730297f7X1;srpvid=3b4e9d79a8fd02d9;type=total;ucfs=1&#hotelTmpl/');
    



 

    }


    public function vista(){

        return view('vista');
    }



      public function autocomplete(Request $request)
     {

      if($request->ajax())
    {
     $output = '';
     $query = $request->get('query');
     if($query != '')
     {
      $data = DB::table('cities')->where('city', 'like', '%'.$query.'%')->take(6)->get();
       
     }     
     $total_row = $data->count();

     if($total_row > 0)
     {

      foreach($data as $row)
      {
       $output .= $row->city.'-'.$row->id.' ';
      }

     }
          $data = array(
      'result'  => $output
     );

     echo json_encode($data);
    }

   }




   public function infopayu(){
      return view('pago.infopayu');
   }





     public function booking(Request $request){

        //recibiendo datos del form
         $var = $request->all();

        
         //convirtiendo las fechas a ncohes
         $noches = substr($var['checkout'],8,2)- substr($var['checkin'],8,2);
          
          //insertando solo precio en la base de datos
                      
              //Insertando info en base de datos
             DB::table('bookingdetails')->insert([

        "hotel_id"                       => $var['hotel_id'],
        "nombre_hotel"                   => $var['nombre_hotel'],
        "direccion"                      => $var['direccion'],
        "descripcion"                    => $var['descripcion'],
        "checkin"                        => $var['checkin'],
        "checkout"                       => $var['checkout'],
        "noches"                         => $noches,
        "tipo_habitacion"                => $var['tipo_habitacion'],
        "precio"                         => $var['precio'],
        "email"                          => $var['email'],
        "nombre"                         => $var['nombre'],
        "telefono"                       => $var['telefono'],
        "nombre_huesped"                 => $var['nombre_huesped'],
        "created_at"                     => Carbon::now(),
        "updated_at"                     => Carbon::now(),     

                ]);

            //return response()->json('reservacion guardada');

      //      return view('pago.formpayu');

     }


     public function commerce(){


          $comercio = Comercio::all();

        return response()->json($comercio);



     }



          

}
