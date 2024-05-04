@extends('layouts.app')
@section('custom-css')
    <link rel="stylesheet" href="{{ asset('dashboard/app-assets/morris-chart/morris.css') }}">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <style>
        .statistic .icon-box {
            width: 60px;
            text-align: center;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 25px;
            border-radius: 13px;
            color: #fff;
        }
        .statistic .card {
            height: 120px;
        }
        .col-border-right{
            border-right: 2px solid #0b62a4;
        }
        .text h4 {
            color: #191a1a;
        }
        .text h6 {
            color: #0b62a4;
        }
    </style>
@endsection
@section('title', __('pages.dashboard'))
@section('content')
    <div class="row my-2">
        <div class="col-lg-8">
            <div class="card border-0 border-r20 py-2 mb-3">
                <div class="card-header bg-transparent border-0">
                    <h4>{{ __('pages.Reports') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 px-2 mb-1 col-6">
                            <div class="small-box first">
                                <div class="smalll-box d-flex justify-content-between flex-column gap-1">
                                    <div class="icon">
                                        <i class="fas fa-pills fa-2xl"></i>
                                    </div>
                                    <div class="inner">
                                        <h3>{{ number_format($medicine, 0, '.', ',') }}</h3>
                                    </div>
                                </div>
                                <a href="{{ route('instock') }}" class="small-box-footer">
                                    <span>{{ __('Stock Medicine') }}</span> <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 px-2 mb-1 col-6">
                            <div class="small-box second">
                                <div class="smalll-box d-flex justify-content-between flex-column gap-1">
                                    <div class="icon">
                                        <i class="fas fa-usd fa-2xl"></i>
                                    </div>
                                    <div class="inner">
                                        <h3>{{ App\CPU\Helpers::priceFormat($total_sell_amount) }}</h3>
                                    </div>
                                </div>
                                <a href="{{ route('invoice.reports') }}" class="small-box-footer">
                                    <span>{{ __('Total Sales') }}</span> <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 px-2 mb-1 col-6">
                            <div class="small-box third">
                                <div class="smalll-box d-flex justify-content-between flex-column gap-1">
                                    <div class="icon">
                                        <i class="fas fa-hourglass-end fa-2xl"></i>
                                    </div>
                                    <div class="inner">
                                        <h3>{{ $expire->count() }}</h3>
                                    </div>
                                </div>
                                <a href="{{ route('expired') }}" class="small-box-footer">
                                    <span>{{ __('pages.expired_medicine') }}</span> <i
                                        class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 px-2 mb-1 col-6">
                            <div class="small-box fourth">
                                <div class="smalll-box d-flex justify-content-between flex-column gap-1">
                                    <div class="icon">
                                        <i class="fa-brands fa-product-hunt fa-2xl"></i>
                                    </div>
                                    <div class="inner">
                                        <h3>{{ $stockout->count() }}</h3>
                                    </div>
                                </div>
                                <a href="{{ route('stockout') }}" class="small-box-footer">
                                    <span>{{ __('pages.stock_out_medicine') }}</span> <i
                                        class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 border-r20 mb-3">
                <div class="card-header bg-transparent border-0">
                    <h4 class="card-title">{{ __('pages.Purchases & Sales') }}</h4>
                </div>
                <div class="card-body">
                    <div id="line-example" style="height: 180px;color: red" class="line-atl morris-chart"></div>
                </div>
            </div>
        </div>
    </div>
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-md-7 col-sm-12">
                <div class="card border-0 border-r20 py-2 mb-3">
                    <div class="card-header">
                        <h4>{{ translate('Others') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col statistic px-2 col-border-right">
                                <div class="text text-dark text-center text-capitalize">
                                    <h4 class="count">{{ App\Models\Customer::count() }}</h4>
                                    <h6 class="mb-0">{{ translate('Total Customer') }}</h6>
                                </div>
                            </div>
                            <div class="col statistic px-2 col-border-right">
                                <div class="text text-dark text-center text-capitalize">
                                    <h4 class="count">{{ App\Models\Supplier::count() }}</h4>
                                    <h6 class="mb-0">{{ translate('Total Manufacturer') }}</h6>
                                </div>
                            </div>
                            <div class="col statistic px-2 col-border-right">
                                <div class="text text-dark text-center text-capitalize">
                                    <h4 class="count">{{ App\CPU\Helpers::priceFormat($total_cash_in_hand) }}</h4>
                                    <h6 class="mb-0">{{ translate('Total cash in hand ') }}</h6>
                                </div>
                            </div>
                            <div class="col statistic px-2">
                                <div class="text text-dark text-center text-capitalize">
                                    <h4 class="count">{{ App\CPU\Helpers::priceFormat($total_customer_due) }}</h4>
                                    <h6 class="mb-0">{{ translate('Total customer due') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="basic-table">
                    <div class="col-md-6 col-sm-12">
                        <div class="card border-0 border-r20">
                            <div class="card-header bg-primary border-0">
                                <h4 class="card-title Titlee">Today's sell</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('pages.title') }}</th>
                                            <th>{{ __('pages.amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {{ __('pages.today_invoice') }}
                                            </td>
                                            <td>{{ $today_sell }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{ __('pages.sold_amount') }}
                                            </td>
                                            <td>{{ App\CPU\Helpers::priceFormat($today_sell_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{ __('pages.received_amount') }}
                                            </td>
                                            <td>{{ App\CPU\Helpers::priceFormat($today_received) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">

                        <div class="card border-0 border-r20">
                            <div class="card-header bg-success border-0">
                                <h4 class="card-title Titlee">{{ translate('Today\'s Purchase') }} </h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('pages.title') }}</th>
                                            <th>{{ __('pages.amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ __('pages.today_purchase') }}</td>
                                            <td>{{ $today_purchase }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('pages.purchase_amount') }}</td>
                                            <td>{{ App\CPU\Helpers::priceFormat($today_purchase_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('pages.paid_amount') }}</td>
                                            <td>{{ App\CPU\Helpers::priceFormat($today_paid) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-5 col-sm-12">
                <div class="card border-0 border-r20">
                    <div class="card-header bg-transparent border-0">
                        <div class="header-left">
                            <h4 class="card-title" style="color:#000; font-weight:600;">{{ __('pages.Purchases & Sales') }}
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="piChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="stockmodal" class="modal fade show" role="dialog" style="padding-right: 15px; display: none;"
        aria-modal="true">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div>
                        <h4>
                            <center>{{ __('pages.expired_medicine') }}</center>
                        </h4>
                    </div>
                    <table id="" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('pages.name') }}</th>
                                <th class="text-center">{{ __('pages.batch') }}</th>
                                <th class="text-center">{{ __('pages.Expired on') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expire_medicines as $batch)
                                <tr>
                                    <td>
                                        @php
                                            $medicine = \App\Models\Medicine::where('id', $batch->medicine_id)->first();
                                        @endphp
                                        @if ($medicine != null)
                                            {{ $medicine->name }}
                                        @endif
                                    </td>
                                    <td>{{ $batch->name }}</td>

                                    <td>{{ $batch->expire }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        <h4>
                            <center>{{ __('pages.stock_out') }}</center>
                        </h4>
                    </div>
                    <table id="" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('pages.name') }}</th>

                                <th class="text-center">{{ translate('Qauntity') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stockout_medicines as $medicine)
                                <tr>
                                    <td class="text-center">
                                        {{ $medicine->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $medicine->total_quantity }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="is_modal_shown" id="is_modal_shown">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#stockmodal').modal('show');
        });
    </script>

    <script src="{{ asset('dashboard/app-assets/morris-chart/popper.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/morris-chart//raphael-min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/morris-chart/morris.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @php
        $dfrom = date('Y-m-d', strtotime('-7 day', time()));
        $dto = date('Y-m-d');
        $datelist = list_days($dfrom, $dto);
        $i = 0;
        $data = [];
    @endphp

    @foreach ($datelist as $date)
        @if (Auth::user()->shop->email != env('SUPERUSER'))
            @php
                $data[$i]['y'] = $date;
                $data[$i]['a'] = \App\Models\Invoice::where('shop_id', Auth::user()->shop_id)
                    ->where('date', $date)
                    ->count();
                $data[$i]['b'] = \App\Models\Purchase::where('shop_id', Auth::user()->shop_id)
                    ->where('date', $date)
                    ->count();
                $i++;
            @endphp
        @else
            @php
                $data[$i]['y'] = $date;
                $data[$i]['a'] = \App\Models\Income::where('shop_id', Auth::user()->shop_id)
                    ->where('date', $date)
                    ->count();
                $data[$i]['b'] = \App\Models\Income::where('shop_id', Auth::user()->shop_id)
                    ->where('date', $date)
                    ->where('status', 1)
                    ->count();
                $data[$i]['c'] = \App\Models\Income::where('shop_id', Auth::user()->shop_id)
                    ->where('date', $date)
                    ->where('status', 1)
                    ->sum('amount');
                $i++;
            @endphp
        @endif
    @endforeach
    <script>
        Morris.Line({
            element: 'line-example',
            data: @php echo json_encode($data) @endphp,
            xkey: 'y',
            labelColor: '#000000',
            @if (Auth::user()->shop->email != env('SUPERUSER'))
                ykeys: ['a', 'b'],
                labels: ['Sales', 'Purchase']
            @else
                ykeys: ['a', 'b', 'c'],
                labels: ['Total Order', 'Approved Order', 'Received Amount']
            @endif
        });
    </script>
    @if (Auth::user()->shop->email != env('SUPERUSER'))
        @php
            $sales = \App\Models\Invoice::sum('total_price');
            $purchase = \App\Models\Purchase::sum('total_price');
        @endphp
    @else
        @php
            $expired = \App\Models\Shop::whereBetween('next_pay', [$dfrom, $dto])->count();
            $active_order = \App\Models\Income::where('status', 1)
                ->whereBetween('date', [$dfrom, $dto])
                ->count();
        @endphp
    @endif

    <script>
        var options = {
            series: [{{ $purchase }}, {{ $sales }}],
            chart: {
                width: 380,
                type: 'pie',
            },
            colors: ['#0088ff', '#f8bf15'],
            labels: ['Purchase', 'Sales'],
            legend: {
                position: 'bottom',
                show: true,
                showForSingleSeries: false,
                showForNullSeries: true,
                showForZeroSeries: true,
            },
        };

        var chart = new ApexCharts(document.querySelector("#piChart"), options);
        chart.render();
    </script>
@endsection
