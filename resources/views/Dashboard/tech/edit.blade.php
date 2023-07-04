@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">{{ __('update task info') }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">

            <div class="row g-0 h-100">
                <div class="col-md-12 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                        <form method="POST" action="{{ route('tech.update', ['task' => $task->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('POST')


                            <div class="mb-3">
                                <label class="form-label" for="type">{{ __('task type') }}</label>

                                <select class="form-select @error('type') is-invalid @enderror" name="type">
                                    <option value="">
                                        {{ __('select task type') }}
                                    </option>
                                    <option value="new" {{ $task->type == 'new' ? 'selected' : '' }}>
                                        {{ __('new') }}
                                    </option>
                                    <option value="migration" {{ $task->type == 'migration' ? 'selected' : '' }}>
                                        {{ __('migration') }}
                                    </option>
                                </select>
                                @error('type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="db">{{ __(' db') }}</label>
                                <input name="db" class="form-control @error('db') is-invalid @enderror"
                                    value="{{ $task->db }}" type="text" id="db" />
                                @error('db')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="box">{{ __('box') }}</label>
                                <input name="box" class="form-control @error('box') is-invalid @enderror"
                                    value="{{ $task->box }}" type="text" autocomplete="on" id="box" />
                                @error('box')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="cab">{{ __('cab no') }}</label>
                                <input name="cab" class="form-control @error('cab') is-invalid @enderror"
                                    value="{{ $task->cab }}" type="text" autocomplete="on" id="cab" />
                                @error('cab')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="cable_type">{{ __('cable type') }}</label>
                                <select class="form-select @error('cable_type') is-invalid @enderror" name="cable_type">
                                    <option value="">
                                        {{ __('select task cable type') }}
                                    </option>
                                    <option value="patch_cord" {{ $task->cable_type == 'patch_cord' ? 'selected' : '' }}>
                                        {{ __('patch cord') }}
                                    </option>
                                    <option value="drop_wire" {{ $task->cable_type == 'drop_wire' ? 'selected' : '' }}>
                                        {{ __('drop wire') }}
                                    </option>
                                </select>
                                @error('cable_type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="cable_length">{{ __('cable length') }}</label>
                                <input name="cable_length" class="form-control @error('cable_length') is-invalid @enderror"
                                    value="{{ $task->cable_length }}" type="text" autocomplete="on" id="cable_length" />
                                @error('cable_length')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="connector">{{ __('connectors') }}</label>
                                <select class="form-select @error('connectors') is-invalid @enderror" name="connectors">
                                    <option value="">
                                        {{ __('select connectors quantity') }}
                                    </option>
                                    <option value="1" {{ $task->connectors == '1' ? 'selected' : '' }}>
                                        {{ __('1') }}
                                    </option>
                                    <option value="2" {{ $task->connectors == '2' ? 'selected' : '' }}>
                                        {{ __('2') }}
                                    </option>
                                    <option value="3" {{ $task->connectors == '3' ? 'selected' : '' }}>
                                        {{ __('3') }}
                                    </option>
                                </select>
                                @error('connectors')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="face_split">{{ __('face split') }}</label>
                                <input name="face_split" class="form-control @error('face_split') is-invalid @enderror"
                                    value="{{ $task->face_split }}" type="text" autocomplete="on" id="face_split" />
                                @error('face_split')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="comment">{{ __('select comment') }}</label>

                                <select class="form-select @error('comment') is-invalid @enderror" name="comment" required>
                                    <option value="">
                                        {{ __('select comment') }}
                                    </option>
                                    @foreach ($comments as $comment)
                                        <option value="{{ $comment->id }}"
                                            {{ $comment->id == $task->comment_id ? 'selected' : '' }}>
                                            {{ app()->getLocale() == 'ar' ? $comment->name_ar : $comment->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('comment')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="images">{{ __('images') }}</label>
                                <input name="images[]" class="imgs form-control @error('images') is-invalid @enderror"
                                    type="file" id="images" accept="image/*" multiple />
                                @error('images')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="col-md-12" id="gallery">
                                    @foreach ($task->images as $image)
                                        <img src="{{ asset('storage/images/tasks/' . $image->image) }}"
                                            style="width:100px; border: 1px solid #999" class="img-thumbnail img-prev">
                                    @endforeach
                                </div>
                            </div>


                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                                    name="submit">{{ __('update task info') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
