@extends('layout')

@section('title', 'Dashboard')
<title>KTHRM- Kridentia</title>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
    </script>
    <style>
        #hiredResignedChart {
            width: 100%;
            height: 400px;
            /* Adjust the height of the chart as needed */
        }

        .content-page {
            display: none;
            /* Hide all content by default */
        }

        .content-page:first-of-type {
            display: block;
            /* Show the first page by default */
        }
        #calendar .fc-scrollgrid, 
    #calendar .fc-daygrid-body, 
    #calendar .fc-col-header-cell, 
    #calendar td {
        border: none !important;
    }
    #calendar td, #calendar th {
    width: 14.2% !important;
    padding: 0 !important;
}
    #calendar .fc-scroller-harness {
    overflow: hidden !important;
}
#calendar .fc-daygrid-day-frame {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 45px !important;
    padding: 0;
    margin: 0;
}
#calendar .fc-event {
    background-color: rgba(0, 174, 239, 0.15);
    border: none;
}

#calendar .fc-event-title {
    font-size: 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

#calendar .fc-day-today {
    background-color: #fff !important; /* Light blue background */
    border-radius: 4px;
}

#calendar .fc-day-today .fc-daygrid-day-number {
    color: #00aeef !important; /* Bright blue number */
    font-weight: bold;
}
/* Highlight today's date with rounded background */
#calendar .fc-day-today .fc-daygrid-day-number {
    background-color: #e0f7ff !important;
    border-radius: 50%;
    padding: 15px 15px;
    font-weight: bold;
    display: inline-block;
    line-height: 1;
}

/* Sunday */
#calendar .fc-day-sun .fc-daygrid-day-number {
    color: #8c8c8c !important;
}

/* Saturday */
#calendar .fc-day-sat .fc-daygrid-day-number {
    color: #8c8c8c !important;
}

#calendar .fc-daygrid-day-number {
    font-size: 11px;
    color: #000;
    text-align: center;
    width: 100%;
    margin: 0;
}
#calendar .fc-daygrid {
    row-gap: 0px;
    column-gap: 0px;
}
#calendar .fc-col-header-cell-cushion {
    color: #00aeef !important;  /* Your desired color */
    font-weight: bold;
    font-size: 12px;
}

    #calendar .fc-daygrid-day {
        line-height: 1.1;
    }

    #calendar .fc-toolbar {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    margin-bottom: 5px;
}

#calendar .fc-toolbar-title {
    grid-column: 2; /* Center column */
    color:#595959;
    font-size: 16px;
    font-weight: bold;
    text-align: right;
}
#calendar .fc-toolbar > .fc-toolbar-chunk:first-child {
    justify-content: flex-start;
}

