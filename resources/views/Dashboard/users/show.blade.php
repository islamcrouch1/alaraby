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


            @if ($user->hasRole('tech'))

                <div class="card mb-3" id="customersTable"
                    data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>


                    <form id="bulk-edit" method="POST" action="{{ route('tasks.bulk-action') }}">
                        @csrf

                        <div class="card-header">
                            <div class="row flex-between-center">
                                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">
                                        @if ($tasks->count() > 0 && $tasks[0]->trashed())
                                            {{ __('tasks trash') }}
                                        @else
                                            {{ __('tasks') }}
                                        @endif
                                    </h5>
                                </div>










                                <div class="col-8 col-sm-auto text-end ps-2">
                                    <div class="d-none" id="table-customers-actions">
                                        <div class="d-flex">
                                            <select class="form-select form-select-sm" name="bulk_option" form="bulk-edit"
                                                aria-label="Bulk actions" required>
                                                <option value="">{{ __('Bulk actions') }}</option>
                                                <option value="active">{{ __('Active') }}</option>
                                                <option value="inactive">{{ __('not Active') }}</option>
                                                <option value="paid">{{ __(' Paid') }}</option>
                                                <option value="unpaid">{{ __('Non-Paid') }}</option>


                                            </select>
                                            <button form="bulk-edit" class="btn btn-falcon-default btn-sm ms-2"
                                                type="submit">{{ __('Apply') }}</button>
                                        </div>
                                    </div>
                                    <div id="table-customers-replace-element">
                                        <a href="" data-bs-toggle="modal" data-bs-target="#filter-modal"
                                            class="btn btn-falcon-default btn-sm"><span class="fas fa-filter"
                                                data-fa-transform="shrink-3 down-2"></span><span
                                                class="d-none d-sm-inline-block ms-1">{{ __('Filter') }}</span></a>

                                        @if (auth()->user()->hasPermission('tasks-create'))
                                            <a href="{{ route('tasks.create') }}" class="btn btn-falcon-default btn-sm"
                                                type="button"><span class="fas fa-plus"
                                                    data-fa-transform="shrink-3 down-2"></span><span
                                                    class="d-none d-sm-inline-block ms-1">{{ __('New') }}</span></a>
                                        @endif
                                        <a href="{{ route('tasks.trashed') }}" class="btn btn-falcon-default btn-sm"
                                            type="button"><span class="fas fa-trash"
                                                data-fa-transform="shrink-3 down-2"></span><span
                                                class="d-none d-sm-inline-block ms-1">{{ __('Trash') }}</span></a>
                                        <a href="{{ route('tasks.export', ['status' => request()->status, 'payment_status' => request()->payment_status, 'from' => request()->from, 'to' => request()->to, 'activation_from' => request()->activation_from, 'activation_to' => request()->activation_to]) }}"
                                            class="btn btn-falcon-default btn-sm" type="button"><span
                                                class="fas fa-external-link-alt"
                                                data-fa-transform="shrink-3 down-2"></span><span
                                                class="d-none d-sm-inline-block ms-1">{{ __('Export') }}</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive scrollbar">
                                @if ($tasks->count() > 0)
                                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                                        <thead class="bg-200 text-900">
                                            <tr>
                                                <th>
                                                    <div class="form-check fs-0 mb-0 d-flex align-items-center">
                                                        <input class="form-check-input" id="checkbox-bulk-customers-select"
                                                            form="bulk-edit" type="checkbox"
                                                            data-bulk-select='{"body":"table-customers-body","actions":"table-customers-actions","replacedElement":"table-customers-replace-element"}' />
                                                    </div>
                                                </th>
                                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                                    {{ __('ID') }}
                                                </th>
                                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                                    {{ __('Client Name') }}
                                                </th>
                                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                                    {{ __('Clien Phone') }}
                                                </th>
                                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                                    {{ __('Service Number') }}
                                                </th>
                                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                                    {{ __('Technician Name') }}
                                                </th>
                                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                                    {{ __('Status') }}
                                                </th>
                                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                                    {{ __('Task Date') }}
                                                </th>
                                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                                    {{ __('Task Activated Date') }}
                                                </th>

                                                <th class="sort pe-1 align-middle white-space-nowrap"
                                                    style="min-width: 100px;" data-sort="joined">{{ __('Created at') }}
                                                </th>
                                                @if ($tasks->count() > 0 && $tasks[0]->trashed())
                                                    <th class="sort pe-1 align-middle white-space-nowrap"
                                                        style="min-width: 100px;" data-sort="joined">
                                                        {{ __('Deleted at') }}</th>
                                                @endif

                                                <th class="sort pe-1 align-middle white-space-nowrap"
                                                    data-sort="tech_update">
                                                    {{ __('Tech Update') }}
                                                </th>



                                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="action">
                                                    {{ __('Action') }}
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody class="list" id="table-customers-body">
                                            @foreach ($tasks as $task)
                                                <tr class="btn-reveal-trigger">
                                                    <td class="align-middle py-2" style="width: 28px;">
                                                        <div class="form-check fs-0 mb-0 d-flex align-items-center">
                                                            <input class="form-check-input" name="items[]"
                                                                value="{{ $task->id }}" type="checkbox"
                                                                id="customer-0"
                                                                data-bulk-select-row="data-bulk-select-row" />
                                                        </div>
                                                    </td>

                                                    <td class="joined align-middle py-2">{{ $task->id }} </td>
                                                    <td class="joined align-middle py-2">{{ $task->client_name }} </td>
                                                    <td class="joined align-middle py-2">{{ $task->client_phone }} </td>
                                                    <td class="joined align-middle py-2">{{ $task->service_number }} </td>
                                                    <td class="joined align-middle py-2">{{ $task->user->name }} </td>

                                                    <td class="joined align-middle py-2">
                                                        @if ($task->status == 'active')
                                                            <span
                                                                class="badge badge-soft-success ">{{ __('active') }}</span>
                                                        @elseif ($task->status == 'inactive' || $task->status == null)
                                                            <span
                                                                class="badge badge-soft-danger ">{{ __('inactive') }}</span>
                                                        @endif

                                                        @if ($task->payment_status == 1)
                                                            <span
                                                                class="badge badge-soft-success ">{{ __('paid') }}</span>
                                                        @elseif ($task->payment_status == 2)
                                                            <span
                                                                class="badge badge-soft-danger ">{{ __('unpaid') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="joined align-middle py-2">{{ $task->task_date }} <br>
                                                        {{ interval2($task->task_date) }} </td>

                                                    <td class="joined align-middle py-2">
                                                        @if (isset($task->activation_date))
                                                            {{ $task->activation_date }} <br>
                                                            {{ interval2($task->activation_date) }}
                                                        @endif
                                                    </td>

                                                    <td class="joined align-middle py-2">{{ $task->created_at }} <br>
                                                        {{ interval($task->created_at) }} </td>
                                                    @if ($task->trashed())
                                                        <td class="joined align-middle py-2">{{ $task->deleted_at }} <br>
                                                            {{ interval($task->deleted_at) }} </td>
                                                    @endif
                                                    <td class="joined align-middle py-2">
                                                        @if ($task->cab != null)
                                                            <button class="btn btn-outline-success me-1 mb-1"
                                                                type="button" data-bs-toggle="modal"
                                                                data-bs-target="#show-task-{{ $task->id }}">{{ __('show') }}
                                                            </button>
                                                        @endif
                                                    </td>

                                                    <td class="joined align-middle white-space-nowrap py-2">

                                                        <div class="dropdown font-sans-serif position-static">
                                                            <button
                                                                class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal"
                                                                type="button" id="customer-dropdown-0"
                                                                data-bs-toggle="dropdown" data-boundary="window"
                                                                aria-haspopup="true" aria-expanded="false"><span
                                                                    class="fas fa-ellipsis-h fs--1"></span></button>
                                                            <div class="dropdown-menu dropdown-menu-end border py-0"
                                                                aria-labelledby="customer-dropdown-0">
                                                                <div class="bg-white py-2">
                                                                    @if (
                                                                        $task->trashed() &&
                                                                            auth()->user()->hasPermission('tasks-restore'))
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('tasks.restore', ['task' => $task->id]) }}">{{ __('Restore') }}</a>
                                                                    @elseif(auth()->user()->hasPermission('tasks-update'))
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('tasks.edit', ['task' => $task->id]) }}">{{ __('Edit') }}</a>
                                                                    @endif

                                                                    @if (auth()->user()->hasPermission('tasks-update') && $task->cab != null)
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('tech.edit', ['task' => $task->id]) }}">{{ __('Edit tech update') }}</a>
                                                                    @endif
                                                                    @if (auth()->user()->hasPermission('tasks-delete') ||
                                                                            auth()->user()->hasPermission('tasks-trash'))
                                                                        <a href="{{ route('tasks.del', ['task' => $task->id]) }}"
                                                                            class="dropdown-item text-danger">{{ $task->trashed() ? __('Delete') : __('Trash') }}</a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>

                                    @foreach ($tasks as $task)
                                        <div class="modal fade" id="show-task-{{ $task->id }}" tabindex="-1"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document"
                                                style="max-width: 500px">
                                                <div class="modal-content position-relative">
                                                    <div class="position-absolute top-0 end-0 mt-2 me-2 z-index-1">
                                                        <button
                                                            class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-0">
                                                        <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                                                            <h4 class="mb-1" id="modalExampleDemoLabel">
                                                                {{ __('task service number') . ': ' . $task->service_number }}
                                                            </h4>
                                                        </div>
                                                        <div class="p-4 pb-0">

                                                            <div class="table-responsive scrollbar">
                                                                <table class="table table-bordered overflow-hidden">
                                                                    <colgroup>
                                                                        <col class="bg-soft-primary" />
                                                                        <col />
                                                                    </colgroup>

                                                                    <tbody>

                                                                        <tr class="btn-reveal-trigger">
                                                                            <td>{{ __('db') }}</td>
                                                                            <td> {{ $task->db }}</td>
                                                                        </tr>

                                                                        <tr class="btn-reveal-trigger">
                                                                            <td>{{ __('box') }}</td>
                                                                            <td>{{ $task->box }}</td>
                                                                        </tr>

                                                                        <tr class="btn-reveal-trigger">
                                                                            <td>{{ __('cab number') }}</td>
                                                                            <td>{{ $task->cab }}</td>
                                                                        </tr>

                                                                        <tr class="btn-reveal-trigger">
                                                                            <td>{{ __('cable type') }}</td>
                                                                            <td>{{ $task->cable_type }}</td>
                                                                        </tr>

                                                                        <tr class="btn-reveal-trigger">
                                                                            <td>{{ __('connectors') }}</td>
                                                                            <td>{{ $task->connectors }}</td>
                                                                        </tr>

                                                                        <tr class="btn-reveal-trigger">
                                                                            <td>{{ __('face split') }}</td>
                                                                            <td>{{ $task->face_split }}</td>
                                                                        </tr>

                                                                        <tr class="btn-reveal-trigger">
                                                                            <td>{{ __('cable length') }}</td>
                                                                            <td>{{ $task->cable_length }}</td>
                                                                        </tr>



                                                                        <tr class="btn-reveal-trigger">
                                                                            <td>{{ __('comment') }}</td>
                                                                            <td>{{ getName($task->comment) }}</td>
                                                                        </tr>


                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <div class="mb-3">
                                                                <div class="col-md-12" id="gallery">
                                                                    @foreach ($task->images as $image)
                                                                        <img src="{{ asset('storage/images/tasks/' . $image->image) }}"
                                                                            style="width:100px; border: 1px solid #999"
                                                                            class="img-thumbnail img-prev">
                                                                    @endforeach
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button"
                                                            data-bs-dismiss="modal">{{ __('Close') }}</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <h3 class="p-4">{{ __('No tasksTo Show') }}</h3>
                                @endif
                            </div>
                        </div>

                    </form>

                    <div class="card-footer d-flex align-items-center justify-content-center">
                        {{ $tasks->appends(request()->query())->links() }}
                    </div>

                </div>


                <!-- start filter modal -->
                <div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 60%">
                        <div class="modal-content position-relative">
                            <div class="position-absolute top-0 end-0 mt-2 me-2 z-index-1">
                                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                                    data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="">
                                <div class="modal-body p-0">
                                    <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                                        <h4 class="mb-1" id="modalExampleDemoLabel">
                                            {{ __('search filters') }}</h4>
                                    </div>
                                    <div class="p-4 pb-0">

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <input class="form-control search-input fuzzy-search" type="search"
                                                    value="{{ request()->search }}" name="search" autofocus
                                                    placeholder="{{ __('Search...') }}" />
                                            </div>


                                            <div class="col-md-12 mb-1">
                                                <label for="">{{ __('task date') }}</label>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="input-group"><span class="input-group-text"
                                                        id="from">{{ __('From') }}</span>
                                                    <input type="date" id="from" name="from"
                                                        class="form-control" value="{{ request()->from }}">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="input-group"><span class="input-group-text"
                                                        id="to">{{ __('To') }}</span>
                                                    <input type="date" id="to" name="to"
                                                        class="form-control" value="{{ request()->to }}">
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-1">
                                                <label for="">{{ __('activation date') }}</label>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="input-group"><span class="input-group-text"
                                                        id="from">{{ __('From') }}</span>
                                                    <input type="date" id="from" name="activation_from"
                                                        class="form-control" value="{{ request()->activation_from }}">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="input-group"><span class="input-group-text"
                                                        id="to">{{ __('To') }}</span>
                                                    <input type="date" id="to" name="activation_to"
                                                        class="form-control" value="{{ request()->activation_to }}">
                                                </div>
                                            </div>


                                            <div class="col-md-3 mb-3">
                                                <select name="status" class="form-select">
                                                    <option value="">{{ __('All Status') }}</option>
                                                    <option value="active"
                                                        {{ request()->status == 'active' ? 'selected' : '' }}>
                                                        {{ __('active') }}</option>
                                                    <option value="inactive"
                                                        {{ request()->status == 'inactive' ? 'selected' : '' }}>
                                                        {{ __('inactive') }}</option>

                                                </select>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <select name="payment_status" class="form-select">
                                                    <option value="">{{ __('payment status') }}</option>
                                                    <option value="1"
                                                        {{ request()->payment_status == '1' ? 'selected' : '' }}>
                                                        {{ __('paid') }}</option>
                                                    <option value="2"
                                                        {{ request()->payment_status == '2' ? 'selected' : '' }}>
                                                        {{ __('unpaid') }}</option>

                                                </select>
                                            </div>


                                        </div>

                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button"
                                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                                    <button class="btn btn-primary" type="submit">{{ __('Apply') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- end filter modal -->

            @endif


        </div>
    </div>
@endsection
