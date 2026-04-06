<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - JBrothers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/obspalo_designs/css/style.css" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="{{ asset('image/JBlogo.jpg') }}">
</head>
<body>
    @include('layouts.navbar_admin')

    <div class="container mt-4">
        <h1>Welcome, Admin!</h1>
        <hr>

        <h2>Create New Post</h2>
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Post Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter post title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description / Details</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter post details" required></textarea>
            </div>
            <div class="mb-3">
                <label for="images" class="form-label">Images (optional, you can select multiple)</label>
                <input class="form-control" type="file" id="images" name="images[]" accept="image/*" multiple>
            </div>
            <button type="submit" class="btn btn-success">Publish Post</button>
        </form>

        <hr>
        <h3 class="mt-4">Existing Posts</h3>
        <div class="row">
            @php
                $posts = \App\Models\Post::orderBy('created_at', 'desc')->get();
            @endphp

            @foreach($posts as $post)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    @php
                        $images = $post->image ? json_decode($post->image, true) : [];
                    @endphp
                    @if(count($images) > 0)
                        <div id="carouselPost{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($images as $key => $img)
                                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/'.$img) }}" class="d-block w-100" alt="Post Image">
                                </div>
                                @endforeach
                            </div>
                            @if(count($images) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselPost{{ $post->id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselPost{{ $post->id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            @endif
                        </div>
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ $post->description }}</p>
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
