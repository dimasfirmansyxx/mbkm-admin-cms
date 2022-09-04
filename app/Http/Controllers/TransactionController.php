<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Models\Transaction;
use App\Models\TransactionDetail;

class TransactionController extends Controller
{
    public function list(Request $request)
    {
        $data = Transaction::select('id','code','customer_name','status','total')->orderBy('created_at','desc')->get();

        return view('trx.list', compact('data'));
    }

    public function create()
    {
        $products = Product::where('status',1)->with('category')->get();
        $categories = ProductCategory::all();

        return view('trx.trx')->with('products',$products)->with('categories',$categories);
    }

    public function update($id)
    {
        $transaction = Transaction::where('id',$id)->where('status',1)->with('trxDetails.product')->with('vocUsages.voucher')->first();
        if(!$transaction) return redirect('/trx');
        $products = Product::where('status',1)->with('category')->get();
        $categories = ProductCategory::all();

        $cart = (object) [];
        foreach($transaction->trxDetails as $detail) {
            $cart->{$detail->products_id} = (object) [
                'id' => $detail->products_id,
                'name' => $detail->product->name,
                'code' => $detail->product->code,
                'price' => $detail->product->price,
                'qty' => $detail->qty,
                'subtotal' => $detail->product->price * $detail->qty,
            ];
        }

        $total = (object) ['subtotal' => 0, 'discount' => (object) ['type' => 'flat','value' => 0, 'voucher' => ''],'total' => 0];
        if($transaction->vocUsages) {
            $vocType = ($transaction->vocUsages->voucher->type == '1') ? 'flat' : 'percentage';
            $total->discount->type = $vocType;
            $total->discount->value = $transaction->vocUsages->voucher->disc_value;
            $total->discount->voucher = $transaction->vocUsages->voucher->code;
        }

        $customer = (object) [
            'name' => $transaction->customer_name, 
            'email' => $transaction->customer_email, 
            'phone' => $transaction->customer_phone, 
            'additional_request' => $transaction->additional_request, 
            'payment_method' => $transaction->payment_method, 
        ];

        $data = (object) compact('cart','total','customer');

        return view('trx.trx')->with('products',$products)->with('categories',$categories)->with('data',$data);
    }

