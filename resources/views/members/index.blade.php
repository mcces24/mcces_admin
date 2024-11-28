@extends('layouts.app')
@section('content')
  <div class="pagetitle">
    <h1>Members</h1>
    <nav>
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="members">Members</a></li>
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
                <h5 class="card-title mb-0">Search Member</h5>
              </div>
              <form method="GET">
                <div class="row mb-3">
                  <label for="email" class="col-sm-1 col-form-label">Email: </label>
                  <div class="col-sm-3">
                    <input type="email" name="email" id="email" class="form-control" value="{{ isset($_GET['email']) ? $_GET['email'] : null}}">
                  </div>
                  <label for="status" class="col-sm-1 col-form-label">Username: </label>
                  <div class="col-sm-3">
                    <select id="status" name="status" class="form-select">
                      <option selected value="">All</option>
                      <option value="1" {{ isset($_GET['status']) && $_GET['status'] == "1" ? 'selected' : null}}>Active</option>
                      <option value="0" {{ isset($_GET['status']) && $_GET['status'] == "0" ? 'selected' : null}}>Inactive</option>
                    </select>
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
              <div class="d-flex align-items-center">
                <h5 class="card-title mb-0">Members</h5>
                <div class="spinner-border text-primary data-loading" role="status" style="width: 20px; height: 20px; margin-left: 5px">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
              <p class="card-text">List of all members</p>
              <!-- Table with stripped rows -->
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Email</th>
                      <th>Status</th>
                      <th>Verified Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody class="data" style="display: none"> 
                    @foreach ($members as $member)
                    <tr>
                      <td>{{ $loop->iteration + ($members->currentPage() - 1) * $members->perPage() }}</td>
                      <td class="align-middle">{{ $member->email }}</td>
                      <td class="align-middle">
                        @if ($member->status == '1')
                          <span class="badge bg-success">Active</span>
                        @else
                          <span class="badge bg-danger">Inactive</span>
                        @endif
                      </td>
                      <td class="align-middle">
                        @if ($member->verified_status == 1)
                          <span class="badge bg-success">Verified</span>
                        @else
                          <span class="badge bg-danger">Not Verified</span>
                        @endif
                      <td class="align-middle">
                        @if ($member->status == '1')
                          <form id="blockMemberForm" data-email="{{ $member->email }}" action="{{ route('members.destroy', [$member->Id, 'block']) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-warning btn-sm">Block</button>
                          </form>
                        @else
                          <form id="unBlockMemberForm" data-email="{{ $member->email }}" action="{{ route('members.destroy', [$member->Id, 'unblock']) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-success btn-sm">Unblock</button>
                          </form>
                        @endif
                        <form id="deleteMemberForm" data-email="{{ $member->email }}" action="{{ route('members.destroy', [$member->Id, 'delete']) }}" method="POST" style="display: inline;">
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
                        Showing {{ $members->firstItem() }} to {{ $members->lastItem() }} of {{ $members->total() }} results
                    </div>
                    <div>
                        {{ $members->links() }}
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
  
      $('.data-loading').hide();
      $('.data').show();
      //when submit deleteMemberForm jqeury
      $(document).on('submit', '#deleteMemberForm, #blockMemberForm, #unBlockMemberForm', function(e) {
        e.preventDefault();
        var formId = $(this).attr('id');
        var form = $(this); // Reference the form
        var url = form.attr('action'); // Get the form's action URL
        var name = form.data('email');
        var title = '';
        var successTitle = '';
        var text = '';
        var confirmButtonText = '';
  
        if (formId == 'blockMemberForm') {
          title = "Are you sure to block " + name + "?";
          successTitle = "Blocked!";
          text = "Member will be blocked and can't login";
          confirmButtonText = "Yes, block it!";
        } else if (formId == 'unBlockMemberForm') {
          title = "Are you sure to unblock " + name + "?";
          successTitle = "Unblocked!";
          text = "Member will be unblocked and can login";
          confirmButtonText = "Yes, unblock it!";
        } else {
          title = "Are you sure to delete " + name + "?";
          successTitle = "Deleted!";
          text = "You won't be able to revert this!";
          confirmButtonText = "Yes, delete it!";
        }
  
        Swal.fire({
          title: title,
          text: text,
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: confirmButtonText
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
                    title: successTitle,
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
                  text: 'Something went wrong. Please try again later',
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