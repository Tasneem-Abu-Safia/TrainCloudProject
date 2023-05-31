<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ManagerMiddleware;
use App\Http\Middleware\TraineeMiddleware;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BillingController extends Controller
{
    public function __construct()
    {
        $this->middleware(TraineeMiddleware::class)->only(['create', 'store', 'edit', 'update']);
        $this->middleware(ManagerMiddleware::class)->only(['index', 'requests', 'active', 'inactive', 'destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Billing::with(['trainee' => function ($q) {
                return $q->with('user');
            }])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($billing) {
                    $buttons = '<div class="btn-group" role="group">';
                    if ($billing->payment_status === 'inactive') {
                        $buttons .= '<a class="billingActive btn btn-light-success" data-id="' . $billing->id . '" title="Active"><i class="fas fa-arrow-up"></i> Active</a>';
                    } else {
                        $buttons .= '<a class="billingDeActive btn btn-light-danger" data-id="' . $billing->id . '" title="Inactive"><i class="fas fa-arrow-down"></i> Inactive</a>';
                    }
                    $buttons .= '<a class="mainDelete btn btn-light-danger" data-id="' . $billing->id . '"><i class="fas fa-trash"></i> Delete</a></div>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('layouts.billing.index');

    }


    public function requests(Request $request)
    {
        if ($request->ajax()) {
            $data = Billing::with(['trainee' => function ($q) {
                return $q->with('user');
            }])->where('payment_status', 'inactive')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($billing) {
                    $buttons = '<div class="btn-group" role="group">';
                    if ($billing->payment_status === 'inactive') {
                        $buttons .= '<a class="billingActive btn btn-light-success" data-id="' . $billing->id . '" title="Active"><i class="fas fa-arrow-up"></i> Active</a>';
                    } else {
                        $buttons .= '<a class="billingDeActive btn btn-light-danger" data-id="' . $billing->id . '" title="Inactive"><i class="fas fa-arrow-down"></i> Inactive</a>';
                    }
                    $buttons .= '<a class="mainDelete btn btn-light-danger" data-id="' . $billing->id . '"><i class="fas fa-trash"></i> Delete</a></div>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('layouts.billing.requests');
    }

    public function create()
    {
        $billing = Billing::where('trainee_id', Auth::user()->trainee->id)->first();
        return view('layouts.billing.create', compact('billing'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visa' => 'required|unique:billings,visa|regex:/^4[0-9]{3}\s?[0-9]{4}\s?[0-9]{4}\s?[0-9]{4}$/',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'cvc' => 'required|digits:4'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Billing::create([
            'trainee_id' => Auth::user()->trainee->id,
            'visa' => $request->visa,
            'amount_due' => $request->amount,
            'payment_date' => $request->payment_date,
            'cvc' => $request->cvc,
        ]);

        return redirect()->route('billings.create')->with('success', 'Billing created successfully.');
    }


    public function update(Request $request, Billing $billing)
    {
        $billing->update([
            'visa' => $request->visa,
            // Update other billing fields here
        ]);

        return redirect()->route('billing.create')->with('success', 'Billing updated successfully.');
    }


    public function active($id)
    {
        $Billing = Billing::find($id);
        $Billing->payment_status = 'active';
        $Billing->save();
        return response()->json(['msg' => 'Billing Active']);
    }

    public function deActive($id)
    {
        $Billing = Billing::find($id);
        $Billing->payment_status = 'inactive';
        $Billing->save();
        return response()->json(['msg' => 'Billing inActive']);
    }

    public function destroy($id)
    {
        Billing::destroy($id);
        return back()->with('msg', 'Deleted Done');
    }
}
