@extends('layouts.app')
@section('title', __('pages.payment_method'))
@php
    $setting = Auth::user()->shop;
@endphp

@section('content')
    <section class="app-user-list">
        <div class="card">
            <div class="card-header bg-transparent">
                <h4 class="card-title">{{ __('pages.payment_method') }}</h4>
                <a class="dt-button btn btn-primary btn-add-record ms-2" tabindex="0" aria-controls="DataTables_Table_0"
                    href="{{ route('payment.add') }}">
                    <span>{{ __('pages.add_new') }}</span>
                </a>
            </div>
            <div class="mx-2 card-datatable table-responsive pt-0">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('pages.sn') }}</th>
                            <th>{{ __('pages.name') }}</th>
                            <th>{{ __('pages.balance') }}</th>
                            <th>{{ __('pages.option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($methods as $method)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $method->name }}</td>
                                <td>{{ App\CPU\Helpers::priceFormat($method->balance) }}</td>
                                <td><a onclick="return confirm('Are you sure?')"
                                        href="{{ route('payment.delete', $method->id) }}" class="badge bg-danger"><i
                                            class="fas fa-trash"></i></a></td>
                            </tr>
                        @endforeach

                        <tr>
                            <td></td>
                            <td class="text-end bg-warning fw-bold">{{ translate('Total Balance ') }}</td>
                            <td><strong>{{ App\CPU\Helpers::priceFormat($total_balance) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
