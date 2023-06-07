<nav class="navbar navbar-light navbar-glass navbar-top navbar-expand">

    <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false"
        aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
    <a class="navbar-brand me-1 me-sm-3" href="{{ route('home') }}">
        <div class="d-flex align-items-center"><img class="me-2" src="{{ asset('assets/img/logo-blue.png') }}"
                alt="" width="150" />
        </div>
    </a>
    <ul class="navbar-nav align-items-center d-none d-lg-block">
        <li class="nav-item">
            <div class="search-box" data-list='{"valueNames":["title"]}'>
                <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
                    <input class="form-control search-input fuzzy-search" type="search" value="{{ request()->search }}"
                        name="search" autofocus placeholder="Search..." aria-label="Search" />
                    <span class="fas fa-search search-box-icon"></span>

                </form>
            </div>
        </li>
    </ul>
    <ul class="navbar-nav navbar-nav-icons ms-auto flex-row align-items-center">
        <li class="nav-item">
            <div class="theme-control-toggle fa-icon-wait px-2">
                <a style="text-decoration: none" href="{{ route('setlocale') }}">
                    <div
                        style="background-color:#d8e2ef; align-items: center; display: flex; justify-content: center; padding:5px ; border-radius:50% ; width:30px; height:30px">
                        <span data-bs-toggle="tooltip" data-bs-placement="left" title="Switch language"
                            style="color:#2c7be5 !important" class="material-icons text-secondary fs-2">language</span>
                    </div>
                </a>

            </div>
        </li>
        <li class="nav-item">
            <div class="theme-control-toggle fa-icon-wait px-2">
                <input class="form-check-input ms-0 theme-control-toggle-input" id="themeControlToggle" type="checkbox"
                    data-theme-control="theme" value="dark" />
                <label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle"
                    data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to light theme"><span
                        class="fas fa-sun fs-0"></span></label>
                <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle"
                    data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to dark theme"><span
                        class="fas fa-moon fs-0"></span></label>
            </div>
        </li>
        @if (Auth::user()->hasRole('affiliate'))
            <li class="nav-item  d-sm-block">
                <a class="nav-link px-0 notification-indicator notification-indicator-warning notification-indicator-fill fa-icon-wait"
                    href="{{ route('cart') }}"><span class="fas fa-shopping-cart" data-fa-transform="shrink-7"
                        style="font-size: 33px;"></span><span
                        class="notification-indicator-number cart-count">{{ Auth::user()->cart->products->count() }}</span></a>

            </li>
            <li class="nav-itemd-sm-block">
                <a class="nav-link px-0 notification-indicator notification-indicator-danger notification-indicator-fill fa-icon-wait"
                    href="{{ route('favorite') }}"><span class="fas fa-solid fa-heart" data-fa-transform="shrink-7"
                        style="font-size: 33px;"></span><span
                        class="notification-indicator-number fav-icon">{{ Auth::user()->fav->count() }}</span></a>

            </li>
        @endif

        {{-- <li class="nav-item dropdown">
            <a class="nav-link notification-indicator notification-indicator-primary notification-indicator-fill px-0 fa-icon-wait"
                id="navbarDropdownNotification" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false" data-hide-on-body-scroll="data-hide-on-body-scroll"><span class="fas fa-bell"
                    data-fa-transform="shrink-6" style="font-size: 33px;"></span><span
                    class="notification-indicator-number noty-count">{{ Auth::user()->notifications->where('status', 0)->count() }}</span></a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-card dropdown-menu-notification dropdown-caret-bg noty-nav"
                aria-labelledby="navbarDropdownNotification" data-local="{{ app()->getLocale() }}"
                data-id="{{ Auth::id() }}">
                <div class="card card-notification shadow-none">
                    <div class="card-header">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto">
                                <h6 class="card-header-title mb-0">Notifications</h6>
                            </div>
                            <div class="col-auto ps-0 ps-sm-3"><a class="card-link fw-normal"
                                    href="{{ route('notifications.change.all') }}">Mark all as
                                    read</a></div>
                        </div>
                    </div>
                    <div class="scrollbar-overlay" style="max-height:19rem">
                        <div class="list-group list-group-flush fw-normal fs--1">
                            <div class="list-group-title border-bottom">NEW</div>
                            <div class="noty-list">
                                @foreach (Auth::user()->notifications()->where('status', 0)->orderBy('id', 'desc')->limit(50)->get() as $notification)
                                    <div class="list-group-item">
                                        <a class="notification notification-flush notification-unread noty"
                                            href="{{ $notification->url }}"
                                            data-url="{{ route('notifications.change', ['notification' => $notification->id]) }}">
                                            <div class="notification-avatar">
                                                <div class="avatar avatar-2xl me-3">
                                                    <img class="rounded-circle"
                                                        src="{{ Auth::user()->hasRole('administrator|superadministrator') ? $notification->sender_image : asset('assets/img/fevicon.png') }}"
                                                        alt="" />

                                                </div>
                                            </div>
                                            <div class="notification-body">
                                                <p class="mb-1">
                                                    <strong>{{ app()->getLocale() == 'ar' ? $notification->title_ar : $notification->title_en }}</strong>
                                                    {{ app()->getLocale() == 'ar' ? $notification->body_ar : $notification->body_en }}
                                                </p>
                                                @php
                                                    $date = Carbon\Carbon::now();
                                                    $interval = $notification->created_at->diffForHumans($date);
                                                @endphp
                                                <span class="notification-time"><span class="me-2" role="img"
                                                        aria-label="Emoji">💬</span>{{ $interval }}</span>

                                            </div>
                                        </a>

                                    </div>
                                @endforeach
                            </div>

                            <div class="list-group-title border-bottom">EARLIER</div>
                            @foreach (Auth::user()->notifications()->where('status', 1)->orderBy('id', 'desc')->limit(50)->get() as $notification)
                                <div class="list-group-item">
                                    <a class="notification notification-flush noty" href="{{ $notification->url }}"
                                        data-url="{{ route('notifications.change', ['notification' => $notification->id]) }}">
                                        <div class="notification-avatar">
                                            <div class="avatar avatar-2xl me-3">
                                                <img class="rounded-circle"
                                                    src="{{ asset('assets/img/fevicon.png') }}" alt="" />

                                            </div>
                                        </div>
                                        <div class="notification-body">
                                            <p class="mb-1">
                                                <strong>{{ app()->getLocale() == 'ar' ? $notification->title_ar : $notification->title_en }}</strong>
                                                {{ app()->getLocale() == 'ar' ? $notification->body_ar : $notification->body_en }}
                                            </p>
                                            @php
                                                $date = Carbon\Carbon::now();
                                                $interval = $notification->created_at->diffForHumans($date);
                                            @endphp
                                            <span class="notification-time"><span class="me-2" role="img"
                                                    aria-label="Emoji">💬</span>{{ $interval }}</span>

                                        </div>
                                    </a>

                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="card-footer text-center border-top"><a class="card-link d-block"
                            href="{{ route('notifications.index') }}">View all</a></div>
                </div>
            </div>

        </li>
 --}}

        <li class="nav-item dropdown"><a class="nav-link pe-0 ps-2" id="navbarDropdownUser" role="button"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-xl">
                    <img class="rounded-circle" src="{{ asset('storage/images/users/' . Auth::user()->profile) }}"
                        alt="" />

                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="navbarDropdownUser">
                <div class="bg-white dark__bg-1000 rounded-2 py-2">

                    {{-- <div class="dropdown-divider"></div> --}}
                    <a class="dropdown-item" href="{{ route('user.edit') }}">Profile &amp; account</a>
                    <a class="dropdown-item" href="{{ route('notifications.index') }}">{{ __('Notifications') }}</a>
                    <a class="dropdown-item" href="{{ route('messages.index') }}">{{ __('Messages') }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')" class="dropdown-item"
                            style="padding-left:1rem !important; padding-left:1rem !important"
                            onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            {{ __('Logout') }}
                        </x-dropdown-link>
                    </form>
                </div>
            </div>
        </li>
    </ul>
</nav>
