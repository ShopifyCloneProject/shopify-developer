@extends('client.client')
@section('content')
  @if(session()->has('message'))
      <div class="alert alert-success">
          {{ session()->get('message') }}
      </div>
  @endif
  <div id="home">
    <maindata :data="{{ json_encode($data) }}"  ></maindata>
  </div>  
@stop