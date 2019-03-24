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
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
        @endif
        {{-- return message end --}}
            <div class="card">
                <div class="card-header">Skills
                    <a href="{{action('SkillController@create')}}" class="btn btn-success float-right">Add New</a>
                </div>
                    
                <div class="card-body">
                    <table class="table table-sm table-striped" id="mainTable">
            <thead class="">
              <tr>
                <th scope="col">Category</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col" colspan="2">Actions</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($skills as $skill)
                <td>{{ $skill->category_id }}</td>
                <td>{{ $skill->skill_name }}</td>
                <td>{{ $skill->description }}</td>
                <td>
                    <a href="{{action('SkillController@edit', $skill->id)}}" class="btn btn-primary">Edit</a>
                </td>
                <td>
                <form action="{{action('SkillController@destroy', $skill->id)}}" method="post">
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
</script>
@stop
