

@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $post->title }} ( Author: {{ $post->user->name }})</div>
              
                
                <div class="card-body">
                    <p>{{ $post->content }}</p>
                </div>
            </div>

            <!-- Display Comments -->
            <h2 class="mt-3">Comments</h2>
            <ul class="list-group">
                @foreach ($post->comments as $comment)
                    <li class="list-group-item">
                        <strong>{{ $comment->name }}</strong> ({{ $comment->created_at->format('F j, Y, g:i a') }}):
                       <br> {{ $comment->content }}
                    </li>
                @endforeach
            </ul>

            <!-- Comment Form -->
            <div class="card mt-3">
                <div class="card-header">Add a Comment</div>
                <div class="card-body">
                    <form id="comment-form">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email (optional)</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="content">Comment</label>
                            <textarea name="content" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Comment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    
    $('#comment-form').submit(function (e) {
    e.preventDefault();
    
    var formData = $(this).serialize();

    $.ajax({
        type: 'POST',
        url: '/comments', // Create a route for comment creation
        data: formData,
        success: function (response) {
            if (response.success) {
                // Clear the form
                $('#comment-form')[0].reset();

                // Append the new comment to the comments section
                var commentHtml = '<li class="list-group-item">';
                commentHtml += '<strong>' + response.comment.name + '</strong> : <br>';
                commentHtml += response.comment.content;
                commentHtml += '</li>';

                $('ul.list-group').append(commentHtml);
            }
        },
        error: function (xhr, status, error) {
            // Handle errors (e.g., validation errors)
            // You can display error messages or handle errors as needed
        }
    });
});

</script>
@endsection