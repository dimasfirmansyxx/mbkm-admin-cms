<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function list()
    {
        $data = Voucher::all();

        return view('voucher.list', compact('data'));
    }

    public function form(Request $request)
    {
        $view = view('voucher.form');

        if($request->filled('id')) {
            $data = Voucher::where('id',$request->id)->first();
            if(!$data) return redirect('/voucher');
            $view = $view->with('data', $data);
        }

        return $view;
    }

    public function save(Request $request)
    {
        \DB::beginTransaction();
        try {
            if(!$request->filled('code')) throw new \Exception('Code field must be filled');
            if(!$request->filled('type') || $request->type == '0') throw new \Exception('Type field must be filled');
            if(!$request->filled('disc_value') || $request->disc_value < 1) throw new \Exception('Discount Value field must be filled');
            if(!$request->filled('start_date')) throw new \Exception('Start Date field must be filled');
            if(!$request->filled('end_date')) throw new \Exception('End Date field must be filled');

            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            if($startDate->gt($endDate)) throw new \Exception('Start Date cannot be greater than End Date');

            if($request->type == '2' && ($request->disc_value < 0 || $request->disc_value > 100)) throw new \Exception('Discount value not valid for percentage type');



            if($request->filled('id')) $voucher = Voucher::where('id',$request->id)->first();
            else $voucher = new Voucher;
            if(!$voucher) return redirect('/voucher');

            $voucher->code = $request->code;
            $voucher->type = $request->type;
            $voucher->disc_value = $request->disc_value;
            $voucher->start_date = $request->start_date;
            $voucher->end_date = $request->end_date;
            $voucher->status = true;
            $voucher->save();

            \DB::commit();

            return redirect('/voucher')->with('success','Voucher saved successfully');
        } catch(\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        \DB::beginTransaction();
        try {
            if(!$request->filled('id')) return redirect()->back();

            $voucher = Voucher::where('id',$request->id)->first();
            if(!$voucher) throw new \Exception('ID not found');
            $voucher->delete();

            \DB::commit();

            return redirect('/voucher')->with('success','Voucher deleted successfully');
        } catch(\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
}
