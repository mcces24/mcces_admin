@extends('layouts.app')
@section('content')
  <div class="pagetitle">
    <h1>Logs</h1>
    <nav>
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="logs">Logs</a></li>
          <li class="breadcrumb-item active">Attemp List</li>
      </ol>
    </nav>
  </div>
  <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Search Logs</h5>
              </div>
              <form method="GET">
                <div class="row mb-3">
                  <label for="attemp" class="col-sm-1 col-form-label">Attempt: </label>
                  <div class="col-sm-3">
                    <input type="email" name="attemp" id="attemp" class="form-control" value="{{ isset($_GET['attemp']) ? $_GET['attemp'] : null}}">
                  </div>
                  <label for="type" class="col-sm-1 col-form-label">Type: </label>
                  <div class="col-sm-3">
                    <select id="type" name="type" class="form-select">
                      <option selected value="">All</option>
                      <option value="success" {{ isset($_GET['type']) && $_GET['type'] == "success" ? 'selected' : null}}>Success</option>
                      <option value="failed" {{ isset($_GET['type']) && $_GET['type'] == "failed" ? 'selected' : null}}>Failed</option>
                    </select>
                  </div>
                  <label for="portal" class="col-sm-1 col-form-label">Portal: </label>
                  <div class="col-sm-3">
                    <select id="portal" name="portal" class="form-select">
                      <option selected value="">All</option>
                      <option value="guidance" {{ isset($_GET['portal']) && $_GET['portal'] == "guidance" ? 'selected' : null}}>Guidance</option>
                      <option value="bsit" {{ isset($_GET['portal']) && $_GET['portal'] == "bsit" ? 'selected' : null}}>BSIT</option>
                      <option value="bsed" {{ isset($_GET['portal']) && $_GET['portal'] == "bsed" ? 'selected' : null}}>BSED</option>
                      <option value="beed" {{ isset($_GET['portal']) && $_GET['portal'] == "beed" ? 'selected' : null}}>BEED</option>
                      <option value="bshm" {{ isset($_GET['portal']) && $_GET['portal'] == "bshm" ? 'selected' : null}}>BSHM</option>
                      <option value="bsba" {{ isset($_GET['portal']) && $_GET['portal'] == "bsba" ? 'selected' : null}}>BSBA</option>
                      <option value="registrar" {{ isset($_GET['portal']) && $_GET['portal'] == "registrar" ? 'selected' : null}}>Registrar</option>
                      <option value="id" {{ isset($_GET['portal']) && $_GET['portal'] == "id" ? 'selected' : null}}>ID Section</option>
                      <option value="cor" {{ isset($_GET['portal']) && $_GET['portal'] == "cor" ? 'selected' : null}}>COR Section</option>
                      <option value="admin" {{ isset($_GET['portal']) && $_GET['portal'] == "admin" ? 'selected' : null}}>Admin</option>
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
                <h5 class="card-title mb-0">Logs</h5>
              </div>
              <p class="card-text">List of attemp logs</p>
              <!-- Table with stripped rows -->
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Attemp</th>
                      <th>Portal</th>
                      <th>Location</th>
                      <th>Accuracy</th>
                      <th>Type</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody class="data"> 
                    @foreach ($logs as $log)
                    <tr>
                      <td>{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                      <td class="align-middle">{{ $log->attemp }}</td>
                      <td class="align-middle">{{ $log->portal }}</td>
                      <td class="align-middle">{{ $log->com_location }}</td>
                      <td class="align-middle">
                        @if ($log->accuracy < 10)
                          <span class="badge bg-success">High</span>
                        @elseif ($log->accuracy < 100)
                          <span class="badge bg-warning">Medium</span>
                        @else
                          <span class="badge bg-danger">Low</span>
                        @endif
                         | {{ round($log->accuracy) }} Meters Radius
                      </td>
                      <td class="align-middle">
                        @if ($log->type == "success")
                          <span class="badge bg-success">Success</span>
                        @else
                          <span class="badge bg-danger">Failed</span>
                        @endif
                      </td>
                      <td class="align-middle">
                        <a href="{{ route('logs.view', $log->id) }}" class="btn btn-sm btn-primary">View</a>
                      </td>
                    @endforeach
                  </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} results
                    </div>
                    <div>
                        {{ $logs->links() }}
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
    });
  </script>
@endsection
