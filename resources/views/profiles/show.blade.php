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
	        		@foreach($activities as $date => $records)
	        			<div class="h3 page-header">
	        				{{ $date }}
	        			</div>
	        			@foreach($records as $record)		        			
	            			@include("profiles.partials.{$record->type}", ['activity' => $record])
	        			@endforeach
	        		@endforeach
		        </div>				
			</div>
		</div>
		
	</div>
@endsection