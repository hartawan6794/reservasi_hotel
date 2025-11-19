@extends('frontend.main_master')
@section('main')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

  <!-- Inner Banner -->
  <div class="inner-banner inner-bg10">
    <div class="container">
        <div class="inner-title">
            <ul>
                <li>
                    <a href="index.html">Home</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>Room Details </li>
            </ul>
            <h3>{{ $roomdetails->type->name }}</h3>
        </div>
    </div>
</div>
<!-- Inner Banner End -->

<!-- Room Details Area End -->
<div class="room-details-area pt-100 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="room-details-side">
                    <div class="side-bar-form">
                        <h3>Booking Sheet </h3>

<form action="{{ route('user_booking_store',$roomdetails->id) }}" method="post" id="bk_form">
    @csrf
    
    <input type="hidden" name="room_id" value="{{ $roomdetails->id }}">


    <div class="row align-items-center">
        <div class="col-lg-12">
            <div class="form-group">
                <label>Check in</label>
                <div class="input-group">
    <input autocomplete="off"  type="text" required name="check_in" id="check_in"  class="form-control dt_picker" value="{{ old('check_in') ? date('Y-m-d', strtotime(old('check_in'))) : '' }}" >
                    <span class="input-group-addon"></span>
                </div>
                <i class='bx bxs-calendar'></i>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                <label>Check Out</label>
                <div class="input-group">
   <input autocomplete="off"  type="text" required name="check_out" id="check_out"  class="form-control dt_picker" value="{{ old('check_out') ? date('Y-m-d', strtotime(old('check_out'))) : '' }}" >
                    <span class="input-group-addon"></span>
                </div>
                <i class='bx bxs-calendar'></i>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                <label>Numbers of Persons</label>
                <select class="form-control" name="persion" id="nmbr_person">
                @for ($i = 1; $i <= 4; $i++) 
      <option {{ old('persion') == $i ? 'selected' : '' }} value="0{{ $i }}" >0{{ $i }} </option>
              @endfor
                </select>	
            </div>
        </div>

        <input type="hidden" id="total_adult" value="{{ $roomdetails->total_adult }}">
        <input type="hidden" id="room_price" value="{{ $roomdetails->price }}">
        <input type="hidden" id="discount_p" value="{{ $roomdetails->discount }}">

        <div class="col-lg-12">
            <div class="form-group">
                <label>Numbers of Rooms</label>
                <select class="form-control number_of_rooms" name="number_of_rooms" id="select_room">
                    @for ($i = 1; $i <= 5; $i++)  
                    <option value="0{{ $i }}">0{{ $i }}</option>
                    @endfor
                    
                </select>	
            </div>
            <input type="hidden" name="available_room" id="available_room" >
            <p class="available_room"></p>
        </div>
     

        <div class="col-lg-12">
            <table class="table">
                
    <tbody>
        <tr> 
        <td><p> SubTotal</p></td>
        <td style="text-align: right" ><span class="t_subtotal">0</span> </td> 
        </tr>

        <tr> 
        <td><p> Discount</p></td>
        <td style="text-align: right" ><span class="t_discount">0</span></td> 
        </tr>

        <tr> 
        <td><p> Total</p></td>
        <td style="text-align: right" ><span class="t_g_total">0</span></td> 
        </tr>
        
    </tbody>
              </table>

        </div>






        <div class="col-lg-12 col-md-12">
            <button type="submit" class="default-btn btn-bg-three border-radius-5">
                Book Now
            </button>
        </div>
    </div>
