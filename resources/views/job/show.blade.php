@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mt-5">
        <div class="col-md-12">
        {{-- return message start --}}
        @if (session("message"))
        {{-- session("message")[0]: message status; 
        session("message")[1]: message content --}}
            <div class="alert alert-{{session("message")[0]}} alert-dismissible fade show" role="alert">
            <strong>{{session("message")[1]}}</strong>
            </div>
        @endif
        {{-- return message end --}}
        
        {{-- Job Info start --}}
        <div class="card bg-light">
            <div class="card-header">
                <div class="row">
                    <h5 class="ml-3"><b>{{$job->title}}</b></h5> 
                    <h5 class="ml-auto mr-3 {{$job->status == "incomplete" ? "text-danger" : "text-success"}} font-weight-bold text-uppercase">{{ $job->status }}</h5>
                </div>
            </div>
            <div class="card-body">
                <p class="card-text mb-2"><b>Description: </b> {{$job->description}}</p>
                <div class="row mb-2">
                    <div class="col-lg-4"><b>Estimated budget: </b> ${{ number_format($job->estimated_budget, 2) }}</div>
                    <div class="col-lg-4"><b>Number of BIDs: </b> {{$job->number_of_application}} </div>
                    <div class="col-lg-4"><b>AVG BID:</b> ${{ number_format($job->avg_bid, 2) }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-4"><b>City: </b> {{$job->city}}</div>
                    <div class="col-lg-4"><b>Province: </b> {{$job->province}} </div>
                    <div class="col-lg-4"><b>Country:</b> {{$job->country}} </div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-4"><b>Create at: </b> {{$job->created_at}}</div>
                    <div class="col-lg-4"><b>Update at: </b> {{$job->updated_at}} </div>
                    {{-- user has current not applied and user is not an employer --}}
                    @php
                        $canApply = 1;
                        if(Auth::user() != null){
                            if(Auth::user()->id == $job->employer_id)
                                $canApply = 0;//user is employer
                            else{
                                foreach ($job->job_applications as $application) {
                                    if($application->jobseeker_id == Auth::user()->id){
                                        $canApply = 0;//user has applied
                                        break;
                                    }
                                }
                            }
                        }else{
                            $canApply = 0;
                        }
                    @endphp

                    @if ($canApply && $job->status == "open")
                    <div class="col-lg-4">
                        {{-- <a href="{{action('JobApplicationController@create', $job->id)}}" class="btn btn-success float-right">Apply</a> --}}
                        <form action="{{action('JobApplicationController@create')}}" method="get">
                            {{csrf_field()}}
                            <input name="job_id" type="hidden" value="{{$job->id}}">
                            <input name="jobseeker_id" type="hidden" value="{{Auth::user()->id}}">
                            <input name="job_title" type="hidden" value="{{$job->title}}">
                            <button class="btn btn-success" type="submit">Apply</button>
                        </form>
                    </div>
                    @endif
                </div>
                <p class="mb-0">
                <b>Skills required: </b>
                @for ($i = 0; $i < sizeof($job->required_skills); $i++)
                    @if ($i == sizeof($job->required_skills)-1)
                        {{$job->required_skills[$i]->skill_name}}
                    @else
                        {{$job->required_skills[$i]->skill_name . ", "}}
                    @endif
                @endfor
                </p>
            </div>
        </div>
        {{-- Job Info end --}}

        {{-- Job Applications start --}}
            <table class="table table-sm table-hover mb-3" id="mainTable">
            <thead>
              <tr>
                <th scope="col">Jobseeker</th>
                <th scope="col">Reputation</th>
                <th scope="col">Bid</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
            @foreach ($job->job_applications as $application)
                <tr class="{{$job->jobseeker_id == $application->jobseeker_info->id?
                "bg-warning" : ""}}">
                <td>
                    
                    <b>{{$application->jobseeker_info->first_name . " " .
                    $application->jobseeker_info->last_name}}</b><br>
                    <b>Email: </b> {{$application->jobseeker_info->email}}<br>
                    <b>Phone: </b> {{$application->jobseeker_info->phone}}
                    <div class="row">
                        <div class="col-lg-4"><b>City: </b> {{$application->jobseeker_info->city}}</div>
                        <div class="col-lg-4"><b>Province: </b> {{$application->jobseeker_info->province}} </div>
                        <div class="col-lg-4"><b>Country:</b> {{$application->jobseeker_info->country}} </div>
                    </div>
                    <b>Skills: </b> 
                    @for ($i = 0; $i < sizeof($application->jobseeker_info->user_skills); $i++)
                        {{
                            $i != (sizeof($application->jobseeker_info->user_skills) - 1) ?
                            $application->jobseeker_info->user_skills[$i]->skill_name . ", " :
                            $application->jobseeker_info->user_skills[$i]->skill_name
                        }}
                    @endfor
                </td>
                <td>
                    <b>{{$application->jobseeker_info->jobseeker_avg_rate}}</b>/5.00<br>(<b>
                    {{$application->jobseeker_info->total_jobseeker_reviews . ' '}}</b>reviews)
                </td>
                <td>
                    <b>{{ "$" . number_format($application->bid_value, 2)}}</b><br>in
                    <b>{{$application->bid_completion_day . " " }}</b> days
                </td>
                <td>
                    {{-- If current user is employer of this job post --}}
                    @if ( Auth::user() != null && Auth::user()->id == $job->employer_id && $job->status == "open")
                    <form action="{{action('JobController@employerPick', $job->id)}}" method="post">
                        {{csrf_field()}}
                        <input name="_method" type="hidden" value="PUT">
                        <input name="jobseeker_id" type="hidden" value="{{$application->jobseeker_id}}">
                        <button class="btn btn-success" type="submit">Pick</button>
                    </form>
                    @endif

                    @if (Auth::user() != null && Auth::user()->id ==  $application->jobseeker_info->id && $job->status == "open")
                        <a href="{{action('JobApplicationController@edit', $job->id)}}" class="btn btn-primary ml-3">Edit</a>
                    @endif
                </td>
                </tr>
            @endforeach
            </tbody></table>
        {{-- Job Applications end --}} 
        </div>
    </div>
</div>
@endsection

@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#mainTable').DataTable({
            "aaSorting": []//disable initial sort
        });
    });
</script>
@stop