#calendar .fc-toolbar > .fc-toolbar-chunk:last-child {
    justify-content: flex-end;
    display: flex;
}

    #calendar .fc-button {
        background: none;
        border: none;
        color: #00aeef;
        font-size: 16px;
        font-weight: bold;
    }
    </style>
    @php
        $totalEmployees = \App\Models\Employee::whereNull('termination_date')->count();
    @endphp

    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

            <!-- Content Selection Box -->
            <div class="card shadow-sm mb-4" style="width: 100%; max-width: 300px;">
                <div class="card-body p-3">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle w-100" type="button"
                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Choose View
                        </button>
                        <div class="dropdown-menu w-100 shadow-sm animated--fade-in" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" onclick="showPage('page1')">
                                <i class="fas fa-chart-bar mr-2 text-primary"></i> Employee Overview
                            </a>
                            <a class="dropdown-item" href="#" onclick="showPage('page2')">
                                <i class="fas fa-chart-pie mr-2 text-warning"></i> Employee Distribution
                            </a>
                            <a class="dropdown-item" href="#" onclick="showPage('page3')">
                                <i class="fas fa-briefcase mr-2 text-success"></i> Recruitment
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div id="page1" class="content-page">
            <!-- Content Row -->
            <div class="row">

                <!-- Employees Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2" style="border-left: 4px solid #176CA1;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1"
                                        style="font-size: 1rem; color: #176CA1">
                                        Employees
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEmployees }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x" style="color: #176CA1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Companies Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2" style="border-left: 4px solid #18ABDD;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1"
                                        style="font-size: 1rem; color: #18ABDD">
                                        Subsidiary
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCompanies }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-building fa-2x" style="color: #18ABDD"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example 
                                                                                    <div class="col-xl-3 col-md-6 mb-4">
                                                                                        <div class="card border-left-info shadow h-100 py-2">
                                                                                            <div class="card-body">
                                                                                                <div class="row no-gutters align-items-center">
                                                                                                    <div class="col mr-2">
                                                                                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Projects
                                                                                                        </div>
                                                                                                        <div class="row no-gutters align-items-center">
                                                                                                            <div class="col-auto">
                                                                                                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                                                                                            </div>
                                                                                                            <div class="col">
                                                                                                                <div class="progress progress-sm mr-2">
                                                                                                                    <div class="progress-bar bg-info" role="progressbar"
                                                                                                                        style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                                                                                        aria-valuemax="100"></div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-auto">
                                                                                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div> -->

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2" style="border-left: 4px solid #1AC9E7;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1"
                                        style="font-size: 1rem; color: #1AC9E7">
                                        Ratio of Permanent:Others</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ratioPermanent }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-percent fa-2x" style="color: #1AC9E7"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2" style="border-left: 4px solid #1CD4D4;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1"
                                        style="font-size: 1rem; color: #1CD4D4">
                                        Races Ratio
                                        (M : C : I : Others)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ratioRaces }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-pie fa-2x" style="color: #1CD4D4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->

            <div class="row">

                <!-- Calendar Card -->
                <div class="col-xl-4 col-lg-5 d-flex">
                    <div class="card shadow mb-4 w-100" style="height: 400px;"> <!-- fixed card height -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #00aeef">Calendar</h6>
                        </div>
                        <div class="card-body p-0"> <!-- remove padding here -->
                            <div id="calendar" style="height: 100%; width: 100%; padding:0px 20px 10px;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-4 col-lg-5 d-flex">
                    <div class="card shadow w-100" style="height: 400px;">
                        <div class="card-header py-3 d-flex align-items-center">
                            <i class="fas fa-birthday-cake fa-lg mr-2" style="color: #00aeef;"></i>
                            <h6 class="m-0 font-weight-bold" style="color: #00aeef">Employee Birthdays</h6>
                        </div>
                        <div class="card-body overflow-auto" style="height: calc(100% - 50px); font-size: 14px;">

                            {{-- Today --}}
                            <h6 class="font-weight-bold text-dark">
                                <i class="fas fa-gift fa-sm mr-1 text-primary"></i> Today
                            </h6>
                            @forelse ($todayBirthdays as $birthday)
                                <div class="mb-1">
                                    <i class="fas fa-user fa-sm text-success mr-1"></i>
                                    <strong>{{ $birthday['name'] }}</strong> - {{ $birthday['birthday'] }}
                                </div>
                            @empty
                                <p class="text-muted">No birthdays today</p>
                            @endforelse

                            <hr class="my-2">

                            {{-- Upcoming --}}
                            <h6 class="font-weight-bold text-dark">
                                <i class="fas fa-calendar-week fa-sm mr-1 text-warning"></i> Next 7 Days
                            </h6>
                            @forelse ($upcomingBirthdays as $birthday)
                                <div class="mb-1">
                                    <i class="fas fa-user fa-sm text-warning mr-1"></i>
                                    <strong>{{ $birthday['name'] }}</strong> - {{ $birthday['birthday'] }}
                                </div>
                            @empty
                                <p class="text-muted">No upcoming birthdays</p>
                            @endforelse

                            @if ($nextBirthday)
                                <hr class="my-2">
                                <h6 class="font-weight-bold text-dark">
                                    <i class="fas fa-hourglass-half fa-sm mr-1 text-info"></i> Next After
                                </h6>
                                <p>
                                    <i class="fas fa-user fa-sm text-info mr-1"></i>
                                    <strong>{{ $nextBirthday['name'] }}</strong> - {{ $nextBirthday['birthday'] }}
                                </p>
                            @endif

                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="col-xl-4 col-lg-5 d-flex">
                    <div class="card shadow mb-4 w-100" style="height: 400px;"> <!-- Set same height here -->
                        <div class="card-header py-3  d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold" style="color: #00aeef">Hired VS Resigned (Annual)</h6>
                        </div>
                        <div class="card-body pb-3" style="height: calc(100% - 50px);">
                            <!-- Bar chart will be rendered inside this canvas -->
                            <canvas id="hiredResignedChart" style="width:100%;height:100%"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7 d-flex">
                    <div class="card shadow mb-4 w-100" style="height: 400px;"> <!-- Set same height here -->
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold" style="color: #00aeef">Employee Status Monthly Statistics</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownMenuLink">
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body pb-3" style="height: calc(100% - 50px);">
                            <div class="chart-area">
                                <canvas id="myChart1" style="width:100%;height:100%;"></canvas>
                            </div>h
                        </div>
                    </div>
                </div>
            
            
        </div>

        <div id="page2" class="content-page" style="display: none;">
            <!-- Content Row -->
            <div class="row">

                <!-- Content Column -->
                <div class="col-lg-5 mb-4">

                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold" style="color: #00aeef">Employee Distribution By Subsidiary</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownMenuLink">
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie">
                                <div id="myChart2" style="width:100%; max-width:1000px; height:100%;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--        Project Card Example    
                                                                                            <div class="card shadow mb-4">
                                                                                                <div class="card-header py-3">
                                                                                                    <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                                                                                                </div>
                                                                                                <div class="card-body">
                                                                                                    <h4 class="small font-weight-bold">Server Migration <span class="float-right">20%</span></h4>
                                                                                                    <div class="progress mb-4">
                                                                                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20"
                                                                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                                                                    </div>
                                                                                                    <h4 class="small font-weight-bold">Sales Tracking <span class="float-right">40%</span></h4>
                                                                                                    <div class="progress mb-4">
                                                                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="40"
                                                                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                                                                    </div>
                                                                                                    <h4 class="small font-weight-bold">Customer Database <span class="float-right">60%</span></h4>
                                                                                                    <div class="progress mb-4">
                                                                                                        <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60"
                                                                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                                                                    </div>
                                                                                                    <h4 class="small font-weight-bold">Payout Details <span class="float-right">80%</span></h4>
                                                                                                    <div class="progress mb-4">
                                                                                                        <div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="80"
                                                                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                                                                    </div>
                                                                                                    <h4 class="small font-weight-bold">Account Setup <span class="float-right">Complete!</span></h4>
                                                                                                    <div class="progress">
                                                                                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100"
                                                                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div> -->
                </div>

                <div class="col-lg-7 mb-4">

                    <!-- Card Container -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #00aeef">Employee Distribution by Department</h6>
                        </div>
                        <div class="card-body">
                            <!-- Chart Container with White Background -->
                            <div class="chart-pie">
                                <div id="departmentChart"
                                    style="width:100%; height:100%; max-width:1000px; height:250px; background-color: white;">
                                    <!-- The chart will be rendered here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="page3" class="content-page" style="display: none;">
            <div class="row">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold" style="color: #00aeef">Recruitment</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>No. of Vacancies</th>
                                        <th>No. of Applicants</th>
                                        <th>No. of Interviewed</th>
                                        <th>No. of Hired</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($jobs as $job)
                                        <tr>
                                            <td>{{ $job->title }}</td>
                                            <td>{{ $job->vacancies ?? 0 }}</td>
                                            <td>{{ $job->applicants ?? 0 }}</td>
                                            <td>{{ $job->interviewed ?? 0 }}</td>
                                            <td>{{ $job->hired }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No job vacancies available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <!-- Pie Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script>
        // This will make sure that page1 is shown by default
        window.onload = function () {
            showPage('page1');
        };

        function showPage(pageId) {
            // Hide all pages
            var pages = document.querySelectorAll('.content-page');
            pages.forEach(function (page) {
                page.style.display = 'none';
            });

            // Show the selected page
            var selectedPage = document.getElementById(pageId);
            selectedPage.style.display = 'block';
        }
    </script>
    <canvas id="myChart1"></canvas>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            firstDay: 1,
            fixedWeekCount: true,
            expandRows: true,
            height: '100%',
            timeZone: 'Asia/Kuala_Lumpur',
            headerToolbar: {
                left: 'title',
                center: '',
                right: 'prev,next'
            },

        });

        calendar.render();

        window.addEventListener('load', function () {
            calendar.updateSize();
        });
    });
