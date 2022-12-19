@include('frontend.layouts.default.header')
<body class="home-page home-01 ">
    @include('frontend.layouts.default.topNavbar')
    <main id="main" class="main-site">
        <div>
            @yield('content')
        </div>
    </main>
    @include('frontend.layouts.default.footer')
    @include('frontend.layouts.default.footerScript')
</body>
</html>