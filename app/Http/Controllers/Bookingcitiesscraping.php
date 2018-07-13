<?php

namespace App\Http\Controllers;

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

class Bookingcitiesscraping extends Controller
{
    //


private $reshotels = array();

public function scrapbogota(){


    // try{

                      
                     
                      
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
                'city'              =>      -578472,
                'checkin_monthday'  =>      substr(Carbon::today()->toDateString(), 8,2)  ,
                'checkin_month'     =>      substr(Carbon::today()->toDateString(), 5,2),
                'checkin_year'      =>      substr(Carbon::today()->toDateString(), 0,4),
                'checkout_monthday' =>      substr(Carbon::tomorrow()->toDateString(), 8,2),
                'checkout_month'    =>      substr(Carbon::tomorrow()->toDateString(), 5,2),
                'checkout_year'     =>      substr(Carbon::tomorrow()->toDateString(), 0,4),
                'group_adults'      =>      2,
                'group_children'    =>      0,
                 'age'               =>     0,
                'no_rooms'          =>      1,
                'ss_raw'            =>      'Bogota',
                'ac_position'       =>      0,
                'ac_langcode'       =>      'es',
                'dest_id'           =>      -578472,
                'dest_type'         =>      'city',
                'search_selected'   =>      'true',
                'selected_currency' =>      'COP',

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
            
            // dd(Carbon::tomorrow()->toDateString());



for ($i = 0; $i <1; $i++)
     {
             if ( $i != 0 ) {    
                    $p = $i * 15;
                    $crawler = $crawl->request('GET', $url.$endpoint.http_build_query($data).'&rows=15&offset='.$p.'&nflt=ht_id%3D204%3B',
                        ['stream' => true,
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
            return response()->json([ 'scrapped'=>$result, 'pages'=>$pages],200);
        // }  catch(\Exception $e){
        //     return  $e;
        // }


}




public function scrapmedellin(){


    // try{

                      
                     
                      
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
                'city'              =>      -592318,
                'checkin_monthday'  =>      substr(Carbon::today()->toDateString(), 8,2)  ,
                'checkin_month'     =>      substr(Carbon::today()->toDateString(), 5,2),
                'checkin_year'      =>      substr(Carbon::today()->toDateString(), 0,4),
                'checkout_monthday' =>      substr(Carbon::tomorrow()->toDateString(), 8,2),
                'checkout_month'    =>      substr(Carbon::tomorrow()->toDateString(), 5,2),
                'checkout_year'     =>      substr(Carbon::tomorrow()->toDateString(), 0,4),
                'group_adults'      =>      2,
                'group_children'    =>      0,
                 'age'               =>     0,
                'no_rooms'          =>      1,
                'ss_raw'            =>      'Medellín',
                'ac_position'       =>      0,
                'ac_langcode'       =>      'es',
                'dest_id'           =>      -592318,
                'dest_type'         =>      'city',
                'search_selected'   =>      'true',
                'selected_currency' =>      'COP',

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
            
            // dd(Carbon::tomorrow()->toDateString());



for ($i = 0; $i <1; $i++)
     {
             if ( $i != 0 ) {    
                    $p = $i * 15;
                    $crawler = $crawl->request('GET', $url.$endpoint.http_build_query($data).'&rows=15&offset='.$p.'&nflt=ht_id%3D204%3B',
                        ['stream' => true,
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
            return response()->json([ 'scrapped'=>$result, 'pages'=>$pages],200);
        // }  catch(\Exception $e){
        //     return  $e;
        // }


}





public function scrapsantamarta(){


    // try{

                      
                     
                      
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
                'city'              =>      -598739,
                'checkin_monthday'  =>      substr(Carbon::today()->toDateString(), 8,2)  ,
                'checkin_month'     =>      substr(Carbon::today()->toDateString(), 5,2),
                'checkin_year'      =>      substr(Carbon::today()->toDateString(), 0,4),
                'checkout_monthday' =>      substr(Carbon::tomorrow()->toDateString(), 8,2),
                'checkout_month'    =>      substr(Carbon::tomorrow()->toDateString(), 5,2),
                'checkout_year'     =>      substr(Carbon::tomorrow()->toDateString(), 0,4),
                'group_adults'      =>      2,
                'group_children'    =>      0,
                 'age'               =>     0,
                'no_rooms'          =>      1,
                'ss_raw'            =>      'Santa Marta',
                'ac_position'       =>      0,
                'ac_langcode'       =>      'es',
                'dest_id'           =>      -598739,
                'dest_type'         =>      'city',
                'search_selected'   =>      'true',
                'selected_currency' =>      'COP',

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
            
            // dd(Carbon::tomorrow()->toDateString());



for ($i = 0; $i <1; $i++)
     {
             if ( $i != 0 ) {    
                    $p = $i * 15;
                    $crawler = $crawl->request('GET', $url.$endpoint.http_build_query($data).'&rows=15&offset='.$p.'&nflt=ht_id%3D204%3B',
                        ['stream' => true,
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
            return response()->json([ 'scrapped'=>$result, 'pages'=>$pages],200);
        // }  catch(\Exception $e){
        //     return  $e;
        // }


}





public function scrapCartagena(){


    // try{

                      
                     
                      
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
                'city'              =>      -579943,
                'checkin_monthday'  =>      substr(Carbon::today()->toDateString(), 8,2)  ,
                'checkin_month'     =>      substr(Carbon::today()->toDateString(), 5,2),
                'checkin_year'      =>      substr(Carbon::today()->toDateString(), 0,4),
                'checkout_monthday' =>      substr(Carbon::tomorrow()->toDateString(), 8,2),
                'checkout_month'    =>      substr(Carbon::tomorrow()->toDateString(), 5,2),
                'checkout_year'     =>      substr(Carbon::tomorrow()->toDateString(), 0,4),
                'group_adults'      =>      2,
                'group_children'    =>      0,
                 'age'               =>     0,
                'no_rooms'          =>      1,
                'ss_raw'            =>      'Cartagena',
                'ac_position'       =>      0,
                'ac_langcode'       =>      'es',
                'dest_id'           =>      -579943,
                'dest_type'         =>      'city',
                'search_selected'   =>      'true',
                'selected_currency' =>      'COP',

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
            
            // dd(Carbon::tomorrow()->toDateString());



for ($i = 0; $i <1; $i++)
     {
             if ( $i != 0 ) {    
                    $p = $i * 15;
                    $crawler = $crawl->request('GET', $url.$endpoint.http_build_query($data).'&rows=15&offset='.$p.'&nflt=ht_id%3D204%3B',
                        ['stream' => true,
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
            return response()->json([ 'scrapped'=>$result, 'pages'=>$pages],200);
        // }  catch(\Exception $e){
        //     return  $e;
        // }


}


public function scrapBarranquilla(){



    // try{

                      
                     
                      
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
                'city'              =>      -578007,
                'checkin_monthday'  =>      substr(Carbon::today()->toDateString(), 8,2)  ,
                'checkin_month'     =>      substr(Carbon::today()->toDateString(), 5,2),
                'checkin_year'      =>      substr(Carbon::today()->toDateString(), 0,4),
                'checkout_monthday' =>      substr(Carbon::tomorrow()->toDateString(), 8,2),
                'checkout_month'    =>      substr(Carbon::tomorrow()->toDateString(), 5,2),
                'checkout_year'     =>      substr(Carbon::tomorrow()->toDateString(), 0,4),
                'group_adults'      =>      2,
                'group_children'    =>      0,
                 'age'               =>     0,
                'no_rooms'          =>      1,
                'ss_raw'            =>      'barranquilla',
                'ac_position'       =>      0,
                'ac_langcode'       =>      'es',
                'dest_id'           =>      -578007,
                'dest_type'         =>      'city',
                'search_selected'   =>      'true',
                'selected_currency' =>      'COP',

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
            
            // dd(Carbon::tomorrow()->toDateString());



for ($i = 0; $i <1; $i++)
     {
             if ( $i != 0 ) {    
                    $p = $i * 15;
                    $crawler = $crawl->request('GET', $url.$endpoint.http_build_query($data).'&rows=15&offset='.$p.'&nflt=ht_id%3D204%3B',
                        ['stream' => true,
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
            return response()->json([ 'scrapped'=>$result, 'pages'=>$pages],200);
        // }  catch(\Exception $e){
        //     return  $e;
        // }



}





public function scrapVillavicencio(){




    // try{

                      
                     
                      
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
                'city'              =>      -601471,
                'checkin_monthday'  =>      substr(Carbon::today()->toDateString(), 8,2)  ,
                'checkin_month'     =>      substr(Carbon::today()->toDateString(), 5,2),
                'checkin_year'      =>      substr(Carbon::today()->toDateString(), 0,4),
                'checkout_monthday' =>      substr(Carbon::tomorrow()->toDateString(), 8,2),
                'checkout_month'    =>      substr(Carbon::tomorrow()->toDateString(), 5,2),
                'checkout_year'     =>      substr(Carbon::tomorrow()->toDateString(), 0,4),
                'group_adults'      =>      2,
                'group_children'    =>      0,
                 'age'               =>     0,
                'no_rooms'          =>      1,
                'ss_raw'            =>      'villavicencio',
                'ac_position'       =>      0,
                'ac_langcode'       =>      'es',
                'dest_id'           =>      -601471,
                'dest_type'         =>      'city',
                'search_selected'   =>      'true',
                'selected_currency' =>      'COP',

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
            
            // dd(Carbon::tomorrow()->toDateString());



for ($i = 0; $i <1; $i++)
     {
             if ( $i != 0 ) {    
                    $p = $i * 15;
                    $crawler = $crawl->request('GET', $url.$endpoint.http_build_query($data).'&rows=15&offset='.$p.'&nflt=ht_id%3D204%3B',
                        ['stream' => true,
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
            return response()->json([ 'scrapped'=>$result, 'pages'=>$pages],200);
        // }  catch(\Exception $e){
        //     return  $e;
        // }


}


public function scrapCali(){


    // try{

                      
                     
                      
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
                'city'              =>      -579248,
                'checkin_monthday'  =>      substr(Carbon::today()->toDateString(), 8,2)  ,
                'checkin_month'     =>      substr(Carbon::today()->toDateString(), 5,2),
                'checkin_year'      =>      substr(Carbon::today()->toDateString(), 0,4),
                'checkout_monthday' =>      substr(Carbon::tomorrow()->toDateString(), 8,2),
                'checkout_month'    =>      substr(Carbon::tomorrow()->toDateString(), 5,2),
                'checkout_year'     =>      substr(Carbon::tomorrow()->toDateString(), 0,4),
                'group_adults'      =>      2,
                'group_children'    =>      0,
                 'age'               =>     0,
                'no_rooms'          =>      1,
                'ss_raw'            =>      'cali',
                'ac_position'       =>      0,
                'ac_langcode'       =>      'es',
                'dest_id'           =>      -579248,
                'dest_type'         =>      'city',
                'search_selected'   =>      'true',
                'selected_currency' =>      'COP',

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
            
            // dd(Carbon::tomorrow()->toDateString());



for ($i = 0; $i <1; $i++)
     {
             if ( $i != 0 ) {    
                    $p = $i * 15;
                    $crawler = $crawl->request('GET', $url.$endpoint.http_build_query($data).'&rows=15&offset='.$p.'&nflt=ht_id%3D204%3B',
                        ['stream' => true,
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
            return response()->json([ 'scrapped'=>$result, 'pages'=>$pages],200);
        // }  catch(\Exception $e){
        //     return  $e;
        // }


}


public function scrapSanandres(){


    // try{

                      
                     
                      
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
                'city'              =>      -597118,
                'checkin_monthday'  =>      substr(Carbon::today()->toDateString(), 8,2)  ,
                'checkin_month'     =>      substr(Carbon::today()->toDateString(), 5,2),
                'checkin_year'      =>      substr(Carbon::today()->toDateString(), 0,4),
                'checkout_monthday' =>      substr(Carbon::tomorrow()->toDateString(), 8,2),
                'checkout_month'    =>      substr(Carbon::tomorrow()->toDateString(), 5,2),
                'checkout_year'     =>      substr(Carbon::tomorrow()->toDateString(), 0,4),
                'group_adults'      =>      2,
                'group_children'    =>      0,
                 'age'               =>     0,
                'no_rooms'          =>      1,
                'ss_raw'            =>      'San Andres',
                'ac_position'       =>      0,
                'ac_langcode'       =>      'es',
                'dest_id'           =>      -597118,
                'dest_type'         =>      'city',
                'search_selected'   =>      'true',
                'selected_currency' =>      'COP',

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
            
            // dd(Carbon::tomorrow()->toDateString());



for ($i = 0; $i <1; $i++)
     {
             if ( $i != 0 ) {    
                    $p = $i * 15;
                    $crawler = $crawl->request('GET', $url.$endpoint.http_build_query($data).'&rows=15&offset='.$p.'&nflt=ht_id%3D204%3B',
                        ['stream' => true,
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
            return response()->json([ 'scrapped'=>$result, 'pages'=>$pages],200);
        // }  catch(\Exception $e){
        //     return  $e;
        // }

}



}
