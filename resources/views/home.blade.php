<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JBrothers - Home</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/obspalo_designs/css/style.css" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="{{ asset('storage/JBlogo.jpg') }}">
    <style>
        .carousel-item {
            transition: transform 0.8s ease-in-out;
        }
        /* Lightbox background */
        .lightbox {
            display: none;
            position: fixed;
            z-index: 9999;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
        }

        /* Image inside lightbox */
        .lightbox img {
            display: block;
            margin: auto;
            max-width: 90%;
            max-height: 80%;
            border-radius: 10px;
            animation: zoomIn 0.3s ease;
        }

        /* Animation */
        @keyframes zoomIn {
            from {transform: scale(0.7); opacity: 0;}
            to {transform: scale(1); opacity: 1;}
        }

        /* Close button */
        .lightbox:target {
            display: block;
        }
</style>
</head>
<body>
    
    {{-- Navbar --}}
    @include('layouts.navbar')

    {{-- Content of the Home Page --}}
    <div class="container mt-4 px-2">
        <div class="row">
            {{-- Div in the Left side --}}
            <div class="col-md-8">
                <h3 class="text-center">Band Highlights</h3>
                <hr class="col-md-0">
                {{-- News Feed --}}
                <div>
                    @php
                        $posts = \App\Models\Post::orderBy('created_at','desc')->get();
                    @endphp

                    @if($posts->count() > 0)
                        @foreach($posts as $post)
                            {{-- Post Card --}}
                            <div class="card mb-4 shadow-sm">
                                @php
                                    $images = $post->image ? json_decode($post->image, true) : [];
                                @endphp

                                @if(!empty($images))
                                    <div id="carouselPost{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach($images as $index => $img)
                                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                    <img src="{{ asset('storage/'.$img) }}" class="d-block w-100" alt="Post Image" style="max-height:400px; object-fit:cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                        @if(count($images) > 1)
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselPost{{ $post->id }}" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carouselPost{{ $post->id }}" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        @endif
                                    </div>
                                @endif

                                <div class="card-body">
                                    <h5 class="card-title">{{ $post->title }}</h5>
                                    <p class="card-text">{{ $post->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-muted">No posts available at the moment.</p>
                    @endif
                </div>
            </div>

            {{-- Div in the Right side --}}
            <div class="col-md-4 mt-5">
                <div class="card shadow-lg p-4 w-100" style="max-width: 600px; border-radius: 15px;">
                    <h5 class="text-center">How it startded?</h5>
                    <hr>
                    <p>JBrothers started as a group of friends who shared a 
                        passion for music and performance, and who wanted to establish 
                        their own group. Coming from different backgrounds and experience in other groups, 
                        they decided to combine their talents and creativity. This collaboration led to the 
                        formation of JBrothers DBC, a group dedicated to bringing energy, skill, and excitement 
                        to every performance. Since then, they have grown not only as performers but also as a 
                        close-knit team committed to inspiring others through their art.
                    </p>
                    <a href="#viewImg1">
                        <img src="{{ asset('image/JBoldpic.jpg') }}" 
                            alt="Description of image" 
                            class="card-img-top my-3" 
                            style="border-radius: 10px; cursor: pointer;">
                    </a>
                    <div id="viewImg1" class="lightbox">
                        <a href="#">
                            <img src="{{ asset('image/JBoldpic.jpg') }}">
                        </a>
                    </div>
                    <small>2018.</small>
                    <p></p>
                    <p></p>
                    <p></p>
                    <p></p>
                    <p></p>
                    <p></p>

                    <h5 class="text-center">Visit JBrothers DBC</h5>
                    <hr>
                    <p>Visit JBrothers DBC and experience the energy and talent of our dynamic band! Founded by a group of friends who shared a passion for music and performance, 
                        JBrothers DBC has grown into a team dedicated to bringing exciting shows and inspiring teamwork to every event. 
                        JBrothers is located in Purok Gemilina, Pilapilan, Yati, Liloan, 6002 Cebu where our Bandroom serves as the heart of our creativity 
                        and practice. Whether you’re attending one of our performances or just stopping by our Bandroom, you’ll see the 
                        dedication and spirit that make JBrothers DBC special. Check out our location below on Google Maps and come 
                        join us for a musical experience like no other!</p>
                    <p></p>
                    <a href="#viewMap1">
                        <img src="{{ asset('image/JBGooglemap1.jpg') }}"
                            alt="Description of image"
                            class="card-img-top my-3"
                            style="border-radius: 10px; cursor: pointer;">
                    </a>

                    <div id="viewMap1" class="lightbox">
                        <a href="#">
                            <img src="{{ asset('image/JBGooglemap1.jpg') }}">
                        </a>
                    </div>
                    <small>This is view in Google Map.</small>

                    <a href="#viewMap2">
                        <img src="{{ asset('image/JBGooglemap.jpg') }}"
                            alt="Description of image"
                            class="card-img-top my-3"
                            style="border-radius: 10px; cursor: pointer;">
                    </a>

                    <div id="viewMap2" class="lightbox">
                        <a href="#">
                            <img src="{{ asset('image/JBGooglemap.jpg') }}">
                        </a>
                    </div>
                    <small>This is the where the BandRoom of JBrothersDBC.</small>
                    <p></p>
                    <p></p>
                    <p></p>
                    <p></p>
                    <p></p>
                    <p></p>

                    <h5 class="text-center">How to Join</h5>
                    <hr>
                    <p>We were Working for that.</p>

                </div>
            </div>
        </div>
    </div>

    <!-- Admin Login Modal -->
    <div class="modal fade" id="adminLoginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- Gradient Header -->
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0d6efd, #6610f2);">
                    <h5 class="modal-title fw-bold">Admin Login</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body p-4" style="background: #f8f9fa;">
                    <form method="POST" action="{{ url('/admin-login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control rounded-pill px-3" name="username" placeholder="Enter username" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control rounded-pill px-3" name="password" placeholder="Enter password" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn text-white fw-semibold rounded-pill"
                                style="background: linear-gradient(135deg, #0d6efd, #6610f2);">
                                Login
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.altKey && e.key === 'a') {
                var myModal = new bootstrap.Modal(document.getElementById('adminLoginModal'));
                myModal.show();
            }
        });
    </script>

</body>

<footer class="text-white text-center p-4 mt-5" style="background: linear-gradient(to right, #1e3c72, #2a5298);">
    <div class="container text-center">
        <h5>JBrothers DBC</h5>
        <p>Performing Arts Organization</p>
        <p>📞 09324994185 | 📧 jbrothersdbc@gmail.com</p>
        <hr class="bg-light">
        <small>© 2026 All Rights Reserved</small>
    </div>
</footer>
</html>
