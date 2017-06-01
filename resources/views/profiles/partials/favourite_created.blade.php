@component('profiles.partials.activity')
    @slot('heading')
        <a href="{{ "{$activity->subject->favourited->path()}" }}">{{ $profileUser->name }}</a> favourited the reply..
    @endslot

    @slot('body')
        {{ $activity->subject->favourited->body }}
    @endslot
@endcomponent