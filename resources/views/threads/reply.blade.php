<div id = 'reply-{{ $reply->id }}' class="panel panel-default">
     <div class="panel-heading">
     	<div class="level">
	     	<h5 class="flex">
		     	<a href = "{{ route('profile', $reply->owner) }}">{{ $reply->owner->name }}</a>
		     	 said {{ $reply->created_at->diffForHumans() }}
		    </h5>
		    <div>
				<form method='post' action="/replies/{{ $reply->id }}/favourite">
					{{ csrf_field() }}
					<button type='submit' class='btn btn-default' {{ $reply->isFavourited() ? 'disabled' : '' }}>
						{{ $reply->favourites_count }}
						Like
					</button>					
				</form>
		    </div> 		
     	</div>

     </div>
      <div class="panel-body">
            {{ $reply->body }}
            <hr>
     </div>
     @can ('update', $reply)
	     <div class="panel-footer level">
	     	<button class='btn-xs mr-1'>Edit</button>
			<form method='post' action="/replies/{{ $reply->id }}">
				{{ method_field('DELETE') }}
				{{ csrf_field() }}
				<button class='btn btn-default btn-xs btn-danger'>
					Delete
				</button>
			</form>	   
		</div>
	@endcan			  
</div>