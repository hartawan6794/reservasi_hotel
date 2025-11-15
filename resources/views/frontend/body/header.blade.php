
@php
    $setting = App\Models\SiteSetting::find(1);
@endphp

<header class="top-header top-header-bg">
    <div class="container">
        <div class="row align-items-center">
             <div class="col-lg-3 col-md-2 pr-0">
                <div class="language-list">
                    <select class="language-list-item" onchange="window.location.href='{{ url('/lang') }}/' + this.value">
                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                        <option value="id" {{ app()->getLocale() == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                    </select>
                </div>
             </div>    

            <div class="col-lg-9 col-md-10">
                <div class="header-right">
                    <ul>
                        <li>
                            <i class='bx bx-home-alt'></i>
                            <a href="#">{{ $setting->address }}</a>
                        </li>
                        <li>
                            <i class='bx bx-phone-call'></i>
                            <a href="tel:{{ $setting->phone }}">{{ $setting->phone }}</a>
                        </li>
  
  @auth

  <li>
    <i class='bx bxs-user-pin'></i>
    <a href="{{ route('dashboard') }}">{{ __('common.dashboard') }}</a>
</li>

<li>
    <i class='bx bxs-user-rectangle'></i>
    <a href="{{ route('user.logout') }}">{{ __('common.logout') }}</a>
</li>

  @else

  <li>
    <i class='bx bxs-user-pin'></i>
    <a href="{{ route('login') }}">{{ __('common.login') }}</a>
</li>

<li>
    <i class='bx bxs-user-rectangle'></i>
    <a href="{{ route('register') }}">{{ __('common.register') }}</a>
</li>
      
  @endauth
                       

                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>