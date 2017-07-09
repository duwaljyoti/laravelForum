@extends('layouts.app')

@section('content')
    <thread-view 
         :replies-counter="{{ $thread->replies_count }}"
         inline-template 
         >
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                                <span class="flex">
                                    <a href = "{{ route('profile', $thread->creator) }}">
                                        {{ $thread->creator->name }}
                                    </a> posted {{ $thread->title }}                                
                                </span>
                                @can('update', $thread)
                                    <form action={{ $thread->path() }} method='POST'>
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type='submit' class='btn btn-link'>Delete</button>
                                    </form>

                                @endcan    
                            </div>    
                        </div>
                        <div class="panel-body">
                            {{ $thread->body }}
                        </div>
                    </div>
                    <hr>

                    <replies 
                        :data="{{ $thread->replies }}"
                        @reply-created="replyCount++"
                        @removed="replyCount--"
                    ></replies>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            This thread was published at {{ $thread->created_at->diffForHumans() }}
                            By <a href = '#'>{{ $thread->creator->name }}</a><p>
                            {{-- {{ $thread->replies_count }} --}}
                            <div v-text='replyCount'></div>
                            {{ str_plural('comment', $thread->replies_count) }}

                        </div>
                    </div>                        
                </div>
            </div>       
        </div>
    </thread-view>
@endsection
