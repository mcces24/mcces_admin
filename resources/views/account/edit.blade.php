@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <h1>Account Settings</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="profile">Account</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
          <div class="col-xl-4">
            <div class="card">
              <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                <img src="{{ !empty($user->profile_pic) ? asset($user->profile_pic) : asset('assets/img/logo.png') }}" alt="Profile" class="rounded-circle">
                <h2>{{$user->name}}</h2>
                <h3>{{$user->position}}</h3>
              </div>
            </div>
  
          </div>
  
          <div class="col-xl-8">
            <div class="card">
              <div class="card-body pt-3">
                <!-- Bordered Tabs -->
                <ul class="nav nav-tabs nav-tabs-bordered">
  
                  <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                  </li>
  
                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                  </li>
  
                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                  </li>

                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-face-recognation">Face Recognation</button>
                  </li>
  
                </ul>
                <div class="tab-content pt-2">
                  <div class="tab-pane fade show active profile-overview" id="profile-overview">
                    <h5 class="card-title">About</h5>
                    <p class="small fst-italic">{{$user->about}}</p>
  
                    <h5 class="card-title">Profile Details</h5>
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label ">Full Name</div>
                      <div class="col-lg-9 col-md-8">{{$user->name}}</div>
                    </div>
  
                    {{-- <div class="row">
                      <div class="col-lg-3 col-md-4 label">Company</div>
                      <div class="col-lg-9 col-md-8">{{$user->position}}</div>
                    </div> --}}
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Position</div>
                      <div class="col-lg-9 col-md-8">{{$user->position}}</div>
                    </div>
  
                    {{-- <div class="row">
                      <div class="col-lg-3 col-md-4 label">Country</div>
                      <div class="col-lg-9 col-md-8">USA</div>
                    </div> --}}
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Address</div>
                      <div class="col-lg-9 col-md-8">{{$user->address}}</div>
                    </div>
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Contact</div>
                      <div class="col-lg-9 col-md-8">{{$user->contact}}</div>
                    </div>
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Email</div>
                      <div class="col-lg-9 col-md-8">{{$user->email}}</div>
                    </div>
  
                  </div>
  
                  <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
  
                    <!-- Profile Edit Form -->
                    <form id="profileUpdateForm" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="row mb-3">
                            <label for="profile_pic" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                            <div class="col-md-8 col-lg-9">
                                <img id="profileImagePreview" src="{{ !empty($user->profile_pic) ? asset($user->profile_pic) : asset('assets/img/logo.png') }}" alt="Profile">
                                <div class="pt-2">
                                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*" style="display: none;">
                                    <a href="#" class="btn btn-primary" id="profileImageBtn" title="Upload new profile image"><i class="bi bi-upload"> Upload</i></a>
                                    <div class="invalid-feedback"></div>
                                    <small class="text-muted">Upload a profile image not more than 2MB</small>
                                </div>
                            </div>
                        </div>
                    
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="name" value="{{$user->name}}" type="text" class="form-control" id="name" minlength="2" maxlength="100" placeholder="Enter your full name">
                                <div class="invalid-feedback"></div>
                                <small class="text-muted">Please provide your full name (e.g., John Doe)</small>
                            </div>
                        </div>
                    
                        <div class="row mb-3">
                            <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                            <div class="col-md-8 col-lg-9">
                                <textarea name="about" class="form-control" id="about" style="height: 100px" maxlength="500" placeholder="Tell us a little about yourself">{{$user->about}}</textarea>
                                <div class="invalid-feedback"></div>
                                <small class="text-muted">Describe yourself in a few sentences (max 500 characters)</small>
                            </div>
                        </div>
                    
                        <div class="row mb-3">
                            <label for="position" class="col-md-4 col-lg-3 col-form-label">Position</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="position" value="{{$user->position}}" type="text" class="form-control" id="position" minlength="2" maxlength="100" placeholder="Enter your position (e.g., Manager)">
                                <div class="invalid-feedback"></div>
                                <small class="text-muted">Enter a valid position (e.g., Teacher, Head Teacher, School President)</small>
                            </div>
                        </div>
                    
                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="address" value="{{$user->address}}" type="text" class="form-control" id="address" minlength="5" maxlength="255" placeholder="Enter your address">
                                <div class="invalid-feedback"></div>
                                <small class="text-muted">Enter a valid address (e.g., 123 Main St, City, Country)</small>
                            </div>
                        </div>
                    
                        <div class="row mb-3">
                            <label for="contact" class="col-md-4 col-lg-3 col-form-label">Contact</label>
                            <div class="col-md-8 col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text">+63</span>
                                    <input name="contact" value="{{ $user->contact }}" type="number" class="form-control" id="contact" pattern="^\d{10}$" title="Enter a valid Philippine phone number with 9 digits (e.g., 9458445982)">
                                </div>
                                <div class="invalid-feedback"></div>
                                <small class="text-muted">Enter a valid phone number (e.g., 9458445982)</small>
                            </div>
                        </div>
                    
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="email" value="{{$user->email}}" type="email" class="form-control" id="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Enter a valid email address (e.g., user@example.com)">
                                <div class="invalid-feedback"></div>
                                <small class="text-muted">Enter a valid email address (e.g., user@example.com)</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="face_recognition_flg" class="col-md-4 col-lg-3 col-form-label">Face Recognition</label>
                            <div class="col-md-8 col-lg-9 mt-2">
                                <input name="face_recognition_flg" type="checkbox" class="form-check-input" id="face_recognition_flg" {{$user->face_recognition_flg == 1 ? 'checked' : ''}}>
                                <div class="invalid-feedback"></div>
                                <small class="text-muted
                                ">Enable face recognition for secure login</small>
                            </div>
                        </div>                   
                    
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span class="spinner-border spinner-border-sm" id="spinner" role="status" aria-hidden="true" style="display: none;"></span>
                                Save Changes
                            </button>
                        </div>
                    </form>
                    <!-- End Profile Edit Form -->
  
                  </div>
    
                  <div class="tab-pane fade pt-3" id="profile-change-password">
                    <!-- Change Password Form -->
                    <form id="updatePasswordForm" method="post">
                        @csrf
                        @method('patch')
                        <div class="row mb-3">
                            <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                            <div class="col-md-8 col-lg-9">
                            <input name="current_password" type="password" class="form-control" id="current_password">
                            <div class="invalid-feedback"></div>
                            <small class="text-muted">Enter old password</small>
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <label for="new_password" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="new_password" type="password" class="form-control" id="new_password">
                                <div class="invalid-feedback"></div>
                                <small class="text-muted">Enter a password</small>
                                <div id="password-strength" class="mt-2">
                                    <div class="progress mt-1" style="height: 5px; display: none;">
                                        <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <label for="new_password_confirmation" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                            <div class="col-md-8 col-lg-9">
                            <input name="new_password_confirmation" type="password" class="form-control" id="new_password_confirmation">
                            <div class="invalid-feedback"></div>
                            <small class="text-muted">Verify password</small>
                            </div>
                        </div>
    
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span class="spinner-border spinner-border-sm" id="spinner" role="status" aria-hidden="true" style="display: none;"></span>
                                Save Changes
                            </button>
                        </div>
                    </form><!-- End Change Password Form -->
  
                  </div>
                  
                  <div class="tab-pane fade pt-3" id="profile-face-recognation">
                    <!-- Face Recognation Form -->
                    <div class="col-md-8 col-lg-9">
                        <!-- Button to open the camera -->
                        <button type="button" id="openCameraButton" class="btn btn-primary btn-sm">Register Face</button>
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">Ensure your face is visible in the camera for recognition.</small>
                        <!-- Video preview for webcam -->
                        <div class="video-container" style="display: none; position: relative; margin-top: 20px !important;">
                            <video id="video" autoplay muted></video>
                            <canvas id="canvas" style="display: none;"></canvas>
                            <div class="face-frame"></div>
                            <!-- Timer overlay -->
                            <div id="timer" style="
                                display: none;
                                position: absolute;
                                top: 20%;
                                left: 50%;
                                transform: translate(-50%, -50%);
                                font-size: 48px;
                                font-weight: bold;
                                color: white;
                                background: rgba(0, 0, 0, 0.5);
                                padding: 20px;
                                border-radius: 10px;
                                z-index: 1000;
                                text-align: center;">
                            </div>
                        </div>
                    </div>
                        
                    <!-- End Face Recognation Form -->
                  </div>
  
                </div><!-- End Bordered Tabs -->
  
              </div>
            </div>
  
          </div>
        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#profileImageBtn').on('click', function (e) {
                e.preventDefault();
                $('#profile_pic').click();
            });
    
            $('#profile_pic').on('change', function () {
                var file = $(this)[0].files[0];
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#profileImagePreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            });
    
            $('#profileUpdateForm, #updatePasswordForm').on('submit', function (e) {
                e.preventDefault();
                var changePass = $(this).attr('id') === 'updatePasswordForm';
                var thisId = "#" + $(this).attr('id');
                var formData = new FormData(this);
                if (changePass) {
                    formData.append('type', 'changePass');
                }
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('#spinner').css('display', 'inline-block');
                        $('#submitBtn').prop('disabled', true);
                    },
                    success: function (response) {
                        console.log(this);
                        $(thisId).find('.invalid-feedback').html('');
                        $(thisId).find('.text-muted').css('color', '');
                        $(thisId).find('.text-muted').hide();
                        $(thisId).find('.is-invalid').removeClass('is-invalid');
                        if (response.success) {
                            console.log(response);
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            $.each(response.errors, function (key, value) {
                                var element = $('#' + key);
                                element.addClass('is-invalid');
                                element.siblings('.invalid-feedback').html(value);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Log the error details if AJAX request fails
                        console.log("Error details:", status, error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error processing your request.',
                            icon: 'error',
                            confirmButtonText: 'Try Again'
                        })
                    },
                    complete: function() {
                        // Hide the spinner and enable the submit button after the request completes
                        $('#spinner').css('display', 'none');
                        $('#submitBtn').prop('disabled', false);
    
                        $(thisId).find('.text-muted').each(function(index, element) {
                            if ($(element).siblings('.invalid-feedback').html() == '') {
                                $(element).show();
                            }
                        });
    
                    }
                });
            });
    
            $('#new_password').on('input', function () {
                var password = $(this).val();
                var strength = getPasswordStrength(password);
                
                var $strengthBar = $('#strength-bar');
                var $feedback = $('#password-strength small');
    
                // Update the strength bar and feedback text based on strength
                if (strength.score === 0) {
                    $strengthBar.css('width', '0%').removeClass().addClass('progress-bar');
                    $feedback.text('Enter a password').removeClass().addClass('text-muted');
                } else if (strength.score <= 1) {
                    $strengthBar.css('width', '25%').removeClass().addClass('progress-bar bg-danger');
                    $feedback.text('Weak').removeClass().addClass('text-danger');
                } else if (strength.score === 2) {
                    $strengthBar.css('width', '50%').removeClass().addClass('progress-bar bg-warning');
                    $feedback.text('Moderate').removeClass().addClass('text-warning');
                } else if (strength.score === 3) {
                    $strengthBar.css('width', '75%').removeClass().addClass('progress-bar bg-info');
                    $feedback.text('Strong').removeClass().addClass('text-info');
                } else if (strength.score === 4) {
                    $strengthBar.css('width', '100%').removeClass().addClass('progress-bar bg-success');
                    $feedback.text('Very Strong').removeClass().addClass('text-success');
                }
    
                if (password != '') {
                    $('.progress').show();
                } else {
                    $('.progress').hide();
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openCameraButton = document.getElementById('openCameraButton');
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const videoContainer = document.querySelector('.video-container');
            const timer = document.getElementById('timer');
    
            let videoStream = null;
    
            // Load face-api.js models
            async function loadModels() {
                try {
                    console.log("Loading face detection models...");
                    await faceapi.nets.ssdMobilenetv1.loadFromUri('/models');
                    await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
                    await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
                    console.log("Models loaded successfully");
                } catch (error) {
                    console.error("Error loading models:", error);
                }
            }
    
            // Open camera when the button is clicked
            openCameraButton.addEventListener('click', async () => {
                // Load models before starting detection
                await loadModels();
    
                try {
                    // Show video container
                    videoContainer.style.display = 'flex';
    
                    // Access the webcam
                    videoStream = await navigator.mediaDevices.getUserMedia({ video: true });
                    video.srcObject = videoStream;
    
                    // Detect faces continuously
                    detectFaceInCamera();
                } catch (err) {
                    console.error("Error accessing the webcam:", err);
                    alert("Unable to access the webcam. Please check your device settings.");
                }
            });
    
            // Detect faces in the video feed
            async function detectFaceInCamera() {
                
                const ctx = canvas.getContext('2d');
    
                // Continuously check for faces
                const detectionInterval = setInterval(async () => {
                    const detections = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors();
    
                    if (detections.length > 0) {
                        console.log("Face detected!");
                        $('.text-muted').text('Please position your face in the camera');
                        startTimerAndCapture();
                        // Stop video stream and capture the image
                        clearInterval(detectionInterval);
                        
                        setTimeout(() => {
                            captureImage(detections[0]); 
                            stopWebcam();                           
                        }, 5000);

                    } else {
                        $('.text-muted').text('No face detected');
                        console.log("No face detected");
                    }
                }, 2000); // Check every 2000ms (2 seconds)
            }

            function startTimerAndCapture() {
                let countdown = 5; // Start from 5 seconds
                timer.style.display = 'block';
                timer.textContent = countdown;

                const interval = setInterval(() => {
                    countdown -= 1;
                    timer.textContent = countdown;

                    if (countdown <= 0) {
                        clearInterval(interval);
                        timer.style.display = 'none';
                    }
                }, 1000); // Update every second
            }

    
            // Capture the image from the video feed
            function captureImage(detection) {
                const ctx = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                // Draw the current video frame onto the canvas
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Convert the canvas content to a data URL (image)
                const dataURL = canvas.toDataURL('image/png');

                // Prepare the Base64 string for upload
                const imageData = dataURL.replace(/^data:image\/\w+;base64,/, "");
                
                // Send the image to the server
                $.ajax({
                    url: '/profile/upload-face-image', // Backend endpoint
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        face_recognition_image: imageData // Include CSRF token for security
                    },
                    success: function (response) {
                        if (response.success) {
                            alert("Face registered successfully!");
                        } else {
                            alert("Failed to register face. Please try again.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error uploading image:", error);
                        alert("An error occurred while uploading the image.");
                    }
                });
            }

            // Stop the webcam feed
            function stopWebcam() {
                if (videoStream) {
                    const tracks = videoStream.getTracks();
                    tracks.forEach(track => track.stop());
                    video.srcObject = null;
                    videoStream = null;
                }
    
                // Hide the video container
                videoContainer.style.display = 'none';
            }
        });
    </script>
@endsection
