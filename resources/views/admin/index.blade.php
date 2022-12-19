@include('admin.header')

    <!-- Left Panel -->

    @include('admin.sideBar')

    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        @include('admin.topNavBar')
        <!-- Header-->

        @yield('dashboardTitle')
        <!-- .content Area-->
        
        @yield('content')
        
        <!-- content Area -->
        
    </div>

    <!-- Right Panel -->
    
    <!-- Footer Script -->
    @include('admin.footerScript')
    <!-- Footer Script -->