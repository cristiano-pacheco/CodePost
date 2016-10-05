@extends('layouts.app')

@section('content')

    <div class="container">

        <h3>Posts Deleted</h3>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@stop