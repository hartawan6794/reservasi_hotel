@php
    $testimonial = App\Models\Testimonial::latest()->take(10)->get();
@endphp

@if($testimonial->count() > 0)
<div class="testimonials-area-three pb-70">
    <div class="container">
        <div class="section-title text-center">
            <span class="sp-color">TESTIMONIAL</span>
            <h2>Our Latest Testimonials and What Our Client Says</h2>
            <p class="mt-3">Discover what our satisfied guests have to say about their experience with us</p>
        </div>
        <div class="row align-items-center pt-45">
            <div class="col-lg-6 col-md-6">
                <div class="testimonials-img-two">
                    <img src="{{ asset('frontend/assets/img/testimonials/testimonials-img5.jpg') }}" alt="Testimonials" style="width: 100%; height: auto; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="testimonials-slider-area owl-carousel owl-theme">
                    @foreach ($testimonial as $item) 
                    <div class="testimonials-slider-content">
                        <div class="quote-icon">
                            <i class="flaticon-left-quote"></i>
                        </div>
                        <p class="testimonial-message">
                            "{{ $item->message }}"
                        </p>
                        <div class="testimonial-author">
                            <img src="{{ asset($item->image ?? 'upload/no_image.jpg') }}" alt="{{ $item->name }}" 
                                 class="author-image">
                            <div class="author-info">
                                <h3>{{ $item->name }}</h3>
                                <span><i class="bx bx-map"></i> {{ $item->city }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.testimonials-slider-content {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 40px 35px;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
    margin: 15px;
}

.testimonials-slider-content:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 50px rgba(0,0,0,0.12);
}

.quote-icon {
    position: absolute;
    top: 20px;
    right: 25px;
    opacity: 0.1;
}

.quote-icon i {
    font-size: 80px;
    color: #B56952;
}

.testimonial-message {
    font-size: 16px;
    line-height: 1.8;
    color: #555;
    font-style: italic;
    margin: 25px 0 30px 0;
    position: relative;
    z-index: 1;
    padding-right: 20px;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #e8e8e8;
}

.author-image {
    width: 70px !important;
    height: 70px !important;
    min-width: 70px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    flex-shrink: 0;
}

.author-info {
    flex: 1;
    min-width: 0;
}

.author-info h3 {
    font-size: 18px;
    font-weight: 600;
    color: #292323;
    margin-bottom: 5px;
}

.author-info span {
    color: #777;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.author-info span i {
    font-size: 16px;
    color: #B56952;
}

.testimonials-slider-area .owl-dots {
    text-align: center;
    margin-top: 30px;
}

.testimonials-slider-area .owl-dots .owl-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #ddd;
    margin: 0 5px;
    transition: all 0.3s ease;
}

.testimonials-slider-area .owl-dots .owl-dot.active {
    background: #B56952;
    width: 30px;
    border-radius: 10px;
}

.testimonials-slider-area .owl-nav {
    margin-top: 20px;
}

.testimonials-slider-area .owl-nav button {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #e0e0e0;
    color: #555;
    font-size: 20px;
    transition: all 0.3s ease;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.testimonials-slider-area .owl-nav button:hover {
    background: #B56952;
    border-color: #B56952;
    color: #fff;
    transform: scale(1.1);
}

@media (max-width: 768px) {
    .testimonials-slider-content {
        padding: 30px 25px;
        margin: 10px;
    }
    
    .testimonial-message {
        font-size: 15px;
    }
    
    .testimonials-img-two {
        margin-bottom: 40px;
    }
}
</style>
@else
<div class="testimonials-area-three pb-70">
    <div class="container">
        <div class="section-title text-center">
            <span class="sp-color">TESTIMONIAL</span>
            <h2>Our Latest Testimonials and What Our Client Says</h2>
        </div>
        <div class="row justify-content-center pt-45">
            <div class="col-lg-8 col-md-10">
                <div class="text-center py-5">
                    <i class="flaticon-left-quote" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
                    <p class="text-muted" style="font-size: 18px;">No testimonials available at the moment. Check back soon!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif