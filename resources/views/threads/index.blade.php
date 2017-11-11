@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('threads._list')
            </div>
            <div class="col-md-4">
                <div class="panel panel-heading">
                    <div class="panel-heading">
                        Search
                    </div>
                    <div class="panel-body">
                        <form action="/threads/search">
                            <div class="form-group">
                                <input name="query" class="form-control" placeholder="Search..">
                            </div>
                            <button type="submit" class="btn-primary">Search</button>
                        </form>
                    </div>
                </div>

                <div class="panel panel-heading">
                    <div class="panel-heading">
                        Trending Threads
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            @forelse($trendingThreads as $thread)
                                <li class="list-group-item">
                                    <a href="{{ url($thread->path) }}">
                                        {{ $thread->title }}
                                    </a>
                                </li>
                            @empty
                                No Trending Threads
                            @endforelse
                        </ul>

                    </div>
                </div>
            </div>
        </div>
        {{ $threads->links() }}
    </div>
@endsection
