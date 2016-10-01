@extends('layouts.app')

@section('content')

    <div class="container">

        <h3>Posts</h3>

        <a href="{{ route('admin.posts.create') }}" class="btn btn-default">Create Post</a>

        <br><br>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                    <td>
                        <a href="{{ route('admin.posts.edit', ['id' => $post->id]) }}"
                           class="btn btn-default">Edit</a>

                        <a href="{{ route('admin.posts.delete', ['id' => $post->id]) }}"
                           class="btn btn-default">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@stop