<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\WalletTransaction;
use App\Models\User;
use App\Models\Provider;
use App\Models\ProviderType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class WalletTransactionController extends Controller
{
      public function index()
    {
        $transactions = WalletTransaction::with(['user', 'admin'])->orderBy('created_at', 'desc')->get();
        $users = User::all();

        
        return view('admin.wallet_transactions.index', compact('transactions', 'users',));
    }

  
    public function create()
    {
        $users = User::all();
        return view('admin.wallet_transactions.create', compact('users'));
    }

    
  public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'amount' => 'required|numeric|min:0.01',
        'type_of_transaction' => 'required|in:1,2',
        'note' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return redirect()
            ->route('wallet_transactions.create')
            ->withErrors($validator)
            ->withInput();
    }

    $user = User::findOrFail($request->user_id);

    $transactionData = [
        'user_id' => $user->id,
        'amount' => $request->amount,
        'type_of_transaction' => $request->type_of_transaction,
        'note' => $request->note,
        'admin_id' => Auth::id(),
    ];

    if ($request->type_of_transaction == 1) {
        $user->balance += $request->amount;
    } else {
        if ($user->balance < $request->amount) {
            return redirect()
                ->route('wallet_transactions.create')
                ->with('error', __('messages.Insufficient_Balance'))
                ->withInput();
        }
        $user->balance -= $request->amount;
    }

    $user->save();
    WalletTransaction::create($transactionData);

    return redirect()
        ->route('wallet_transactions.index')
        ->with('success', __('messages.Transaction_Created_Successfully'));
}


   
    public function show($id)
    {
        $transaction = WalletTransaction::with(['user', 'admin'])->findOrFail($id);
        return view('admin.wallet_transactions.show', compact('transaction'));
    }

    /**
     * Filter transactions by entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_type' => 'nullable|in:all,1,2',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('wallet_transactions.index')
                ->withErrors($validator);
        }

        $query = WalletTransaction::with(['user', 'provider.providerTypes', 'admin']);

     

        // Filter by transaction type
        if ($request->transaction_type && $request->transaction_type != 'all') {
            $query->where('type_of_transaction', $request->transaction_type);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get the filtered transactions
        $transactions = $query->orderBy('created_at', 'desc')->get();
        
        // Get users, providers, and provider types for the filter dropdowns
        $users = User::all();

        return view('admin.wallet_transactions.index', compact('transactions', 'users', 'providers', 'providerTypes'));
    }

    

}