<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    public function productList(Request $request)
    {
        $data = Product::with('category');

        if($request->filled('name')) $data = $data->where('name','LIKE','%'.$request->name.'%');
        if($request->filled('code')) $data = $data->where('code',$request->code);
        if($request->filled('status')) {
            if($request->status == 'active') $data = $data->where('status', 1);
            elseif($request->status == 'nonactive') $data = $data->where('status', 0);
        }

        $data = $data->get();

        return view('product.list', compact('data'));
    }

    public function productForm(Request $request, $id = null)
    {
        $categories = ProductCategory::all();
        $view = view('product.form', compact('categories'));

        if($id) {
            $data = Product::where('id',$id)->with('category')->first();
            if(!$data) return redirect('/product');
            $view = $view->with('data', $data);
        }

        return $view;
    }

    public function productSave(Request $request, $id = null)
    {
        \DB::beginTransaction();
        try {
            if(!$request->filled('name')) throw new \Exception('Name field must be filled');
            if(!$request->filled('code')) throw new \Exception('Code field must be filled');
            if(!$request->filled('product_categories_id') || $request->product_categories_id == '0') throw new \Exception('Category field must be filled');
            if(!$request->filled('price') || $request->price < 1) throw new \Exception('Price field must be filled');
            if(!$request->filled('purchase_price') || $request->purchase_price < 1) throw new \Exception('Purchase Price field must be filled');

            if($id) {
                $data = Product::where('id',$id)->first();
                if($request->code != $data->code) {
                    $check = Product::where('code',$request->code)->first();
                    if($check) throw new \Exception('Code already exist');
                }
            } else {
                $data = new Product;
                $check = Product::where('code',$request->code)->first();
                if($check) throw new \Exception('Code already exist');
            }

            if(!$data) return redirect('/product');

            $data->name = $request->name;
            $data->code = $request->code;
            $data->product_categories_id = $request->product_categories_id;
            $data->status = ($request->filled('status') && $request->status == 'true');
            $data->price = $request->price;
            $data->purchase_price = $request->purchase_price;
            $data->short_description = $request->short_description;
            $data->description = $request->description;
            $data->new_product = ($request->filled('new_product') && $request->new_product == 'true');
            $data->best_seller = ($request->filled('best_seller') && $request->best_seller == 'true');
            $data->featured = ($request->filled('featured') && $request->featured == 'true');
            $data->save();

            \DB::commit();

            return redirect('/product')->with('success','Product saved successfully');
        } catch(\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function productDelete(Request $request)
    {
        \DB::beginTransaction();
        try {
            if(!$request->filled('id')) return redirect()->back();

            $product = Product::where('id',$request->id)->first();
            if(!$product) throw new \Exception('ID not found');
            $product->delete();

            \DB::commit();

            return redirect('/product')->with('success','Product deleted successfully');
        } catch(\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function categoryList()
    {
        $data = ProductCategory::all();

        return view('category.list', compact('data'));
    }

    public function categoryForm(Request $request)
    {
        $view = view('category.form');

        if($request->filled('id')) {
            $data = ProductCategory::where('id',$request->id)->first();
            if(!$data) return redirect('/product/category');
            $view = $view->with('data', $data);
        }

        return $view;
    }

    public function categorySave(Request $request)
    {
        \DB::beginTransaction();
        try {
            if(!$request->filled('category')) throw new \Exception('Category field must be filled');

            if($request->filled('id')) $category = ProductCategory::where('id',$request->id)->first();
            else $category = new ProductCategory;
    
            if(!$category) return redirect('/product/category');

            $category->category = $request->category;
            $category->description = $request->description;
            $category->save();

            \DB::commit();

            return redirect('/product/category')->with('success','Category saved successfully');
        } catch(\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function categoryDelete(Request $request)
    {
        \DB::beginTransaction();
        try {
            if(!$request->filled('id')) return redirect()->back();

            $category = ProductCategory::where('id',$request->id)->first();
            if(!$category) throw new \Exception('ID not found');
            $category->delete();

            \DB::commit();

            return redirect('/product/category')->with('success','Category deleted successfully');
        } catch(\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
}
