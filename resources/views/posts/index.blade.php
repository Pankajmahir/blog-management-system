@extends('layout')
@section('content')

<div class="container mt-5 mb-5">
    <div class="row">
    @foreach($posts as $post)
        <div class="col-sm-3 mb-3 mt-3">
        <div class="card">
            <div class="card-body">
            <h6 class="card-title">{{ Str::limit($post->title, 20) }}</h6>
            <h6 class="card-subtitle mb-2 text-muted">{{ $post->user->name }}</h6>
            <p class="card-text">{{ Str::limit($post->content, 100) }}</p>
            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">Read More</a>
                @if(Auth::check() && Auth::user()->id === $post->user_id)
                    <button class="btn btn-primary edit-post" data-post-id="{{ $post->id }}"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger delete-post" data-post-id="{{ $post->id }}"><i class="fas fa-trash-alt"></i></button>
                @endif
            </div>
        </div>
        </div>
    @endforeach
    
  </div>
  {{ $posts->links('pagination::bootstrap-4') }}
</div>
  

  @endsection

  @section('script')
<script>
    
    $('.edit-post').click(function () {
        var postId = $(this).data('post-id');
        window.location.href = '/posts/' + postId + '/edit';
    });

    // Delete a post
    $('.delete-post').click(function () {
        var postId = $(this).data('post-id');
        var postContainer = $(this).closest('.col-sm-3');

        if (confirm('Are you sure you want to delete this post?')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'DELETE',
                url: '/posts/' + postId,
                success: function (response) {
                    postContainer.remove();
                },
                error: function (xhr, status, error) {
                    // Handle errors
                }
            });
        }
    });

</script>
@endsection

