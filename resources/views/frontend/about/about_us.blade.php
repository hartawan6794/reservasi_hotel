@extends('frontend.main_master')
@section('main')

<!-- Inner Banner -->
<div class="inner-banner inner-bg1">
    <div class="container">
        <div class="inner-title">
            <ul>
                <li>
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>About Us</li>
            </ul>
            <h3>About Us</h3>
        </div>
    </div>
</div>
<!-- Inner Banner End -->

<!-- About Area -->
<div class="about-area pt-100 pb-70">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-content">
                    <div class="section-title">
                        <span class="sp-color">ABOUT US</span>
                        <h2>We Are More Than A Hotel Company</h2>
                        <p>
                            Welcome to our luxurious hotel, where comfort meets elegance. We are dedicated to providing you with an unforgettable experience during your stay. Our hotel combines modern amenities with traditional hospitality to ensure your complete satisfaction.
                        </p>
                        <p>
                            With years of experience in the hospitality industry, we have built a reputation for excellence. Our team is committed to making your stay as comfortable and enjoyable as possible, offering personalized service that exceeds expectations.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-img">
                    <img src="{{ asset('frontend/assets/img/about/about-img1.jpg') }}" alt="Images">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About Area End -->

<!-- Choose Area -->
<div class="choose-area pt-100 pb-70">
    <div class="container">
        <div class="section-title text-center">
            <span class="sp-color">WHY CHOOSE US</span>
            <h2>Our Hotel Features</h2>
        </div>
        <div class="row pt-45">
            <div class="col-lg-4 col-md-6">
                <div class="choose-card">
                    <i class='bx bx-home-alt'></i>
                    <h3>Luxurious Rooms</h3>
                    <p>Experience comfort and elegance in our beautifully designed rooms, equipped with modern amenities for your convenience.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="choose-card">
                    <i class='bx bx-restaurant'></i>
                    <h3>Fine Dining</h3>
                    <p>Enjoy exquisite cuisine at our restaurant, featuring a variety of local and international dishes prepared by our expert chefs.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="choose-card">
                    <i class='bx bx-spa'></i>
                    <h3>Spa & Wellness</h3>
                    <p>Relax and rejuvenate at our spa facility, offering a range of treatments to help you unwind and feel refreshed.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="choose-card">
                    <i class='bx bx-swim'></i>
                    <h3>Swimming Pool</h3>
                    <p>Take a refreshing dip in our outdoor swimming pool, perfect for relaxation and recreation during your stay.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="choose-card">
                    <i class='bx bx-wifi'></i>
                    <h3>Free WiFi</h3>
                    <p>Stay connected with high-speed internet access available throughout the hotel, free of charge for all guests.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="choose-card">
                    <i class='bx bx-support'></i>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated staff is available around the clock to assist you with any needs or inquiries during your stay.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Choose Area End -->

<!-- Team Area -->
@if($team->count() > 0)
<div class="team-area-three pt-100 pb-70">
    <div class="container">
        <div class="section-title text-center">
            <span class="sp-color">TEAM</span>
            <h2>Let's Meet Up With Our Special Team Members</h2>
        </div>
        <div class="team-slider-two owl-carousel owl-theme pt-45">
            @foreach ($team as $item) 
            <div class="team-item">
                <a href="javascript:void(0)">
                    @if(!empty($item->image) && file_exists(public_path($item->image)))
                        <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                    @else
                        <img src="{{ asset('upload/no_image.jpg') }}" alt="{{ $item->name }}">
                    @endif
                </a>
                <div class="content">
                    <h3><a href="javascript:void(0)">{{ $item->name }}</a></h3>
                    <span>{{ $item->postion }}</span>
                    <ul class="social-link">
                        <li>
                            <a href="{{ $item->facebook }}" target="_blank"><i class='bx bxl-facebook'></i></a>
                        </li> 
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
<!-- Team Area End -->

<!-- Contact Info Area -->
@if($setting)
<div class="contact-another pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="contact-another-content">
                    <div class="section-title text-center">
                        <h2>Contact Information</h2>
                        <p>
                            We are one of the best hotels and we can easily make a contact
                            with us anytime on the below details.
                        </p>
                    </div>

                    <div class="contact-item text-center">
                        <ul>
                            <li>
                                <i class='bx bx-home-alt'></i>
                                <div class="content">
                                    <span>{{ $setting->address ?? 'N/A' }}</span>
                                </div>
                            </li>
                            <li>
                                <i class='bx bx-phone-call'></i>
                                <div class="content">
                                    <span><a href="tel:{{ $setting->phone ?? '' }}">{{ $setting->phone ?? 'N/A' }}</a></span>
                                </div>
                            </li>
                            <li>
                                <i class='bx bx-envelope'></i>
                                <div class="content">
                                    <span><a href="mailto:{{ $setting->email ?? '' }}">{{ $setting->email ?? 'N/A' }}</a></span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- Contact Info Area End -->

@endsection

