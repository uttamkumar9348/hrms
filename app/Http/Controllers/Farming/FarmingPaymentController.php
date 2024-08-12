<?php

namespace App\Http\Controllers\Farming;

use App\Models\Farming;
use App\Models\FarmingPayment;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmingPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = FarmingPayment::where('type', FarmingPayment::SECURITY_DEPOSIT)->where('created_by', Auth::user()->id)->get();
        return view('admin.farmer.payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $farmings = Farming::query()->select('farmings.*')->join('users', 'users.id', 'farmings.created_by')
            ->where('farmings.is_validate', 1)
            ->where('farmings.created_by', Auth::user()->id)
            ->orWhere('users.supervisor_id', Auth::user()->id)
            ->get();

        return view('admin.farmer.payment.create', compact('farmings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            if ($request->amount != null) {
                $this->validate($request, [
                    'farming_id' => 'required',
                    'created_by' => 'required',
                    'amount' => 'required|integer',
                ]);
            } else {
                $this->validate($request, [
                    'farming_id' => 'required',
                    'created_by' => 'required',
                ]);
            }

            $client = new FarmingPayment;
            $client->farming_id = $request->farming_id;
            $client->receipt_no = $request->receipt_no;
            $client->receipt_type = $request->receipt_type;
            $client->agreement_number = $request->agreement_number;
            $client->date = $request->date;
            $client->amount = $request->amount;
            $client->type = $request->type;
            $client->bank = $request->bank;
            $client->loan_account_number = $request->loan_account_number;
            $client->ifsc = $request->ifsc;
            $client->branch = $request->branch;
            $client->created_by = Auth::user()->id;
            $client->save();

            return redirect()->to(route('admin.farmer.farming_registration.show', $request->farming_id))->with('success', 'Payment Added Successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FarmingPayment $farmingPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $payment = FarmingPayment::find($id);
        $farmings = Farming::query()->select('farmings.*')->join('users', 'users.id', 'farmings.created_by')
            ->where('farmings.is_validate', 1)
            ->where('farmings.created_by', Auth::user()->id)
            ->orWhere('users.supervisor_id', Auth::user()->id)
            ->get();
        return view('admin.farmer.payment.edit', compact(
            'payment',
            'farmings',
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $farmingPayment = FarmingPayment::find($id);
        $farmingPayment->farming_id = $request->farming_id;
        $farmingPayment->receipt_no = $request->receipt_no;
        $farmingPayment->agreement_number = $request->agreement_number;
        $farmingPayment->date = $request->date;
        $farmingPayment->amount = $request->amount;
        $farmingPayment->bank = $request->bank;
        $farmingPayment->loan_account_number = $request->loan_account_number;
        $farmingPayment->ifsc = $request->ifsc;
        $farmingPayment->branch = $request->branch;
        $farmingPayment->created_by = Auth::user()->id;
        $farmingPayment->save();

        return redirect()->back()->with('success', 'Farming Payment Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $farmingPayment = FarmingPayment::find($id);
        $farmingPayment->delete();
        return redirect()->back()->with('success', 'Farming Payment Deleted Successfully.');
    }
    public function bankGuarantee()
    {
        $farmings = Farming::query()->select('farmings.*')->join('users', 'users.id', 'farmings.created_by')
            ->where('farmings.is_validate', 1)
            ->where('farmings.created_by', Auth::user()->id)
            ->orWhere('users.supervisor_id', Auth::user()->id)
            ->get();
        return view('admin.farmer.bank_guarantee.create', compact('farmings'));
    }
    public function reimbursement()
    {
        $payments = FarmingPayment::where('type', FarmingPayment::REIMBURSEMENT)
            ->where('created_by', Auth::user()->id)->get();

        return view('admin.farmer.reimbursement.index', compact('payments'));
    }
    public function reimbursementCreate()
    {
        $farmings = Farming::query()->select('farmings.*')->join('users', 'users.id', 'farmings.created_by')
            ->where('farmings.is_validate', 1)
            ->where('farmings.created_by', Auth::user()->id)
            ->orWhere('users.supervisor_id', Auth::user()->id)
            ->get();
        return view('admin.farmer.reimbursement.create', compact('farmings'));
    }
    public function reimbursement_delete($id)
    {
        $farmingPayment = FarmingPayment::find($id);
        $farmingPayment->delete();
        return redirect()->back()->with('success', 'Farming Payment Deleted Successfully.');
    }
    public function editBankGuarantee($id)
    {
        $payment = FarmingPayment::find($id);
        $farmings = Farming::query()->select('farmings.*')->join('users', 'users.id', 'farmings.created_by')
            ->where('farmings.is_validate', 1)
            ->where('farmings.created_by', Auth::user()->id)
            ->orWhere('users.supervisor_id', Auth::user()->id)
            ->get();
        return view('admin.farmer.bank_guarantee.edit', compact(
            'payment',
            'farmings',
        ));
    }
    public function pdfBankGuarantee($id)
    {
        $payment = FarmingPayment::find($id);
        return view('admin.farmer.bank_guarantee.show', compact(
            'payment'
        ));
    }

    public function g_code(Request $request)
    {
        $farmer = Farming::find($request->farmer_id);

        return response()->json([
            'registration_id' => $farmer->registration_no,
            'g_code' => $farmer->g_code
        ]);
    }
}
