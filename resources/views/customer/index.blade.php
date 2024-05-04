@extends('layouts.app')
@section('title', __('pages.customer_list'))
@section('custom-css')
@endsection
@section('content')
    <div class="card border-0 bg-white">
        <div class="card-header bg-transparent mb-0">
            <h3 class="mb-0">{{ __('pages.customer_list') }}</h3>
            <div class="">
                <a class="btn btn-success" href="{{ route('customer.send_email') }}">
                    <i class="fa fa-envelope"></i> Send
                    Email</a>
                <a class="btn btn-primary ms-5" href="{{ route('customer.add') }}">
                    {{ translate('Add New') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="user-list-table table">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('pages.sn') }}</th>
                            <th>{{ __('pages.name') }}</th>
                            <th>{{ __('pages.phone') }}</th>
                            <th>{{ __('pages.due') }}</th>
                            <th>{{ __('pages.address') }}</th>
                            <th>{{ __('pages.Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collection as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->phone }}</td>
                                <td>{{ App\CPU\Helpers::priceFormat($row->due) }}</td>
                                <td>{{ $row->address }}</td>
                                <td>
                                    <a href="{{ route('customer.edit', $row->id) }}" class="btn btn-primary btn-circle">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a href="{{ route('customer.view', $row->id) }}" class="btn btn-info btn-circle">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <form class="d-inline-block" method="post" onsubmit="return confirm('Are you sure?')"
                                        action="{{ route('customer.delete', $row->id) }}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-circle">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination">
                {!! $collection->links() !!}
            </div>
        </div>
    </div>
@endsection

@section('custom-js')

@endsection
