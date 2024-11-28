@extends('layouts.app')
@section('content')
<div class="pagetitle">
    <h1>Add Portal User</h1>
    <nav>
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/users">Portal Users</a></li>
          <li class="breadcrumb-item active">Add User</li>
      </ol>
    </nav>
</div>
<section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Add User Form</h5>

            <!-- General Form Elements -->
            <form id="addNewUsers" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="row mb-3">
                  <label for="profile" class="col-sm-2 col-form-label">Avatar</label>
                    <div class="col-sm-10">
                        <img id="profileImagePreview" src="{{ asset('assets/img/logo.png') }}" alt="Profile" width="100">
                        <div class="pt-2">
                            <input type="file" name="profile" id="profile" accept="image/*" style="display: none;">
                            <a href="#" class="btn btn-primary" id="profileImageBtn" title="Upload new profile image"><i class="bi bi-upload"> Upload</i></a>
                            <div class="invalid-feedback"></div>
                            <small class="text-muted">Upload a profile image not more than 2MB</small>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" id="name" class="form-control" >
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">
                            Please provide full name (e.g., John Doe)
                        </small>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="address" class="col-sm-2 col-form-label">Address</label>
                    <div class="col-sm-10">
                        <input type="text" name="address" id="address" class="form-control" >
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">
                            Please provide address (e.g., 123 Main Street, New York, USA)
                        </small>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="contact" class="col-sm-2 col-form-label">Contact</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-text">+63</span>
                            <input name="contact" type="number" class="form-control" id="contact" pattern="^\d{10}$"  title="Enter a valid Philippine phone number with 9 digits (e.g., 9458445982)">
                        </div>
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">Enter a valid phone number (e.g., 9458445982)</small>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="role" class="col-sm-2 col-form-label">Portal</label>
                    <div class="col-sm-10">
                        <select class="form-select" multiple="" name="role" id="role" aria-label="multiple select example" >
                            <option disabled="">Select Role</option>
                            <option value="Guidance Office">Guidance Office</option>
                            <option value="BSIT Portal">BSIT Portal</option>
                            <option value="BSBA Portal">BSBA Portal</option>
                            <option value="BSHM Portal">BSHM Portal</option>
                            <option value="BSED Portal">BSED Portal</option>
                            <option value="BEED Portal">BEED Portal</option>
                            <option value="Registrar Office">Registrar Office</option>
                            <option value="ID Section">ID Section</option>
                            <option value="COR Section">COR Section</option>
                        </select>
                        <div class="invalid-feedback"></div>
                        <small class="text-muted mb-3">
                            Select 1 portal where the user belongs
                        </small>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="department" class="col-sm-2 col-form-label">Department</label>
                    <div class="col-sm-10">
                        <input name="department" value="" type="text" class="form-control" id="department" >
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">
                            Please provide department (e.g., BSIT Department)
                        </small>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                    <select class="form-control" name="status" id="status" >
                        <option selected="" disabled="">Select Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <small class="text-muted">
                        Select user's status
                    </small>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="username" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-md-8 col-lg-10">
                        <input name="username" type="email" class="form-control" id="username"  pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Enter a valid email address (e.g., user@example.com)">
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">Enter a valid email address (e.g., user@example.com)</small>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="new_password" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-md-10 col-lg-10">
                        <input name="password" type="password" class="form-control" id="password" >
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">Enter a password</small>
                        <div id="password-strength" class="mt-2">
                            <div class="progress mt-1" style="height: 5px; display: none;">
                                <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-12 text-center">
                    <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary">Submit Form</button>
                    </div>
                </div>

            </form><!-- End General Form Elements -->

          </div>
        </div>

      </div>
    </div>
</section>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('#profileImageBtn').on('click', function (e) {
            e.preventDefault();
            $('#profile').click();
        });

        $('#profile').on('change', function () {
            var file = $(this)[0].files[0];
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#profileImagePreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        });

        $('#addNewUsers').on('submit', function (e) {
            e.preventDefault();
            var thisId = "#" + $(this).attr('id');
            var formData = new FormData(this);
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
                                window.location.href = '/users';
                            }
                        });
                    } else {
                        $.each(response.errors, function (key, value) {
                            var element = $('#' + key);
                            element.addClass('is-invalid');
                            if (element.parent().siblings('.invalid-feedback').html() == '') {
                                element.parent().siblings('.invalid-feedback').html(value).show();
                            } else {
                                element.siblings('.invalid-feedback').html(value);
                            }
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

        $('#password').on('input', function () {
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
@endsection
