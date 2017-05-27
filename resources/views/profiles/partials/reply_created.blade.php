@component('profiles.partials.activity')
    @slot('heading')
        <a href="/profile/{{ $profileUser->name }}">{{ $profileUser->name }}</a>
         commented on 
        <a href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->thread->title }}</a>
    @endslot

    @slot('body')
        <a href = ''>{{ $activity->subject->owner->name }}</a> replied {{ $activity->subject->body }}
    @endslot
@endcomponent