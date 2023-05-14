@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="row">
        <div class="col-12">
            <div class="card mb-3 btn-reveal-trigger">
                <div class="card-header position-relative min-vh-25 mb-8">
                    <div class="cover-image">
                        <div class="bg-holder rounded-3 rounded-bottom-0"
                            style="background-image:url(../../assets/img/generic/4.jpg);">
                        </div>
                    </div>
                    <div class="avatar avatar-5xl avatar-profile shadow-sm img-thumbnail rounded-circle">
                        <div class="h-100 w-100 rounded-circle overflow-hidden position-relative"> <img
                                src="{{ asset('storage/images/users/' . $user->profile) }}" width="200" alt=""
                                data-dz-thumbnail="data-dz-thumbnail" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-0">
        <div class="col-lg-12 pe-lg-2">
            <div class="card mb-3 overflow-hidden">
                <div class="card-header">
                    <h5 class="mb-0">Account Information</h5>
                </div>
                <div class="card-body bg-light">


                    <h6 class="fw-bold">{{ __('User ID') . ': #' . $user->id }}</h6>
                    <h6 class="mt-2 fw-bold">{{ __('User Name') . ': ' . $user->name }}</h6>
                    <h6 class="mt-2 fw-bold">{{ __('User Type') . ': ' }}
                        @if ($user->hasRole('affiliate'))
                            {{ __('Affiliate') }}
                        @elseif($user->hasRole('vendor'))
                            {{ __('Vendor') }}
                        @endif
                    </h6>
                    <h6 class="mt-2 fw-bold">
                        {{ __('Country') . ': ' . App()->getLocale() == 'ar' ? $user->country->name_ar : $user->country->name_en }}
                    </h6>
                    <h6 class="mt-2 fw-bold">{{ __('Phone') . ': ' . $user->phone }}</h6>
                    <h6 class="mt-2 fw-bold">{{ __('Gender') . ': ' . $user->gender }}</h6>
                    <h6 class="mt-2 fw-bold">{{ __('Verification Code') . ': ' . $user->verification_code }}</h6>
                    <h6 class="mt-2 fw-bold">{{ __('Created At') . ': ' . $user->created_at }}</h6>
                    <h6 class="mt-2 fw-bold">{{ __('Updated At') . ': ' . $user->updated_at }}</h6>
                    <h6 class="mt-2 fw-bold">{{ __('Email') . ': ' . $user->email }}</h6>
                    <div class="border-dashed-bottom my-3"></div>
                    <h6 class="mt-2 fw-bold"><a href="{{ route('users.edit', ['user' => $user->id]) }}"
                            class="btn btn-falcon-primary me-1 mb-1" type="button">{{ __('Edit') }}
                        </a></h6>

                </div>
            </div>
            <div class="card py-3 mb-3">
                <div class="card-body py-3">
                    <div class="row g-0">
                        <div class="col-6 col-md-4 border-200 border-bottom border-end pb-4">
                            <h6 class="pb-1 text-700">{{ __('Available balance') }} </h6>
                            <p class="font-sans-serif lh-1 mb-1 fs-2">
                                {{ ($user->balance->available_balance < 0 ? 0 : $user->balance->available_balance) . ' ' . $user->country->currency }}
                            </p>
                        </div>
                        <div class="col-6 col-md-4 border-200 border-md-200 border-bottom border-md-end pb-4 ps-3">
                            <h6 class="pb-1 text-700">{{ __('Bonus balance') }}</h6>
                            <p class="font-sans-serif lh-1 mb-1 fs-2">
                                {{ $user->balance->bonus . ' ' . $user->country->currency }}</p>
                        </div>
                        <div
                            class="col-6 col-md-4 border-200 border-bottom border-end border-md-end-0 pb-4 pt-4 pt-md-0 ps-md-3">
                            <h6 class="pb-1 text-700">{{ __('Outstanding balance') }}</h6>
                            <p class="font-sans-serif lh-1 mb-1 fs-2">
                                {{ $user->balance->outstanding_balance . ' ' . $user->country->currency }}</p>
                        </div>
                        <div
                            class="col-6 col-md-4 border-200 border-md-200 border-bottom border-md-bottom-0 border-md-end pt-4 pb-md-0 ps-3 ps-md-0">
                            <h6 class="pb-1 text-700">{{ __('Pending withdrawal requests') }}</h6>
                            <p class="font-sans-serif lh-1 mb-1 fs-2">
                                {{ $user->balance->pending_withdrawal_requests . ' ' . $user->country->currency }}</p>
                        </div>
                        <div class="col-6 col-md-4 border-200 border-md-bottom-0 border-end pt-4 pb-md-0 ps-md-3">
                            <h6 class="pb-1 text-700">{{ __('Completed withdrawal requests') }}</h6>
                            <p class="font-sans-serif lh-1 mb-1 fs-2">
                                {{ $user->balance->completed_withdrawal_requests . ' ' . $user->country->currency }}</p>
                        </div>
                        <div class="col-6 col-md-4 pb-0 pt-4 ps-3">

                        </div>
                    </div>
                </div>
            </div>

            @if (auth()->user()->hasPermission('notes-read'))
                <div class="card mb-3 overflow-hidden">
                    <div class="card-header">
                        <h5 class="mb-0">User Notes</h5>
                    </div>
                    <div class="card-body bg-light">

                        <div class="row g-0 h-100">

                            @if ($notes->count() > 0)
                                @foreach ($notes as $note)
                                    <a class="border-bottom-0 notification rounded-0 border-x-0 border-300"
                                        href="{{ route('users.show', ['user' => $note->admin->id]) }}">
                                        <div class="notification-avatar">
                                            <div class="avatar avatar-xl me-3">
                                                <img class="rounded-circle"
                                                    src="{{ asset('storage/images/users/' . $note->admin->profile) }}"
                                                    alt="" />

                                            </div>
                                        </div>
                                        <div class="notification-body">
                                            <p class="mb-1">{{ $note->note }}</p>
                                            <span class="notification-time"><span class="me-2" role="img"
                                                    aria-label="Emoji">📢</span>
                                                {{ $note->created_at }}
                                                <span
                                                    class="badge badge-soft-info ">{{ interval($note->created_at) }}</span>

                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div class="notification-body">
                                    <p>{{ __('There are currently no notes for this user') }}</p>
                                </div>
                            @endif
                        </div>

                        @if (auth()->user()->hasPermission('notes-create'))
                            <div class="row pt-1 g-0 h-100">
                                <div class="col-md-12 d-flex flex-center">
                                    <div class="flex-grow-1">
                                        <form method="POST" action="{{ route('users.note', ['user' => $user->id]) }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label" for="note">Note</label>
                                                <input name="note"
                                                    class="form-control @error('note') is-invalid @enderror"
                                                    value="{{ old('note') }}" type="text" autocomplete="on"
                                                    id="note" required />
                                                @error('note')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                                                    name="submit">Save
                                                    Note</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if (auth()->user()->hasPermission('messages-read'))
                <div class="card mb-3 overflow-hidden">
                    <div class="card-header">
                        <h5 class="mb-0">User Messages</h5>
                    </div>
                    <div class="card-body bg-light">

                        <div class="row g-0 h-100">

                            @if ($messages->count() > 0)
                                @foreach ($messages->sortBy('created_at') as $message)
                                    <div class="notification">
                                        <a class="border-bottom-0  rounded-0 border-x-0 border-300"
                                            href="{{ route('users.show', ['user' => $message->sender->id]) }}">
                                            <div class="notification-avatar">
                                                <div class="avatar avatar-xl me-3">
                                                    <img class="rounded-circle"
                                                        src="{{ asset('storage/images/users/' . $message->sender->profile) }}"
                                                        alt="" />

                                                </div>
                                            </div>
                                        </a>
                                        <div class="notification-body">
                                            <p class="mb-1">{{ $message->message }}</p>
                                            <span class="notification-time"><span class="me-2" role="img"
                                                    aria-label="Emoji">📢</span>
                                                {{ $message->created_at }}
                                                <span
                                                    class="badge badge-soft-info ">{{ interval($message->created_at) }}</span>
                                                @if (auth()->user()->hasPermission('messages-trash|messages-delete'))
                                                    <a
                                                        href="{{ route('messages.admin.destroy', ['message' => $message->id]) }}">Delete</a>
                                                @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="notification-body">
                                    <p>{{ __('There are currently no messages for this user') }}</p>
                                </div>
                            @endif
                        </div>

                        @if (auth()->user()->hasPermission('messages-create'))
                            <div class="row pt-1 g-0 h-100">
                                <div class="col-md-12 d-flex flex-center">
                                    <div class="flex-grow-1">
                                        <form method="POST"
                                            action="{{ route('messages.admin.store', ['user' => $user->id]) }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label" for="message">Message</label>
                                                <input name="message"
                                                    class="form-control @error('note') is-invalid @enderror"
                                                    value="{{ old('message') }}" type="text" autocomplete="on"
                                                    id="message" required />
                                                @error('message')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                                                    name="submit">Send
                                                    Message</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
