<!doctype html>
<html lang="zxx">
    <head>
        <!-- Required Meta Tags -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
        <!-- Bootstrap CSS --> 
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
        <!-- Animate Min CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.min.css') }}">
        <!-- Flaticon CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/fonts/flaticon.css') }}">
        <!-- Boxicons CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/boxicons.min.css') }}">
        <!-- Magnific Popup CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/magnific-popup.css') }}">
        <!-- Owl Carousel Min CSS --> 
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.theme.default.min.css') }}">
        <!-- Nice Select Min CSS --> 
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/nice-select.min.css') }}">
        <!-- Meanmenu CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/meanmenu.css') }}">
        <!-- Jquery Ui CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/jquery-ui.css') }}">
        <!-- Style CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
        <!-- Responsive CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/responsive.css') }}">
        <!-- Theme Dark CSS -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/theme-dark.css') }}">

        <!-- Favicon -->
        @php
            $setting = App\Models\SiteSetting::find(1);
        @endphp
        @if($setting && $setting->favicon)
            <link rel="icon" type="image/png" href="{{ asset($setting->favicon) }}">
        @else
            <link rel="icon" type="image/png" href="{{ asset('frontend/assets/img/favicon.png') }}">
        @endif

        <!-- Dynamic Color Settings -->
        <style>
            :root {
                --primary-color: {{ ($setting && $setting->primary_color) ? $setting->primary_color : '#B56952' }};
                --secondary-color: {{ ($setting && $setting->secondary_color) ? $setting->secondary_color : '#C890FF' }};
                --accent-color: {{ ($setting && $setting->accent_color) ? $setting->accent_color : '#EE786C' }};
                --text-color: {{ ($setting && $setting->text_color) ? $setting->text_color : '#292323' }};
                --link-color: {{ ($setting && $setting->link_color) ? $setting->link_color : '#B56952' }};
            }

            /* Apply Primary Color */
            .btn-bg-one,
            .default-btn.btn-bg-one {
                background-color: var(--primary-color) !important;
            }

            .section-title span {
                color: var(--primary-color) !important;
            }

            /* Apply Secondary Color */
            .btn-bg-two {
                background-color: var(--secondary-color) !important;
            }

            /* Apply Accent Color */
            .btn-bg-three {
                background-color: var(--accent-color) !important;
            }

            .sp-color {
                color: var(--accent-color) !important;
            }

            /* Apply Text Color */
            h1, h2, h3, h4, h5, h6 {
                color: var(--text-color) !important;
            }

            .section-title h2 {
                color: var(--text-color) !important;
            }

            /* Apply Link Color */
            a {
                color: var(--link-color);
            }

            a:hover {
                color: var(--primary-color);
            }

            .article-content a {
                color: var(--link-color) !important;
            }

            .article-content a:hover {
                color: var(--primary-color) !important;
            }

            .article-content table thead {
                background-color: var(--primary-color) !important;
            }

            .article-content blockquote {
                border-left-color: var(--primary-color) !important;
            }

            /* Additional common elements */
            .navbar-area .navbar .navbar-nav .nav-item .nav-link.active,
            .navbar-area .navbar .navbar-nav .nav-item .nav-link:hover {
                color: var(--primary-color) !important;
            }

            .top-header .top-header-left ul li a:hover {
                color: var(--primary-color) !important;
            }

            .inner-banner .inner-title {
                color: var(--text-color) !important;
            }

            /* Button hover effects */
            .default-btn:hover {
                background-color: var(--primary-color) !important;
            }
        </style>

        <!-- CKEditor Content Styles -->
        <style>
            /* CKEditor Content Styling */
            .article-content {
                line-height: 1.8;
                color: #555555;
                overflow-x: auto;
            }

            .article-content h1,
            .article-content h2,
            .article-content h3,
            .article-content h4,
            .article-content h5,
            .article-content h6 {
                margin-top: 1.5em;
                margin-bottom: 0.8em;
                font-weight: 700;
                color: #292323;
                line-height: 1.4;
            }

            .article-content h1 {
                font-size: 2em;
            }

            .article-content h2 {
                font-size: 1.75em;
            }

            .article-content h3 {
                font-size: 1.5em;
            }

            .article-content h4 {
                font-size: 1.25em;
            }

            .article-content p {
                margin-bottom: 1.2em;
                line-height: 1.8;
            }

            .article-content ul,
            .article-content ol {
                margin: 1em 0;
                padding-left: 2em;
            }

            .article-content ul li,
            .article-content ol li {
                margin-bottom: 0.5em;
                line-height: 1.8;
            }

            .article-content ul {
                list-style-type: disc;
            }

            .article-content ol {
                list-style-type: decimal;
            }

            .article-content a {
                color: #B56952;
                text-decoration: underline;
            }

            .article-content a:hover {
                color: #292323;
            }

            .article-content blockquote {
                border-left: 4px solid #B56952;
                padding-left: 1.5em;
                margin: 1.5em 0;
                font-style: italic;
                color: #666;
            }

            /* Table Styling */
            .article-content table {
                width: 100%;
                border-collapse: collapse;
                margin: 1.5em 0;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 5px;
                overflow: hidden;
            }

            .article-content table thead {
                background-color: #B56952;
                color: #fff;
            }

            .article-content table th {
                padding: 12px 15px;
                text-align: left;
                font-weight: 700;
                border: 1px solid #ddd;
            }

            .article-content table td {
                padding: 12px 15px;
                border: 1px solid #ddd;
            }

            .article-content table tbody tr {
                transition: background-color 0.3s;
            }

            .article-content table tbody tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            .article-content table tbody tr:hover {
                background-color: #f5f5f5;
            }

            .article-content table tbody tr:last-child td {
                border-bottom: 1px solid #ddd;
            }

            /* Image Styling */
            .article-content img {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
                margin: 1.5em 0;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            /* Code Styling */
            .article-content code {
                background-color: #f4f4f4;
                padding: 2px 6px;
                border-radius: 3px;
                font-family: 'Courier New', monospace;
                font-size: 0.9em;
            }

            .article-content pre {
                background-color: #f4f4f4;
                padding: 15px;
                border-radius: 5px;
                overflow-x: auto;
                margin: 1.5em 0;
            }

            .article-content pre code {
                background-color: transparent;
                padding: 0;
            }

            /* Responsive Table */
            @media (max-width: 768px) {
                .article-content {
                    overflow-x: auto;
                }

                .article-content table {
                    font-size: 0.9em;
                    min-width: 100%;
                }

                .article-content table th,
                .article-content table td {
                    padding: 8px 10px;
                }
            }
        </style>

        	<!-- toastr CSS -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
    <!-- toastr CSS -->

        <title>Hotel</title>
    </head>
    <body>

        <!-- PreLoader Start -->
        <div class="preloader">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="sk-cube-area">
                        <div class="sk-cube1 sk-cube"></div>
                        <div class="sk-cube2 sk-cube"></div>
                        <div class="sk-cube4 sk-cube"></div>
                        <div class="sk-cube3 sk-cube"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- PreLoader End -->

        <!-- Top Header Start -->
        @include('frontend.body.header')
        <!-- Top Header End -->

        <!-- Start Navbar Area -->
        @include('frontend.body.navbar')
        <!-- End Navbar Area -->

        @yield('main')

        <!-- Footer Area -->
        @include('frontend.body.footer')
        <!-- Footer Area End -->


        <!-- Jquery Min JS -->
        <script src="{{ asset('frontend/assets/js/jquery.min.js') }}"></script>
        <!-- Bootstrap Bundle Min JS -->
        <script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>
        <!-- Magnific Popup Min JS -->
        <script src="{{ asset('frontend/assets/js/jquery.magnific-popup.min.js') }}"></script>
        <!-- Owl Carousel Min JS -->
        <script src="{{ asset('frontend/assets/js/owl.carousel.min.js') }}"></script>
        <!-- Nice Select Min JS -->
        <script src="{{ asset('frontend/assets/js/jquery.nice-select.min.js') }}"></script>
        <!-- Wow Min JS -->
        <script src="{{ asset('frontend/assets/js/wow.min.js') }}"></script>
        <!-- Jquery Ui JS -->
        <script src="{{ asset('frontend/assets/js/jquery-ui.js') }}"></script>
        <!-- Meanmenu JS -->
        <script src="{{ asset('frontend/assets/js/meanmenu.js') }}"></script>
        <!-- Ajaxchimp Min JS -->
        <script src="{{ asset('frontend/assets/js/jquery.ajaxchimp.min.js') }}"></script>
        <!-- Form Validator Min JS -->
        <script src="{{ asset('frontend/assets/js/form-validator.min.js') }}"></script>
        <!-- Contact Form JS -->
        <script src="{{ asset('frontend/assets/js/contact-form-script.js') }}"></script>
        <!-- Custom JS -->
        <script src="{{ asset('frontend/assets/js/custom.js') }}"></script>
        
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
 @if(Session::has('message'))
 var type = "{{ Session::get('alert-type','info') }}"
 switch(type){
    case 'info':
    toastr.info(" {{ Session::get('message') }} ");
    break;

    case 'success':
    toastr.success(" {{ Session::get('message') }} ");
    break;

    case 'warning':
    toastr.warning(" {{ Session::get('message') }} ");
    break;

    case 'error':
    toastr.error(" {{ Session::get('message') }} ");
    break; 
 }
 @endif 
</script>


    </body>
</html>