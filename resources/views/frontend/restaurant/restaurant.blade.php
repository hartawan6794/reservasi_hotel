@extends('frontend.main_master')
@section('main')

<!-- Inner Banner -->
<div class="inner-banner inner-bg2">
    <div class="container">
        <div class="inner-title">
            <ul>
                <li>
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>Restaurant</li>
            </ul>
            <h3>Restaurant</h3>
        </div>
    </div>
</div>
<!-- Inner Banner End -->

<!-- Restaurant Area -->
<div class="restaurant-area pt-100 pb-70">
    <div class="container">
        <div class="section-title text-center">
            <span class="sp-color">RESTAURANT</span>
            <h2>Our Fine Dining Experience</h2>
            <p>
                Indulge in a culinary journey at our restaurant, where we serve exquisite dishes prepared with the finest ingredients and served with exceptional hospitality.
            </p>
        </div>

        <div class="row pt-45">
            <div class="col-lg-6 col-md-6">
                <div class="restaurant-item">
                    <div class="restaurant-img">
                        <img src="{{ asset('frontend/assets/img/restaurant/restaurant-img1.jpg') }}" alt="Images">
                    </div>
                    <div class="restaurant-content">
                        <h3><a href="javascript:void(0)">Breakfast Menu</a></h3>
                        <p>
                            Start your day with our delicious breakfast selection, featuring fresh pastries, eggs, pancakes, and a variety of hot beverages. Perfect for a morning meal before your day begins.
                        </p>
                        <h4>Available: 6:00 AM - 11:00 AM</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="restaurant-item">
                    <div class="restaurant-img">
                        <img src="{{ asset('frontend/assets/img/restaurant/restaurant-img2.jpg') }}" alt="Images">
                    </div>
                    <div class="restaurant-content">
                        <h3><a href="javascript:void(0)">Lunch Special</a></h3>
                        <p>
                            Enjoy our lunch specials with a wide range of local and international cuisines. From light salads to hearty main courses, we have something to satisfy every palate.
                        </p>
                        <h4>Available: 11:00 AM - 3:00 PM</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="restaurant-item">
                    <div class="restaurant-img">
                        <img src="{{ asset('frontend/assets/img/restaurant/restaurant-img3.jpg') }}" alt="Images">
                    </div>
                    <div class="restaurant-content">
                        <h3><a href="javascript:void(0)">Dinner Experience</a></h3>
                        <p>
                            Experience fine dining at its best with our dinner menu. Featuring premium ingredients, expertly prepared dishes, and an elegant atmosphere perfect for special occasions.
                        </p>
                        <h4>Available: 6:00 PM - 11:00 PM</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="restaurant-item">
                    <div class="restaurant-img">
                        <img src="{{ asset('frontend/assets/img/restaurant/restaurant-img4.jpg') }}" alt="Images">
                    </div>
                    <div class="restaurant-content">
                        <h3><a href="javascript:void(0)">Bar & Lounge</a></h3>
                        <p>
                            Unwind at our bar and lounge with a selection of premium cocktails, wines, and spirits. Perfect for relaxing after a long day or enjoying evening entertainment.
                        </p>
                        <h4>Available: 4:00 PM - 12:00 AM</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Restaurant Area End -->

<!-- Restaurant Features Area -->
<div class="choose-area pt-100 pb-70 section-bg">
    <div class="container">
        <div class="section-title text-center">
            <span class="sp-color">FEATURES</span>
            <h2>Why Choose Our Restaurant</h2>
        </div>
        <div class="row pt-45">
            <div class="col-lg-4 col-md-6">
                <div class="choose-card">
                    <i class='bx bx-restaurant'></i>
                    <h3>Expert Chefs</h3>
                    <p>Our experienced chefs bring years of culinary expertise to create exceptional dishes that delight your taste buds.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="choose-card">
                    <i class='bx bx-food-menu'></i>
                    <h3>Fresh Ingredients</h3>
                    <p>We use only the freshest, locally sourced ingredients to ensure the highest quality in every dish we serve.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="choose-card">
                    <i class='bx bx-wine'></i>
                    <h3>Premium Beverages</h3>
                    <p>Enjoy our curated selection of fine wines, craft beers, and signature cocktails to complement your meal.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="choose-card">
                    <i class='bx bx-time-five'></i>
                    <h3>Flexible Hours</h3>
                    <p>We serve breakfast, lunch, and dinner with extended hours to accommodate your schedule and preferences.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="choose-card">
                    <i class='bx bx-group'></i>
                    <h3>Private Dining</h3>
                    <p>Host your special events in our private dining area, perfect for celebrations, business meetings, or intimate gatherings.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="choose-card">
                    <i class='bx bx-heart'></i>
                    <h3>Exceptional Service</h3>
                    <p>Our attentive staff is dedicated to providing you with personalized service and ensuring a memorable dining experience.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Restaurant Features Area End -->

<!-- Contact Info Area -->
@if($setting)
<div class="contact-another pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="contact-another-content">
                    <div class="section-title text-center">
                        <h2>Restaurant Reservation</h2>
                        <p>
                            For reservations or inquiries about our restaurant, please contact us using the information below.
                        </p>
                    </div>

                    <div class="contact-item text-center">
                        <ul>
                            <li>
                                <i class='bx bx-phone-call'></i>
                                <div class="content">
                                    <span><a href="tel:{{ $setting->phone ?? '' }}">{{ $setting->phone ?? 'N/A' }}</a></span>
                                    <span>Call for Reservations</span>
                                </div>
                            </li>
                            <li>
                                <i class='bx bx-envelope'></i>
                                <div class="content">
                                    <span><a href="mailto:{{ $setting->email ?? '' }}">{{ $setting->email ?? 'N/A' }}</a></span>
                                    <span>Email for Inquiries</span>
                                </div>
                            </li>
                            <li>
                                <i class='bx bx-time-five'></i>
                                <div class="content">
                                    <span>Daily: 6:00 AM - 12:00 AM</span>
                                    <span>Open Every Day</span>
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

