<?php

namespace App\Http\Controllers\Scraps;


//use App\Models\CityDestiny;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\HelpersController;
use Illuminate\Http\JsonResponse;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use App\Comercio;
use Carbon\Carbon;
use App\City;
use DB;
use Illuminate\Support\Facades\Input;
use Mail;

//use App\Dao\BookingCityDestinyDao;

class BookingScrapperController extends Controller
{

    private $reshotels = array();
    

    /**
     * Function to scrap a general page
     * @return json object
     */


    public function scrapSearchByCityAndDate(Request $request)
    {
                   // try{

                        $var = $request->json()->all();
                     
                      
                         $url    =   'https://www.booking.com/';
                          $endpoint   =   'searchresults.es.html?';

                       $crawl = new Client();
                       $guzzleClient = new GuzzleClient(array(
                            'timeout' => 100,
                           ));
                         $crawl->setClient($guzzleClient);

                            $crawl->setHeader('Accept', '*/*');
                            $crawl->setHeader('Cache-Control', 'max-age=0');
                            $crawl->setHeader('Connection', 'keep-alive');
                            $crawl->setHeader('Keep-Alive', '600');
                            $crawl->setHeader('Accept-Charset','ISO-8859-1,utf-8;q=0.7,*;q=0.7' );
                            $crawl->setHeader('Accept-Language','en-us,en;q=0.5' );
                            $crawl->setHeader('Pragma','');
            $data = array(
                'label'     =>  'gen173nr-1DCAsoMkINc3VpdGVzLXJlY3Jlb0gKWARoMogBAZgBCsIBA3gxMcgBDNgBA-gBAfgBA5ICAXmoAgM',
                'city' => $var['destiny']['idcity'],
                'checkin_monthday'  =>      substr($var['checkin'], 8,2)  ,
                'checkin_month'     =>      substr($var['checkin'], 5,2),
                'checkin_year'      =>      substr($var['checkin'], 0,4),
                'checkout_monthday' =>      substr($var['checkout'], 8,2),
                'checkout_month'    =>      substr($var['checkout'], 5,2),
                'checkout_year'     =>      substr($var['checkout'], 0,4),
                'group_adults'      =>      ($var['adult']['quantity'] != null ? $var['adult']['quantity'] : 0 ),
                'group_children'    =>      ($var['child']['quantity'] != null ? $var['child']['quantity'] : 0),
                 'age'               =>     ($var['child']['age'] != null ? $var['child']['age'] : 0),
                'no_rooms'          =>      ($var['destiny']['idcity']  !=  null ? $var['destiny']['idcity'] : 0),
                'ss_raw'            =>      $var['destiny']['city'],
                'ac_position'       =>      0,
                'ac_langcode'       =>      'es',
                'dest_id'           =>      $var['destiny']['idcity'],
                'dest_type'         =>      $var['destiny']['type'],
                'search_selected'   =>      'true',
                'selected_currency' =>       'COP',

            );

            $crawler = $crawl->request('GET', $url.$endpoint.http_build_query($data).'&nflt=ht_id%3D204%3B', [
                'stream' => true,
                'read_timeout' => 100,
            ]);          
              
 
               $pages =
                // preg_replace( '/[^A-Za-z0-9\-]/', '', (
                $crawler->filter('.results-paging > div.bui-pagination__nav > ul > li.bui-pagination__pages > ul > li a')->extract(array('href') );
                // ->count() > 0)
                // ? $crawler->filter('.results-paging > div.bui-pagination__nav > ul > li.bui-pagination__pages > ul > li:nth-last-child(2)')->text()
                //  :0);


                $pages2 = preg_replace( '/[^A-Za-z0-9\-]/', '', (
                    $crawler->filter('.results-paging  li')->count() > 0)
                ? $crawler->filter('.results-paging li:nth-last-child(1)')->text()
                : 0);
            

for ($i = 0; $i <$pages2; $i++)
     {
             if ( $i != 0 ) {    
                    $p = $i * 15;
                    $crawler = $crawl->request('GET', $url.$endpoint.http_build_query($data).'&rows=15&offset='.$p.'&nflt=ht_id%3D204%3B',
                        ['stream' => true,
                         'read_timeout' => 100,
                        ]);                
                }

                $nodescount = $crawler->filter( '.hotellist_wrap .sr_item')->count();
                if($nodescount > 0){
                    // try{
                        $crawler->filter( '.hotellist_wrap .sr_item')
                        ->each( function ( $node )  {
                            if(!empty($node)) {



                                $cname = $node->filter( '.sr-hotel__name' )->count();
                                if($cname != '0'){
                                  $name = trim( preg_replace( '/[^;\sa-zA-Z0-9áéíóúüñÁÉÍÓÚÜÑ]+/u', ' ', $node->filter( '.sr-hotel__name' )->text() ) );
                                }else{
                                    $name = "";
                                }

                                $clinkname = $node->filter( '.hotel_name_link url' )->count();
                                if($clinkname!= '0'){
                                  $linkname =  $node->filter( '.hotel_name_link url' )->attr('href');
                                }else{
                                    $linkname = "";
                                }

                                dd($linkname);



                                //Booking, dependiendo del tipo de busqueda que se realize, booking arroja los precios en diferentes clases de css, de esta  forma se escrapean la 3 clases acontinuacion.
                               $cprice = $node->filter( '.totalPrice' )->count();
                                if($cprice != '0'){
                                    // $signo_peso = '$';
                                     $precio =preg_replace( '/\n/', ' ', $node->filter( '.totalPrice' )->text());
                                    $elprecio= explode('COP',$precio);
                                    $price1 = $elprecio[1];

                                }else{
                                    $price1 = "";
                                }
                               

                                $cprice2 = $node->filter( '.price' )->count();
                                if($cprice2 != '0'){
                                    // $signo_peso = '$';
                                    $precio2 = preg_replace( '/\n/', ' ', $node->filter( '.price' )->text());
                                    $elprecio2= explode('COP',$precio2);
                                    $price2 = $elprecio2[1];

                                }else{
                                    $price2 = "";
                                }


                              
                                   $cprice3 = $node->filter( '.sr_gs_price_total' )->count();
                                if($cprice3 != '0'){
                                    // $signo_peso3 = '$';
                                    $precio3 = trim(preg_replace( '/\n/', ' ',$node->filter( '.sr_gs_price_total' )->text()));
                                    $elprecio3= explode('COP',$precio3);
                                    $price3 = $elprecio3[1];

                                }else{
                                    $price3 = "";
                                }



                                $hotelid = $node->filter('.sr_item')->attr('data-hotelid');


                                $clink =    $node->filter( '.sr_item_photo_link' )->count();
                                if($clink != '0'){
                                
                                    $link = $node->filter( '.sr_item_photo_link')->extract(array('href'));
                                    
                                }else{
                                    $link = "";
                                }

                                $cimage = $node->filter( '.hotel_image' )->count();
                                if($cimage != '0'){
                                    $image =$node->filter( '.hotel_image')->extract(array('src') ) ;
                                }else{
                                    $image = "";
                                }


                                $cscore = $node->filter( ' a .review-score-badge' )->count();
                                if($cscore != '0'){
                                    $score2 = trim($node->filter( 'a .review-score-badge')->attr('aria-label'));
                                    $score3 = explode(':', $score2);
                                    $score = $score3[1];
                                }else{
                                    $score = "";
                                }

                                $caddress = $node->filter( ' .district_link ' )->count();
                                if($caddress != '0'){
                                    $address2 = $node->filter( '.district_link ')->text();
                                    $address= explode('Mostrar en el mapa',$address2);
                                    $direccion= trim(preg_replace( '/\n/', ' ', $address[0]));
                                }else{
                                    $direccion = "no hay direccion";
                                }



                                  $ckilometer = $node->filter( '.distfromdest' )->count();
                                if($ckilometer != '0'){
                                    $kilometers = trim(preg_replace( '/\n/', ' ', $node->filter( '.distfromdest')->text()));
                                }else{
                                    $kilometers = "";
                                }


                                   $crecomentation = $node->filter( '.room_link' )->count();
                                if($crecomentation != '0'){
                                  $recommendation = trim( preg_replace( '/\n/', ' ',$node->filter( '.room_link')->text()));
                                }
                                else{
                                    $recommendation = "";
                                }

                                  $cservices = $node->filter( '.sr_room_reinforcement' )->count();
                                if($cservices != '0'){
                                  $services = trim(preg_replace( '/\n/', ' ',$node->filter( '.sr_room_reinforcement')->text()));
                                }
                                else{
                                    $services = "";
                                }

                                

                                if($price3 != "" && $price2 != ""){
                                    $price = $price3;                                
                                }
                                else if($price3 != "" && $price1 != ""){
                                    $price = $price3;                                
                                }
                                 else if($price3 == "" && $price2 != ""){
                                    $price = $price2;                                
                                }
                                else if ($price3=="" && $price1 != "" ){
                                      $price = $price1;   
                                }
                                else if($price2 != "" && $price1 != ""){
                                    $price = $price2;                                
                                }
                                 else if($price1 != "" && $price2 == ""){
                                    $price = $price1;                                
                                }
                                else if ($price1=="" && $price2 == "" ){
                                      $price = "";   
                                }
                                else if ($price1=="" && $price3 != "" ){
                                      $price = $price3;   
                                }
                                else if ($price1=="" && $price3 == "" ){
                                      $price = "";   
                                }
                                else if ($price1!="" && $price3 == "" ){
                                      $price = $price1;   
                                }
                                else if ($price1=="" && $price2 != "" ){
                                      $price = $price2;   
                                }
                                else if ($price2=="" && $price3 != "" ){
                                      $price = $price3;   
                                }
                                 else if ($price2!="" && $price3 != "" ){
                                      $price = $price3;   
                                }
                                 else if ($price2!="" && $price3 == "" ){
                                      $price = $price2;   
                                }
                                 else if ($price2=="" && $price3 == "" ){
                                      $price = "";   
                                }



                                if(!in_array($name, $this->reshotels)){
                                    $this->reshotels[] = array(
                                        'id'    =>  $hotelid,
                                        'name'  =>  $name,
                                        'price' =>  $price,
                                        'link' =>   $link,
                                        'image' =>  $image,
                                        'score' =>  $score,
                                        'direccion' => $direccion,
                                        'kilometers' => $kilometers,
                                        'recommendation' => $recommendation,
                                        'services' => $services,
                                       

                                    );
                                }//en del in_array
                            

                               }//cierre del if node empty
                        });
    
                    // }catch(\Exception $e){
                    //     return response()->json($e);
                    // }
         }//cierre del if nodecount
             else
                {

                    return response()->json("No existen nodos");
                }


            }//cierre del for de paginas
        

            $result = HelpersController::super_unique($this->reshotels, 'name' );
           $cantidad_name = count($result);

           

            for ($i=0; $i <$cantidad_name; $i++) { 
                # code...
            
            DB::table('hotel_details')->insert([

                 "hotel_id"                       => $result[$i]['id'],
                "nombre_hotel"                   => $result[$i]['name'],
                // "puntuacion"                     => $result['0']['puntuacion'],
                // "direccion"                      => $result['0']['direccion'],
                // "descripcion"                    => $result['0']['descripcion'],
                // "servicios"                      => $result['0']['servicios'],
                // "imagenes"                       => $result['0']['puntuacion'],
                // "tipo_habitacion"                => $result['0']['puntuacion'],
                // "servicios_por_tipo_habitacion"  => $result['0']['puntuacion'],
                // "precio"                         => $result['0']['puntuacion'],
                // "ocupacion"                      => $result['0']['puntuacion'],
                // "opciones"                       => $result['0']['puntuacion'],
                // "disponibilidad"                 => $result['0']['puntuacion'],
                // "created_at"                     => Carbon::now(),
                // "updated_at"                     => Carbon::now(),     

                ]);
        }
            return response()->json([ 'scrapped'=>$result, 'pages'=>$pages],200);
        // }  catch(\Exception $e){
        //     return  $e;
        // }

    }




