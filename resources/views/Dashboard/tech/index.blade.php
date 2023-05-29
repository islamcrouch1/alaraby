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
                                {{-- <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">{{ __('ID') }}
                                </th>
                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                    {{ __('Client Name') }}
                                </th> --}}
                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                    {{ __('Clien Phone') }}
                                </th>
                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                    {{ __('Service Number') }}
                                </th>

                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                    {{ __('actions') }}
                                </th>



                            </tr>
                        </thead>
                        <tbody class="list" id="table-customers-body">
                            @foreach ($tasks as $task)
                                <tr class="{{ $task->cab != null ? 'table-danger' : '' }}">
                                    <td class="align-middle py-2" style="width: 28px;">
                                        <div class="form-check fs-0 mb-0 d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" id="customer-0"
                                                data-bulk-select-row="data-bulk-select-row" />
                                        </div>
                                    </td>

                                    {{-- <td class="joined align-middle py-2">{{ $task->id }} </td>
                                    <td class="joined align-middle py-2">{{ $task->client_name }} </td> --}}
                                    <td class="joined align-middle py-2"><a
                                            href="tel:{{ $task->client_phone }}">{{ $task->client_phone }}</a>
                                    </td>
                                    <td class="joined align-middle py-2">{{ $task->service_number }} </td>
                                    <td class="joined align-middle py-2">

                                        <button class="btn btn-outline-success me-1 mb-1" type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#show-task-{{ $task->id }}">{{ __('show') }}</button>
                                        <a class="btn btn-outline-info me-1 mb-1"
                                            href="{{ route('tech.edit', ['task' => $task->id]) }}"
                                            type="button">{{ __('update') }}
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
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
                                                            <td>{{ __('client name') }}</td>
                                                            <td> {{ $task->client_name }}</td>
                                                        </tr>

                                                        <tr class="btn-reveal-trigger">
                                                            <td>{{ __('client phone') }}</td>
                                                            <td>{{ $task->client_phone }}</td>
                                                        </tr>

                                                        <tr class="btn-reveal-trigger">
                                                            <td>{{ __('service number') }}</td>
                                                            <td>{{ $task->service_number }}</td>
                                                        </tr>

                                                        <tr class="btn-reveal-trigger">
                                                            <td>{{ __('address') }}</td>
                                                            <td>{{ $task->address }}</td>
                                                        </tr>

                                                        <tr class="btn-reveal-trigger">
                                                            <td>{{ __('central name') }}</td>
                                                            <td>{{ getName($task->central) }}</td>
                                                        </tr>

                                                        <tr class="btn-reveal-trigger">
                                                            <td>{{ __('compound') }}</td>
                                                            <td>{{ getName($task->compound) }}</td>
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
