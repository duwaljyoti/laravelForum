@forelse($threads as $thread)
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href='{{ $thread->path() }}'>
                            @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                            <b>{{ $thread->title }}</b>
                            @else
                            {{ $thread->title }}
                            @endif
                        </a>
                    </h4>
                    <h5><a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a></h5>
                </div>

                <a href={{ $thread->path() }} >
                    {{ $thread->replies_count }}
                    {{ str_plural('reply', $thread->replies_count) }}
                </a>
            </div>
        </div>
        <div class="panel-body">
            <article>
                <div class="body">{{ $thread->body }}</div>
            </article>
            <hr>
        </div>
    </div>
@empty
    No Relevant Threads at the moment
@endforelse