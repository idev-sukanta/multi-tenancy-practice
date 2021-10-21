@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Data From <b>{{ auth()->user()->tenant->database }}</b></div>

                <div class="card-body">
                    <ul>
                        @foreach($blogs as $item)
                            <li> 
                            <b>[Title] :</b> {{ $item->title }} , 
                            <b>[Head] :</b> {{ $item->head_title }},
                            <b>[Content] :</b> {{ $item->content }},
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
