@extends('layouts.app')

@section('title', 'District')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">District</h1>
        <a href="{{route('districts.create')}}" class="btn btn-sm btn-primary" >
            <i class="fas fa-plus"></i> Add New District
        </a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Districts</h6>
            
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="40%">S.No</th>
                            <th width="40%">District Name</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($districts as $district)
                           <tr>
                               <td>{{$district->id}}</td>
                               <td>{{$district->district_name}}</td>
                               <td style="display: flex">
                                   <a href="{{ route('districts.edit', ['district' => $district->id]) }}" class="btn btn-primary m-2">
                                        <i class="fa fa-pen"></i>
                                   </a>
                                   <form method="POST" action="{{ route('districts.destroy', ['district' => $district->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger m-2" type="submit">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                   </form>
                               </td>
                           </tr>
                       @endforeach
                    </tbody>
                </table>

              
            </div>
        </div>
    </div>

</div>


@endsection