</form>
                    </div>

                  
                </div>
            </div>

            <div class="col-lg-8">
                <div class="room-details-article">
                    
                    <div class="room-details-slider owl-carousel owl-theme">
                        @foreach ($multiImage as $image) 
                        <div class="room-details-item">
                            <img src="{{ asset('upload/roomimg/multi_img/'.$image->multi_img) }}" alt="Images">
                        </div>
                        @endforeach
                       
                    </div>





                    <div class="room-details-title">
                        <h2>{{ $roomdetails->type->name }}</h2>
                        <ul>
                            
                            <li>
                               <b> Basic : {{ rupiah($roomdetails->price) }}/Night/Room</b>
                            </li> 
                         
                        </ul>
                    </div>

                    <div class="room-details-content">
                        <p>
                            {!! $roomdetails->description !!}
                        </p>




<div class="side-bar-plan">
                        <h3>Basic Plan Facilities</h3>
                        <ul>
                            @foreach ($facility as $fac) 
                            <li><a href="#">{{ $fac->facility_name }}</a></li>
                            @endforeach
                        </ul>

                        
                    </div>







<div class="row"> 
<div class="col-lg-6">



<div class="services-bar-widget">
                        <h3 class="title">Room Details </h3>
<div class="side-bar-list">
    <ul>
       <li>
            <a href="#"> <b>Capacity : </b> {{ $roomdetails->room_capacity }} Person <i class='bx bxs-cloud-download'></i></a>
        </li>
        <li>
             <a href="#"> <b>Size : </b> {{ $roomdetails->size }}ft2 <i class='bx bxs-cloud-download'></i></a>
        </li>
       
       
    </ul>
</div>
</div>




</div>



<div class="col-lg-6">
<div class="services-bar-widget">
<h3 class="title">Room Details </h3>
<div class="side-bar-list">
    <ul>
       <li>
            <a href="#"> <b>View : </b> {{ $roomdetails->view }} <i class='bx bxs-cloud-download'></i></a>
        </li>
        <li>
             <a href="#"> <b>Bad Style : </b> {{ $roomdetails->bed_style }} <i class='bx bxs-cloud-download'></i></a>
        </li>
         
    </ul>
