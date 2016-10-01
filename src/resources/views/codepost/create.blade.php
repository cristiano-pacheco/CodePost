@extends('layouts.app')

@section('content')

    <div class="container">

        <h3>Create Post</h3>

        {!! Form::open(['method'=>'post','route'=>['admin.posts.store']]) !!}

        <div class="form-group">
            {!! Form::label('title',"Title:") !!}
            {!! Form::text('title',null, ['class'=>'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('content',"Content:") !!}
            <textarea name="content" id="post-tiny"></textarea>
            @include('tinymce::tpl')
        </div>

        <div class="form-group">
            {!! Form::submit('Submit',['class'=> 'btn btn-primary']) !!}
        </div>

        {!! Form::close() !!}

    </div>

@stop