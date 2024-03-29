@extends('layouts.admin')
@section('page-title')
    {{ __('Journal Detail') }}
@endsection
@php
    $user = \App\Models\User::find($journalEntry->created_by);
@endphp

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('journal-entry.index') }}">{{ __('Journal Entry') }}</a></li>
    <li class="breadcrumb-item">{{ $user->journalNumberFormat($journalEntry->journal_id) }}</li>
    {{-- create by {{ $user->name }} --}}
@endsection


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div style="margin: 20px 0">


                        @if ($journalEntry->Approve == 1)
                            <div style="display:inline-block">
                                <span class="alert alert-success">{{ __('Approved') }}</span>
                            </div>
                        @else
                            <div style="display:inline-block">
                                <span type="submit" class="alert alert-primary">{{ __('Waiting') }}</span>
                            </div>
                        @endif
                        @if ($journalEntry->red_flag == 1)
                            <div style="display:inline-block">
                                <span class="alert alert-danger">{{ __('Red Flagged') }}</span>
                            </div>
                        @else
                        @endif
                    </div>
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row invoice-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                    <h2>{{ __('Journal') }} {{ __('created by') }} {{ $user->name }}</h2>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                                    <h3 class="invoice-number">
                                        {{ $user->journalNumberFormat($journalEntry->journal_id) }}</h3>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="font-style">
                                        <strong>{{ __('To') }} :</strong><br>
                                        {{ !empty($settings['company_name']) ? $settings['company_name'] : '' }}<br>
                                        {{ !empty($settings['company_telephone']) ? $settings['company_telephone'] : '' }}<br>
                                        {{ !empty($settings['company_address']) ? $settings['company_address'] : '' }}<br>
                                        {{ !empty($settings['company_city']) ? $settings['company_city'] : '' . ', ' }}
                                        {{ !empty($settings['company_state']) ? $settings['company_state'] : '' . ', ' }}
                                        {{ !empty($settings['company_country']) ? $settings['company_country'] : '' . '.' }}
                                    </small>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small>
                                        <strong>{{ __('Journal No') }} :</strong>
                                        {{ $user->journalNumberFormat($journalEntry->journal_id) }}
                                    </small><br>
                                    <small>
                                        <strong>{{ __('Journal Ref') }} :</strong>
                                        {{ $journalEntry->reference }}
                                    </small> <br>
                                    <small>
                                        <strong>{{ __('Journal Date') }} :</strong>
                                        {{ $user->dateFormat($journalEntry->date) }}
                                    </small>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="font-weight-bold">{{ __('Journal Account Summary') }}</div>
                                    <div class="table-responsive mt-2">
                                        <table class="table mb-0 ">
                                            <tr>
                                                <th data-width="40" class="text-dark">#</th>
                                                <th class="text-dark">{{ __('Account') }}</th>
                                                <th class="text-dark" width="25%">{{ __('Description') }}</th>
                                                <th class="text-dark">{{ __('Debit') }}</th>
                                                <th class="text-dark">{{ __('Credit') }}</th>
                                                <th class="text-dark text-end">{{ __('Amount') }}</th>
                                            </tr>

                                            @foreach ($accounts as $key => $account)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ !empty($account->accounts) ? $account->accounts->code . ' - ' . $account->accounts->name : '' }}
                                                    </td>
                                                    <td>{{ !empty($account->description) ? $account->description : '-' }}
                                                    </td>
                                                    <td>{{ $user->priceFormat($account->debit) }}</td>
                                                    <td>{{ $user->priceFormat($account->credit) }}</td>
                                                    <td class="text-end">
                                                        @if ($account->debit != 0)
                                                            {{ $user->priceFormat($account->debit) }}
                                                        @else
                                                            {{ $user->priceFormat($account->credit) }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <tfoot>

                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td class="text-end"><b>{{ __('Total Credit') }}</b></td>
                                                    <td class="text-end">
                                                        {{ $user->priceFormat($journalEntry->totalCredit()) }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td class="text-end"><b>{{ __('Total Debit') }}</b></td>
                                                    <td class="text-end">
                                                        {{ $user->priceFormat($journalEntry->totalDebit()) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="font-weight-bold">
                                        {{ __('Description') }} : <br>
                                    </div>
                                    <small>{{ $journalEntry->description }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="text-align: center">


                @if ($journalEntry->Approve == 1)
                    {{-- <div style="text-align: center;display:inline-block">
                        <span class="alert alert-success">{{ __('Approved') }}</span>
                    </div> --}}
                    <form style="display: inline-block" action="{{ route('adminWaiting', $journalEntry->id) }}" method="POST">
                        @method('post')
                        @csrf
                        <div style="text-align: center;display:inline-block">
                            <button type="submit" class="btn btn-warning">{{ __('Add To Waiting') }}</button>
                        </div>
                    </form>
                    @else
                    <form style="display: inline-block" action="{{ route('AdminApprove', $journalEntry->id) }}" method="POST">
                        @method('post')
                        @csrf
                        <div style="text-align: center;display:inline-block">
                            <button type="submit" class="btn btn-success">{{ __('Approve') }}</button>
                        </div>
                    </form>
                @endif
                @if ($journalEntry->red_flag == 0)
                    {{-- <div style="text-align: center;display:inline-block">
                        <span class="alert alert-danger">{{ __('Red Flagged') }}</span>
                    </div> --}}
                    <form style="display: inline-block" action="{{ route('makeRedFlag', $journalEntry->id) }}"
                        method="POST">
                        @method('post')
                        @csrf
                        <div style="text-align: center;display:inline-block">
                            <button type="submit" class="btn btn-primary">{{ __('Add Red Flag') }}</button>
                        </div>
                    </form>
                @else
                    <form style="display: inline-block" action="{{ route('RemoveRedFlag', $journalEntry->id) }}"
                        method="POST">
                        @method('post')
                        @csrf
                        <div style="text-align: center;display:inline-block">
                            <button type="submit" class="btn btn-success">{{ __('Remove Red Flag') }}</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

    </div>
@endsection
