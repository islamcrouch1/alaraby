@extends('layouts.Dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">{{ __('Edit Central') }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">

            <div class="row g-0 h-100">
                <div class="col-md-12 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                        <form method="POST" action="{{ route('Centrals.update', ['Central' => $Central->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="name_ar">{{ __('Central name - arabic') }}</label>
                                <input name="name_ar" class="form-control @error('name_ar') is-invalid @enderror"
                                    value="{{ $Central->name_ar }}" type="text" autocomplete="on" id="name_ar"
                                    autofocus required />
                                @error('name_ar')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="name_en">{{ __('Central name - english') }}</label>
                                <input name="name_en" class="form-control @error('name_en') is-invalid @enderror"
                                    value="{{ $Central->name_en }}" type="text" autocomplete="on" id="name_en"
                                    autofocus required />
                                @error('name_en')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- <div class="mb-3">
                                <label class="form-label"
                                    for="description_ar">{{ __('Central description - arabic') }}</label>
                                <input name="description_ar"
                                    class="form-control @error('description_ar') is-invalid @enderror"
                                    value="{{ $Central->description_ar }}" type="text" autocomplete="on"
                                    id="description_ar" autofocus required />
                                @error('description_ar')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            {{-- <div class="mb-3">
                                <label class="form-label"
                                    for="description_en">{{ __('Central description - english') }}</label>
                                <input name="description_en"
                                    class="form-control @error('description_en') is-invalid @enderror"
                                    value="{{ $Central->description_en }}" type="text" autocomplete="on"
                                    id="description_en" autofocus required />
                                @error('description_en')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div> --}}


                            <div class="mb-3">
                                <label class="form-label" for="parent_id">{{ __('Parnet Central') }}</label>

                                <select class="form-select @error('parent_id') is-invalid @enderror" aria-label=""
                                    name="parent_id" id="parent_id">
                                    <option {{ $Central->parent_id == null ? 'selected' : '' }} value="">
                                        {{ __('Main Central') }}</option>
                                    @foreach ($Centralsas $sCentral)
                                        <option value="{{ $sCentral->id }}"
                                            {{ $Central->parent_id == $sCentral->id ? 'selected' : '' }}>
                                            {{ app()->getLocale() == 'ar' ? $sCentral->name_ar : $sCentral->name_en }}
                                        </option>

                                        @if ($sCentral->children->count() > 0)
                                            @foreach ($sCentral->children as $subCat)
                                                @include('Dashboard.Centrals._Central_options_edit', [
                                                    'sCentral' => $subCat,
                                                ])
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                                @error('parent')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>




                            <div class="mb-3">
                                <label class="form-label" for="country">{{ __('Country') }}</label>

                                <select class="form-select @error('country') is-invalid @enderror" aria-label=""
                                    name="country" id="country" required>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ $Central->country_id == $country->id ? 'selected' : '' }}>
                                            {{ app()->getLocale() == 'ar' ? $country->name_ar : $country->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>



                            {{-- <div class="mb-3">
                                <label class="form-label" for="profit">{{ __('Central Profit %') }}</label>
                                <input name="profit" class="form-control @error('profit') is-invalid @enderror"
                                    value="{{ $Central->profit }}" type="number" min="0" autocomplete="on"
                                    id="profit" autofocus required />
                                @error('profit')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="image">{{ __('Central image') }}</label>
                                <input name="image" class="img form-control @error('image') is-invalid @enderror"
                                    type="file" id="image" />
                                @error('image')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div> --}}


                            <div class="mb-3">

                                <div class="col-md-10">
                                    <img src="{{ asset('storage/images/Centrals/' . $Central->image) }}"
                                        style="width:100px; border: 1px solid #999" class="img-thumbnail img-prev">
                                </div>

                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                                    name="submit">{{ __('Edit
                                                                                                            Central') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
