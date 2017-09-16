@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="pageHeader">
					<h1>{{ $profileUser->name }}</h1>
					<small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
                    <p></p>
                    <img src="{{ $profileUser->avatar() }}" height="50" width="50">
                    @can('update', $profileUser)
                        <p>
                            <form action="{{ route('upload-avatar', $profileUser) }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="file" name="avatar">
                            <button type="submit" class="btn btn-primary mr-1 btn-xs">Submit</button>
                            </form>
                        </p>
                    @endcan
				</div>

		        <div class="panel-body">
	        		@forelse($activities as $date => $records)
	        			<div class="h3 page-header">
	        				{{ $date }}
	        			</div>
	        			@foreach($records as $record)
	        				@if(view()->exists("/profiles/partials/{$record->type}"))
		            			@include("profiles.partials.{$record->type}", ['activity' => $record])
	        				@endif
	        			@endforeach
	        		@empty
	        			No Activities recorded for this User.	
	        		@endforelse
		        </div>				
			</div>
		</div>
		
	</div>
@endsection