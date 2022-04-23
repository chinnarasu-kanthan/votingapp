@extends('layouts.app')

@section('title', 'Add District')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit District</h1>
        <a href="{{route('districts.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit District</h6>
        </div>
        <div class="card-body">
        <form method="POST" action="{{route('districts.update', ['district' => $district->id])}}">
                @csrf
                @method('PUT')
                <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>Select State</label>
                        <select class="form-control form-control-user @error('state_id') is-invalid @enderror" name="state_id">
                            <option>Select Status</option>
                            @foreach($states as $state)
                            <option value="{{$state->id}}" {{old('state_id') ? ((old('state_id') == $state->id) ? 'selected' : '') : (($district->state_id == $state->id) ? 'selected' : '')}}>{{$state->state_name}}</option>
                            @endforeach
                        </select>
                        @error('state_id')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                </div>

                    {{-- Name --}}
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>District Name</label>
                        <input 
                            type="text" 
                            class="form-control form-control-user @error('district_name') is-invalid @enderror" 
                            id="exampleName"
                            placeholder="Name" 
                            name="district_name" 
                            value="{{ old('district_name') ? old('district_name') : $district->district_name }}">

                        @error('name')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                   

                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>Status</label>
                        <select class="form-control form-control-user @error('status') is-invalid @enderror" name="status">
                            <option >Select Status</option>
                            <option value="Y" {{old('status') ? ((old('status') == 'Y') ? 'selected' : '') : (($district->status == 'Y') ? 'selected' : '')}}>Active</option>
                            <option value="N" {{old('status') ? ((old('status') == 'N') ? 'selected' : '') : (($district->status == 'N') ? 'selected' : '')}}>Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                {{-- Save Button --}}
                <button type="submit" class="btn btn-success btn-user btn-block">
                    Update
                </button>

            </form>
        </div>
    </div>

</div>


@endsection