</div>
</div> 

            </div> 
                </div>



                    </div>

                    <!-- Reviews Section -->
                    <div class="room-details-review mt-5">
                        <div class="review-header mb-4">
                            <h2>Reviews & Ratings</h2>
                            @if($totalReviews > 0)
                            <div class="review-summary">
                                <div class="average-rating">
                                    <span class="rating-number">{{ number_format($averageRating, 1) }}</span>
                                    <div class="rating-stars-display">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class='bx {{ $i <= round($averageRating) ? 'bxs-star' : 'bx-star' }} text-warning'></i>
                                        @endfor
                                    </div>
                                    <span class="total-reviews">({{ $totalReviews }} {{ $totalReviews == 1 ? 'review' : 'reviews' }})</span>
                                </div>
                            </div>
                            @else
                            <p class="text-muted">No reviews yet. Be the first to review!</p>
                            @endif
                        </div>

                        <!-- Review Form -->
                        <div class="review-form-section mb-5">
                            <h3>Write a Review</h3>
                            <form action="{{ route('room.review.store') }}" method="POST" id="reviewForm">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $roomdetails->id }}">
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Your Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" 
                                                   value="{{ Auth::check() ? Auth::user()->name : '' }}" 
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Your Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" 
                                                   value="{{ Auth::check() ? Auth::user()->email : '' }}" 
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Your Rating <span class="text-danger">*</span></label>
                                    <div class="star-rating" id="starRating">
                                        <input type="radio" name="rating" value="5" id="star5" required>
                                        <label for="star5" class="star-label" data-rating="5">
                                            <i class='bx bxs-star'></i>
                                        </label>
                                        <input type="radio" name="rating" value="4" id="star4">
                                        <label for="star4" class="star-label" data-rating="4">
                                            <i class='bx bxs-star'></i>
                                        </label>
                                        <input type="radio" name="rating" value="3" id="star3">
                                        <label for="star3" class="star-label" data-rating="3">
                                            <i class='bx bxs-star'></i>
                                        </label>
                                        <input type="radio" name="rating" value="2" id="star2">
                                        <label for="star2" class="star-label" data-rating="2">
                                            <i class='bx bxs-star'></i>
                                        </label>
                                        <input type="radio" name="rating" value="1" id="star1">
                                        <label for="star1" class="star-label" data-rating="1">
                                            <i class='bx bxs-star'></i>
                                        </label>
                                        <span class="rating-text">Click to rate</span>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Your Review <span class="text-danger">*</span></label>
                                    <textarea name="comment" class="form-control" rows="6" 
                                              placeholder="Share your experience with this room..." 
                                              required minlength="10"></textarea>
                                    <small class="text-muted">Minimum 10 characters</small>
                                </div>

                                <button type="submit" class="default-btn btn-bg-three">
                                    Submit Review
                                </button>
                            </form>
                        </div>

                        <!-- Reviews List -->
                        @if($reviews->count() > 0)
                        <div class="reviews-list">
                            <h3>Customer Reviews</h3>
                            @foreach($reviews as $review)
                            <div class="review-item">
                                <div class="review-author">
                                    <div class="author-avatar">
                                        <i class='bx bx-user'></i>
                                    </div>
                                    <div class="author-info">
                                        <h4>{{ $review->name }}</h4>
                                        <span class="review-date">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class='bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }} text-warning'></i>
                                    @endfor
                                </div>
                                <div class="review-comment">
                                    <p>{{ $review->comment }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <style>
                    .review-header {
                        border-bottom: 2px solid #e8e8e8;
                        padding-bottom: 20px;
                    }
                    
                    .review-summary {
                        margin-top: 15px;
                    }
                    
                    .average-rating {
                        display: flex;
                        align-items: center;
                        gap: 15px;
                    }
                    
                    .rating-number {
                        font-size: 48px;
                        font-weight: 700;
                        color: #B56952;
                    }
                    
                    .rating-stars-display {
                        display: flex;
                        gap: 3px;
                    }
                    
                    .rating-stars-display i {
                        font-size: 24px;
                    }
                    
                    .total-reviews {
                        color: #777;
                        font-size: 16px;
                    }
                    
                    .review-form-section {
                        background: #f8f9fa;
                        padding: 30px;
                        border-radius: 15px;
                        margin-bottom: 40px;
                    }
                    
                    .star-rating {
                        display: flex;
                        flex-direction: row-reverse;
                        justify-content: flex-end;
                        gap: 5px;
                        position: relative;
                    }
                    
                    .star-rating input[type="radio"] {
                        display: none;
                    }
                    
                    .star-label {
                        font-size: 32px;
                        color: #ddd;
                        cursor: pointer;
                        transition: all 0.2s;
                    }
                    
                    .star-label:hover {
                        color: #ffc107 !important;
                    }
                    
                    .star-label.active {
                        color: #ffc107 !important;
                    }
                    
                    .star-label.active i {
                        color: #ffc107 !important;
                    }
                    
                    .rating-text {
                        margin-left: 15px;
                        color: #777;
                        font-size: 14px;
                        align-self: center;
                    }
                    
                    .review-item {
                        background: #fff;
                        padding: 25px;
                        border-radius: 10px;
                        margin-bottom: 20px;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                    }
                    
                    .review-author {
                        display: flex;
                        align-items: center;
                        gap: 15px;
                        margin-bottom: 15px;
                    }
                    
                    .author-avatar {
                        width: 50px;
                        height: 50px;
                        border-radius: 50%;
                        background: #B56952;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: #fff;
                        font-size: 24px;
                    }
                    
                    .author-info h4 {
                        margin: 0;
                        font-size: 18px;
                        color: #292323;
                    }
                    
                    .review-date {
                        color: #777;
                        font-size: 14px;
                    }
                    
                    .review-rating {
                        margin-bottom: 10px;
                    }
                    
                    .review-rating i {
                        font-size: 18px;
                    }
                    
                    .review-comment p {
                        margin: 0;
                        color: #555;
                        line-height: 1.8;
                    }
                    </style>

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const starLabels = document.querySelectorAll('.star-label');
                        const ratingText = document.querySelector('.rating-text');
                        const ratingInputs = document.querySelectorAll('input[name="rating"]');
                        
                        // Handle click on stars
                        starLabels.forEach((label) => {
                            label.addEventListener('click', function(e) {
                                e.preventDefault();
                                const rating = parseInt(this.getAttribute('data-rating'));
                                const input = document.getElementById('star' + rating);
                                input.checked = true;
                                updateStarDisplay(rating);
                            });
                            
                            // Hover effect
                            label.addEventListener('mouseenter', function() {
                                const rating = parseInt(this.getAttribute('data-rating'));
                                highlightStars(rating);
                            });
                        });
                        
                        // Reset on mouse leave
                        document.querySelector('.star-rating').addEventListener('mouseleave', function() {
                            const checkedInput = document.querySelector('input[name="rating"]:checked');
                            if (checkedInput) {
                                updateStarDisplay(parseInt(checkedInput.value));
                            } else {
                                resetStars();
                            }
                        });
                        
                        // Handle radio change
                        ratingInputs.forEach(input => {
                            input.addEventListener('change', function() {
                                updateStarDisplay(parseInt(this.value));
                            });
                        });
                        
                        function updateStarDisplay(rating) {
                            starLabels.forEach((label) => {
                                const labelRating = parseInt(label.getAttribute('data-rating'));
                                const icon = label.querySelector('i');
                                
                                if (labelRating <= rating) {
                                    label.classList.add('active');
                                    label.style.color = '#ffc107';
                                    icon.className = 'bx bxs-star';
                                    icon.style.color = '#ffc107';
                                } else {
                                    label.classList.remove('active');
                                    label.style.color = '#ddd';
                                    icon.className = 'bx bx-star';
                                    icon.style.color = '#ddd';
                                }
                            });
                            
                            const ratingTexts = {
                                1: 'Poor',
                                2: 'Fair',
                                3: 'Good',
                                4: 'Very Good',
                                5: 'Excellent'
                            };
                            ratingText.textContent = ratingTexts[rating] || 'Click to rate';
                        }
                        
                        function highlightStars(rating) {
                            starLabels.forEach((label) => {
                                const labelRating = parseInt(label.getAttribute('data-rating'));
                                const icon = label.querySelector('i');
                                
                                if (labelRating <= rating) {
                                    label.style.color = '#ffc107';
                                    icon.style.color = '#ffc107';
                                } else {
                                    label.style.color = '#ddd';
                                    icon.style.color = '#ddd';
                                }
                            });
                        }
                        
                        function resetStars() {
                            starLabels.forEach((label) => {
                                label.style.color = '#ddd';
                                const icon = label.querySelector('i');
                                icon.style.color = '#ddd';
                            });
                            ratingText.textContent = 'Click to rate';
                        }
                        
                        // Handle form submission
                        document.getElementById('reviewForm').addEventListener('submit', function(e) {
                            const rating = document.querySelector('input[name="rating"]:checked');
                            if (!rating) {
                                e.preventDefault();
                                alert('Please select a rating');
                                return false;
                            }
                        });
                    });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Room Details Area End -->

