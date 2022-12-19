<?php

namespace App\Http\Controllers;

use Validator;
use App\ProductAttribute;
use App\AttributeType;
use App\Product;
use App\ProductToAttribute;
use Response;
use Auth;
use Helper;
use DB;
use Redirect;
use Session;
use Illuminate\Http\Request;

class ProductToAttributeController extends Controller {

    public function index(Request $request) {
        $productList = Product::where('variant_product', '1');
        $productList = $productList->where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productArr = ['0' => __('label.SELECT_PRODUCT_OPT')] + $productList;
        $attributeTypeList = AttributeType::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $attributeTypeArr = ['0' => __('label.SELECT_ATTRIBUTE_TYPE_OPT')] + $attributeTypeList;
        $attributeArr = $attributeRelateToProduct = [];
        $inactiveAttributeArr = [];

        if (!empty($request->get('product_id'))) {
            //get all product list
            $attributeArr = ProductAttribute::select('product_attribute.id', 'product_attribute.name')
                            ->orderBy('order', 'asc')->get()->toArray();
            $inactiveAttributeArr = ProductAttribute::where('status', '2')->pluck('id')->toArray();
            $relatedAttributeArr = ProductToAttribute::select('product_to_attribute.attribute_id')
                    ->where('product_to_attribute.product_id', $request->get('product_id'))
                    ->get();

            if (!$relatedAttributeArr->isEmpty()) {
                foreach ($relatedAttributeArr as $relatedAttribute) {
                    $attributeRelateToProduct[$relatedAttribute->attribute_id] = $relatedAttribute->attribute_id;
                }
            }
        }

        return view('productToAttribute.index')->with(compact('productArr', 'attributeTypeArr', 'attributeArr', 'attributeRelateToProduct', 'request', 'inactiveAttributeArr'));
    }

    public function getAttributesToRelate(Request $request) {

        $attributeArr = $attributeRelateToProduct = [];
        $attributeArr = ProductAttribute::select('product_attribute.id', 'product_attribute.name')
                        ->where('product_attribute.attribute_type_id', $request->get('attribute_type_id'))
                        ->orderBy('order', 'asc')->get();

        $inactiveAttributeArr = ProductAttribute::where('product_attribute.attribute_type_id', $request->get('attribute_type_id'))
                        ->where('status', '2')->pluck('id')->toArray();

        $relatedAttributeArr = ProductToAttribute::join('product_attribute', 'product_attribute.id', '=', 'product_to_attribute.attribute_id')
                ->select('product_to_attribute.attribute_id')
                ->where('product_attribute.attribute_type_id', $request->attribute_type_id)
                ->where('product_to_attribute.product_id', $request->product_id)
                ->get();
//        echo '<pre>';
//        print_r($relatedAttributeArr->toArray());
//        exit();
        if (!$relatedAttributeArr->isEmpty()) {
            foreach ($relatedAttributeArr as $relatedAttribute) {
                $attributeRelateToProduct[$relatedAttribute->attribute_id] = $relatedAttribute->attribute_id;
            }
        }

        $view = view('productToAttribute.showAttributes', compact('attributeArr', 'attributeRelateToProduct', 'request', 'inactiveAttributeArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedAttributes(Request $request) {
        // Set Name of Selected Supplier
        $product = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->select('product.name', 'product.product_code as code', 'product_category.name as category_name')
                        ->where('product.id', $request->product_id)->first();
        $relatedAttributeArr = ProductToAttribute::select('product_to_attribute.attribute_id')
                ->where('product_to_attribute.product_id', $request->product_id)
                ->get();
        // Make array selected Product of related Brand's  
        $attributeRelateToProduct = [];
        if (!$relatedAttributeArr->isEmpty()) {
            foreach ($relatedAttributeArr as $relatedAttribute) {
                $attributeRelateToProduct[$relatedAttribute->attribute_id] = $relatedAttribute->attribute_id;
            }
        }
        // Get Details of Related Brand
        $attributeArr = [];
        if (isset($attributeRelateToProduct)) {
            $attributeArr = ProductAttribute::whereIn('product_attribute.id', $attributeRelateToProduct)
                            ->select('product_attribute.name', 'product_attribute.id')
                            ->where('status', '1')
                            ->orderBy('product_attribute.name', 'asc')->get()->toArray();
        }
        $inactiveAttributeArr = ProductAttribute::where('status', '2')->pluck('id')->toArray();
        $view = view('productToAttribute.showRelatedAttributes', compact('attributeArr'
                        , 'relatedAttributeArr', 'attributeRelateToProduct', 'request', 'product'
                        , 'inactiveAttributeArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function relateProductToAttribute(Request $request) {
        $rules = [
            'product_id' => 'required|not_in:0',
            'attributeType_id' => 'required|not_in:0',
            'attribute' => 'required',
        ];
         
//        echo '<pre>';
//        print_r($request);
//        exit();

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $i = 0;
        $target = [];
        if (!empty($request->attribute)) {
            foreach ($request->attribute as $attributeId) {
                //data entry to product pricing table
                $target[$i]['product_id'] = $request->product_id;
                $target[$i]['attribute_id'] = $attributeId;
                $target[$i]['attribute_type_id'] = $request->attributeType_id;
                $target[$i]['created_by'] = Auth::user()->id;
                $target[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        //delete before inserted 
        ProductToAttribute::where('product_id', $request->product_id)->where('attribute_type_id', $request->attributeType_id)->delete();

        if (ProductToAttribute::insert($target)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_HAS_BEEN_RELATED_TO_BRAND_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_RELATE_PRODUCT_TO_BRAND')), 401);
        }
    }

}