    public function createTrx(Request $request)
    {
        \DB::beginTransaction();
        try {
            $request = json_decode($request->data);
            if(count($request->items) < 1) throw new \Exception('Please select at least one product');

            $calculate = $this->calculate($request);

            $trx = new Transaction;
            $trx->code = $this->generateCode();
            $trx->customer_name = $request->customer->name;
            $trx->customer_email = $request->customer->email;
            $trx->customer_phone = $request->customer->phone;
            $trx->additional_request = $request->customer->additional_request;
            $trx->subtotal = $calculate->total->subtotal;
            $trx->total = $calculate->total->total;
            $trx->total_purchase = $calculate->total->purchase;
            $trx->payment_method = $request->customer->payment_method;
            $trx->status = ($request->action == 'save') ? 1 : 2;
            $trx->save();

            foreach($calculate->items as $item) {
                $detail = new TransactionDetail;
                $detail->transactions_id = $trx->id;
                $detail->products_id = $item->products_id;
                $detail->qty = $item->qty;
                $detail->price_satuan = $item->price_satuan;
                $detail->price_total = $item->price_total;
                $detail->price_purchase_satuan = $item->price_purchase_satuan;
                $detail->price_purchase_total = $item->price_purchase_total;
                $detail->save();
            }

            if($request->total->discount->value > 0) {
                $voucher = Voucher::where('code',$request->total->discount->voucher)->where('status',1)->first();
                if(!$voucher) throw new \Exception('Voucher not found');
                $today = Carbon::now()->format('Y-m-d');
                $start = Carbon::parse($voucher->start_date)->format('Y-m-d');
                $end = Carbon::parse($voucher->end_date)->format('Y-m-d');

                if(strtotime($today) >= strtotime($start) && strtotime($today) <= strtotime($end)) {
                    $vocUsage = new VoucherUsage;
                    $vocUsage->transactions_id = $trx->id;
                    $vocUsage->vouchers_id = $voucher->id;
                    $vocUsage->discounted_value = $calculate->total->discount;
                    $vocUsage->save();

                    $voucher->status = 0;
                    $voucher->save();
                } else throw new \Exception('Voucher are not eligible');
            }

            \DB::commit();

            return redirect('/trx')->with('success','Transaction created');
        } catch(\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function updateTrx(Request $request, $id)
    {
        \DB::beginTransaction();
        try {
            $request = json_decode($request->data);
            if(count($request->items) < 1) throw new \Exception('Please select at least one product');

            $calculate = $this->calculate($request);

            $trx = Transaction::where('id',$id)->where('status',1)->first();
            if(!$trx) throw new \Exception('Transaction not found');
            $trx->customer_name = $request->customer->name;
            $trx->customer_email = $request->customer->email;
            $trx->customer_phone = $request->customer->phone;
            $trx->additional_request = $request->customer->additional_request;
            $trx->subtotal = $calculate->total->subtotal;
            $trx->total = $calculate->total->total;
            $trx->total_purchase = $calculate->total->purchase;
            $trx->payment_method = $request->customer->payment_method;
            $trx->status = ($request->action == 'save') ? 1 : 2;
            $trx->save();

            TransactionDetail::where('transactions_id',$id)->delete();
            foreach($calculate->items as $item) {
                $detail = new TransactionDetail;
                $detail->transactions_id = $trx->id;
                $detail->products_id = $item->products_id;
                $detail->qty = $item->qty;
                $detail->price_satuan = $item->price_satuan;
                $detail->price_total = $item->price_total;
                $detail->price_purchase_satuan = $item->price_purchase_satuan;
                $detail->price_purchase_total = $item->price_purchase_total;
                $detail->save();
            }

            if($request->total->discount->value > 0) {
                $vocUsageOld = VoucherUsage::where('transactions_id',$trx->id)->with('voucher')->first();
                if($vocUsageOld && $request->total->discount->voucher != $vocUsageOld->voucher->code) {
                    $voucher = Voucher::where('code',$request->total->discount->voucher)->where('status',1)->first();
                    if(!$voucher) throw new \Exception('Voucher not found');
                    $today = Carbon::now()->format('Y-m-d');
                    $start = Carbon::parse($voucher->start_date)->format('Y-m-d');
                    $end = Carbon::parse($voucher->end_date)->format('Y-m-d');

                    if(strtotime($today) >= strtotime($start) && strtotime($today) <= strtotime($end)) {
                        $vocUsage = new VoucherUsage;
                        $vocUsage->transactions_id = $trx->id;
                        $vocUsage->vouchers_id = $voucher->id;
                        $vocUsage->discounted_value = $calculate->total->discount;
                        $vocUsage->save();

                        $voucher->status = 0;
                        $voucher->save();
                    } else throw new \Exception('Voucher are not eligible');

                    $vocUsageOld->delete();
                    $vocOld = Voucher::where('id',$vocUsageOld->vouchers_id)->first();
                    $vocOld->status = 1;
                    $vocOld->save();
                }
            }

            \DB::commit();
            return redirect('/trx')->with('success','Transaction updated');
        } catch(\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function generateCode()
    {
        $latestTrx = Transaction::orderBy('created_at','desc')->first();
        $serial = (int) substr($latestTrx->code, -3) + 1;
        if(strlen($serial) == 1) $serial = '00' . $serial;
        elseif(strlen($serial) == 2) $serial = '0' . $serial;

        return 'TR-' . Carbon::now()->format('Ymd') . $serial;
    }

    private function calculate($req)
    {
        $data = (object) ['items' => [], 'total' => (object) ['subtotal' => 0, 'discount' => 0, 'total' => 0, 'purchase' => 0]];
        foreach($req->items as $item) {
            $product = Product::where('id',$item->id)->first();
            if(!$product->status || $item->qty < 1) continue;
            $data->items[] = (object) [
                'products_id' => $product->id,
                'qty' => $item->qty,
                'price_satuan' => $product->price,
                'price_total' => $product->price * $item->qty,
                'price_purchase_satuan' => $product->purchase_price,
                'price_purchase_total' => $product->purchase_price * $item->qty,
            ];

            $data->total->subtotal += $product->price * $item->qty;
            $data->total->purchase += $product->purchase_price * $item->qty;
        }

        $discount = $req->total->discount->value;
        if($req->total->discount->type == 'percentage') $discount = $data->total->subtotal * $req->total->discount->value / 100;
        $data->total->discount = $discount;
        $data->total->total = $data->total->subtotal - $discount;

        return $data;
    }

    public function setPaid(Request $request)
    {
        \DB::beginTransaction();
        try {
            if(!$request->filled('id')) return redirect('/trx');

            $transaction = Transaction::where('id',$request->id)->first();
            if($transaction->status != 1) return redirect('/trx')->with('error','Status of transaction must be PENDING');
            if(!$transaction) return redirect('/trx');
            $transaction->status = 2;
            $transaction->save();

            \DB::commit();
            return redirect('/trx')->with('success','Transaction Paid Successfully');
        } catch(\Exception $e) {
            \DB::rollback();
            return redirect('/trx')->with('error',$e->getMessage());
        }
    }

    public function setCancel(Request $request)
    {
        \DB::beginTransaction();
        try {
            if(!$request->filled('id')) return redirect('/trx');

            $transaction = Transaction::where('id',$request->id)->first();
            if($transaction->status != 1) return redirect('/trx')->with('error','Status of transaction must be PENDING');
            if(!$transaction) return redirect('/trx');
            $transaction->status = 0;
            $transaction->save();

            $vocUsage = VoucherUsage::where('transactions_id',$request->id)->with('voucher')->first();
            if($vocUsage) {
                $vocUsage->voucher->status = 1;
                $vocUsage->voucher->save();
                $vocUsage->delete();
            }

            \DB::commit();
            return redirect('/trx')->with('success','Transaction Canceled Successfully');
        } catch(\Exception $e) {
            \DB::rollback();
            return redirect('/trx')->with('error',$e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        \DB::beginTransaction();
        try {
            if(!$request->filled('id')) return redirect('/trx');

            $transaction = Transaction::where('id',$request->id)->first();
            if($transaction->status != 0) return redirect('/trx')->with('error','Status of transaction must be CANCELED');
            if(!$transaction) return redirect('/trx');
            $transaction->delete();

            \DB::commit();
            return redirect('/trx')->with('success','Transaction Deleted Successfully');
        } catch(\Exception $e) {
            \DB::rollback();
            return redirect('/trx')->with('error',$e->getMessage());
        }
    }

    public function getProduct($id)
    {
        try {
            $product = Product::where('id',$id)->where('status',1)->select('id','name','code','price')->first();
            if(!$product) throw new \Exception('Product not found');

            return response()->json(['status' => true, 'data' => $product], 200);
        } catch(\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function claimVoucher(Request $request)
    {
        try {
            if(!$request->filled('code')) throw new \Exception('Please enter voucher code');

            $voucher = Voucher::where('code',$request->code)->where('status',1)->first();
            if(!$voucher) throw new \Exception('Voucher Code not found');
            
            $today = Carbon::now()->format('Y-m-d');
            $start = Carbon::parse($voucher->start_date)->format('Y-m-d');
            $end = Carbon::parse($voucher->end_date)->format('Y-m-d');

            if(strtotime($today) >= strtotime($start) && strtotime($today) <= strtotime($end)) {
                return response()->json([
                    'status' => true,
                    'data' => [
                        'value' => $voucher->disc_value,
                        'type' => ($voucher->type == 1) ? 'flat' : 'percentage',
                        'voucher' => $voucher->code
                    ]
                ]);
            }

            throw new \Exception('Voucher are not eligible');
        } catch(\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
