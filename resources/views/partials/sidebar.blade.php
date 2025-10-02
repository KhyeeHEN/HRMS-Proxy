<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand bg-white d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('k.png') }}" style="width: 50px; height: 50px;">
        </div>
        <div class="sidebar-brand-text mx-3" style="color: #00aeef; font-size: 17px;">Kridentia</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    @auth
        @if(auth()->user()->access === 'Admin')
            <!-- Admin Section -->
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Admin</div>
            <li class="nav-item">
                <a class="nav-link link" href="{{ route('users.index') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>User</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link" href="{{ route('company.index') }}">
                    <i class="fas fa-fw fa-building"></i>
                    <span>Subsidiary</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link" href="{{ route('department.index') }}">
                    <i class="fas fa-fw fa-sitemap"></i>
                    <span>Department</span>
                </a>
            </li>
        @endif

        @if(in_array(auth()->user()->access, ['Admin', 'HR', 'Technical']))
            <!-- Management Section -->
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Management</div>

            <!-- Employee Section -->
            @if(auth()->user()->access === 'HR' || auth()->user()->access === 'Admin' || auth()->user()->access === 'Technical')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                        aria-expanded="true" aria-controls="collapseUtilities">
                        <i class="fas fa-fw fa-address-card"></i>
                        <span>Employee</span>
                    </a>
                    <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="{{ route('employees') }}">Employees</a>
                            @if(auth()->user()->access === 'Admin' || auth()->user()->access === 'HR')
                                <a class="collapse-item" href="{{ route('past.employees') }}">Past Employees</a>
                                <a class="collapse-item" href="{{ route('uploademp.form') }}">Upload Employees</a>
                                <a class="collapse-item" href="{{ route('terminate.form') }}">Remove Employees</a>
                            @endif
                        </div>
                    </div>
                </li>
            @endif

            <!-- Family (Admin & HR only) -->
            @if(auth()->user()->access === 'Admin' || auth()->user()->access === 'HR')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseZero" aria-expanded="true"
                        aria-controls="collapseZero">
                        <i class="fas fa-fw fa-users"></i>
                        <span>Family</span>
                    </a>
                    <div id="collapseZero" class="collapse" aria-labelledby="headingZero" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="{{ route('uploadfam.form') }}">Upload Families</a>
                        </div>
                    </div>
                </li>
            @endif

            <!-- Recruitment (Admin & HR only) -->
            @if(auth()->user()->access === 'Admin' || auth()->user()->access === 'HR')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                        aria-controls="collapseOne">
                        <i class="fas fa-fw fa-suitcase"></i>
                        <span>Recruitment</span>
                    </a>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="{{ route('jobs.index') }}">Vacancies</a>
                        </div>
                    </div>
                </li>
            @endif

            <!-- Company Asset (Admin & Technical only) -->
            @if(auth()->user()->access === 'Admin' || auth()->user()->access === 'Technical')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAssets" aria-expanded="true"
                        aria-controls="collapseAssets">
                        <i class="fas fa-fw fa-boxes"></i>
                        <span>Company Asset</span>
                    </a>
                    <div id="collapseAssets" class="collapse" aria-labelledby="headingAssets" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="{{ route('assets.index') }}">Asset</a>
                            <a class="collapse-item" href="{{ route('upload.assets') }}">Upload Assets</a>
                            <a class="collapse-item" href="{{ route('asset-categories.index') }}">Asset Category</a>
                        </div>
                    </div>
                </li>
            @endif
        @endif

        @if(auth()->user()->access === 'Admin' || auth()->user()->access === 'HR' || auth()->user()->access === 'Employee' || auth()->user()->access === 'Manager')
            <hr class="sidebar-divider">
            <div class="sidebar-heading">PMS</div>
            {{-- ADDED: New PMS pages --}}
            @if(in_array(auth()->user()->access, ['Admin', 'HR', 'Manager']))
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKpi" aria-expanded="true"
                        aria-controls="collapseKpi">
                        <i class="fas fa-fw fa-clipboard-list"></i>
                        <span>KPI Management</span>
                    </a>
                    <div id="collapseKpi" class="collapse" aria-labelledby="headingKpi" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="{{ route('kpi.create') }}">Create KPI</a>
                            <a class="collapse-item" href="{{ route('kpi.index') }}">View KPI List</a>  
                        </div>
                    </div>
                </li>
            @endif

            {{-- ADDED: Staff KPI Management Dropdown --}}
            @if(auth()->user()->access === 'Admin' || auth()->user()->access === 'HR' || auth()->user()->access === 'Employee')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAppraisal"
                        aria-expanded="true" aria-controls="collapseAppraisal">
                        <i class="fas fa-fw fa-clipboard-check"></i>
                        <span>Staff KPI Management</span>
                    </a>
                    <div id="collapseAppraisal" class="collapse" aria-labelledby="headingAppraisal" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="{{ route('pms-dashboard') }}">PMS Profile</a>
                            <a class="collapse-item" href="{{ route('kpi.index') }}">Assigned KPI</a>
                            <a class="collapse-item" href="">Documentation</a>
                        </div>
                    </div>
                </li>
            @endif
        @endif

        <!-- Extras - All roles -->
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Extras</div>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('book') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Employee Handbook</span>
            </a>
        </li>
    @endauth

</ul>
<!-- End of Sidebar -->