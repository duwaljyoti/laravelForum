<reply :attribute= "{{ $reply }}" inline-template v-cloak>
	<div id = 'reply-{{ $reply->id }}' class="panel panel-default">
	     <div class="panel-heading">
	     	<div class="level">
		     	<h5 class="flex">
			     	<a href = "{{ route('profile', $reply->owner) }}">{{ $reply->owner->name }}</a>
			     	 said {{ $reply->created_at->diffForHumans() }}
			    </h5>
			    <div>
			    	<favourite :reply="{{ $reply }}"></favourite>
			    </div> 		
	     	</div>

	     </div>
	      <div class="panel-body">
	      	<div v-if='editing'>
		      	<div class="form-group">
					<textarea class = 'form-control' v-model='reply'>
					</textarea>
				</div>	
				<button class='btn btn-primary btn-xs' @click='update'>Update</button>
				<button class='btn btn-xs' @click='editing = false'>Cancel</button>
	      	</div>

	      	<div v-else v-text='reply'></div>    
	     </div>
	     @can ('update', $reply)
		     <div class="panel-footer level">
		     	<button class='btn-xs mr-1' @click='editing = true'>Edit</button>
		     	<button class='btn btn-default btn-xs btn-danger' @click='destroy'>Delete</button>
			</div>
		@endcan			  
	</div>
</reply>	