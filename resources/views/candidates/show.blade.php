@extends('layouts.app')

@section('title', 'Candidate Answer')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Candidate Answer</h1>
        <a href="{{route('candidates.create')}}" class="btn btn-sm btn-primary" >
            <i class="fas fa-plus"></i>  Candidate
        </a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"> Candidate Answer</h6>
            
        </div>
        <div class="card-body">
            <div class="table-responsive">
                
                <ul>
                @foreach ($answers as $key => $answer)

                <b> {{$answer->statement }} </b> <br>
                <p>{{$answer->candidate_answer }}</p>
                @endforeach
                </ul>
              
            </div>
        </div>
    </div>

</div>


@endsection