<?php

namespace App\Listeners;
use App\bitacora;
use App\Events\Peticion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class RegistroPeticiones
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Peticion  $event
     * @return void
     */
    public function handle(Peticion $event)
    {


      if (Auth::user()->id) {
        $new=new bitacora;
        $new->Diahora=date("Y-m-d G:i:s");
        $new->Movimiento=$event->peticion;
        $new->user_id=Auth::user()->id;
        $new->save();
      }

    }
}
