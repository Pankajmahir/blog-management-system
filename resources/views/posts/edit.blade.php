@extends('layout')

@section('content')
<div class="container">
    <div id="response"></div>
    <h1>Edit Post</h1>
    <form id="edit-post-form" data-post-id="{{ $post->id }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}">
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" id="content" name="content" rows="4">{{ $post->content }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>
</div>
@endsection

@section('script')
<script>

    $('#edit-post-form').submit(function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var postId = $(this).data('post-id');

        $.ajax({
            type: 'PUT',
            url: '/posts/' + postId,
            data: formData,
            success: function (response) {
                $('#response').html('<div class="alert alert-success">' + response.message + '</div>');
            // $('#edit-post-form')[0].reset();
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    var errors = JSON.parse(xhr.responseText).errors;
                    var errorHtml = '<div class="alert alert-danger"><ul>';
                    for (var key in errors) {
                        errorHtml += '<li>' + errors[key] + '</li>';
                    }
                    errorHtml += '</ul></div>';
                    $('#response').html(errorHtml);
                } else {
                    $('#response').html('<div class="alert alert-danger">An error occurred.</div>');
                }
            }
        });
    });

</script>
@endsection