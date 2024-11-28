@extends('layouts.app')
@section('content')
  <div class="pagetitle">
    <h1>Portal Users</h1>
    <nav>
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="users">Portal Users</a></li>
          <li class="breadcrumb-item active">List</li>
      </ol>
    </nav>
  </div>
  <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Search User</h5>
                <a href="users/add" class="btn btn-success btn-sm">Add New User</a>
              </div>
              <form method="GET">
                <div class="row mb-3">
                  <label for="name" class="col-sm-1 col-form-label">Name: </label>
                  <div class="col-sm-3">
                    <input type="text" name="name" id="name" class="form-control" value="{{ isset($_GET['name']) ? $_GET['name'] : null}}">
                  </div>
                  <label for="username" class="col-sm-1 col-form-label">Username: </label>
                  <div class="col-sm-3">
                    <input type="email" name="username" id="username" class="form-control" value="{{ isset($_GET['username']) ? $_GET['username'] : null}}">
                  </div>
                  <label for="contact" class="col-sm-1 col-form-label">Contact: </label>
                  <div class="col-sm-3">
                    <input type="number" name="contact" id="contact" class="form-control" value="{{ isset($_GET['contact']) ? $_GET['contact'] : null}}">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="role" class="col-sm-1 col-form-label">Portal: </label>
                  <div class="col-sm-3">
                    <select id="role" name="role" class="form-select">
                      <option selected value="">All</option>
                      <option value="Guidance Office" {{ isset($_GET['role']) && $_GET['role'] == "Guidance Office" ? 'selected' : null}}>Guidance Office</option>
                      <option value="BSIT Portal" {{ isset($_GET['role']) && $_GET['role'] == "BSIT Portal" ? 'selected' : null}}>BSIT Portal</option>
                      <option value="BSBA Portal" {{ isset($_GET['role']) && $_GET['role'] == "BSBA Portal" ? 'selected' : null}}>BSBA Portal</option>
                      <option value="BSHM Portal" {{ isset($_GET['role']) && $_GET['role'] == "BSHM Portal" ? 'selected' : null}}>BSHM Portal</option>
                      <option value="BSED Portal" {{ isset($_GET['role']) && $_GET['role'] == "BSED Portal" ? 'selected' : null}}>BSED Portal</option>
                      <option value="BEED Portal" {{ isset($_GET['role']) && $_GET['role'] == "BEED Portal" ? 'selected' : null}}>BEED Portal</option>
                      <option value="Registrar Office" {{ isset($_GET['role']) && $_GET['role'] == "Registrar Office" ? 'selected' : null}}>Registrar Office</option>
                      <option value="ID Section" {{ isset($_GET['role']) && $_GET['role'] == "ID Section" ? 'selected' : null}}>ID Section</option>
                      <option value="COR Section" {{ isset($_GET['role']) && $_GET['role'] == "COR Section" ? 'selected' : null}}>COR Section</option>
                    </select>
                  </div>
                  <label for="status" class="col-sm-1 col-form-label">Status: </label>
                  <div class="col-sm-3">
                    <select id="status" name="status" class="form-select">
                      <option selected value="">All</option>
                      <option value="1" {{ isset($_GET['status']) && $_GET['status'] == "1" ? 'selected' : null}}>Active</option>
                      <option value="0" {{ isset($_GET['status']) && $_GET['status'] == "0" ? 'selected' : null}}>Inactive</option>
                    </select>
                  </div>
                  <label for="department" class="col-sm-1 col-form-label">Department: </label>
                  <div class="col-sm-3">
                    <input type="text" id="department" name="department" class="form-control" value="{{ isset($_GET['department']) ? $_GET['department'] : null}}">
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary btn-sm">Search</button>
                  {{-- <button type="reset" class="btn btn-secondary">Reset</button> --}}
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Portal Users</h5>
                {{-- <a href="users/add" class="btn btn-primary btn-sm">Add New User</a> --}}
              </div>
              <p class="card-text">List of all portal users</p>
              <!-- Table with stripped rows -->
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Avatar</th>
                      <th>Name</th>
                      <th>Portal</th>
                      <th>Username</th>
                      <th>Contact</th>
                      <th>Address</th>
                      <th>Department</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($users as $user)
                    <tr>
                      <td>
                        <img src="{{ !empty($user->profile) ? asset($user->profile) : asset('assets/img/logo.png') }}" alt="Avatar" class="avatar" width="80">
                      </td>
                      <td class="align-middle">{{ $user->name }}</td>
                      <td class="align-middle">{{ $user->role }}</td>
                      <td class="align-middle">{{ $user->username }}</td>
                      <td class="align-middle">{{ $user->contact }}</td>
                      <td class="align-middle">{{ $user->address }}</td>
                      <td class="align-middle">{{ $user->department }}</td>
                      <td class="align-middle">
                        @if ($user->status == '1')
                          <span class="badge bg-success">Active</span>
                        @else
                          <span class="badge bg-danger">Inactive</span>
                        @endif
                      </td>
                      <td class="align-middle">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form id="deleteUserForm" data-name="{{ $user->name }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
              </div>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
  </section>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      //when submit deleteUserForm jqeury
      $(document).on('submit', '#deleteUserForm', function(e) {
        e.preventDefault();
        var form = $(this); // Reference the form
        var url = form.attr('action'); // Get the form's action URL
        var name = form.data('name');
  
        Swal.fire({
          title: "Are you sure to delete " + name + "?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        }).then((result) => {
          if (result.isConfirmed) {
  
            var data = form.serialize();
            $.ajax({
              url: url,
              type: 'POST',
              data: data,
              success: function(response) {
                console.log(response);
                if (response.status == 'success') {
                  Swal.fire({
                    title: "Deleted!",
                    text: response.message,
                    icon: "success"
                  }).then((result) => {
                    if (result.isConfirmed) {
                      location.reload();
                    }
                  });
                } else {
                  Swal.fire({
                    title: "Error!",
                    text: response.message,
                    icon: "error"
                  }).then((result) => {
                    if (result.isConfirmed) {
                      // location.reload();
                    }
                  });
                }
              },
              error: function(error) {
                console.log(error);
                Swal.fire({
                  title: "Error!",
                  text: "Something went wrong. Please try again later.",
                  icon: "error"
                }).then((result) => {
                  if (result.isConfirmed) {
                    // location.reload();
                  }
                });
              }
            });
          }
        });
      });
    });
  </script>
@endsection
