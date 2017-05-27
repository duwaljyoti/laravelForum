<div class="panel panel-default">
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
</div>