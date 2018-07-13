<?php

namespace App\Http\Controllers;

use App\Bookingdetail;
use Illuminate\Http\Request;

use DB;

class BookingdetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $reservas_pendientes= DB::table('bookingdetails')->get();

        // return dd($reservas_pendientes);

        
         return view('bookingdetails.index', ['reservas_pendientes' =>$reservas_pendientes]);

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
     * @param  \App\Bookingdetail  $bookingdetail
     * @return \Illuminate\Http\Response
     */
    public function show(Bookingdetail $bookingdetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bookingdetail  $bookingdetail
     * @return \Illuminate\Http\Response
     */
    public function edit(Bookingdetail $bookingdetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bookingdetail  $bookingdetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bookingdetail $bookingdetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bookingdetail  $bookingdetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bookingdetail $bookingdetail)
    {
        //
    }
}
