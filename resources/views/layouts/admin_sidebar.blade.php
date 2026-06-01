<!-- Start::app-sidebar -->
<aside class="app-sidebar sticky bg-body-tertiary" id="sidebar">


    <div class="main-sidebar" id="sidebar-scroll">

        
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu" >
                <li class="slide">
                    <a href="{{ route('admin.dashboard') }}" class="side-menu__item">
                        <i class="bi bi-house-door me-2 text-primary fs-5"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li> 
                 <li class="slide">
                    <a href="{{ route('admin.praharis.index') }}" class="side-menu__item">
                        <i class="bi bi-people me-2 text-primary fs-5"></i>
                        <span class="side-menu__label">Prahari</span>
                    </a>
                </li> 
                 <li class="slide">
                    <a href="{{ route('admin.cases.index') }}" class="side-menu__item">
                        <i class="bi bi-file-text me-2 text-primary fs-5"></i>
                        <span class="side-menu__label">Cases</span>
                    </a>
                </li> 
                 <li class="slide">
                    <a href="{{ route('admin.challans.index') }}" class="side-menu__item">
                        <i class="bi bi-receipt me-2 text-primary fs-5"></i>
                        <span class="side-menu__label">Challans</span>
                    </a>
                </li> 
                <li class="slide">
                    <a href="{{ route('admin.transactions.index') }}" class="side-menu__item">
                        <i class="bi bi-credit-card me-2 text-primary fs-5"></i>
                        <span class="side-menu__label">Payments</span>
                    </a>
                </li> 
                 <li class="slide">
                    <a href="{{ route('admin.reports') }}" class="side-menu__item">
                        <i class="bi bi-file-earmark-bar-graph me-2 text-primary fs-5"></i>
                        <span class="side-menu__label">Reports</span>
                    </a>
                </li> 
                 <li class="slide">
                    <a href="{{ route('admin.admins') }}" class="side-menu__item">
                        <i class="bi bi-person me-2 text-primary fs-5"></i>
                        <span class="side-menu__label">Admins</span>
                    </a>
                </li> 
                 <li class="slide">
                    <a href="{{ route('admin.settings') }}" class="side-menu__item">
                        <i class="bi bi-gear me-2 text-primary fs-5"></i>
                        <span class="side-menu__label">Settings</span>
                    </a>
                </li> 

                <li class="slide">
                    <a href="{{ route('logout') }}" class="side-menu__item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-left me-2 text-primary fs-5"></i>
                        <span class="side-menu__label">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg></div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>
<!-- End::app-sidebar -->

