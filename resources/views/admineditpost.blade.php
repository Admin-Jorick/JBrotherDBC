<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - JBrothers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="{{ asset('storage/JBlogo.jpg') }}">
    <link href="/obspalo_designs/css/style.css" rel="stylesheet">
    <style>
        body {
            background-color: #001f3f; /* navy blue */
            color: white;
            font-family: Arial, sans-serif;
        }

        /* Card Style */
        .card {
            background-color: #012a57;
            border: 1px solid #FFD700;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.6);
        }

        .card h2 {
            color: #FFD700;
            font-weight: bold;
        }

        /* Form Inputs */
        .form-control {
            background-color: #f8f9fa;
            color: black;
            border-radius: 8px;
        }

        .form-label {
            color: #FFD700;
            font-weight: 600;
        }

        /* Buttons */
        .btn-primary {
            background-color: #FFD700;
            border: none;
            color: #001f3f;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #e6c200;
            color: #fff;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        /* Image Preview */
        .img-fluid {
            border: 2px solid #FFD700;
            border-radius: 8px;
        }

        /* Heading Style */
        h2 {
            border-bottom: 2px solid #FFD700;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <h2>Edit Post</h2>

            <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Post Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required>{{ $post->description }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="images" class="form-label">Add Images (you can select multiple)</label>
                    <input class="form-control" type="file" id="images" name="images[]" multiple accept="image/*">
                </div>

                @php
                    $images = $post->image ? json_decode($post->image, true) : [];
                @endphp

                @if(!empty($images) && count($images) > 0)
                    <div class="mb-3">
                        <p class="text-warning fw-bold">Current Images:</p>
                        <div class="row">
                            @foreach($images as $index => $img)
                                <div class="col-md-3 mb-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <img src="{{ asset('storage/'.$img) }}" 
                                            class="img-fluid rounded mb-2" 
                                            style="max-height: 120px; object-fit: cover;">

                                        <!-- Checkbox para i-mark yung image na ide-delete -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="delete_images[]" value="{{ $index }}" id="deleteImage{{ $index }}">
                                            <label class="form-check-label text-danger" for="deleteImage{{ $index }}">
                                                Delete this
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">Update Post</button>
                <a href="{{ route('admin') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>

</html>
