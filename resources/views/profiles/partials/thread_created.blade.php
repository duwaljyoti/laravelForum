@component('profiles.partials.activity')

    @slot('heading')
         <a href="/profile/{{ $profileUser->name }}">{{ $profileUser->name }}</a>
         posted <a href = {{ $activity->subject->path() }}> {{ $activity->subject->title }}</a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
        @endslot()
@endcomponent
