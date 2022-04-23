@extends('layouts.app')

@section('title', 'Edit category')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit category</h1>
        <a href="{{route('category.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit category</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{route('category.update', ['category' => $category->id])}}">
                @csrf
                @method('PUT')
                <div class="form-group row">

                    {{-- Name --}}
                    <div class="col-sm-6 mb-3 mb-sm-0">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>Name</label>
                        <input 
                            type="text" 
                            class="form-control form-control-user @error('category_name') is-invalid @enderror" 
                            id="exampleName"
                            placeholder="Name" 
                            name="category_name" 
                            value="{{ old('category_name') ? old('name') : $category->category_name }}">

                        @error('name')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>


                    {{-- Guard Name --}}
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>Description</label>
                        <textarea 
                            type="text" 
                            class="form-control form-control-user @error('description') is-invalid @enderror" 
                            id="exampleName"
                            placeholder="Name" 
                            name="description" 
                            >{{ old('description') ? old('description') : $category->description }}</textarea>

                        @error('name')
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


@section('scripts')
<script>
    $("#checkAllPermissions").click(function(){
        $('.permission-input').not(this).prop('checked', this.checked);
    });
</script>
@endsection