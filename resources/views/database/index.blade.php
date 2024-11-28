@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <h1>Database</h1>
        <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="database">Database</a></li>
            <li class="breadcrumb-item active">Backup</li>
        </ol>
        </nav>
    </div><section class="section">
        <div class="row">
          <div class="col-lg-12">
  
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Members</h5>
                    <button class="btn btn-primary btn-sm" id="backup-btn">
                        Database Backup
                    </button>
                </div>
                <p class="card-text">List of backup database</p>
                <!-- Table with stripped rows -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>File Name</th>
                            <th>File Size</th>
                            <th>Backup Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody class="data"> 
                        @foreach ($backups as $backup)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">{{ $backup->filename }}</td>
                            <td class="align-middle">{{ number_format($backup->filesize / 1024 / 1024, 2) }} MB</td>
                            <td class="align-middle">{{ $backup->created_at->format('l, F j, Y \a\t g:i A') }}</td>
                            <td class="align-middle">
                                <a href="{{ route('download', $backup->filename) }}" class="btn btn-success btn-sm">Download</a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            Showing {{ $backups->firstItem() }} to {{ $backups->lastItem() }} of {{ $backups->total() }} results
                        </div>
                        <div>
                            {{ $backups->links() }}
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
            $('#backup-btn').click(function() {
                $.ajax({
                    url: '{{ route('backup') }}', // Laravel route
                    type: 'GET',
                    beforeSend: function() {
                        Swal.fire({
                            title: "Backing up database...",
                            showClass: {
                                popup: `
                                animate__animated
                                animate__fadeInUp
                                animate__faster
                                `
                            },
                            hideClass: {
                                popup: `
                                animate__animated
                                animate__fadeOutDown
                                animate__faster
                                `
                            },
                            showConfirmButton: false,
                        });
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: "error",
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: "Error!",
                            text: xhr.responseJSON.message,
                            icon: "error",
                        });
                    }
                });
            });
        });
    </script>
@endsection

