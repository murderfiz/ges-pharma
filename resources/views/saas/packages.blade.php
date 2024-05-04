@extends('layouts.app')
@section('title', translate('Package Management'))
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
@endsection
@section('content')
<section class="app-user-list">
  <div class="card">
    <div class="card-body border-bottom">
        <h4 class="card-title">{{ translate('Package Management') }}</h4>
            <div class="dt-action-buttons text-xl-end text-lg-start text-lg-end text-start "><div class="dt-buttons"><a class="dt-button btn btn-primary btn-add-record ms-2" tabindex="0" aria-controls="DataTables_Table_0" href="{{ route('saas.package.add') }}"><span>{{ translate('Add Package') }}</span></a> </div>
        </div>
    </div>
    <div class=" mx-2 card-datatable table-responsive pt-0">
        <table class="user-list-table table">
            <thead class="table-light">
                <tr>
                    <th>{{ translate('ID') }}</th>
                    <th>{{ translate('Name') }}</th>
                    <th>{{ translate('Price') }}</th>
                    <th>{{ translate('Shop') }}</th>
                    <th>{{ __('pages.option') }}</th>
                </tr>
            </thead>
        </table>
    </div>      
  </div>
  <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in"></div>
</section>
@endsection

@section('custom-js')


 <!-- BEGIN: Page Vendor JS-->
 

   
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
   
    <!-- END: Page Vendor JS-->
     <script>
         $(function () {
    
    var table = $('.user-list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('saas.package') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'price', name: 'prce'},
            {data: 'shop', name: 'shop'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
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