</script>
    <script>
        const months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
        const employmentData = @json($employmentData);

        const currentMonthIndex = new Date().getMonth(); // August is index 7, so it will handle upcoming months

        const fullTimeData = months.map((_, i) => (i <= currentMonthIndex ? (employmentData['Full Time'][i + 1] || 0) : null));
        const contractData = months.map((_, i) => (i <= currentMonthIndex ? (employmentData['Contract'][i + 1] || 0) : null));
        const protegeData = months.map((_, i) => (i <= currentMonthIndex ? (employmentData['Protégé'][i + 1] || 0) : null));
        const internshipData = months.map((_, i) => (i <= currentMonthIndex ? (employmentData['Internship'][i + 1] || 0) : null));

        const latestFullTime = fullTimeData[currentMonthIndex] || 0;
        const latestContract = contractData[currentMonthIndex] || 0;
        const latestProtege = protegeData[currentMonthIndex] || 0;
        const latestInternship = internshipData[currentMonthIndex] || 0;

        new Chart("myChart1", {
            type: "line",
            data: {
                labels: months,
                datasets: [{
                    label: `Permanent = ${latestFullTime}`,
                    data: fullTimeData,
                    borderColor: "#0080ff",
                    backgroundColor: "#0080ff",
                    fill: false
                }, {
                    label: `Contract = ${latestContract}`,
                    data: contractData,
                    borderColor: "#33cccc",
                    backgroundColor: "#33cccc",
                    fill: false
                }, {
                    label: `Protégé = ${latestProtege}`,
                    data: protegeData,
                    borderColor: "#66ffb3",
                    backgroundColor: "#66ffb3",
                    fill: false
                }, {
                    label: `Internship = ${latestInternship}`,
                    data: internshipData,
                    borderColor: "#bb99ff",
                    backgroundColor: "#bb99ff",
                    fill: false
                }]
            },
            options: {
                responsive: true, // Make the chart responsive
                maintainAspectRatio: false, // Optional: disable the aspect ratio to fill the container
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        suggestedMin: 0, // Enforce minimum y-axis value
                        suggestedMax: 70, // Enforce maximum y-axis value
                        ticks: {
                            stepSize: 10
                        },
                        beginAtZero: true,
                    }
                }
            }
        });
    </script>

    <!-- Line Chart -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            const chartData = @json($companyData);

            const data = google.visualization.arrayToDataTable([
                ['Company', 'Employees'],
                ...chartData
            ]);

            const options = {
                is3D: true,
                chartArea: {
                    width: '95%',
                    height: '90%'
                },
                colors: ['#66b3ff', '#b366ff', '#b3b3ff'],
                pieSliceText: 'value',
                pieSliceTextStyle: {
                    color: '#ffffff',
                    fontSize: 12,
                    bold: true
                },
                legend: {
                    textStyle: {
                        fontSize: 14 // Font size for legend labels
                    }
                },
            };

            const chart = new google.visualization.PieChart(document.getElementById('myChart2'));
            chart.draw(data, options);

            // Redraw chart on window resize for responsiveness
            window.addEventListener('resize', function () {
                chart.draw(data, options);
            });
        }
    </script>

    <script>
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            const chartData = @json($departmentData);

            const data = google.visualization.arrayToDataTable([
                ['Department', 'Employees'],
                ...chartData
            ]);

            const options = {
                is3D: true,
                chartArea: {
                    width: '95%',
                    height: '90%'
                },
                colors: ['#95749f', '#cc96b8', '#ffbdcf', '#f7a0d0', '#e388db', '#be78ec', '#7d71ff'],
                pieSliceText: 'value',
                pieSliceTextStyle: {
                    color: '#ffffff',
                    fontSize: 10,
                    bold: true
                },
                legend: {
                    textStyle: {
                        fontSize: 14 // Font size for legend labels
                    }
                },
            };

            const chart = new google.visualization.PieChart(document.getElementById('departmentChart'));
            chart.draw(data, options);

            // Redraw chart on window resize
            window.addEventListener('resize', function () {
                chart.draw(data, options);
            });
        }
    </script>


    <!-- Bar Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data from the backend
        const hiredCount = @json($hiredResignedData['hiredCount']);
        const resignedCount = @json($hiredResignedData['resignedCount']);

        // Create the bar chart
        new Chart(document.getElementById("hiredResignedChart").getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Hired', 'Resigned'],  // Labels for the x-axis
                datasets: [{
                    label: `Hired = ${hiredCount}`,  // Legend label with total count
                    data: [hiredCount, 0],  // Data for Hired
                    backgroundColor: '#00e6ac',  // Green color for Hired
                    borderColor: '#00e6ac',
                    borderWidth: 1
                }, {
                    label: `Resigned = ${resignedCount}`,  // Legend label with total count
                    data: [0, resignedCount],  // Data for Resigned
                    backgroundColor: '#ff8080',  // Red color for Resigned
                    borderColor: '#ff8080',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true  // Stack bars on the x-axis
                    },
                    y: {
                        beginAtZero: true  // Ensure y-axis starts at 0
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 14  // Font size for legend labels
                            },
                            generateLabels: function (chart) {
                                const data = chart.data;
                                return data.datasets.map((dataset, i) => {
                                    return {
                                        text: dataset.label, // Show the label with count in the legend
                                        fillStyle: dataset.backgroundColor,
                                        strokeStyle: dataset.borderColor,
                                        lineWidth: dataset.borderWidth,
                                        hidden: chart.getDatasetMeta(i).hidden,
                                        index: i
                                    };
                                });
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                // Show only the value in the tooltip
                                const datasetLabel = tooltipItem.dataset.label || '';
                                const value = tooltipItem.raw;
                                return `${value}`; // Only show the value
                            }
                        }
                    },
                }
            }
        });
    </script>


@endsection