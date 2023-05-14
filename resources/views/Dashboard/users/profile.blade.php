@extends('layouts.dashboard.app')

@section('adminContent')


    @if (Auth::user()->hasRole('affiliate'))
        <div class="card mb-3">

            <div class="card-header position-relative min-vh-25 mb-7">
                <form method="POST" action="{{ route('user.store.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="cover-image">
                        <div class="bg-holder rounded-3 rounded-bottom-0"
                            style="background-image:url({{ $user->store_cover == null ? asset('assets/img/store_cover.jpg') : asset('storage/images/users/' . $user->store_cover) }});">
                        </div>
                        <!--/.bg-holder-->

                        <input name="store_cover" class="d-none sonoo-form" id="upload-cover-image" type="file" />
                        <label class="cover-image-file-input" for="upload-cover-image"><span
                                class="fas fa-camera me-2"></span><span>Change cover photo</span></label>
                    </div>
                    <div class="avatar avatar-5xl avatar-profile shadow-sm img-thumbnail rounded-circle">
                        <div class="h-100 w-100 rounded-circle overflow-hidden position-relative"> <img
                                src="{{ $user->store_profile == null ? asset('storage/images/users/' . $user->profile) : asset('storage/images/users/' . $user->store_profile) }}"
                                width="200" alt="" data-dz-thumbnail="data-dz-thumbnail" />
                            <input name="store_profile" class="d-none sonoo-form" id="profile-image" type="file" />
                            <label class="mb-0 overlay-icon d-flex flex-center" for="profile-image"><span
                                    class="bg-holder overlay overlay-0"></span><span
                                    class="z-index-1 text-white dark__text-white text-center fs--1"><span
                                        class="fas fa-camera"></span><span class="d-block">Update</span></span></label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info" role="alert">Please complete your store information to use this
                            service!</div>

                        <form method="POST" action="{{ route('user.store.update') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="store_name">Your store name</label>
                                <input name="store_name" class="form-control @error('store_name') is-invalid @enderror"
                                    value="{{ $user->store_name }}" type="text" autocomplete="on" id="store_name" />
                                @error('store_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="store_description">Your store description</label>
                                <textarea name="store_description" class="form-control @error('store_description') is-invalid @enderror" type="text"
                                    id="store_description">{{ $user->store_description }}</textarea>
                                @error('store_description')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                                    name="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    @endif




    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">My Profile</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">

            <div class="row g-0 h-100">
                <div class="col-md-12 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                        <form method="POST" action="{{ route('user.update') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ $user->name }}" type="text" autocomplete="on" id="name" autofocus
                                    required />
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="email">Email address</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email"
                                    id="email" name="email" autocomplete="on" value="{{ $user->email }}" required />
                                @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="role">Account type</label>

                                <select class="form-select @error('role') is-invalid @enderror" aria-label=""
                                    name="role" id="role" disabled>
                                    @if ($user->hasRole('vendor') || $user->hasRole('affiliate'))
                                        <option value="{{ $user->hasRole('affiliate') ? '4' : '3' }}" selected>
                                            {{ $user->hasRole('affiliate') ? 'affiliate' : 'vendor' }}</option>
                                    @else
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}
                                            </option>
                                        @endforeach
                                    @endif

                                </select>
                                @error('role')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="country">Country</label>

                                <select class="form-select @error('country') is-invalid @enderror" aria-label=""
                                    name="country" id="country" disabled>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ $user->country_id == $country->id ? 'selected' : '' }}>
                                            {{ app()->getLocale() == 'ar' ? $country->name_ar : $country->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="phone">Phone</label>
                                <input class="form-control @error('phone') is-invalid @enderror" type="txt"
                                    autocomplete="on" id="phone" name="phone" autocomplete="on"
                                    value="{{ $user->phone }}" disabled />
                                @error('phone')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="row gx-2">
                                <div class="mb-3 col-sm-6">
                                    <label class="form-label" for="password">Password</label>
                                    <input class="form-control @error('password') is-invalid @enderror" type="password"
                                        autocomplete="on" id="password" name="password" />
                                    @error('password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-sm-6">
                                    <label class="form-label" for="password_confirmation">Confirm
                                        Password</label>
                                    <input class="form-control @error('password_confirmation') is-invalid @enderror"
                                        type="password" autocomplete="on" id="password_confirmation"
                                        name="password_confirmation" />
                                    @error('password_confirmation')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="gender">Gender</label>

                                <br>
                                <div class="form-check form-check-inline">
                                    <input {{ $user->gender == 'male' ? 'checked' : '' }}
                                        class="form-check-input @error('gender') is-invalid @enderror" id="gender1"
                                        type="radio" name="gender" value="male" disabled />
                                    <label class="form-check-label" for="flexRadioDefault1">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input {{ $user->gender == 'female' ? 'checked' : '' }}
                                        class="form-check-input @error('gender') is-invalid @enderror" id="gender2"
                                        type="radio" name="gender" value="female" disabled />
                                    <label class="form-check-label" for="flexRadioDefault2">Female</label>
                                </div>

                                @error('gender')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="profile">Profile picture</label>
                                <input name="profile" class="img form-control @error('profile') is-invalid @enderror"
                                    type="file" id="profile" />
                                @error('profile')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">

                                <div class="col-md-10">
                                    <img src="{{ asset('storage/images/users/' . $user->profile) }}"
                                        style="width:100px; border: 1px solid #999" class="img-thumbnail img-prev">
                                </div>

                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit">Edit
                                    My Profile</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
