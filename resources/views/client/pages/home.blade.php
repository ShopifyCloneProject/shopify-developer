@extends('client.client')
@section('content')
  <div id="home">

    <maindata :data="{{ json_encode($data) }}"  ></maindata>
  </div>  
@stop
