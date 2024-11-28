{{-- face recognation --}}
<button type="button" class="btn btn-primary face-recognation-modal" data-bs-toggle="modal" data-bs-target="#faceRecognation" style="display: none;"></button>
<div class="modal fade" id="faceRecognation" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-body">
            <img style="display: none;" id="inputImageMyFace" src="{{ asset('assets/face_recognition_images/myface.jpg') }}" alt="Face for recognition" width="200"/>
            <img style="display: none;" id="inputImage" src="{{ !empty($user->face_recognition_image) ? asset($user->face_recognition_image) : asset('assets/face_recognition_images/other.png') }}" alt="Face for recognition" width="200"/>
            <h5 class="modal-title text-center">Face Recognation</h5>
            <p class="text-center notes">Please position your face at the camera</p>
            <div class="video-container">
                <video id="video" width="640" height="480" autoplay></video>
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
            <canvas style="display: none" id="canvas" width="200" height="150"></canvas>
            <button style="display: none"  id="startDetection">Start Face Detection</button>
        </div>
    </div>
    </div>
</div>
    
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const startDetectionButton = document.getElementById('startDetection');
        let attempts = 0;
        let isRecognition = getCookie('isRecognition');

        if (document.cookie.indexOf('face_recognition=1') == -1) {
            if (isRecognition == 'true') {
                return;
            } else {
                setTimeout(function(){
                    $('.face-recognation-modal').click();
                }, 1000);

                setTimeout(() => {
                    startDetectionButton.click();
                }, 2000);
            }
        } else {
            return;
        }

        // Store known face embeddings
        let knownFaceEmbeddings = [];

        // Load face-api.js models
        async function loadModels() {
            try {
                console.log("Loading models...");
                await faceapi.nets.ssdMobilenetv1.loadFromUri('/models');
                await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
                await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
                console.log("Models loaded successfully");
            } catch (error) {
                console.error("Error loading models:", error);
            }
        }

        // Access webcam feed
        navigator.mediaDevices.getUserMedia({
            video: true
        }).then(stream => {
            video.srcObject = stream;
        }).catch(err => {
            console.error("Error accessing webcam:", err);
            alert("Error accessing webcam. Please allow access to the camera.");
            // reload
            location.reload();
        });

        // Load the image and extract embeddings for known faces
        async function loadFaceApi() {
            const image = document.getElementById('inputImageMyFace');
            const detections = await faceapi.detectAllFaces(image)
                .withFaceLandmarks()
                .withFaceDescriptors();

            // Get the first face descriptor (embedding)
            if (detections.length > 0) {
                const embedding = detections[0].descriptor;
                const name = '{{ $user->name }}'; // Replace with actual name
                knownFaceEmbeddings.push({ name, embedding });
                console.log('Known face embeddings:', knownFaceEmbeddings);
            } else {
                console.warn('No face detected in the input image');
            }
        }

        async function loadFaceApiMyface() {
            const image = document.getElementById('inputImageMyFace');
            const detections = await faceapi.detectAllFaces(image)
                .withFaceLandmarks()
                .withFaceDescriptors();

            // Get the first face descriptor (embedding)
            if (detections.length > 0) {
                const embedding = detections[0].descriptor;
                const name = 'MCCES Developer'; // Replace with actual name
                knownFaceEmbeddings.push({ name, embedding });
                console.log('Known face embeddings:', knownFaceEmbeddings);
            } else {
                console.warn('No face detected in the input image');
            }
        }

        // Compare embeddings to recognize faces
        function compareEmbeddings(embedding1, embedding2) {
            const distance = faceapi.euclideanDistance(embedding1, embedding2);
            return distance < 0.6; // Adjust threshold for accuracy
        }

        // Recognize face based on embeddings
        async function recognizeFace(detection) {
            const detectedEmbedding = detection.descriptor;
            for (let knownFace of knownFaceEmbeddings) {
                if (compareEmbeddings(detectedEmbedding, knownFace.embedding)) {
                    return knownFace.name;
                }
            }
            return "Unknown";
        }

        // Capture a picture from the webcam and compare it
        async function captureAndCompare() {
            startTimerAndCapture();
            $('.notes').text('Please wait while we recognize your face...');

            if (attempts >= 3) {
                $.ajax({
                    url: "{{ route('logout') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        console.log(response);
                        window.location.href = "{{ route('login') }}";
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }

            // Draw the current video frame to the canvas
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Use the captured canvas image for face detection
            const capturedImage = canvas; // Canvas as input for face-api
            const detections = await faceapi.detectAllFaces(capturedImage)
                .withFaceLandmarks()
                .withFaceDescriptors();

            setTimeout(() => {
                if (detections.length > 0) {
                    detections.forEach(async detection => {
                        // Draw bounding box around detected face
                        const box = detection.detection.box;
                        ctx.beginPath();
                        ctx.rect(box.x, box.y, box.width, box.height);
                        ctx.lineWidth = 2;
                        ctx.strokeStyle = 'blue';
                        ctx.stroke();

                        // Recognize the face
                        const recognizedName = await recognizeFace(detection);
                        ctx.fillStyle = 'blue';
                        ctx.fillText(recognizedName, box.x, box.y - 10);
                        console.log(`Recognized as: ${recognizedName}`);

                        if (recognizedName === '{{ $user->name }}') {
                            //set new cookies recognition_success = true
                            setCookie('isRecognition', 'true', 12);

                            //sweet alert
                            Swal.fire({
                                title: 'Face Recognized',
                                text: 'You have been successfully recognized.',
                                icon: 'success',
                                timer: 3000
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            setCookie('isRecognition', 'false', 12);
                            attempts++;
                            setTimeout(() => {
                                captureAndCompare();
                            }, 3000);
                            $('.notes').text('Face not recognized. Please try again');
                            console.log('Face not recognized.');
                        }
                    });                          
                } else {
                    attempts++;
                    setTimeout(() => {
                        captureAndCompare();
                    }, 3000);
                    $('.notes').text('No face detected. Please position your face at the camera');
                    console.log('No face detected in the captured image.');
                }
            }, 5000);
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

        // Event listener for button to start detection
        startDetectionButton.addEventListener('click', async () => {
            await loadModels();  // Ensure models are loaded
            await loadFaceApi(); // Load known faces
            await loadFaceApiMyface(); // Load known faces
            captureAndCompare(); // Capture and compare the face
        });

        // Prevent modal from closing using JavaScript
        $('#faceRecognation').on('hide.bs.modal', function (e) {
            e.preventDefault(); // Prevent closing the modal
        });

    });
</script>


