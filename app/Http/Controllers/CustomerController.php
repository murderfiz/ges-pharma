<?php

namespace App\Http\Controllers;

use App\Mail\SendCustomerMail;
use App\Models\Method;
use http\Exception\RuntimeException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePay;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        $data['collection'] = Customer::select('id', 'name', 'address', 'phone', 'due')
            ->latest()
            ->paginate($request->input('limit', 5));
        return view('customer.index')->with($data);
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')) {

            $custom = Customer::where('phone', $request->phone)->where('shop_id', Auth::user()->shop_id)->first();

            if ($custom != null) {
                Toastr::error('Customer Existes With Given Phone Number', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-top-right']);
            }
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->phone = $request->phone;
            $customer->email = $request->email;
            $customer->address = $request->address;
            if ($request->filled('due')) {
                $customer->due = $request->due;
            }
            $customer->shop_id = Auth::user()->shop_id;
            $customer->district_id = Auth::user()->shop->district_id;
            $customer->thana_id = Auth::user()->shop->thana_id;
            if ($customer->save()) {
                Toastr::success('Customer successfully created', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-top-right']);
                return redirect()->route('customer.list');
            } else {
                Toastr::error('Something Went Wrong', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-top-right']);
                return redirect()->route('customer.list');
            }
        } else {
            return view('customer.add');
        }
    }


    public function edit(Request $request, $id)
    {
        $customer = Customer::where('shop_id', Auth::user()->shop_id)->where('id', $id)->firstOrFail();
        if ($request->isMethod('post')) {
            $customer->name = $request->name;
            $customer->phone = $request->phone;
            $customer->email = $request->email;
            $customer->address = $request->address;
            if ($request->filled('due')) {
                $customer->due = $request->due;
            }
            $customer->shop_id = Auth::user()->shop_id;
            $customer->thana_id = Auth::user()->shop->thana_id;
            $customer->district_id = Auth::user()->shop->district_id;
            if ($customer->save()) {
                Toastr::success('Customer successfully created', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-top-right']);
                return redirect()->route('customer.list');
            } else {
                Toastr::error('Something Went Wrong', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-top-right']);
                return redirect()->route('customer.list');
            }
        } else {

            return view('customer.edit', compact('customer'));
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $customer = Customer::where('id', $id)->firstOrFail();

            $customer->name = $request->name;
            $customer->phone = $request->phone;
            $customer->email = $request->email;
            $customer->address = $request->address;

            $customer->save();
            DB::commit();
            \App\CPU\Helpers::successAlert('Successfully updated');
            return redirect()->route('customer.list');
        }catch (\Exception $e) {
            DB::rollBack();
            \App\CPU\Helpers::errorAlert($e->getMessage());
            return redirect()->back();
        }
    }


    public function delete(Request $request, $id)
    {
        $customer = Customer::where('shop_id', Auth::user()->shop_id)->where('id', $id)->firstOrFail();

        if ($customer->delete()) {
            Toastr::success('Customer successfully Deleted', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-top-right']);
            return redirect()->route('customer.list');
        } else {
            Toastr::error('Something Went Wrong', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-top-right']);
            return redirect()->route('customer.list');
        }
    }


    public function view(Request $request, $id)
    {
        $data['customer'] = Customer::where('id', $id)->firstOrFail();
        $data['invoice'] = Invoice::where('customer_id', $id)->get();
        $data['transaction'] = InvoicePay::where('customer_id', $id)->get();
        $data['methods'] = Method::all();
        return view('customer.view')->with($data);
    }

    public function duePayment(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'invoice_id' => 'required',
            'method_id' => 'required',
        ]);
        try {
            $amount  = $request->input('amount');
            DB::beginTransaction();
            $customer = Customer::findOrFail($request->customer_id);
            $invoice = Invoice::findOrFail($request->invoice_id);
            $customer->due -= $amount;
            $customer->save();

            $invoice->due_price -= $amount;
            $invoice->paid_amount += $amount;
            $invoice->save();

            $transaction = new InvoicePay();
            $transaction->amount = $amount;
            $transaction->invoice_id = $request->input('invoice_id');
            $transaction->customer_id = $request->input('customer_id');
            $transaction->method_id = $request->input('method_id');
            $transaction->date = now();
            $transaction->save();

            $paymentMethod = Method::findOrFail($request->input('method_id'));
            $paymentMethod->balance += $amount;
            $paymentMethod->save();

            DB::commit();
            \App\CPU\Helpers::successAlert('Payment successfully processed');
            return redirect()->back();
        }catch (\Exception $e) {
            DB::rollBack();
            \App\CPU\Helpers::errorAlert($e->getMessage());
            return redirect()->back();
        }
    }


    public function due(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::select('id', 'name', 'address', 'phone', 'due')->where('shop_id', Auth::user()->shop_id)->where('due', '>', 0);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('customer.edit', $row->id) . '" class="badge bg-primary"><i class="fas fa-edit"></i></a> <a href="' . route('customer.view', $row->id) . '" class="badge bg-info"><i class="fas fa-eye"></i></a> <a onclick="return confirm(\'Are you sure?\')" href="' . route('customer.delete', $row->id) . '" class="badge bg-danger"><i class="fas fa-trash"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $customer = Customer::where('shop_id', Auth::user()->shop_id)->get();

        return view('customer.due', compact('customer'));
    }



    public function sendEmail(Request $request)
    {
        $customers = Customer::select('id', 'name', 'address', 'phone', 'email')->where('shop_id', Auth::user()->shop_id)->latest()->get();
        return view('customer.email_sender', compact('customers'));
    }

    public function sendEmailProcess(Request $request)
    {
        $request->validate([
            'customers'     => 'required',
            'email_subject'   => 'required',
            'email_body'   => 'required',
        ], [
            'customers.required' => 'Customers is required',
            'email_subject.required' => 'Subject field is required',
            'email_body.required' => 'Email body field is required',
        ]);
        try {
            $company = Auth::user()->shop;
            $user = Auth::user();
            $data = [
                'subject' => $request->email_subject . ' | ' . env('MAIL_FROM_NAME'),
                'company_owner' => $user->name,
                'company_name' => $company->name,
                'from_email' => env('MAIL_FROM_ADDRESS'),
                'body' => $request->email_body,
                'logo' => asset('storage/images/admin/site_logo/' . $company->site_logo),
                'site_name' => $company->site_title,
            ];
            foreach ($request->customers as $customer) {
                $cmr = Customer::where('email', $customer)->first();
                $data['customer_name'] = $cmr->name;
                Mail::to($customer)->send(new SendCustomerMail($data));
            }
            Toastr::success('Mail has been sent successfuly!', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-top-right']);
            return back();
        } catch (Exception $exception) {
            Toastr::error($exception->getMessage(), '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-top-right']);
            return redirect()->back();
        }
    }
}