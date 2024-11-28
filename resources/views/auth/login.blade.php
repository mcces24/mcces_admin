<x-app-layout>
    <div class="container">

        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4 flex-column align-items-center ">
                            <img src="assets/img/logo.png" alt="MCC Logo" style="width: 150px;" class="py-2">
                            <a href="login" class="logo d-flex align-items-center w-auto flex-column">
                                <span class="d-none d-lg-block">MCCES</span>
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">

                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Welcome To Admin</h5>
                                    <p class="text-center small">Enter your username & password to login</p>
                                </div>

                                <div style="display: none;" id="alert" class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                                    <span style="font-size: .9rem;" class="message"></span>
                                </div>

                                <form class="row g-3" method="POST" id="loginForm" action="{{ route('login') }}">
                                    @csrf
                                    <div class="col-12">
                                        <label for="email" class="form-label">Username</label>
                                        <div class="input-group has-validation">
                                            <input type="email" name="email" value="{{old('email')}}" class="form-control" id="email">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" id="password">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="form-group" id="captcha_form" style="display: none;">
                                        <label for="captcha">Captcha</label>
                                        <div>
                                            <span id="captcha-image">
                                                <img src="{{ captcha_src('default') }}">
                                            </span>
                                            <button type="button" class="btn btn-link" onclick="reloadCaptcha()"><i class="bi bi-arrow-repeat"></i></button>
                                        </div>
                                        <input type="text" id="captcha" name="captcha" class="form-control mt-2" placeholder="Enter CAPTCHA">
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" value="true" id="remember_me">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button id="loginbtn" class="btn btn-primary w-100" type="submit" >
                                            <span class="spinner-border spinner-border-sm" id="spinner" role="status" aria-hidden="true" style="display: none"></span>
                                            <span id="textbtn">Login</span>
                                        </button>
                                    </div>
                                    {{-- <div class="col-12">
                                        <p class="small mb-0">Don't have account? <a href="/register">Create an account</a></p>
                                    </div> --}}
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </section>

    </div>
</x-app-layout>


<script>
    

    document.addEventListener("DOMContentLoaded", function() {
        var login_attempts =  {{ session('login_attempts', 0) }};

        if (login_attempts >= 3) {
            $('#captcha_form').show();
        } else {
            $('#captcha_form').hide();
        }

        $('#email, #password').on('input', function(e) {
            var id = $(this).attr('id');
            console.log(id);
            validate(id);
        });

        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            $('#loginbtn').attr('disabled', true);

            // check if the input fields are empty
            var email = validate('email');
            var password = validate('password');

            // Check if either #password or #email is not empty
            if ($('#email').val() !== '' && $('#password').val() !== '' && email && password) {
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    beforeSend: function() {
                        $('#spinner').show();
                        $('#textbtn').text('Logging in...');
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status === 'success') {
                            $('#alert').removeClass('bg-danger');
                            $('#alert').addClass('bg-success');
                            $('#alert').show();
                            $('#alert .message').text(response.message);
                            // redirect to dashboard
                            setTimeout(() => {
                                window.location.href = '/home';
                            }, 1000);
                        } else {
                            $('#loginbtn').attr('disabled', false);
                            $('#spinner').hide();
                            $('#textbtn').text('Login');
                            $('#alert').removeClass('bg-success');
                            $('#alert').addClass('bg-danger');
                            $('#alert').show();
                            //if response.errors is an object
                            if (typeof response.errors === 'object') {
                                $.each(response.errors, function(key, value) {
                                    $('#alert .message').text(value);
                                });
                            } else {
                                $('#alert .message').text(response.message);
                            }

                            login_attempts = response.attempts;
                            if (login_attempts >= 3) {
                                $('#captcha_form').show();
                            }
                            reloadCaptcha();
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        $('#loginbtn').attr('disabled', false);
                        $('#alert').removeClass('bg-success');
                        $('#alert').addClass('bg-danger');
                        $('#alert').show();
                        $('#alert .message').text('An error occurred. Please try again later.');
                    }
                });
            } else {
                $('#loginbtn').attr('disabled', false);
                $('#alert').removeClass('bg-success');
            }

            // Optionally, you can proceed to submit the form using AJAX or redirect
            // this.submit(); // Uncomment this to submit the form normally after the alert
        });
    });

    function validate(id) {
        // Validate id == email if value is email
        if (id === 'email' && $('#' + id).val() !== '') {
            var email = $('#' + id).val();
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            if (!emailPattern.test(email)) {
                $('#' + id).addClass('is-invalid');
                $('#' + id).removeClass('is-valid');
                $('#' + id).siblings('.invalid-feedback').html('Please enter a valid email address.');
                return false;
            }
        }
        // Check if either #password or #email is empty
        if ($('#' + id).val() === '') {
            $('#' + id).addClass('is-invalid');
            $('#' + id).removeClass('is-valid');
            $('#' + id).siblings('.invalid-feedback').html('Please enter your ' + id + '.');
        } else {
            $('#' + id).addClass('is-valid');
            $('#' + id).removeClass('is-invalid');
        }
        return true;
    }

    function reloadCaptcha() {
        // Send an AJAX request to get a new CAPTCHA image
        $.ajax({
            url: '/reload-captcha',
            type: 'GET',
            success: function(data) {
                $('#captcha-image').html(data.captcha);
            },
            error: function(xhr, status, error) {
                console.error('Error reloading CAPTCHA:', status, error);
            }
        });
    }

</script>