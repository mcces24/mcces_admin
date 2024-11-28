@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Students Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card students-card">

                            <div class="card-body">
                                <h5 class="card-title">Total Students Enrolled </h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$totalStudentsEnrolled}}</h6>
                                        {{-- <span class="text-success small pt-1 fw-bold">
                                        </span> --}}
                                        <span class="text-muted small pt-2 ps-1">
                                            {{$semester}} | {{$academics}}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Students Card -->

                    <!-- Male Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card male-card">

                            <div class="card-body">
                                <h5 class="card-title">Male Students</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-gender-male"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$totaMaleStudentsEnrolled}}</h6>
                                        {{-- <span class="text-success small pt-1 fw-bold">
                                        </span> --}}
                                        <span class="text-muted small pt-2 ps-1">
                                            {{$semester}} | {{$academics}}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Male Card -->

                    <!-- Female Card -->
                    <div class="col-xxl-4 col-xl-12">

                        <div class="card info-card female-card">

                            <div class="card-body">
                                <h5 class="card-title">Female Students</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$totalFemaleStudentsEnrolled}}</h6>
                                        {{-- <span class="text-success small pt-1 fw-bold">
                                        </span> --}}
                                        <span class="text-muted small pt-2 ps-1">
                                            {{$semester}} | {{$academics}}
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- End Female Card -->

                    <!-- Reports -->
                    <div class="col-12">
                        <div class="card">

                            <div class="card-body">
                                <h5 class="card-title">Students Report</h5>

                                <!-- Line Chart -->
                                <div id="reportsChart"></div>

                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        var reportsChart = {!! json_encode($reportsChart) !!};
                                        var xaxis = reportsChart.xaxis;
                                        var series = reportsChart.series;

                                        if (Object.keys(reportsChart).length === 0) {
                                            document.querySelector("#reportsChart").innerHTML = "<h5 class='text-center'>No data available</h5>";
                                            return
                                        }
                                        new ApexCharts(document.querySelector("#reportsChart"), {
                                            series: [{
                                                name: "First Sem",
                                                data: series['First Sem']['data'],
                                            }, {
                                                name: "Second Sem",
                                                data: series['Second Sem']['data'],
                                            }, {
                                                name: "Summer",
                                                data: series['Summer']['data']
                                            }],
                                            chart: {
                                                height: 350,
                                                type: 'area',
                                                toolbar: {
                                                    show: false
                                                },
                                            },
                                            markers: {
                                                size: 4
                                            },
                                            colors: ['#4154f1', '#2eca6a', '#ff771d'],
                                            fill: {
                                                type: "gradient",
                                                gradient: {
                                                    shadeIntensity: 1,
                                                    opacityFrom: 0.3,
                                                    opacityTo: 0.4,
                                                    stops: [0, 90, 100]
                                                }
                                            },
                                            dataLabels: {
                                                enabled: false
                                            },
                                            stroke: {
                                                curve: 'smooth',
                                                width: 2
                                            },
                                            xaxis: {
                                                type: 'text',
                                                categories: [
                                                    xaxis[0],
                                                    xaxis[1]
                                                ],
                                            },
                                            tooltip: {
                                                x: {
                                                    format: 'dd/MM/yy HH:mm'
                                                },
                                            }
                                        }).render();
                                    });
                                </script>
                                <!-- End Line Chart -->

                            </div>

                        </div>
                    </div><!-- End Reports -->

                    <!-- Users Activity -->
                    <div class="col-12">
                        <div class="card recent-students overflow-auto">
                           
                            <div class="card-body">
                                <h5 class="card-title">Users Activity</h5>

                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Users</th>
                                            <th scope="col">Portal</th>
                                            <th scope="col">Location</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $number = 1; @endphp
                                        @foreach ($usersActivities as $usersActivity)
                                            <tr>
                                                <th scope="row">{{$number++}}</th>
                                                <td>{{$usersActivity->name}}</td>
                                                <td>{{$usersActivity->portal}}</td>
                                                <td>{{$usersActivity->com_location}}</td>
                                                <td>
                                                    @if ($usersActivity->type == 'success')
                                                        <span class="badge bg-success">Success</span>
                                                    @else 
                                                        <span class="badge bg-danger">Failed</span>
                                                    @endif
                                                    
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Users Activity -->

                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Recent Activity -->
                <div class="card">
                    {{-- <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div> --}}

                    <div class="card-body">
                        <h5 class="card-title">Recent Activity</h5>

                        <div class="activity">

                            <div class="activity-item d-flex">
                                <div class="activite-label">32 min</div>
                                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                <div class="activity-content">
                                    Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a> beatae
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">56 min</div>
                                <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                                <div class="activity-content">
                                    Voluptatem blanditiis blanditiis eveniet
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">2 hrs</div>
                                <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                                <div class="activity-content">
                                    Voluptates corrupti molestias voluptatem
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">1 day</div>
                                <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                                <div class="activity-content">
                                    Tempore autem saepe <a href="#" class="fw-bold text-dark">occaecati voluptatem</a> tempore
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">2 days</div>
                                <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                                <div class="activity-content">
                                    Est sit eum reiciendis exercitationem
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">4 weeks</div>
                                <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                                <div class="activity-content">
                                    Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
                                </div>
                            </div><!-- End activity item-->

                        </div>

                    </div>
                </div><!-- End Recent Activity -->

                <!-- Department Statistics -->
                <div class="card">

                    <div class="card-body pb-0">
                        <h5 class="card-title">Department Statistics</h5>

                        <div id="deparmentChart" style="min-height: 400px;" class="echart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                var courses = {!! json_encode($allCourses) !!};
                                var departmentData = {!! json_encode($departmentChats) !!};
                                var max = {{$totalStudentsEnrolled}};
                                var data = [];
                                for (const key in departmentData) {
                                    if (Object.hasOwnProperty.call(departmentData, key)) {
                                        data.push({
                                            value: departmentData[key],
                                            name: key
                                        });
                                    }
                                }
                                var deparmentChart = echarts.init(document.querySelector("#deparmentChart")).setOption({
                                    legend: {
                                        data: ['1st Year', '2nd Year', '3rd Year', '4th Year']
                                    },
                                    radar: {
                                        // shape: 'circle',
                                        indicator: [{
                                                name: courses[0],
                                                max: max
                                            },
                                            {
                                                name: courses[1],
                                                max: max
                                            },
                                            {
                                                name: courses[2],
                                                max: max
                                            },
                                            {
                                                name: courses[3],
                                                max: max
                                            },
                                            {
                                                name: courses[4],
                                                max: max
                                            },
                                        ]
                                    },
                                    tooltip: {
                                        trigger: 'item',
                                    },
                                    series: [{
                                        name: 'Budget vs spending',
                                        type: 'radar',
                                        data: data
                                    }]
                                });
                            });
                        </script>

                    </div>
                </div><!-- End Department Statistics -->

                <!-- Students Per Department  -->
                <div class="card">

                    <div class="card-body pb-0">
                        <h5 class="card-title">Students Per Department</h5>

                        <div id="departmentStudents" style="min-height: 400px;" class="echart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                var departmentStudents = {!! json_encode($studentsPerCourse) !!};
                                var data = [];
                                for (const key in departmentStudents) {
                                    if (Object.hasOwnProperty.call(departmentStudents, key)) {
                                        data.push({
                                            value: departmentStudents[key],
                                            name: key
                                        });
                                    }
                                }

                                echarts.init(document.querySelector("#departmentStudents")).setOption({
                                    tooltip: {
                                        trigger: 'item'
                                    },
                                    legend: {
                                        top: '5%',
                                        left: 'center'
                                    },
                                    series: [{
                                        name: 'Access From',
                                        type: 'pie',
                                        radius: ['40%', '70%'],
                                        avoidLabelOverlap: false,
                                        label: {
                                            show: false,
                                            position: 'center'
                                        },
                                        emphasis: {
                                            label: {
                                                show: true,
                                                fontSize: '18',
                                                fontWeight: 'bold'
                                            }
                                        },
                                        labelLine: {
                                            show: false
                                        },
                                        data: data
                                    }]
                                });
                            });
                        </script>

                    </div>
                </div><!-- End Students Per Department  -->

            </div><!-- End Right side columns -->

        </div>
    </section>
@endsection