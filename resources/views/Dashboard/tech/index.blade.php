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


                                <th class="align-middle no-sort"></th>
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