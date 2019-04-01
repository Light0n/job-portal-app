@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mt-5">
        <div class="col-md-10 offset-md-1">
        {{-- return message start --}}
        @if (session("message"))
        {{-- session("message")[0]: message status; 
        session("message")[1]: message content --}}
            <div class="alert alert-{{session("message")[0]}} alert-dismissible fade show" role="alert">
            <strong>{{session("message")[1]}}</strong>
            </div>
        @endif
        {{-- return message end --}}
            <div class="card">
                <div class="card-header">Categories
                    <a href="{{action('CategoryController@create')}}" class="btn btn-success float-right">Add New</a>
                </div>
                    
                <div class="card-body">
                    <table class="table table-sm table-striped" id="mainTable">
            <thead>
              <tr>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Actions</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr>
                <td>{{ $category->category_name }}</td>
                <td>{{ $category->description }}</td>
                <td>
                    <a href="{{action('CategoryController@edit', $category->id)}}" class="btn btn-primary">Edit</a>
                </td>
                <td>
                <form action="{{action('CategoryController@destroy', $category->id)}}" method="post">
                    {{csrf_field()}}
                    <input name="_method" type="hidden" value="DELETE">
                    <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                </td>
                </tr>
            @endforeach
            </tbody></table>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js-files')
@stop

@section('page-js-script')
<script type="text/javascript">
$(document).ready(function () {
    $('#mainTable').DataTable();
    $('#mainTable').css('display','table');
});
</script>
@stop
