@extends('layouts.app')

@section('head-style')
    <link href="{{ asset('css/vendor/jquery.atwho.css') }}" rel="stylesheet">
@endsection

@section('content')
    <thread-view
         :thread = "{{ $thread }}"
         path = "{{ $thread->path() }}"
         inline-template
         >
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    @include('threads._subject')
                    <hr>
                    <replies
                        @reply-created="replyCount++"
                        @removed="replyCount--"
                    ></replies>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            This thread was published at {{ $thread->created_at->diffForHumans() }}
                            By <a href = '#'>{{ $thread->creator->name }}</a><p>
                            <div v-text='replyCount'></div>
                            {{ str_plural('comment', $thread->replies_count) }}
                            <p></p>
                            <subscription
                                    :is-subscribed={{ json_encode($thread->isSubscribed) }}
                                    v-if="signedIn"
                            ></subscription>
                            <button type="button" class="btn btn-default btn-md"
                                    v-if="authorize('isAdmin')"
                                    @click="toggleLock"
                            >
                                <span class="glyphicon glyphicon-lock" v-text="lockDisplay"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>       
        </div>
    </thread-view>
@endsection
