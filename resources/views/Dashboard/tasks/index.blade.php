@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
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
                            <select class="form-select form-select-sm" aria-label="Bulk actions">
                                <option selected="">{{ __('Bulk actions') }}</option>
                                <option value="Refund">{{ __('Refund') }}</option>
                                <option value="Delete">{{ __('Delete') }}</option>
                                <option value="Archive">{{ __('Archive') }}</option>
                            </select>
                            <button class="btn btn-falcon-default btn-sm ms-2" type="button">{{ __('Apply') }}</button>
                        </div>
                    </div>
                    <div id="table-customers-replace-element">

                        @if (auth()->user()->hasPermission('tasks-create'))
                            <a href="{{ route('tasks.create') }}" class="btn btn-falcon-default btn-sm" type="button"><span
                                    class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span><span
                                    class="d-none d-sm-inline-block ms-1">{{ __('New') }}</span></a>
                        @endif
                        <a href="{{ route('tasks.trashed') }}" class="btn btn-falcon-default btn-sm" type="button"><span
                                class="fas fa-trash" data-fa-transform="shrink-3 down-2"></span><span
                                class="d-none d-sm-inline-block ms-1">{{ __('Trash') }}</span></a>
                        <button class="btn btn-falcon-default btn-sm" type="button"><span class="fas fa-external-link-alt"
                                data-fa-transform="shrink-3 down-2"></span><span
                                class="d-none d-sm-inline-block ms-1">{{ __('Export') }}</span></button>
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
                                        <input class="form-check-input" id="checkbox-bulk-customers-select" type="checkbox"
                                            data-bulk-select='{"body":"table-customers-body","actions":"table-customers-actions","replacedElement":"table-customers-replace-element"}' />
                                    </div>
                                </th>
                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">{{ __('ID') }}
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
                                    {{ __('Task Date') }}
                                </th>



                                <th class="sort pe-1 align-middle white-space-nowrap" style="min-width: 100px;"
                                    data-sort="joined">{{ __('Created at') }}</th>
                                @if ($tasks->count() > 0 && $tasks[0]->trashed())
                                    <th class="sort pe-1 align-middle white-space-nowrap" style="min-width: 100px;"
                                        data-sort="joined">{{ __('Deleted at') }}</th>
                                @endif


                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                    {{ __('Tech Update') }}
                                </th>

                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                    {{ __('Action') }}
                                </th>
                            </tr>
                        </thead>

                        <tbody class="list" id="table-customers-body">
                            @foreach ($tasks as $task)
                                <tr class="btn-reveal-trigger">
                                    <td class="align-middle py-2" style="width: 28px;">
                                        <div class="form-check fs-0 mb-0 d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" id="customer-0"
                                                data-bulk-select-row="data-bulk-select-row" />
                                        </div>
                                    </td>

                                    <td class="joined align-middle py-2">{{ $task->id }} </td>
                                    <td class="joined align-middle py-2">{{ $task->client_name }} </td>
                                    <td class="joined align-middle py-2">{{ $task->client_phone }} </td>
                                    <td class="joined align-middle py-2">{{ $task->service_number }} </td>
                                    <td class="joined align-middle py-2">{{ $task->task_date }} <br>
                                        {{ interval2($task->task_date) }} </td>

                                    <td class="joined align-middle py-2">{{ $task->created_at }} <br>
                                        {{ interval($task->created_at) }} </td>
                                    @if ($task->trashed())
                                        <td class="joined align-middle py-2">{{ $task->deleted_at }} <br>
                                            {{ interval($task->deleted_at) }} </td>
                                    @endif
                                    <td class="joined align-middle py-2">

                                        <button class="btn btn-outline-success me-1 mb-1" type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#show-task-{{ $task->id }}">{{ __('show') }}</button>

                                    </td>
                                    <td class="align-middle white-space-nowrap py-2 text-end">

                                        <div class="dropdown font-sans-serif position-static">
                                            <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal"
                                                type="button" id="customer-dropdown-0" data-bs-toggle="dropdown"
                                                data-boundary="window" aria-haspopup="true" aria-expanded="false"><span
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

                                                    @if (auth()->user()->hasPermission('tasks-update'))
                                                        <a class="dropdown-item"
                                                            href="{{ route('tech.edit', ['task' => $task->id]) }}">{{ __('Edit tech update') }}</a>
                                                    @endif
                                                    @if (auth()->user()->hasPermission('tasks-delete') ||
                                                            auth()->user()->hasPermission('tasks-trash'))
                                                        <form method="POST"
                                                            action="{{ route('tasks.destroy', ['task' => $task->id]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item text-danger"
                                                                type="submit">{{ $task->trashed() ? __('Delete') : __('Trash') }}</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                        </tbody>

                    </table>

                    @foreach ($tasks as $task)
                        <div class="modal fade" id="show-task-{{ $task->id }}" tabindex="-1" role="dialog"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px">
                                <div class="modal-content position-relative">
                                    <div class="position-absolute top-0 end-0 mt-2 me-2 z-index-1">
                                        <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
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
                @endforeach
                </tbody>

                </table>
            @else
                <h3 class="p-4">{{ __('No tasksTo Show') }}</h3>
                @endif
            </div>
        </div>


        <div class="card-footer d-flex align-items-center justify-content-center">
            {{ $tasks->appends(request()->query())->links() }}
        </div>

    </div>
@endsection
