@extends('layouts.app')

@section('content')

    <div class="container">

        <h3>Edit Post</h3>

        {!! Form::open(['method'=>'post','route' => [ 'admin.posts.update', 'id' => $post->id ]]) !!}

        <div class="form-group">
            {!! Form::label('title',"Title:") !!}
            {!! Form::text('title',  $post->title, ['class'=>'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('content',"Content:") !!}
            <textarea name="content" id="post-tiny">{{$post->content}}</textarea>
            @include('tinymce::tpl')
        </div>

        <div class="form-group">
            {!! Form::submit('Submit',['class'=> 'btn btn-primary']) !!}
        </div>

        {!! Form::close() !!}

    </div>

@stop