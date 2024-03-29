@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Journal Entry') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Journal Entry') }}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        @can('create journal entry')
            <a href="{{ route('journal-entry.create') }}" data-title="{{ __('Create New Journal') }}" data-bs-toggle="tooltip"
                title="{{ __('Create') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th> #</th>
                                    <th> {{ __('Journal ID') }}</th>
                                    <th> {{ __('Date') }}</th>
                                    <th> {{ __('Amount') }}</th>
                                    <th> {{ __('Description') }}</th>
                                    <th> {{ __('created by') }}</th>
                                    <th width="10%"> {{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($journalEntries as $journalEntry)
                                @php
                                    $user = \App\Models\User::find($journalEntry->created_by);
                                @endphp
                                    <tr>
                                        <td>
                                            @if($journalEntry->red_flag == 1)
                                            <i class="ti ti-flag" style="color: red;font-size: 25px"></i>
                                            @else
                                            <i class="ti ti-flag" style="color: green;font-size: 25px"></i>
                                            @endif
                                        </td>
                                        <td class="Id">
                                            <a href="{{ route('showAdminJournal', $journalEntry->id) }}"
                                                class="btn btn-outline-primary">{{ $user->journalNumberFormat($journalEntry->journal_id) }}</a>
                                        </td>
                                        <td>{{ Auth::user()->dateFormat($journalEntry->date) }}</td>
                                        <td>
                                            {{ \Auth::user()->priceFormat($journalEntry->totalCredit()) }}
                                        </td>
                                        <td>{{ !empty($journalEntry->description) ? $journalEntry->description : '-' }}</td>
                                        <td>{{$user->name }}</td>
                                        <td>

                                                <div class="action-btn bg-primary ms-2">
                                                    <a data-title="{{ __('Edit Journal') }}"
                                                        href="{{ route('journal-entry.edit', [$journalEntry->id]) }}"
                                                        class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('Edit') }}" data-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>


                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['journal-entry.destroy', $journalEntry->id],
                                                        'id' => 'delete-form-' . $journalEntry->id,
                                                    ]) !!}

                                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                                        data-original-title="{{ __('Delete') }}"
                                                        data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="document.getElementById('delete-form-{{ $journalEntry->id }}').submit();">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
