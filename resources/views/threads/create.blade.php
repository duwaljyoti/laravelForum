@extends('layouts.app')

@section('head-style')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create Thread</div>
                    <div class="panel-body">
                        {!! Form::open(['method' => 'post', 'url' => '/threads']) !!}
                        <div class="form-group">
                            {!! Form::label('channel_id', 'Choose a Channel') !!}
                            <select name='channel_id' id='channel_id' class='form-control' required>
                                <option>Choose Channel</option>
                                @foreach($channels as $channel)
                                    <option value={{ $channel->id }} class='form-control'
                                            {{ old('channel_id') == $channel->id ? 'selected' : ''}}
                                    >
                                        {{ $channel->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            {!! Form::label('title',null,  ['class' => 'control-label']) !!}
                            {!! Form::text('title',null,  ['class' => 'form-control', 'placeholder' => 'Thread Title', 'value' => "{{ old('title') }}", 'required']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('Description',null,  ['class' => 'control-label']) !!}
                            <textarea rows='5' name='body' class='form-control'
                                      placeholder='This sounds more appropriate!!' required>{{ old('body') }}</textarea>
                        </div>

                        <div class="form-group">

                            <div class="g-recaptcha" data-sitekey="6LcEHTYUAAAAAJzI5btY92R50kSeHeuInCx2sMMN"></div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class='btn btn-default'>Submit</button>
                        </div>
                        @if(count($errors))
                            <ul class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

