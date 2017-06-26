@extends('layouts.app')


@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="pageHeader">
					<h1>{{ $profileUser->name }}
					<small>Since {{ $profileUser->created_at->diffForHumans() }}</small></h1>
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