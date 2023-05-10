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
                                        href="">
                                        <img style="width:150px" src="{{ asset('assets/img/logo.png') }}" alt="">
                                    </a>
                                    <p class="opacity-75 text-white">
                                        {{ __('.') }}
                                    </p>

                                </div>
                            </div>
                            <div class="mt-3 mb-4 mt-md-4 mb-md-5 light">
                                <p class="pt-3 text-white">{{ __('Have an account?') }}<br><a
                                        class="btn btn-outline-light mt-2 px-4"
                                        href="{{ route('login') }}">{{ __('Log In') }}</a></p>
                            </div>
                        </div>
                        <div class="col-md-7 d-flex flex-center">
                            <div class="p-4 p-md-5 flex-grow-1">
                                <h3>{{ __('Register') }}</h3>
                                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label" for="name">{{ __('Name') }}</label>
                                        <input name="name" class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" type="text" autocomplete="on" id="name"
                                            autofocus required />
                                        @error('name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="email">{{ __('Email address') }}</label>
                                        <input class="form-control @error('email') is-invalid @enderror" type="email"
                                            autocomplete="on" id="email" name="email" autocomplete="on"
                                            value="{{ old('email') }}" />
                                        @error('email')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    {{-- <div class="mb-3">
                                        <label class="form-label" for="role">{{ __('Account type') }}</label>

                                        <select class="form-select @error('role') is-invalid @enderror" aria-label=""
                                            name="role" id="role" required>
                                            <option value="4" {{ old('role') == '4' ? 'selected' : '' }}>
                                                {{ __('Affiliate') }}
                                            </option>
                                            <option value="3" {{ old('role') == '3' ? 'selected' : '' }}>
                                                {{ __('Vendor') }}</option>
                                        </select>
                                        @error('role')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="country">{{ __('country') }}</label>

                                        <select class="form-select @error('country') is-invalid @enderror" aria-label=""
                                            name="country" id="country" required>
                                            <option value="1" {{ old('country') == '1' ? 'selected' : '' }}>
                                                {{ __('Egypt') }}
                                            </option>
                                        </select>
                                        @error('country')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div> --}}

                                    <div class="mb-3">
                                        <label class="form-label" for="phone">{{ __('Phone') }}</label>
                                        <input class="form-control @error('phone') is-invalid @enderror" type="number"
                                            autocomplete="on" id="phone" name="phone" autocomplete="on"
                                            value="{{ old('phone') }}" required />
                                        @error('phone')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="row gx-2">
                                        <div class="mb-3 col-sm-6">
                                            <label class="form-label" for="password">{{ __('Password') }}</label>
                                            <input class="form-control @error('password') is-invalid @enderror"
                                                type="password" autocomplete="on" id="password" name="password" required />
                                            @error('password')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-sm-6">
                                            <label class="form-label"
                                                for="password_confirmation">{{ __('Confirm Password') }}</label>
                                            <input class="form-control @error('password_confirmation') is-invalid @enderror"
                                                type="password" autocomplete="on" id="password_confirmation"
                                                name="password_confirmation" required />
                                            @error('password_confirmation')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label" for="gender">{{ __('Gender') }}</label>

                                        <br>
                                        <div class="form-check form-check-inline">
                                            <input {{ old('gender') == 'male' ? 'checked' : '' }}
                                                class="form-check-input @error('gender') is-invalid @enderror"
                                                id="gender1" type="radio" name="gender" value="male" required />
                                            <label class="form-check-label"
                                                for="flexRadioDefault1">{{ __('Male') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input {{ old('gender') == 'female' ? 'checked' : '' }}
                                                class="form-check-input @error('gender') is-invalid @enderror"
                                                id="gender2" type="radio" name="gender" value="female" required />
                                            <label class="form-check-label"
                                                for="flexRadioDefault2">{{ __('Female') }}</label>
                                        </div>

                                        @error('gender')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="profile">{{ __('Profile picture') }}</label>
                                        <input name="profile"
                                            class="img form-control @error('profile') is-invalid @enderror" type="file"
                                            id="profile" />
                                        @error('profile')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="mb-3">

                                        <div class="col-md-10">
                                            <img src="{{ asset('assets/img/avatar/avatarmale.png') }}"
                                                style="width:100px; border: 1px solid #999"
                                                class="img-thumbnail img-prev">
                                        </div>

                                    </div>



                                    {{--
                                    <div class="form-check">
                                        <input class="form-check-input @error('check') is-invalid @enderror"
                                            type="checkbox" id="check" name="check" required />
                                        <label class="form-label" for="check">{{ __('I accept the') }} <a
                                                href="{{ route('front.terms') }}">{{ __('terms') }}
                                            </a>{{ __('and') }} <a
                                                href="{{ route('front.terms') }}">{{ __('privacy policy') }}</a></label>
                                        @error('check')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div> --}}



                                    <div class="mb-3">
                                        <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                                            name="submit">{{ __('Register') }}</button>
                                    </div>
                                </form>


                                {{-- <div class="position-relative mt-4">
                                    <hr class="bg-300" />
                                    <div class="divider-content-center">or register with</div>
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
