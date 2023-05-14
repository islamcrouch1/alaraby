@extends('layouts.dashboard.app_login')

@section('authContent')
    <div class="row min-vh-100 flex-center g-0">
        <div class="col-lg-8 col-xxl-5 py-3 position-relative"><img class="bg-auth-circle-shape"
                src="../../../assets/img/icons/spot-illustrations/bg-shape.png" alt="" width="250"><img
                class="bg-auth-circle-shape-2" src="../../../assets/img/icons/spot-illustrations/shape-1.png" alt=""
                width="150">
            <div class="card overflow-hidden z-index-1">
                <div class="card-body p-0">
                    <div class="row g-0 h-100">
                        <div class="col-md-5 text-center bg-card-gradient">
                            <div class="position-relative p-4 pt-md-5 pb-md-7 light">
                                <div class="bg-holder bg-auth-card-shape"
                                    style="background-image:url(../../../assets/img/icons/spot-illustrations/half-circle.png);">
                                </div>
                                <!--/.bg-holder-->

                                <div class="z-index-1 position-relative"><a
                                        class="link-light mb-4 font-sans-serif fs-4 d-inline-block fw-bolder"
                                        href=""> <img style="width:150px" src="{{ asset('assets/img/logo.png') }}"
                                            alt="">
                                    </a>
                                    <p class="opacity-75 text-white">
                                        {{ __('') }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-3 mb-4 mt-md-4 mb-md-5 light">
                                <p class="text-white"> {{ __("Don't have an account?") }}<br><a
                                        class="text-decoration-underline link-light"
                                        href="{{ route('register') }}">{{ __('Get started!') }}</a></p>
                                <p class="mb-0 mt-4 mt-md-5 fs--1 fw-semi-bold text-white opacity-75">{{ __('Read our') }}
                                    <a class="text-decoration-underline text-white" href="#!">{{ __('terms') }}</a>
                                    {{ __('and') }} <a class="text-decoration-underline text-white"
                                        href="#!">{{ __('conditions') }} </a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-7 d-flex flex-center">
                            <div class="p-4 p-md-5 flex-grow-1">
                                <div class="row flex-between-center">
                                    <div class="col-auto">
                                        <h3>{{ __('Account Login') }}</h3>
                                    </div>
                                </div>
                                @if (session('status'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                @if (session('success'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label" for="phone">{{ __('Phone Number') }}</label>
                                        <input class="form-control @error('phone') is-invalid @enderror" id="phone"
                                            type="txt" name="phone" value="{{ old('phone') }}" required autofocus
                                            autocomplete="on" />
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label" for="password">{{ __('Password') }}</label>
                                        </div>
                                        <div style="position: relative" id="show_hide_password">
                                            <input class="form-control @error('password') is-invalid @enderror"
                                                id="password" type="password" name="password" required />
                                            <a style="position: absolute; top: 0px;  padding: 6px; {{ app()->getLocale() == 'ar' ? 'left: 20px;' : 'right: 20px;' }}"
                                                id="show-pass" href=""><i class="fa fa-eye-slash"
                                                    aria-hidden="true"></i></a>
                                        </div>

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="row flex-between-center">
                                        <div class="col-auto">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="checkbox" id="card-checkbox"
                                                    name="remember" checked="checked" />
                                                <label class="form-check-label mb-0"
                                                    for="card-checkbox">{{ __('Remember me') }}</label>
                                            </div>
                                        </div>
                                        {{-- <div class="col-auto"><a class="fs--1"
                                                href="{{ route('password.reset.request') }}">{{ __('Forgot Password?') }}</a>
                                        </div> --}}
                                    </div>
                                    <div class="mb-3">
                                        <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                                            name="submit">{{ __('Log in') }}</button>
                                    </div>
                                </form>
                                {{-- <div class="position-relative mt-4">
                                    <hr class="bg-300" />
                                    <div class="divider-content-center">or log in with</div>
                                </div>
                                <div class="row g-2 mt-2">
                                    <div class="col-sm-6"><a class="btn btn-outline-google-plus btn-sm d-block w-100"
                                            href="#"><span class="fab fa-google-plus-g me-2"
                                                data-fa-transform="grow-8"></span> google</a></div>
                                    <div class="col-sm-6"><a class="btn btn-outline-facebook btn-sm d-block w-100"
                                            href="#"><span class="fab fa-facebook-square me-2"
                                                data-fa-transform="grow-8"></span> facebook</a></div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
