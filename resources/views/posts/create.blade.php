
@extends('layout')
@section('content')
<div class="container">
    <div id="response"></div>
    <h1>Create Post</h1>
    <form id="create-post-form">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title">
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" id="content" name="content" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Post</button>
    </form>
    
</div>
@endsection

@section('script')
<script>
    
    $('#create-post-form').submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '/posts',
            data: formData,
            success: function (response) {
                $('#response').html('<div class="alert alert-success">' + response.message + '</div>');
                $('#create-post-form')[0].reset();
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
