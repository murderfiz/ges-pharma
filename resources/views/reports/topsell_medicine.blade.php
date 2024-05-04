@extends('layouts.app')
@section('title', __('pages.customer_list'))
@section('custom-css')

    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')
    <section class="app-user-list">
     <div class="card">
        <div class="card-body border-bottom">
            <h4 class="card-title">{{ translate('Top sell medicine list') }}</h4>
            <div class="row">
                <div class="col-md-4 user_role"></div>
                <div class="col-md-4 user_plan"></div>
                <div class="col-md-4 user_status"></div>
            </div>
        </div>
    
            <div class="row px-2">
                <div class="col-lg-3">
                    <form method="get">
                        <div class="col-md-12 user_role">
                            <label class="form-label" for="UserRole">{{ __('pages.from_date') }}</label>
                            <input value="@php echo $from_date @endphp  - @php echo $to_date @endphp" type="text" name="date" id="reportrange" class="form-control invoice-edit-input date-picker flatpickr-input">
                        </div>
                    </form>
                </div>
                <div class="col-lg-3 float-end mb-2">
                    <form method="get">
                        <label for=""></label>
                        <input type="search" name="keyword" class="form-control " placeholder="Search medicine.." value="{{ $keyword }}" />
                    </form>
                </div>
            </div>
            
            <table class="topsell_medicine table table-bordered border-dark">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('pages.sn') }}</th>
                        <th>Medicine</th>
                        <th>Sell</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $medicine)
                        <tr>
                            <td> {{ $loop->iteration }} </td>
                            <td> {{ $medicine['name'] }} </td>
                            <td> {{ $medicine['total_sale'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
     </div>
    </section>
@section('custom-js')
   
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
     <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
   @php
   $date = date('Y-m-d', strtotime("-7 day", time()));
   @endphp
    <!-- END: Page Vendor JS-->
     
     
     
      <script type="text/javascript">
$(function() {

    var pvlpaid = $("#total").val();
    console.log(pvlpaid)
     $('#totaldue').html(pvlpaid);
    var start = moment().subtract(29, 'days');
    var end = moment();

    var d = new Date();
 
    var up = function(start,end)
    {
       
        window.location.href = "?from="+start+"&to="+end;
    }

     $('input[name="date"]').daterangepicker({
        startDate: start,
        endDate: end,
         format: 'YYYY-MM-DD',
          timePicker: false,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, function(start, end, label) {
        var start2 = start.format("YYYY-MM-DD");
        var end2 = end.format("YYYY-MM-DD");

		window.start = start2;
		window.end = end2;

        up(start2,end2);

      

    });
    
   
    var table = $('.user-list-table').DataTable({
        processing: true,
        serverSide: false,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
});
</script>
@endsection

@endsection