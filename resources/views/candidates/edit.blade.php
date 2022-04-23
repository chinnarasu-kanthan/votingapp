@extends('layouts.app')

@section('title', 'Add Candidate')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Candidate</h1>
        <a href="{{route('candidates.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Candidate</h6>
        </div>
        <div class="card-body">
        <form method="POST" action="{{route('candidates.update', ['candidate' => $candidate->id])}}">
        @csrf
                @method('PUT')
                <div class="form-group row">

                {{-- First Name --}}
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>First Name</label>
                        <input 
                            type="text" 
                            class="form-control form-control-user @error('firstName') is-invalid @enderror" 
                            id="exampleName"
                            placeholder="First name" 
                            name="firstName" 
                            value="{{ old('firstName') ? old('firstName') : $candidate->firstName }}">

                        @error('firstName')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    {{-- First Name --}}
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>Last Name</label>
                        <input 
                            type="text" 
                            class="form-control form-control-user @error('lastName') is-invalid @enderror" 
                            id="exampleName"
                            placeholder="First name" 
                            name="lastName" 
                            value="{{ old('firstName') ? old('firstName') : $candidate->lastName }}">

                        @error('lastName')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    </div>
                    <div class="form-group row">

                    {{-- Category --}}
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>Select Category</label>
                        <select class="form-control form-control-user @error('category') is-invalid @enderror" name="category">
                            <option selected disabled>Select Status</option>
                            @foreach($categorys as $category)
                            <option value="{{$category->id}}" {{old('category') ? ((old('category') == $category->id) ? 'selected' : '') : (($candidate->category == $category->id) ? 'selected' : '')}}>{{$category->category_name}}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    {{-- Type --}} 
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>Election party</label>
                        <div class="form-check form-check">
                        <input class="form-check-input" type="radio" name="type" id="inlineRadio1" value="1" {{old('type') ? ((old('type') == 1) ? 'checked' : '') : (($candidate->type == '1') ? 'checked' : '')}}>
                        <label class="form-check-label" for="inlineRadio1">U.S. Senate</label>
                        </div>
                        <div class="form-check form-check">
                        <input class="form-check-input" type="radio" name="type" id="inlineRadio2" value="2" {{old('type') ? ((old('type') == 2) ? 'checked' : '') : (($candidate->type == '2') ? 'checked' : '')}}>
                        <label class="form-check-label" for="inlineRadio2">U.S. House of Representatives</label>
                        </div>
                        @error('type')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                   
                    </div>
                    <div class="form-group row">    
                    {{-- State --}}
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>Select State</label>
                        <select class="form-control form-control-user @error('state') is-invalid @enderror" name="state" id="state">
                            <option selected disabled>Select state</option>
                            @foreach($states as $state)
                            <option value="{{$state->id}}" {{old('state') ? ((old('state') == $state->id) ? 'selected' : '') : (($candidate->state == $state->id) ? 'selected' : '')}}>{{$state->state_name}}</option>
                            @endforeach
                        </select>
                        @error('state')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    {{-- district --}}
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>Select district</label>
                        <select class="form-control form-control-user @error('candidate') is-invalid @enderror" name="district" id="district">
                            <option selected disabled>Select district</option>
                            @foreach($districts as $district)
                            <option value="{{$district->id}}" {{old('district') ? ((old('district') == $district->id) ? 'selected' : '') : (($candidate->district == $district->id) ? 'selected' : '')}}>{{$district->district_name}}</option>
                            @endforeach
                        </select>
                        @error('district')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    </div>
                    <div class="form-group row">   

                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>Status</label>
                        <select class="form-control form-control-user @error('status') is-invalid @enderror" name="status">
                            <option selected disabled>Select Status</option>
                            <option value="Y" {{old('status') ? ((old('status') == 'Y') ? 'selected' : '') : (($candidate->status == 'Y') ? 'selected' : '')}}>Active</option>
                            <option value="N" {{old('status') ? ((old('status') == 'N') ? 'selected' : '') : (($candidate->status == 'N') ? 'selected' : '')}}>Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>


                </div>

                {{-- Save Button --}}
                <button type="submit" class="btn btn-success btn-user btn-block">
                    Save
                </button>

            </form>
        </div>
    </div>

</div>


@endsection
@section('scripts')
<script>
    $('#state').on('change', function(e){
        console.log(e);
        var state_id = e.target.value;

        $.get('{{ url('information') }}/create/ajax-state?state_id=' + state_id, function(data) {
           
            var city=$('select #candidate');
            $("#candidate option").remove();
            $.each(data, function(value, display){
                console.log(display);
                
                $('#candidate').append('<option value="' + display.id + '">' + display.candidate_name + '</option>');
            });
            //city.val( concerned id ).prop('selected', true);
        });
    });
</script>
@endsection