    public function scrapSearchByhotel(Request $request)
    {
        try{
        $var = $request->json()->all();

                        
                $url = $var['url'];


                   


                $crawl = new Client();
                $guzzleClient = new GuzzleClient(array(
                    'timeout' => 600,
                ));
                $crawl->setClient($guzzleClient);
                $crawl->setHeader('Accept', '*/*');
                $crawl->setHeader('Cache-Control', 'max-age=0');
                $crawl->setHeader('Connection', 'keep-alive');
                $crawl->setHeader('Keep-Alive', '600');
                $crawl->setHeader('Accept-Charset','ISO-8859-1,utf-8;q=0.7,*;q=0.7' );
                $crawl->setHeader('Accept-Language','en-us,en;q=0.5' );
                $crawl->setHeader('Pragma','');




                $crawler = $crawl->request('GET', $url, [
                    'stream' => true,
                    'read_timeout' => 100,
                ]);

              

                 //Scrap del  id del hotel
                $hotel_id = $crawler->filter('.hp-lists')->attr('data-hotel-id');



                 //Scrap de el nombre del hotel
                $titulo_Hotel = trim(preg_replace('/[^;\sa-zA-Z0-9áéíóúüñÁÉÍÓÚÜÑ\n]\n+/u', ' ',$crawler->filter('.hp__hotel-name')->text()));
             




                //Puntuacion HOtel
                $cpuntuacion_Hotel = $crawler->filter('#js--hp-gallery-scorecard .review-score-badge')->count();
                if ($cpuntuacion_Hotel !=0) {

                        $puntuacion_Hotel =   trim( preg_replace( '/[^;\sa-zA-Z0-9áéíóúüñÁÉÍÓÚÜÑ]\n+/u', ' ',   $crawler->filter('#js--hp-gallery-scorecard .review-score-badge')->text()));

                        // print_r($puntuacion_Hotel);

               //  $puntuacion2= explode('comentarios',$puntuacion_Hotel);
               //  $puntuacion22 = $puntuacion2[1];

               //  $lacoma2 = explode(',', $puntuacion22);
               // $lacoma = $lacoma2[0];
                // $puntuacion222 = explode($lacoma,$puntuacion22);

               
                // $puntuacion2222 = $puntuacion222[1];

                //   // var_dump( $puntuacion2222);

                // $puntuacion22222 =  trim( preg_replace( '/[^;\sa-zA-Z0-9áéíóúüñÁÉÍÓÚÜÑ]\n+/u', ' ',  $lacoma));
                   
               }
                else{
                    $puntuacion_Hotel = "";
                    }

            

               
                               


              

                  //Scrap de la imagenes del hotel
                      $cimagenes_hotel= $crawler->filter('.bh-photo-grid-thumb-cell')->count();
                    if($cimagenes_hotel !=0){

                      $imagenes_hotel= $crawler->filter( '.bh-photo-grid-thumb-cell')

                     ->each(function($fotografia){

                        $nodofotografia = str_replace("max400", "max600",$fotografia->children('a')->extract(array('href'))); 

                        return $nodofotografia;

                                });
                            }
                    else{

                   $imagenes_hotel= str_replace("max400", "max600", $crawler->filter( '#photos_distinct')->children('a')->extract(array('href') ));      
                     }
                      




                        // //Scrap de la direccion completa del hotel
                 $direccion_hotel = trim( preg_replace( '/[^;\sa-zA-Z0-9áéíóúüñÁÉÍÓÚÜÑ]\n+/u', ' ', $crawler->filter('.hp_address_subtitle')->text()));  
                


                        // //scrap de la descripcion completa.    
                $descripcion_hotel = trim( preg_replace( '/[^;\sa-zA-Z0-9áéíóúüñÁÉÍÓÚÜÑ]\n+/u', ' ',$crawler->filter('#summary')->text()));              
                 $descripcion_hotel0 = trim(preg_replace( '/\n/', ' ',$descripcion_hotel));





                         //scrap de los servicios 
                   $servicios_hotel = $crawler->filter('.facilitiesChecklistSection ')->filter('li')
                  ->each(function($servicesitems){

                   return  $listado_de_servicios = trim( preg_replace( '/\n/', ' ', $servicesitems->text()));       
                            });
                


                          


                      //scrap de las estrellas del hotel
                   $cestrellas= $crawler->filter('.bk-icon-stars')->count();
                   if ($cestrellas !=0) {
                        $estrellas = $crawler->filter('.bk-icon-stars')->attr('title');                    
                   }else{
                        $estrellas = "";
                   }
                    




                        // comentarios
                $comentarios_hotel = $crawler->filter('.hp-social-proof-review_score')->filter('div')->filter('.hp-social_proof-quote_bubble')
                     ->each(function($social){
                 $listado_comentarios = trim( preg_replace( '/\n/', ' ', $social->last()->text()));    
                    return $listado_comentarios;  
                  });



                     //autor de comentarios
             $comentarios_autor = $crawler->filter('.hp-social-proof-review_score')->filter('div')->filter('.hp-social_proof-quote_author-details')
                     ->each(function($social){        
                     $listado_autores = trim(preg_replace( '/\n/', ' ', $social->text())); 
                     $listado_autores2 = explode(' ',$listado_autores);
                    return $autores = $listado_autores2[0];                    
                });


                          //pais del autor de comentarios
                 $comentarios_autor2_pais = $crawler->filter('.hp-social-proof-review_score')->filter('div')->filter('.hp-social_proof-quote_author-details')
                 ->each(function($social_pais){        
                     $listado_autores_pais = trim(preg_replace( '/\n/', ' ', $social_pais->text())); 
                     //$listado_autores2_pais = explode(' ',$listado_autores_pais);                  
                    return $autores_pais = $listado_autores_pais;    
                    
                });

                         //bandera del autor de comentarios
                 $comentarios_autor2_pais_bandera = $crawler->filter('.hp-social-proof-review_score')->filter('div')->filter('.hp-social_proof-quote_author-avatar')->filter('img')->filter('.avatar-mask')
                     ->each(function($social_pais_bandera){        
                     $listado_autores_pais_bandera = preg_replace('/\n/',' ',$social_pais_bandera->extract(array('src'))); 
                    return $listado_autores_pais_bandera;                    
                });


                           


                //Checkin de reserva
                 $checkin_de_reserva =   trim( preg_replace( '/[^;\sa-zA-Z0-9áéíóúüñÁÉÍÓÚÜÑ]\n+/u', ' ', $url));
                 $chekin_de_reserva1 = explode('checkin',$checkin_de_reserva);
                 $chekin_de_reserva11 = $chekin_de_reserva1[1];
                 $chekin_de_reserva111 = explode('=',$chekin_de_reserva11);
                 $chekin_de_reserva1111 = $chekin_de_reserva111[1];
                 $chekin_de_reserva11111 =  explode('&',$chekin_de_reserva1111);
                 $chekin_de_reserva111111 = $chekin_de_reserva11111[0];
                 $chekin_de_reserva111111;


                //Ckeckout de reserva
                $checkout_de_reserva =   trim( preg_replace( '/[^;\sa-zA-Z0-9áéíóúüñÁÉÍÓÚÜÑ]\n+/u', ' ', $url));
                $checkout_de_reserva1 = explode('checkout',$checkout_de_reserva);
                $checkout_de_reserva11 = $checkout_de_reserva1[1];
                $checkout_de_reserva111 = explode('=',$checkout_de_reserva11);
                $checkout_de_reserva1111 = $checkout_de_reserva111[1];
                $checkout_de_reserva11111 =  explode('&',$checkout_de_reserva1111);
                $checkout_de_reserva111111 = $checkout_de_reserva11111[0];              
                $checkout_de_reserva111111;




                    //Contador de la tabla descriptiva de booking
                    $nodescount2 = $crawler->filter( '#hp_availability_style_changes .description tbody')->count();
                    if($nodescount2 > 0){

                     try{
                    
                     $crawler->filter('#hp_availability_style_changes .description table tbody ')
                     ->each( function ( $node ) use ($titulo_Hotel
                        ,$puntuacion_Hotel
                        ,$direccion_hotel
                        ,$descripcion_hotel0
                        ,$servicios_hotel
                        ,$imagenes_hotel
                      //,$imagenes_hotel2
                        ,$hotel_id
                        ,$estrellas
                        ,$comentarios_hotel
                        ,$comentarios_autor
                        ,$chekin_de_reserva111111
                        ,$checkout_de_reserva111111
                        ,$comentarios_autor2_pais
                        ,$comentarios_autor2_pais_bandera
                     ) {
                        if(!empty($node)){


                    //Scrap del tipo de Habitacíon
                     $tipo_de_habitacion =   $node->filter('tr')->filter('td')->filter('div')->filter('.hprt-roomtype-icon-link')
                        ->each(function($noderooms) {

                     $listado_noderroms =  trim( preg_replace( '/\n/', ' ', $noderooms->text())); 
                           //    $myoptions = explode('Ver', trim(preg_replace('/\n/', ' ', $listado_noderroms)));
                           // $misopciones= $myoptions[0];

                     $servicios_por_tipo_habitacion = trim( preg_replace( '/\n/', ' ', $noderooms->parents()->filter('.hprt-facilities-block')->text()));


                     $precio_de_tipo_habitacion =  trim(preg_replace( '/\n/', ' ', $noderooms->parents()->filter('tr')->filter('td')->filter('.hprt-price-price')->text()));
               


                     $condiciones_de_tipo_habitacion =   trim( preg_replace( '/\n/', ' ',  $noderooms->parents()->filter('tr')->filter('td')->filter('.hprt-conditions')->text()));
                   
                     $listado_noderroms4 = $condiciones_de_tipo_habitacion;
                     $myoptions = explode('Cancelación', $listado_noderroms4);
                     $misopciones22= $myoptions[0];
                     $misopciones2 = explode('(', $misopciones22);
                     $misopciones = $misopciones2[0];

                     $disponibilidad_de_tipo_habitacion =  trim( preg_replace( '/[^;\sa-zA-Z0-9áéíóúüñÁÉÍÓÚÜÑ]\n+/u', ' ',   $noderooms->parents()->filter('tr')->filter('td')->filter('.hprt-nos-select')->filter('select')->text()));
            
                     $caracteres = preg_split('( )', $disponibilidad_de_tipo_habitacion);




                     return ['title'=>$listado_noderroms,'services'=>$servicios_por_tipo_habitacion,'price'=>$precio_de_tipo_habitacion,'options'=>$misopciones,'availability'=>$caracteres];
      
        ;

                          });


              
                  

                      if(!in_array($titulo_Hotel, $this->reshotels)){
                       $this->reshotels[] = array(
                         'hotel_id'        =>  $hotel_id,
                         'Nombre_hotel'    =>  $titulo_Hotel,
                          'estrellas'      =>  $estrellas,
                          'puntuacion'      =>  $puntuacion_Hotel,
                         'direccion'       =>  $direccion_hotel,
                         'descripcion'     =>  $descripcion_hotel0,
                         'servicios'       =>  $servicios_hotel,
                         'imagenes'        =>  $imagenes_hotel,
                         'checkin'         =>  $chekin_de_reserva111111,
                         'checkout'        =>  $checkout_de_reserva111111,
                         'Tipo_habitacion' =>  $tipo_de_habitacion,
                         'comentarios'     =>  $comentarios_hotel,
                         'autor'           =>  $comentarios_autor,
                         'pais'            =>  $comentarios_autor2_pais,
                         'avatar'          =>  $comentarios_autor2_pais_bandera
                        
                         
                                    );
                             }
                    
                               }//END IF CONTADOR del nodro

                           });//En crawler principal

                        } catch(\Exception $e){
                        return response()->json($e);
                    }            
  
                   }//En contador de la tabla
                   else{
                    return response()->json("No existen nodos");
                }
           
               $result = $this->reshotels;

                // DB::table('hotel_details')->insert([

                // "hotel_id"                       => $result['0']['hotel_id'],
                // "nombre_hotel"                   => $result['0']['Nombre_hotel'],
                // "puntuacion"                     => $result['0']['puntuacion'],
                // "direccion"                      => $result['0']['direccion'],
                // "descripcion"                    => $result['0']['descripcion'],
                // "servicios"                      => $result['0']['servicios'],
                // "imagenes"                       => $result['0']['puntuacion'],
                // "tipo_habitacion"                => $result['0']['puntuacion'],
                // "servicios_por_tipo_habitacion"  => $result['0']['puntuacion'],
                // "precio"                         => $result['0']['puntuacion'],
                // "ocupacion"                      => $result['0']['puntuacion'],
                // "opciones"                       => $result['0']['puntuacion'],
                // "disponibilidad"                 => $result['0']['puntuacion'],
                // "created_at"                     => Carbon::now(),
                // "updated_at"                     => Carbon::now(),     

                // ]);
               
                
               return response()->json(['data'=>$result], 200);

           }  catch(\Exception $e){
            return  $e;
        }

     }//End de la funcion


// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// --------------------Funcion del Auto completado de las ciudades---------------------
     public function autocomplete(Request $request)
     {
       $data = [];
       if($request->has('q')){
           $search = $request->q;
           $data = DB::table("cities")
                   ->select("id","city")
                   ->where('city','LIKE',"%$search%")
                   ->get();
       }
       return response()->json($data); 
   }





// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// --------------------Metodo para consultar todas las ciudades------------------------
   public function allcity(){
           $data = [];
           $data = DB::table("cities")->get(); 
           return response()->json($data);
   }




// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// -------------------Metodo que devuelve la reserva seleccionada----------------------
   public function book(Request $request){

           $var = $request->json()->all();
            return response()->json(['data' => $var]);
     }





// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// -----------------Metodo que inserta la intencion de reserva-------------------------
       public function booking(Request $request){

         // if (Request::ajax()) {
                
            
         $var = $request->json()->all();

    

         $noches = substr($var['checkout'],8,2)- substr($var['checkin'],8,2);
          
         // $precio_con_cop = explode('COP', $var['precio']);
         // $precio =  trim($precio_con_cop[1]);
   
           // dd($noches);
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
        "created_at"                     => Carbon::now('America/Chicago'),
        "updated_at"                     => Carbon::now('America/Chicago'),     

                ]);
             return response()->json('intencion de reserva guardada');
                 // $comercio = Comercio::all();
          // return response()->json(['data'=>$comercio]);
        // }       
     }





// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ---------Metodo que devuelve los datos del comercio para el pago con payu-----------
      public function commerce(){
          $comercio = Comercio::all();
       return response()->json($comercio);
     }







// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ---------Metodo que devuelve los datos del comercio para el pago con payu-----------

public function confirmation(Request $request){

        $var = $request->all();
            $file=fopen('../storage/app/public/test.txt','w');
            fwrite($file,json_encode($var));
            fclose($file);


            
             DB::table('bookingconfirmations')->insert([
             

             "response_code_pol"       => $var['response_code_pol'],

            
             "phone"                   => $var['phone'],
 
            
            "additional_value"         => $var['additional_value'],

            
            "test"                     => $var['test'],

           
            "transaction_date"         => $var['transaction_date'],

           
            "cc_number"                => $var['cc_number'],

           
            "cc_holder"                => $var['cc_holder'],

           
            "billing_country"          => $var['billing_country'],

           
            "bank_referenced_name"     => $var['bank_referenced_name'],

           
            "description"              => $var['description'],

           
            "administrative_fee_tax"   => $var['administrative_fee_tax'],

           
            "value"                    => $var['value'],
 
           
            "administrative_fee"       => $var['administrative_fee'],

           
            "payment_method_type"      => $var['payment_method_type'],

           
            "office_phone"             => $var['office_phone'],

           
            "email_buyer"              => $var['email_buyer'],

           
            "response_message_pol"     => $var['response_message_pol'],

           
            "error_message_bank"       => $var['error_message_bank'],

           
             "shipping_city"           => $var['shipping_city'],

           
            "transaction_id"           => $var['transaction_id'],

           
            "sign"                     => $var['sign'],

           
            "tax"                      => $var['tax'],

           
            "payment_method"           => $var['payment_method'],

           
            "billing_address"          => $var['billing_address'],

           
            "payment_method_name"      => $var['payment_method_name'],

           
            "pse_bank"                 => $var['pse_bank'],

           
             "state_pol"               => $var['state_pol'],

           
             "date"                    => $var['date'],

           
            "nickname_buyer"           => $var['nickname_buyer'],

           
            "reference_pol"            => $var['reference_pol'],

           
            "currency"                 => $var['currency'],

           
            "risk"                     => $var['risk'],

           
            "shipping_address"         => $var['shipping_address'],

           
             "bank_id"                 => $var['bank_id'],
            
           
             "payment_request_state"   => $var['payment_request_state'],

           
            "customer_number"          => $var['customer_number'],

           
            "administrative_fee_base"  => $var['administrative_fee_base'],

           
            "attempts"                 => $var['attempts'],

           
             "merchant_id"             => $var['merchant_id'],

           
             "exchange_rate"           => $var['exchange_rate'],

           
            "shipping_country"         => $var['shipping_country'],

            
            "installments_number"      => $var['installments_number'],

            
            "franchise"                => $var['franchise'],

            
            "extra1"                   => $var['extra1'],

            
            "extra2"                   => $var['extra2'],

            
            "antifraudMerchantId"      => $var['antifraudMerchantId'],

            
            "extra3"                   => $var['extra3'],

            
            "nickname_seller"          => $var['nickname_seller'],

            
            "ip"                       => $var['ip'],

            
            "airline_code"             => $var['airline_code'],

                      
            "billing_city"             => $var['billing_city'],

            
            "pse_reference1"           => $var['pse_reference1'], 
            
            
            "reference_sale"           => $var['reference_sale'],  
    
            
            "pse_reference3"           => $var['pse_reference3'],  

            
            "pse_reference2"           => $var['pse_reference2'],   

            "created_at"               => Carbon::now('America/Chicago'),
            "updated_at"               => Carbon::now('America/Chicago'),     


                ]);

            
            //ENVIAR CORREO
            Mail::send("emails.payureservation", $var, function($message) use ($var){
                    $message->from('info@reserveahora.com', 'reserveahora')
                            ->subject('Detalles de la Reserva')
                            ->to($var['email_buyer'],$var['cc_holder']);

            });




          return response()->json('Informacion de reserva guardada', 200 );


}


 public function pagination(Request $request){


            $var = $request->json()->all();

               $url = 'https://www.booking.com';
                $endpoint  = $var['url'];
                $divisa = '&selected_currency=COP';

             // $id = $var['id'];           


                $crawl = new Client();
                $guzzleClient = new GuzzleClient(array(
                    'timeout' => 600,
                ));
                $crawl->setClient($guzzleClient);
                $crawl->setHeader('Accept', '*/*');
                $crawl->setHeader('Cache-Control', 'max-age=0');
                $crawl->setHeader('Connection', 'keep-alive');
                $crawl->setHeader('Keep-Alive', '600');
                $crawl->setHeader('Accept-Charset','ISO-8859-1,utf-8;q=0.7,*;q=0.7' );
                $crawl->setHeader('Accept-Language','en-us,en;q=0.5' );
                $crawl->setHeader('Pragma','');




                $crawler = $crawl->request('GET', $url.$endpoint.$divisa, [
                    'stream' => true,
                    'read_timeout' => 100,
                ]);


                // print_r($url);

   

                $nodescount = $crawler->filter( '.hotellist_wrap .sr_item')->count();
                if($nodescount > 0){
                    // try{
                        $crawler->filter( '.hotellist_wrap .sr_item')
                        ->each( function ( $node )  {
                            if(!empty($node)) {



                                $cname = $node->filter( '.sr-hotel__name' )->count();
                                if($cname != '0'){
                                  $name = trim( preg_replace( '/[^;\sa-zA-Z0-9áéíóúüñÁÉÍÓÚÜÑ]+/u', ' ', $node->filter( '.sr-hotel__name' )->text() ) );
                                }else{
                                    $name = "";
                                }


                                //Booking, dependiendo del tipo de busqueda que se realize, booking arroja los precios en diferentes clases de css, de esta  forma se escrapean la 3 clases acontinuacion.
                               $cprice = $node->filter( '.totalPrice' )->count();
                                if($cprice != '0'){
                                    // $signo_peso = '$';
                                     $precio =preg_replace( '/\n/', ' ', $node->filter( '.totalPrice' )->text());
                                    $elprecio= explode('COP',$precio);
                                    $price1 = $elprecio[1];

                                }else{
                                    $price1 = "";
                                }
                               

                                $cprice2 = $node->filter( '.price' )->count();
                                if($cprice2 != '0'){
                                    // $signo_peso = '$';
                                    $precio2 = preg_replace( '/\n/', ' ', $node->filter( '.price' )->text());
                                    $elprecio2= explode('COP',$precio2);
                                    $price2 = $elprecio2[1];

                                }else{
                                    $price2 = "";
                                }


                              
                                   $cprice3 = $node->filter( '.sr_gs_price_total' )->count();
                                if($cprice3 != '0'){
                                    // $signo_peso3 = '$';
                                    $precio3 = trim(preg_replace( '/\n/', ' ',$node->filter( '.sr_gs_price_total' )->text()));
                                    $elprecio3= explode('COP',$precio3);
                                    $price3 = $elprecio3[1];

                                }else{
                                    $price3 = "";
                                }



                                $hotelid = $node->filter('.sr_item')->attr('data-hotelid');


                                $clink =    $node->filter( '.sr_item_photo_link' )->count();
                                if($clink != '0'){
                                
                                    $link = $node->filter( '.sr_item_photo_link')->extract(array('href'));
                                    
                                }else{
                                    $link = "";
                                }

                                $cimage = $node->filter( '.hotel_image' )->count();
                                if($cimage != '0'){
                                    $image =$node->filter( '.hotel_image')->extract(array('src') ) ;
                                }else{
                                    $image = "";
                                }


                                $cscore = $node->filter( ' a .review-score-badge' )->count();
                                if($cscore != '0'){
                                    $score2 = trim($node->filter( 'a .review-score-badge')->attr('aria-label'));
                                    $score3 = explode(':', $score2);
                                    $score = $score3[1];
                                }else{
                                    $score = "";
                                }

                                $caddress = $node->filter( ' .district_link ' )->count();
                                if($caddress != '0'){
                                    $address2 = $node->filter( '.district_link ')->text();
                                    $address= explode('Mostrar en el mapa',$address2);
                                    $direccion= trim(preg_replace( '/\n/', ' ', $address[0]));
                                }else{
                                    $direccion = "no hay direccion";
                                }



                                  $ckilometer = $node->filter( '.distfromdest' )->count();
                                if($ckilometer != '0'){
                                    $kilometers = trim(preg_replace( '/\n/', ' ', $node->filter( '.distfromdest')->text()));
                                }else{
                                    $kilometers = "";
                                }


                                   $crecomentation = $node->filter( '.room_link' )->count();
                                if($crecomentation != '0'){
                                  $recommendation = trim( preg_replace( '/\n/', ' ',$node->filter( '.room_link')->text()));
                                }
                                else{
                                    $recommendation = "";
                                }

                                  $cservices = $node->filter( '.sr_room_reinforcement' )->count();
                                if($cservices != '0'){
                                  $services = trim(preg_replace( '/\n/', ' ',$node->filter( '.sr_room_reinforcement')->text()));
                                }
                                else{
                                    $services = "";
                                }

                                

                                if($price3 != "" && $price2 != ""){
                                    $price = $price3;                                
                                }
                                else if($price3 != "" && $price1 != ""){
                                    $price = $price3;                                
                                }
                                 else if($price3 == "" && $price2 != ""){
                                    $price = $price2;                                
                                }
                                else if ($price3=="" && $price1 != "" ){
                                      $price = $price1;   
                                }
                                else if($price2 != "" && $price1 != ""){
                                    $price = $price2;                                
                                }
                                 else if($price1 != "" && $price2 == ""){
                                    $price = $price1;                                
                                }
                                else if ($price1=="" && $price2 == "" ){
                                      $price = "";   
                                }
                                else if ($price1=="" && $price3 != "" ){
                                      $price = $price3;   
                                }
                                else if ($price1=="" && $price3 == "" ){
                                      $price = "";   
                                }
                                else if ($price1!="" && $price3 == "" ){
                                      $price = $price1;   
                                }
                                else if ($price1=="" && $price2 != "" ){
                                      $price = $price2;   
                                }
                                else if ($price2=="" && $price3 != "" ){
                                      $price = $price3;   
                                }
                                 else if ($price2!="" && $price3 != "" ){
                                      $price = $price3;   
                                }
                                 else if ($price2!="" && $price3 == "" ){
                                      $price = $price2;   
                                }
                                 else if ($price2=="" && $price3 == "" ){
                                      $price = "";   
                                }



                                if(!in_array($name, $this->reshotels)){
                                    $this->reshotels[] = array(
                                        'id'    =>  $hotelid,
                                        'name'  =>  $name,
                                        'price' =>  $price,
                                        'link' =>   $link,
                                        'image' =>  $image,
                                        'score' =>  $score,
                                        'direccion' => $direccion,
                                        'kilometers' => $kilometers,
                                        'recommendation' => $recommendation,
                                        'services' => $services,
                                       

                                    );
                                }//en del in_array
                            

                               }//cierre del if node empty
                        });
    
                    // }catch(\Exception $e){
                    //     return response()->json($e);
                    // }
         }//cierre del if nodecount
             else
                {

                    return response()->json("No existen nodos");
                }


            //cierre del for de paginas

            $result = HelpersController::super_unique($this->reshotels, 'name' );
            return response()->json([ 'scrapped'=>$result] ,200);
        // }  catch(\Exception $e){
        //     return  $e;
        // }



 }
    
          

     


    // /**
    //  * Function to scrap by hotel
    //  * 
    //  * @return json object
    //  */
    // public function scrapByHotel(){
    //     try{
    //         $url = 'https://www.expedia.com/Hotel-Search?destination=Medellin%2C+Colombia&latLong=6.234093%2C-75.592979&regionId=2246&startDate=02%2F24%2F2018&endDate=02%2F25%2F2018&_xpid=11905%7C1&adults=2&children=0';
    //         $endpoint   =
    //         $crawl = new Client();

    //         $crawler = $crawl->request('GET', $url );
    //         //var_dump($crawler);
    //        $res =  array();
    //        $resarray = array();
    //         //$crawler->filter('.sr-hotel__name')
    //         $crawler->filter('.hotelTitle')
    //                 ->each(function ($node, $index ) {
    //                       dump($node->text());
    //                       $this->res[$index] =  $node->text();
    //                      }); 

    //        return response($this->res);
    //     } catch(\Exception $e){
    //         return response()->json($e);
    //     }
    // }



    // /**
    //  * Function to fill an array
    //  * 
    //  * @return array
    //  */
    // public function getCityDestinationsInfo(){
    //     try{
    //         $url = 'http://crawl.reserveahora.com/';
    //         $endpoint   = '/destinationfinder/countries/co.es.html?';

    //         $crawl = new Client();
    //         $guzzleClient = new GuzzleClient(array(
    //             'timeout' => 600,
    //         ));
    //         $crawl->setClient($guzzleClient);

    //         $crawl->setHeader('Accept', '*/*');
    //         $crawl->setHeader('Cache-Control', 'max-age=0');
    //         $crawl->setHeader('Connection', 'keep-alive');
    //         $crawl->setHeader('Keep-Alive', '600');
    //         $crawl->setHeader('Accept-Charset','ISO-8859-1,utf-8;q=0.7,*;q=0.7' );
    //         $crawl->setHeader('Accept-Language','en-us,en;q=0.5' );
    //         $crawl->setHeader('Pragma','');
    //         $data = array(
    //             'label'     =>  'gen173nr-1DCAsoMkINc3VpdGVzLXJlY3Jlb0gKWARoMogBAZgBCsIBA3gxMcgBDNgBA-gBAfgBA5ICAXmoAgM',
    //         );

    //         $crawler = $crawl->request('GET', $url, [
    //             'stream' => true,
    //             'read_timeout' => 100,
    //         ] );

    //         $crawler->filter( '.dcard_fake > .dsf_city')->each(function ($node, $i) {
    //                 $this->city   =   $node->filter('.card_border > .min_tile_link > .min_tile_container > .gradual_gradient > h2')->count();
    //                 $this->reshotels[] = [
    //                     'id'    =>  $node->attr('data-ufi'),
    //                     'city'  =>  trim( preg_replace( '/[^;\sa-zA-ZáéíóúüñÁÉÍÓÚÜÑ]+/u', ' ', $node->filter('.card_border > .min_tile_link > .min_tile_container > .gradual_gradient > h2')->text() ) )
    //                 ];
    //         });
    //        $citydestinydao = new BookingCityDestinyDao(new CityDestiny());
    //         $rtn = array();
    //         foreach ($this->reshotels as $item ){
    //             $rtn[] = $citydestinydao->create($item);
    //         }
    //        return response()->json(array('data'=>$this->reshotels, 'resp'=>$rtn));

    //     }catch(\Exception $e){
    //         return $e;
    //     }
    //}
     }




