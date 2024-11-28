@extends('layouts.app')
@section('content')
  <div class="pagetitle">
    <h1>View Logs</h1>
    <nav>
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/logs">Logs</a></li>
          <li class="breadcrumb-item">View</li>
          <li class="breadcrumb-item active">{{ $log->attemp }}</li>
      </ol>
    </nav>
  </div>
  <section class="section">
    <div class="row">
      <!-- Log Attempt Maps -->
      <div class="col-lg-5">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-0">Log Attempt Maps</h5>
            <iframe 
              src="https://www.google.com/maps?q={{ htmlspecialchars($log->lat) }},{{ htmlspecialchars($log->lon) }}&hl=en&z=18&output=embed" 
              frameborder="0" 
              style="width: 100%; height: 400px; border: 0;">
            </iframe>
          </div>
        </div>
      </div>
    
      <!-- Log Details -->
      <div class="col-lg-7">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-0">Log Details</h5>
            <div class="row">
              <div class="col-lg-6">
                <p><strong>Attempt:</strong> {{ $log->attemp }}</p>
                <p><strong>Type:</strong> {{ $log->type }}</p>
                <p><strong>Location:</strong> {{ $log->location }}</p>
                <p><strong>Created At:</strong> {{ $log->created_at->format('l, F j, Y \a\t g:i A') }}</p>
                <p><strong>Accuracy:</strong>
                  @if ($log->accuracy < 10)
                    <span class="badge bg-success">High</span>
                  @elseif ($log->accuracy < 100)
                    <span class="badge bg-warning">Medium</span>
                  @else
                    <span class="badge bg-danger">Low</span>
                  @endif
                  | {{ round($log->accuracy) }} Meters Radius
                </p>
              </div>
              <div class="col-lg-6">
                <p><strong>Latitude:</strong> {{ $log->lat }}</p>
                <p><strong>Longitude:</strong> {{ $log->lon }}</p>
                <p><strong>Complete Location:</strong> {{ $log->com_location }}</p>
              </div>
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