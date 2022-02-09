<?php

namespace Modules\Transaction\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Transaction\Models\Transaction;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $transactions = isset($request->status) ?
                Transaction::where('status', $request->status)->get()->load(['sender', 'recipient']) :
                Transaction::get()->load(['sender', 'recipient']);
            if (count($transactions) == 0) {
                return $this->formatAsJson(true, 'No transaction created', '', '', 404);
            }
            return $this->formatAsJson(true, 'List of all transactions', $transactions, '', 200);
        } catch (Exception $e) {
            return $this->formatAsJson(true, 'An error occurred', $e->getMessage(), '', 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) //create new transaction
    {
        //ensure user cant transfer to himself
        if ($request->sender_wallet_id === $request->receiver_wallet_id) {
            return $this->formatAsJson(false, 'Cant transfer to self, you will be blocked after 5 attempts', [], 'please contact support', 402);
        }
        $sender = $this->getUserByWalletId($request->sender_wallet_id);
        // if ($sender->verified !== "true" || $sender->verified == "false") {
        //     return $this->formatAsJson(false, 'Unverfied Users cant make transfers', [], 'please contact support', 402);
        // }
        if (!Hash::check($request->input('pin'), $sender->pin_hash)) {
            return $this->formatAsJson(false, 'wrong pin,please check email for pin or contact support for help', [], '', 401);
        }

        try {
            if (!$this->checkIfSenderBalanceIsSufficient($request->sender_wallet_id, $request->transaction_amount)) {
                return $this->formatAsJson(false, 'Balance is insufficient', [], '', 402);
            }
            DB::beginTransaction();
            $sent = $this->debitSender($request->sender_wallet_id, $request->transaction_amount); //debit the sender
            $received = $this->creditReceiver($request->receiver_wallet_id, $request->transaction_amount); //then credit the receiver
            if ($sent && $received) {
                $sender_id = $sender->id;
                $receiver_id = $this->getUserByWalletId($request->receiver_wallet_id)->id;
                $newTransaction = new Transaction; //then save it to transaction history
                $newTransaction->sender_id = $sender_id;
                $newTransaction->receiver_id = $receiver_id;
                $newTransaction->transaction_amount = $request->transaction_amount;
                $newTransaction->status = 'completed';
                $saved = $newTransaction->save();

                DB::commit();
                if ($saved) {
                    return $this->formatAsJson(true, 'Successful', Transaction::latest()->first(), '', 200);
                }
            }
            // return $this->formatAsJson(false,'Failed',[],$e->getMessage(),500);
        } catch (Exception $e) {
            return $this->formatAsJson(false, 'Transaction failed', [], $e->getMessage(), 500);
        }
    }

    public function creditReceiver($receiver_wallet_id,  float $transaction_amount)
    {
        $receiverBalance = $this->getUserByWalletId($receiver_wallet_id)->account_balance;
        $newBalance =  $receiverBalance +  $transaction_amount;
        $query = User::where('wallet_id', $receiver_wallet_id)->update(['account_balance' => $newBalance]);
        if ($query) {
            DB::commit();
            return true;
        }
    }
    public function debitSender($sender_wallet_id,  float $transaction_amount)
    {
        $senderBalance = $this->getUserByWalletId($sender_wallet_id)->account_balance;
        $newBalance = $senderBalance -  $transaction_amount;
        $query = User::where('wallet_id', $sender_wallet_id)->update(['account_balance' => $newBalance]);
        if ($query) {
            DB::commit();
            return true;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getUserByWalletId($user_wallet_id)
    {
        $user = User::where('wallet_id', $user_wallet_id)->first();
        if ($user) {
            return $user;
        }
        return false;
    }

    public function checkIfSenderBalanceIsSufficient(int $sender_wallet_id, float $transaction_amount)
    {
        $userBalance = $this->getUserByWalletId($sender_wallet_id)->account_balance;
        if ($userBalance < $transaction_amount) {
            return (bool)0;
        }
        return true;
    }

    public function formatAsJson($status, $message = '', $data = [], $meta = '', $status_code = '')
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'meta' => $meta
        ], $status_code);
    }
    public function analytics() {
        $todaysRecords = Transaction::whereDay('created_at', Carbon::now()->day)->with(['sender', 'recipient'])->get();
        $sumAmount1 = $todaysRecords->sum('transaction_amount');
        
        $thisMonthRecords = Transaction::whereMonth('created_at', Carbon::now()->month)->with(['sender', 'recipient'])->get();
        $sumAmount2 = $thisMonthRecords->sum('transaction_amount');
        
        $lastMonthRecords = Transaction::whereMonth('created_at', Carbon::now()->month - 1)->with(['sender', 'recipient'])->get();
        $sumAmount3 = $lastMonthRecords->sum('transaction_amount');
       
        $queryTransactions = DB::table('users')->get(['first_name', 'last_name', 'account_balance']);
        
        return $this->formatAsJson(
            true, 
            "Transaction Analytics for today,this month and last month, and all users account balance", 
            [
                'today' =>[
                    'SumOfTransactionsToday' => $sumAmount1,
                    'Transactions_count' => count($todaysRecords)
                ],
                'thisMonth' => [
                    'SumOfTransactionsThisMonth' => $sumAmount2,
                    'Transactions_count' => count($thisMonthRecords)
                ],
                'lastMonth' => [
                    'SumOfTransactionsLastMonth' => $sumAmount3,
                    'Transactions_count' => count($lastMonthRecords)
                ],
                'Transactions_Today'=>$todaysRecords,
                'Transactions_ThisMonth'=> $thisMonthRecords,
                'Transactions_LastMonth' => $lastMonthRecords,
                'UsersAccountBalance' => $queryTransactions
            ], 
            '', 200
        );
        
    }
}
