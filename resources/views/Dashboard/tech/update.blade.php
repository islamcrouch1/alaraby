            </div>
            @extends('layouts.dashboard.app')

            @section('adminContent')
                <div class="card mb-3" id="customersTable"
                    data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
                    <div class="card-header">
                        <div class="row flex-between-center">
                            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">{{ __('update') }}</h5>
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
                                            <label class="form-label" for="type">{{ __('task type') }}</label>
                                            <input name="type" class="form-control @error('type') is-invalid @enderror"
                                                value="{{ $task->type }}" type="text" autocomplete="on" id="type"
                                                autofocus required />
                                            @error('type')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="status">{{ __(' task status') }}</label>
                                            <input name="status" class="form-control @error('status') is-invalid @enderror"
                                                value="{{ $task->status }}" type="number" autocomplete="on" id="status"
                                                autofocus required />
                                            @error('status')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="db">{{ __(' db') }}</label>
                                            <input name="db" class="form-control @error('db') is-invalid @enderror"
                                                value="{{ $task->db }}" type="number" autocomplete="on" id="db"
                                                autofocus required />
                                            @error('db')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>


                                        <div class="mb-3">
                                            <label class="form-label" for="box">{{ __('box') }}</label>
                                            <input name="box" class="form-control @error('box') is-invalid @enderror"
                                                value="{{ $task->box }}" type="text" autocomplete="on" id="box"
                                                autofocus required />
                                            @error('box')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="cab">{{ __('cab') }}</label>
                                            <input name="cab" class="form-control @error('cab') is-invalid @enderror"
                                                value="{{ $task->cab }}" type="number" autocomplete="on" id="cab"
                                                autofocus required />
                                            @error('cab')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="cable_type">{{ __('cable type') }}</label>
                                            <input name="cable_type"
                                                class="form-control @error('cable_type') is-invalid @enderror"
                                                value="{{ $task->cable_type }}" type="text" autocomplete="on"
                                                id="cable_type" autofocus required />
                                            @error('cable_type')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="cable_length">{{ __('cable length') }}</label>
                                            <input name="cable_length"
                                                class="form-control @error('cable_length') is-invalid @enderror"
                                                value="{{ $task->cable_length }}" type="number" autocomplete="on"
                                                id="cable_length" autofocus required />
                                            @error('cable_length')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="connector">{{ __('connector') }}</label>
                                            <input name="connector"
                                                class="form-control @error('connector') is-invalid @enderror"
                                                value="{{ $task->connector }}" type="number" autocomplete="on"
                                                id="connector" autofocus required />
                                            @error('connector')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="face_split">{{ __('face  split') }}</label>
                                            <input name="face_split"
                                                class="form-control @error('face_split') is-invalid @enderror"
                                                value="{{ $task->face_split }}" type="number" autocomplete="on"
                                                id="face_split" autofocus required />
                                            @error('face_split')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="comment">{{ __('select comment') }}</label>

                                            <select class="form-select @error('comment') is-invalid @enderror"
                                                name="comment">
                                                @foreach ($comments as $comment)
                                                    <option value="{{ $comment->id }}"
                                                        {{ $comment->id == $task->comment_id ? 'selected' : '' }}>
                                                        {{ app()->getLocale() == 'ar' ? $compound->name_ar : $compound->name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('comment')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>


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
