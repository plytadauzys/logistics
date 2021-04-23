@extends('layouts.app')
@section('content')
    @if($data->count() == 0)
        <p>nėra įvykusių ekspedicijų</p>
    @else
        <p>{{$data}}</p>
    @endif
@endsection
