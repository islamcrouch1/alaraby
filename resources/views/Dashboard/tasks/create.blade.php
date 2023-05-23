@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">{{ __('Add New task') }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">

            <div class="row g-0 h-100">
                <div class="col-md-12 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                        <form method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data">
                            @csrf



                            <div class="mb-3">
                                <label class="form-label" for="task_date">{{ __('task date') }}</label>

                                <input type="datetime-local" id="task_date" name="task_date"
                                    class="form-control @error('task_date') is-invalid @enderror" value="" required>

                                @error('task_date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="client_name">{{ __('client name') }}</label>
                                <input name="client_name" class="form-control @error('client_name') is-invalid @enderror"
                                    value="{{ old('client_name') }}" type="text" autocomplete="on" id="client_name"
                                    autofocus required />
                                @error('client_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="client_phone">{{ __('client pohne') }}</label>
                                <input name="client_phone" class="form-control @error('client_phone') is-invalid @enderror"
                                    value="{{ old('client_phone') }}" type="number" autocomplete="on" id="client_phone"
                                    autofocus required />
                                @error('client_phone')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="service_number">{{ __('service number') }}</label>
                                <input name="service_number"
                                    class="form-control @error('service_number') is-invalid @enderror"
                                    value="{{ old('service_number') }}" type="number" autocomplete="on"
                                    id="service_number" autofocus required />
                                @error('service_number')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="address">{{ __('address') }}</label>
                                <input name="address" class="form-control @error('address') is-invalid @enderror"
                                    value="{{ old('address') }}" type="text" autocomplete="on" id="address" autofocus
                                    required />
                                @error('address')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="central">{{ __('select central') }}</label>

                                <select class="form-select @error('central') is-invalid @enderror" name="central">
                                    @foreach ($centrals as $central)
                                        <option value="{{ $central->id }}">
                                            {{ app()->getLocale() == 'ar' ? $central->name_ar : $central->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('central')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="compound">{{ __('select compound') }}</label>

                                <select class="form-select @error('compound') is-invalid @enderror" name="compound">
                                    @foreach ($compounds as $compound)
                                        <option value="{{ $compound->id }}">
                                            {{ app()->getLocale() == 'ar' ? $compound->name_ar : $compound->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('compound')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="tech">{{ __('select technician') }}</label>

                                <select class="form-select @error('tech') is-invalid @enderror" name="tech">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tech')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>




                            {{-- <div class="mb-3">
                                <label class="form-label" for="kind_of_order">{{ __('kind of work order no') }}</label>
                                <input name="kind_of_order"
                                    class="form-control @error('kind_of_order') is-invalid @enderror"
                                    value="{{ old('kind_of_order') }}" type="text" autocomplete="on" id="kind_of_order"
                                    autofocus required />
                                @error('kind_of_order')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="order_number">{{ __('work order number') }}</label>
                                <input name="order number" class="form-control @error('order number') is-invalid @enderror"
                                    value="{{ old('order_number') }}" type="text" autocomplete="on" id="order_number"
                                    autofocus required />
                                @error('order number')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="mb-3">
                                <label class="form-label" for="central_name">{{ __('central name') }}</label>
                                <input name="name_en" class="form-control @error('central_name') is-invalid @enderror"
                                    value="{{ old('central_name') }}" type="text" autocomplete="on"
                                    id="central_name" autofocus required />
                                @error('central_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>




                            <div class="mb-3">
                                <label class="form-label" for="city_name">{{ __('city name') }}</label>
                                <input name="city_name" class="form-control @error('city_name') is-invalid @enderror"
                                    value="{{ old('city_name') }}" type="text" autocomplete="on" id="city_name"
                                    autofocus required />
                                @error('city_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="mb-3">
                                <label class="form-label" for="email">{{ __('email') }}</label>
                                <input name="name_en" class="form-control @error('name_en') is-invalid @enderror"
                                    value="{{ old('email') }}" type="email" autocomplete="on" id="email"
                                    autofocus required />
                                @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="mb-3">
                                <label class="form-label" for="telepone_no">{{ __('telepone no') }}</label>
                                <input name="telepone_no" class="form-control @error('telepone_no') is-invalid @enderror"
                                    value="{{ old('telepone_no') }}" type="number" autocomplete="on" id="telepone_no"
                                    autofocus required />
                                @error('telepone_no')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="mobile_no">{{ __('mobile no') }}</label>
                                <input name="mobile_no" class="form-control @error('mobile_no') is-invalid @enderror"
                                    value="{{ old('mobile_no') }}" type="number" autocomplete="on" id="mobile_no"
                                    autofocus required />
                                @error('mobile_no')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="rev_no">{{ __('rev no') }}</label>
                                <input name="mobile_no" class="form-control @error('rev_no') is-invalid @enderror"
                                    value="{{ old('rev_no') }}" type="number" autocomplete="on" id="rev_no"
                                    autofocus required />
                                @error('rev_no')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="initiator">{{ __('initiator') }}</label>
                                <input name="initiator" class="form-control @error('initiator') is-invalid @enderror"
                                    value="{{ old('initiator') }}" type="text" autocomplete="on" id="initiator"
                                    autofocus required />
                                @error('initiator')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="mb-3">
                                <label class="form-label" for="stage">{{ __('stage') }}</label>
                                <input name="stage" class="form-control @error('stage') is-invalid @enderror"
                                    value="{{ old('stage') }}" type="text" autocomplete="on" id="stage"
                                    autofocus required />
                                @error('stage')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="mb-3">
                                <label class="form-label" for="status">{{ __('status') }}</label>
                                <input name="status" class="form-control @error('status') is-invalid @enderror"
                                    value="{{ old('status') }}" type="text" autocomplete="on" id="stage"
                                    autofocus required />
                                @error('status')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="status">{{ __('piriorty') }}</label>
                                <input name="piriorty" class="form-control @error('piriorty') is-invalid @enderror"
                                    value="{{ old('piriorty') }}" type="text" autocomplete="on" id="piriorty"
                                    autofocus required />
                                @error('piriorty')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="last_trans_date">{{ __('last trans date') }}</label>
                                <input name="last_trans_date"
                                    class="form-control @error('last_trans_date') is-invalid @enderror"
                                    value="{{ old('last_trans_date') }}" type="number" autocomplete="on"
                                    id="last_trans_date" autofocus required />
                                @error('last_trans_date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>







 --}}



                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                                    name="submit">{{ __('Add New task') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
