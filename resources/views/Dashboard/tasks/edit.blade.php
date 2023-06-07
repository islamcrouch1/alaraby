@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">{{ __('Edit task') }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">

            <div class="row g-0 h-100">
                <div class="col-md-12 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                        <form method="POST" action="{{ route('tasks.update', ['task' => $task->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')


                            <div class="mb-3">
                                <label class="form-label" for="task_date">{{ __('task date') }}</label>

                                <input type="datetime-local" id="task_date" name="task_date"
                                    class="form-control @error('task_date') is-invalid @enderror"
                                    value="{{ $task->task_date }}" required>

                                @error('task_date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="client_name">{{ __('client name') }}</label>
                                <input name="client_name" class="form-control @error('client_name') is-invalid @enderror"
                                    value="{{ $task->client_name }}" type="text" autocomplete="on" id="client_name"
                                    autofocus required />
                                @error('client_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="client_phone">{{ __('client pohne') }}</label>
                                <input name="client_phone" class="form-control @error('client_phone') is-invalid @enderror"
                                    value="{{ $task->client_phone }}" type="number" autocomplete="on" id="client_phone"
                                    required />
                                @error('client_phone')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="mb-3">
                                <label class="form-label" for="service_number">{{ __('service number') }}</label>
                                <input name="service_number"
                                    class="form-control @error('service_number') is-invalid @enderror"
                                    value="{{ $task->service_number }}" type="text" autocomplete="on"
                                    id="service_number" required />
                                @error('service_number')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="address">{{ __('address') }}</label>
                                <input name="address" class="form-control @error('address') is-invalid @enderror"
                                    value="{{ $task->address }}" type="text" autocomplete="on" id="address"
                                    required />
                                @error('address')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="central">{{ __('select central') }}</label>

                                <select class="form-select @error('central') is-invalid @enderror" name="central">
                                    @foreach ($centrals as $central)
                                        <option value="{{ $central->id }}"
                                            {{ $central->id == $task->central_id ? 'selected' : '' }}>
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
                                        <option value="{{ $compound->id }}"
                                            {{ $compound->id == $task->compound_id ? 'selected' : '' }}>
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
                                        <option value="{{ $user->id }}"
                                            {{ $user->id == $task->user_id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tech')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($task->cab != null)
                                <div class="mb-3">
                                    <label class="form-label" for="central">{{ __('status') }}</label>

                                    <select class="form-select @error('status') is-invalid @enderror" name="status">
                                        <option value="">
                                            {{ __('select status') }}
                                        </option>
                                        <option value="active" {{ $task->status == 'active' ? 'selected' : '' }}>
                                            {{ __('active') }}
                                        </option>
                                        <option value="inactive" {{ $task->status == 'inactive' ? 'selected' : '' }}>
                                            {{ __('inactive') }}
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="mb-3">
                                    <label class="form-label" for="central">{{ __('payment status') }}</label>
                                    <select class="form-select @error('payment_status') is-invalid @enderror"
                                        name="payment_status">
                                        <option value="">
                                            {{ __('select payment status') }}
                                        </option>
                                        <option value="1" {{ $task->payment_status == '1' ? 'selected' : '' }}>
                                            {{ __('paid') }}
                                        </option>
                                        <option value="2" {{ $task->payment_status == '2' ? 'selected' : '' }}>
                                            {{ __('unpaid') }}
                                        </option>
                                    </select>
                                    @error('payment_status')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif





                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                                    name="submit">{{ __('Edit task') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
