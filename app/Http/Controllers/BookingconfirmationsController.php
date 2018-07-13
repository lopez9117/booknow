<?php

namespace App\Http\Controllers;

use App\Bookingconfirmation;
use Illuminate\Http\Request;
use DB;

class BookingconfirmationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

           $reservas_pendientes= DB::table('bookingconfirmations')->get();

        // return dd($reservas_pendientes);

        
         return view('bookingconfirmation.index', ['reservas_pendientes' =>$reservas_pendientes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bookingconfirmation  $bookingconfirmation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

            // $confirmationdetails = Bookingconfirmation::find($id);
             $reservas_pendientes = DB::table('bookingconfirmations')->where('id', $id)->get()->toArray();


             // return $confirmationdetails;

             return view('bookingconfirmation.show', ['reservas_pendientes' =>$reservas_pendientes]);



    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bookingconfirmation  $bookingconfirmation
     * @return \Illuminate\Http\Response
     */
    public function edit(Bookingconfirmation $bookingconfirmation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bookingconfirmation  $bookingconfirmation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bookingconfirmation $bookingconfirmation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bookingconfirmation  $bookingconfirmation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bookingconfirmation $bookingconfirmation)
    {
        //
    }
}