<!-- Room Details Other -->
<div class="room-details-other pb-70">
    <div class="container">
        <div class="room-details-text">
            <h2>Other Rooms </h2>
        </div>

        <div class="row ">
           
           @foreach ($otherRooms as $item)
            <div class="col-lg-6">
                <div class="room-card-two">
                    <div class="row align-items-center">
                        <div class="col-lg-5 col-md-4 p-0">
                            <div class="room-card-img">
                                <a href="{{ url('room/details/'.$item->id) }}">
                                    <img src="{{ asset( 'upload/roomimg/'.$item->image ) }}" alt="Images">
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-8 p-0">
                            <div class="room-card-content">
                                 <h3>
             <a href="{{ url('room/details/'.$item->id) }}">{{ $item['type']['name'] }}</a>
                                </h3>
                                <span>{{ rupiah($item->price) }} / Per Night </span>
                                <div class="rating">
                                    <i class='bx bxs-star'></i>
                                    <i class='bx bxs-star'></i>
                                    <i class='bx bxs-star'></i>
                                    <i class='bx bxs-star'></i>
                                    <i class='bx bxs-star'></i>
                                </div>
                                <p>{{ $item->short_desc }}</p>
                                <ul>
                   <li><i class='bx bx-user'></i> {{ $item->room_capacity }} Person</li>
                   <li><i class='bx bx-expand'></i> {{ $item->size }}ft2</li>
                                </ul>

                                <ul>
        <li><i class='bx bx-show-alt'></i>{{ $item->view }}</li>
        <li><i class='bx bxs-hotel'></i> {{ $item->bed_style }}</li>
                                </ul>
                                
                                <a href="room-details.html" class="book-more-btn">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            @endforeach
           


        </div>
    </div>
