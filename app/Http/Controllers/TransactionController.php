<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Voucher;

class TransactionController extends Controller
{
    public function list(Request $request)
    {
        $data = [];

        return view('trx.list', compact('data'));
    }

    public function create()
    {
        $products = Product::where('status',1)->with('category')->get();
        $categories = ProductCategory::all();

        return view('trx.create')->with('products',$products)->with('categories',$categories);
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
                        'type' => ($voucher->type == 1) ? 'flat' : 'percentage'
                    ]
                ]);
            }

            throw new \Exception('Voucher are not eligible');
        } catch(\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
