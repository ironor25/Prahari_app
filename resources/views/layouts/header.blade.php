<!-- app-header -->
<header class="app-header sticky" id="header">

    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid bg-body-secondary rounded-3 border-bottom-2" >

        <!-- Start::header-content-left -->
        <div class="header-content-left">
            <div class="header-element mx-lg-0">
               <span class="header-link fw-bold text-dark fs-5 d-flex align-items-center">
                   <i class="bi bi-shield-lock fs-4 me-2"></i> Prahari Admin
               </span>
            </div>

            <!-- Start::header-element -->
            <div class="header-element mx-lg-0">
                <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link" data-bs-toggle="sidebar"
                    href="javascript:void(0);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon menu-btn" width="24"
                        height="24" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.5" d="M4 5h12M4 12h16M4 19h8" color="currentColor" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon menu-btn-close" width="24"
                        height="24" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.5" d="m18 6l-6 6m0 0l-6 6m6-6l6 6m-6-6L6 6" color="currentColor" />
                    </svg>
                </a>
            </div>
            <!-- End::header-element -->

           

        </div>
        <!-- End::header-content-left -->

        <!-- Start::header-content-right -->
        <ul class="header-content-right d-flex align-items-center mb-0">
            <li class="header-element">
                <a href="javascript:void(0);" class="header-link d-flex align-items-center text-dark text-decoration-none pe-3">
                    <div class="d-flex align-items-center justify-content-center bg-dark text-white rounded-circle me-2" style="width: 38px; height: 38px;">
                        <i class="bi bi-person-fill fs-5"></i>
                    </div>
                    <span class="fw-semibold">{{ Auth::user()->name ?? 'Admin User' }}</span>
                </a>
            </li>
        </ul>
        </div>

</header>
<!-- /app-header -->
