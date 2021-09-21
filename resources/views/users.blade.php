@extends('layouts.app');

@section('content')


@if(Session::has('notice'))
   <p> <strong> {{ Session::get('notice') }} </strong> </p>
@endif
<p> Crear nuevo usuario </p>

