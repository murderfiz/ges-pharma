@extends('layouts.app')
@section('title', $supplier->name)
@section('custom-css')

@endsection
@section('content')
    <section class="app-user-view-account">
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <div class="card border-0 bg-white">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded-circle mb-2" src="{{ \App\CPU\Helpers::globalAsset($supplier->image) }}"
                                    height="100" width="100" alt="User avatar" />
                                <div class="user-info text-center">
                                    <h4>{{ $supplier->name }} </h4>
                                    <span class="badge bg-light-secondary">{{ __('pages.Supplier') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around my-2 pt-75">
                            <div class="d-flex align-items-start me-2">
                                <span class="badge bg-light-primary p-75 rounded">
                                    <i class="fas fa-exchange"></i>
                                </span>
                                <div class="ms-75">
                                    <h4 class="mb-0">{{ App\CPU\Helpers::priceFormat($invoice->sum('total_price')) }}</h4>
                                    <small>{{ __('pages.total_buy') }}</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <span class="badge bg-light-danger p-75 rounded">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                <div class="ms-75">
                                    <h4 class="mb-0">{{ App\CPU\Helpers::priceFormat($supplier->due) }}</h4>
                                    <small>{{ __('pages.total_due') }}</small>
                                </div>
                            </div>
                        </div>
                        <h4 class="fw-bolder border-bottom mb-1">{{ __('pages.details') }}</h4>

                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">{{ __('pages.name') }}:</span>
                                    <span>{{ $supplier->name }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">{{ __('pages.phone') }}:</span>
                                    <span>{{ $supplier->phone }}</span>
                                </li>

                                <li class="mb-75">
                                    <span class="fw-bolder me-25">{{ __('pages.total_invoice') }}:</span>
                                    <span>{{ $invoice->count() }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">{{ __('pages.total_transaction') }}:</span>
                                    <span>{{ $transaction->count() }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">{{ __('pages.total_buy') }}:</span>
                                    <span
                                        class="fw-bold">{{ App\CPU\Helpers::priceFormat($invoice->sum('total_price')) }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">{{ __('pages.total_paid') }}:</span>
                                    <span
                                        class="fw-bold">{{ App\CPU\Helpers::priceFormat($invoice->sum('total_price') - $invoice->sum('due_price')) }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">{{ translate('Address:') }}</span>
                                    <span>{{ $supplier->address }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ translate('Supplier Invoice') }}</h4>
                    </div>
                    <div class="card-body border-bottom">
                        <div class="table-responsive">
                            <table class="table datatable-project">
                                <thead>
                                    <tr>
                                        <th>{{ __('pages.invoice_no') }}</th>
                                        <th>{{ __('pages.total_price') }}</th>
                                        <th>{{ __('pages.paid_amount') }}</th>
                                        <th>{{ translate('due_amount') }}</th>
                                        <th>{{ __('pages.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice as $item)
                                        <tr>
                                            <td>{{ $item->inv_id }}</td>
                                            <td>{{ App\CPU\Helpers::priceFormat($item->total_price) }}</td>
                                            <td>{{ App\CPU\Helpers::priceFormat($item->total_price - $item->due_price) }}
                                            </td>
                                            <td>{{ App\CPU\Helpers::priceFormat($item->due_price) }}</td>
                                            <td>
                                                @if ($item->due_price > 0)
                                                    <a href="javascript:" title="Pay due amount" data-bs-toggle="modal"
                                                        data-bs-target="#duePayment{{ $item->id }}"
                                                        class="btn btn-primary btn-circle">
                                                        <i class="fa fa-credit-card"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('purchase.show', $item->id) }}" title="View Invoice"
                                                    class="btn btn-warning btn-circle">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="duePayment{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="duePayment{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content border-0">
                                                    <form action="{{ route('supplier.paydue') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="supplier_id"
                                                            value="{{ $supplier->id }}">
                                                        <input type="hidden" name="invoice_id"
                                                            value="{{ $item->id }}">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                                {{ translate('Pay your due amount') }}</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <x-form.input readonly="true" col="col-lg-12"
                                                                label="Payble Amount" name="due"
                                                                value="{{ App\CPU\Helpers::priceFormat($item->due_price) }}"></x-form.input>
                                                            <x-form.input col="col-lg-12" label="Amount" name="amount"
                                                                step="any" :required="true"></x-form.input>
                                                            <x-form.select col="col-lg-12" label="Payment Method"
                                                                name="method_id" :required="true">
                                                                @foreach ($methods as $method)
                                                                    <option value="{{ $method->id }}">
                                                                        {{ $method->name }}</option>
                                                                @endforeach
                                                            </x-form.select>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit"
                                                                class="btn btn-primary">{{ translate('Pay Now') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination">
                                {!! $invoice->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom-js')

@endsection
