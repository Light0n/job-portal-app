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

        {{-- user info start --}}
        <div class="card mb-3">
            <div class="card-header bg-primary">User Info</div>
            <div class="card-body">
            <div class="row">
                <div class="col-lg-4"><b>Name: </b> {{ $user->first_name ." ". $user->last_name }}</div>
                <div class="col-lg-4"><b>Email: </b> {{$user->email}} </div>
                <div class="col-lg-4"><b>Phone: </b> {{$user->phone}}</div>
            </div>

            <div class="row">
                <div class="col-lg-4"><b>City: </b> {{ $user->city ." ". $user->last_name }}</div>
                <div class="col-lg-4"><b>Province: </b> {{$user->province}} </div>
                <div class="col-lg-4"><b>Country: </b> {{$user->country}}</div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                <b>Skills ({{sizeof($user->user_skills)}}): </b>
                @for ($i = 0; $i < sizeof($user->user_skills); $i++)
                {{
                    $i != sizeof($user->user_skills) - 1?
                    $user->user_skills[$i]->skill_name . ", ":
                    $user->user_skills[$i]->skill_name
                }}
                @endfor
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-lg-6"><b>As Employer </b> <br>
                <b>Reputation</b>{{ 
                " (".$user->total_employer_reviews." reviews): "
                .number_format($user->employer_avg_rate, 2) ."/5.00" }}<br>
                <b>Open Job Posts:</b>{{ " ".sizeof($user->job_posts_open) }}<br>
                <b>Work in Progress:</b>{{ " ".sizeof($user->job_posts_in_progress) }}<br>
                <b>Past Job Posts:</b>{{ " ".sizeof($user->job_posts_past) }}
                </div>
                <div class="col-lg-6"><b>As Jobseeker </b> <br>
                <b>Reputation</b>{{ 
                " (".$user->total_jobseeker_reviews." reviews): "
                .number_format($user->jobseeker_avg_rate, 2) ."/5.00" }}<br>
                <b>Job Applications:</b>{{ " ".sizeof($user->jobs_apply) }}<br>
                <b>Current Work:</b>{{ " ".sizeof($user->jobs_in_progress) }}<br>
                <b>Past Work:</b>{{ " ".sizeof($user->jobs_past) }}
                </div>
            </div>

            
            </div>
        </div>
        {{-- user info end --}}
            <div class="card mb-3">
                <div class="card-header">Dashboard
                    <div class="form-check form-check-inline float-right">
                        <input class="form-check-input" type="radio" id="jobseekerBtn" name="user-type" value="jobseeker">
                        <label class="form-check-label" for="jobseekerBtn">Jobseeker</label>
                    </div>
                    <div class="form-check form-check-inline float-right">
                        <input class="form-check-input" type="radio" id="employerBtn" name="user-type" value="employer" checked>
                        <label class="form-check-label" for="employerBtn">Employer</label>
                    </div>
                </div>

                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        {{-- employer nav --}}
                        <li class="nav-item">
                            <a class="nav-link employer active" id="open-tab" data-toggle="tab" href="" role="tab" aria-controls="open" aria-selected="true">Open</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link employer" id="work-in-progress-tab" data-toggle="tab" href="" role="tab" aria-controls="work-in-progress" aria-selected="false">Work in Progress</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link employer" id="past-job-posts-tab" data-toggle="tab" href="" role="tab" aria-controls="past-job-posts" aria-selected="false">Past Job Posts</a>
                        </li>

                        {{-- jobseeker nav --}}
                        <li class="nav-item">
                            <a class="nav-link jobseeker" id="job-applications-tab" data-toggle="tab" href="" role="tab" aria-controls="job-applications" aria-selected="false">Job Applications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link jobseeker" id="current-work-tab" data-toggle="tab" href="" role="tab" aria-controls="current-work" aria-selected="false">Current Work</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link jobseeker" id="past-work-tab" data-toggle="tab" href="" role="tab" aria-controls="past-work" aria-selected="false">Past Work</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        {{-- employer content --}}
                        <div class="tab-pane fade employer show active" id="open" role="tabpanel" aria-labelledby="home-tab">

                        {{-- table Open start --}}
                         <table class="table table-sm table-striped" id="openTable" style="display:none">
                            <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Budget</th>
                                <th scope="col">BIDs</th>   
                                <th scope="col">AVG BID</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($user->job_posts_open as $job)
                                <tr>
                                <td>{{ $job->title }}</td>
                                <td>${{ number_format($job->estimated_budget, 2) }}</td>
                                <td>{{ $job->number_of_application }}</td>
                                <td>${{ number_format($job->avg_bid, 2) }}</td>
                                <td>
                                <div class="row">
                                    <a href="{{action('JobController@show', $job->id)}}" class="btn btn-success ml-3">View</a>
                                    <a href="{{action('JobController@edit', $job->id)}}" class="btn btn-primary ml-3">Edit</a>
                                    <form action="{{action('JobController@destroy', $job->id)}}" method="post" class="ml-3">
                                    {{csrf_field()}}
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                                </td>
                                </tr>
                            @endforeach
                            </tbody></table>
                        {{-- table Open end --}}

                        </div>
                        <div class="tab-pane fade employer" id="work-in-progress" role="tabpanel" aria-labelledby="profile-tab">

                        {{-- table Work in Progress start --}}
                        <table class="table table-sm table-striped" id="workInProgressTable">
                            <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Jobseeker</th>
                                <th scope="col">BID Value</th>   
                                <th scope="col">BID Time</th>
                                <th scope="col">Actions</th>
                            </tr>
                            
                            </thead>
                            <tbody>
                            @foreach ($user->job_posts_in_progress as $job)
                                <tr>
                                <td>{{ $job->title }}</td>
                                <td>{{ $job->jobseeker_info->email }}</td>

                                @foreach ($job->job_applications as $application)
                                    @if ($application->jobseeker_id == $job->jobseeker_info->id)
                                        <td>${{ number_format($application->bid_value, 2) }}</td>
                                        <td>{{ $application->bid_completion_day }} days</td>
                                    @endif
                                @endforeach        
                                <td>
                                <div class="row">
                                <a href="{{action('JobController@show', $job->id)}}" class="btn btn-success ml-3">View</a>
                                <form action="{{action('JobController@employerAccept', $job->id)}}" method="post" class="ml-3">
                                    {{csrf_field()}}
                                    <input name="_method" type="hidden" value="PUT">
                                    <button class="btn btn-primary" type="submit">Accept</button>
                                </form>

                                <form action="{{action('JobController@employerCancel', $job->id)}}" method="post" class="ml-3">
                                    {{csrf_field()}}
                                    <input name="_method" type="hidden" value="PUT">
                                    <button class="btn btn-danger" type="submit">Cancel</button>
                                </form>
                                </div>
                                </td>
                                </tr>
                            @endforeach
                            </tbody></table>
                        {{-- table Work in Progress end --}}

                        </div>
                        <div class="tab-pane fade employer" id="past-job-posts" role="tabpanel" aria-labelledby="contact-tab">
                        
                        {{-- table Past Job Posts start --}}
                        <table class="table table-sm table-striped" id="pastJobPostsTable">
                            <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">BIDs</th>
                                <th scope="col">Jobseeker</th>   
                                <th scope="col">BID Value</th>
                                <th scope="col">BID Time</th>
                                <th scope="col">Result</th>
                                <th scope="col">Reason</th>
                                <th scope="col">Actions</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach ($user->job_posts_past as $job)
                                <tr>
                                <td>{{ $job->title }}</td>
                                <td>{{ $job->number_of_application }}</td>
                                <td>{{ $job->jobseeker_info->email }}</td>    
                                @foreach ($job->job_applications as $application)
                                    @if ($application->jobseeker_id == $job->jobseeker_info->id)
                                        <td>${{ number_format($application->bid_value, 2) }}</td>
                                        <td>{{ $application->bid_completion_day }} days</td>
                                    @endif
                                @endforeach                           
                                <td class="{{$job->status == "incomplete"? "text-danger" : "text-success"}}">{{ $job->status }}</td>
                                <td>
                                @if ($job->status == "complete")
                                    employer accepted
                                @else
                                    @if ($job->employer_status == "cancelled")
                                        employer cancelled
                                    @else
                                        jobseeker cancelled
                                    @endif
                                @endif
                                </td>
                                <td>
                                    <a href="{{action('JobController@show', $job->id)}}" class="btn btn-success">View</a>
                                </td>
                                </tr>
                            @endforeach
                            </tbody></table>
                        {{-- table Past Job Posts end --}}

                        </div>

                        {{-- jobseeker content --}}
                        <div class="tab-pane fade jobseeker" id="job-applications" role="tabpanel" aria-labelledby="home-tab">
                        
                        {{-- table job-applications start --}}
                        <table class="table table-sm table-striped" id="jobApplicationsTable">
                            <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">BID Value</th>
                                <th scope="col">BID Time</th>
                                <th scope="col">BIDs</th>
                                <th scope="col">AVG BID</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($user->jobs_apply as $job)
                                <tr>
                                <td>{{ $job->title }}</td>  
                                @foreach ($job->job_applications as $application)
                                    @if ($application->jobseeker_id == Auth::user()->id)
                                        <td>${{ number_format($application->bid_value, 2) }}</td>
                                        <td>{{ $application->bid_completion_day }} days</td>

                                        <td>{{ $job->number_of_application }}</td>
                                        <td>${{ number_format($job->avg_bid, 2) }}</td>  
                                        <td>
                                        <div class="row">
                                            <a href="{{action('JobController@show', $job->id)}}" class="btn btn-success ml-3">View</a>
                                            <a href="{{action('JobApplicationController@edit', $job->id)}}" class="btn btn-primary ml-3">Edit</a>
                                            <form action="{{action('JobApplicationController@destroy', $job->id)}}" method="post" class="ml-3">
                                            {{csrf_field()}}
                                            <input name="_method" type="hidden" value="DELETE">
                                            <input name="job_title" type="hidden" value="{{ $job->title }}">
                                            <input name="jobseeker_id" type="hidden" value="{{ $application->jobseeker_id }}">
                                            <button class="btn btn-danger" type="submit">Delete</button>
                                            </form>
                                        </div>  
                                        </td>
                                    @endif
                                @endforeach  
                                </tr>
                            @endforeach
                            </tbody></table>
                        {{-- table job-applications end --}}

                        </div>
                        <div class="tab-pane fade jobseeker" id="current-work" role="tabpanel" aria-labelledby="profile-tab">
                        
                        {{-- table current-work start --}}
                        <table class="table table-sm table-striped" id="currentWorkTable">
                            <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Employer</th>
                                <th scope="col">BID Value</th>
                                <th scope="col">BID Time</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($user->jobs_in_progress as $job)
                                <tr>
                                <td>{{ $job->title }}</td>
                                <td>{{ $job->employer_info->email }}</td>
                                @foreach ($job->job_applications as $application)
                                    @if ($application->jobseeker_id == $job->jobseeker_info->id)
                                        <td>${{ number_format($application->bid_value, 2) }}</td>
                                        <td>{{ $application->bid_completion_day }} days</td>
                                    @endif
                                @endforeach  
                                <td>
                                <div class="row">
                                    <a href="{{action('JobController@show', $job->id)}}" class="btn btn-success ml-3">View</a>
                                    <form action="{{action('JobController@jobseekerCancel', $job->id)}}" method="post" class="ml-3">
                                    {{csrf_field()}}
                                    <input name="_method" type="hidden" value="PUT">
                                    <button class="btn btn-danger" type="submit">Cancel</button>
                                    </form>
                                </div>
                                </td>
                                </tr>
                            @endforeach
                            </tbody></table>
                        {{-- table current-work end --}}

                        </div>
                        <div class="tab-pane fade jobseeker" id="past-work" role="tabpanel" aria-labelledby="contact-tab">
                        
                        {{-- table past-work start --}}
                        <table class="table table-sm table-striped" id="pastWorkTable">
                            <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Employer</th>
                                <th scope="col">BID Value</th>
                                <th scope="col">BID Time</th>
                                <th scope="col">BIDs</th>
                                <th scope="col">Result</th>
                                <th scope="col">Reason</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($user->jobs_past as $job)
                                <tr>
                                <td>{{ $job->title }}</td>                             
                                <td>{{ $job->employer_info->email }}</td>
                                @foreach ($job->job_applications as $application)
                                    @if ($application->jobseeker_id == Auth::user()->id)
                                        <td>${{ number_format($application->bid_value, 2) }}</td>
                                        <td>{{ $application->bid_completion_day }} days</td>
                                    @endif
                                @endforeach  
                                <td>{{ $job->number_of_application }}</td> 
                                <td class="{{$job->status == "incomplete"? "text-danger" : "text-success"}}">{{ $job->status }}</td>
                                <td>
                                @if ($job->status == "complete")
                                    employer accepted
                                @else
                                    @if ($job->employer_status == "cancelled")
                                        employer cancelled
                                    @else
                                        jobseeker cancelled
                                    @endif
                                @endif
                                </td>
                                <td>
                                    <a href="{{action('JobController@show', $job->id)}}" class="btn btn-success">View</a>
                                </td>
                                </tr>
                            @endforeach
                            </tbody></table>
                        {{-- table past-work end --}}

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function() {
        // var user = {!! json_encode($user) !!};
        // console.log(user);
        //print JSON to page
        // $("#content").html(JSON.stringify(user, undefined, 4));

        $('#myTab a').click(function (e) {
            e.preventDefault()

            // disable all tabs
            $('#myTab a').removeClass("active");
            $(this).addClass("active");//enable selected tab

            //disable all tabcontents
            $('#myTabContent div[role="tabpanel"]').removeClass("show active");
            $('#'+$(this).attr('aria-controls')).addClass("show active");//enable correspoding tabcontent
        });

        //radio button employer/jobseeker
        $('input[type=radio][name=user-type]').change(function() {
            if (this.value == 'jobseeker') {
                $('#myTab .employer').hide();
                $('#myTab .jobseeker').show();
                $('#myTabContent .employer').removeClass("show active");
                $('#myTabContent .jobseeker').addClass("show");

                //show default tab
                $('#job-applications-tab').trigger('click');
            }
            else if (this.value == 'employer') {
                $('#myTab .employer').show();
                $('#myTab .jobseeker').hide();
                $('#myTabContent .jobseeker').removeClass("show active");
                $('#myTabContent .employer').addClass("show");

                //show default tab
                $('#open-tab').trigger('click');
            }
        });
        $('#employerBtn').trigger('change');

        // // tables setup
        $('#openTable').DataTable();
        $('#openTable').css('display','table');
        $('#workInProgressTable').DataTable();
        $('#pastJobPostsTable').DataTable();

        $('#jobApplicationsTable').DataTable();
        $('#currentWorkTable').DataTable();
        $('#pastWorkTable').DataTable();
    });
</script>
@stop