</div>
<!-- Room Details Other End -->

<script>
    $(document).ready(function () {
       var check_in = "{{ old('check_in') }}";
       var check_out = "{{ old('check_out') }}";
       var room_id = "{{ $room_id }}";
       if (check_in != '' && check_out != ''){
          getAvaility(check_in, check_out, room_id);
       }


       $("#check_out").on('change', function () {
          var check_out = $(this).val();
          var check_in = $("#check_in").val();

          if(check_in != '' && check_out != ''){
             getAvaility(check_in, check_out, room_id);
          }
       });

       $(".number_of_rooms").on('change', function () {
          var check_out = $("#check_out").val();
          var check_in = $("#check_in").val();

          if(check_in != '' && check_out != ''){
             getAvaility(check_in, check_out, room_id);
          }
       });


    });



    function getAvaility(check_in, check_out, room_id) {
       $.ajax({
          url: "{{ route('check_room_availability') }}",
          data: {room_id:room_id, check_in:check_in, check_out:check_out},
          success: function(data){
             $(".available_room").html('Availability : <span class="text-success">'+data['available_room']+' Rooms</span>');
             $("#available_room").val(data['available_room']);
             price_calculate(data['total_nights']);
          }
       });
    }

    // Fungsi untuk format Rupiah (JavaScript)
    function rupiah(amount, withPrefix = true) {
       // Konversi ke number
       amount = parseFloat(amount) || 0;
       
       // Format dengan pemisah ribuan (titik) dan tanpa desimal
       var formatted = Math.floor(amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
       
       // Tambahkan prefix Rp jika diperlukan
       if (withPrefix) {
          return 'Rp ' + formatted;
       }
       
       return formatted;
    }

    function price_calculate(total_nights){
       var room_price = $("#room_price").val();
       var discount_p = $("#discount_p").val();
       var select_room = $("#select_room").val();

       var sub_total = room_price * total_nights * parseInt(select_room);

       var discount_price = (parseInt(discount_p)/100)*sub_total;

       $(".t_subtotal").text(rupiah(sub_total));
       $(".t_discount").text(rupiah(discount_price));
       $(".t_g_total").text(rupiah(sub_total-discount_price));

    }

    $("#bk_form").on('submit', function () {
       var av_room = $("#available_room").val();
       var select_room = $("#select_room").val();
       if (parseInt(select_room) >  av_room){
          alert('Sorry, you select maximum number of room');
          return false;
       }
       var nmbr_person = $("#nmbr_person").val();
       var total_adult = $("#total_adult").val();
       if(parseInt(nmbr_person) > parseInt(total_adult)){
          alert('Sorry, you select maximum number of person');
          return false;
       }

    })
 </script>


@endsection