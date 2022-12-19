<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Product;
use App\Model\Category;
use DB;

class ProductController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $products = Product::orderBy('products.id', 'desc')->leftJoin('categories','categories.id','=','products.category_id')
                ->select('products.*',DB::raw('categories.name as catName'))->get();

        $productArr = [];
        if(!$products->isEmpty()){
            foreach ($products as $key => $pInfo){
                $productArr[$pInfo->id]['name'] = $pInfo['name'] ?? '';
                $productArr[$pInfo->id]['catName'] = $pInfo['catName'] ?? '';
            }
             
                 
        }
       
        return view('admin.product.productList', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $categoryArr = Category::where('status',1)->orderBy('name','asc')->pluck('name', 'id')->toArray();
        $categoryList = ['0' => __('lang.SELECT_CATEGORY_OPT')] + $categoryArr;
        return view('admin.product.productCreate')->with(compact('categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|unique:products|max:255',
            'category_id' => 'required',
            'description' => 'string',
            'unit_price' => 'required|numeric',
            'quantity' => 'required||numeric',
        ]);

        $product = new Product();
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->unit_price = $request->unit_price;
        $product->stock = $request->quantity;
        $product->status = $request->status;
        $product->save();
        return Redirect()->route('productList')->with('status', 'Product Created Successfullly!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $product = Product::findOrFail($id);
        return view('admin.product.productEdit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $product = Product::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:products,name,' . $product->id,
            'category_id' => 'required',
            'description' => 'string',
            'unit_price' => 'required|numeric',
            'quantity' => 'required||numeric',
            'status' => 'required||numeric',
        ]);

        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->unit_price = $request->unit_price;
        $product->stock = $request->quantity;
        $product->status = $request->status;
        $product->save();
        return Redirect()->route('productList')->with('status', 'Product Updated Successfullly!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $product = Product::findOrFail($id);
        $product->delete();
        return Redirect()->route('productList')->with('status', 'Product Deleted Successfullly!');
    }

}
