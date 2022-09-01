<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    public function productList()
    {
        $data = Product::with('category')->get();

        return view('product.list', compact('data'));
    }

    public function productForm(Request $request)
    {
        $categories = ProductCategory::all();
        $view = view('product.form', compact('categories'));

        if($request->filled('id')) {
            $data = Product::where('id',$request->id)->with('category')->first();
            if(!$data) return redirect('/product');
            $view = $view->with('data', $data);
        }

        return $view;
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
