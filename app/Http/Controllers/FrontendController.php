<?php

namespace App\Http\Controllers;

use App\Product;
use App\Brand;
use App\ProductImage;
use App\ProductSKUCode;
use App\Customer;
use App\User;
use App\ProductCategory;
use App\ProductToAttribute;
use App\ProductOffer;
use App\Wishlist;
use App\Cluster;
use App\ProductAttribute;
use App\Subscribe; //model class
use App\ContactInfo;
use App\SocialNetwork;
use App\WarehouseStore;
use App\Advertisement;
use App\Banner;
use App\Division;
use App\District;
use App\Thana;
use App\Zone;
use App\NewsAndEvents;
use App\FooterMenu;
use Validator;
use Common;
use Auth;
use Session;
use Redirect;
use Helper;
use Response;
use DB;
use Hash;
use Illuminate\Http\Request;

class FrontendController extends Controller {

    private $controller = 'Frontend';

    public function index() {
        $target = DB::table('product_sku_code')
                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                        , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                ->get();

        $attrList = ProductAttribute::where('status', '1')
                ->pluck('name', 'id')
                ->toArray();

        if (!$target->isEmpty()) {
            foreach ($target as $data) {
                $data->productImage = json_decode($data->productImage, true);

                $attributeIdArr = !empty($data->attribute) ? explode(',', $data->attribute) : [];
                $data->productAttribute = '';

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $data->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }
            }
        }




// add bakibillah
        $bannerArr = Banner::orderBy('banner.order', 'asc')->get();
        $advertisementArr = Advertisement::where('show_advertise', 1)->orderBy('advertisement.order', 'asc')->get();
        $newsAndEvents = NewsAndEvents::where('status_id', 1)->orderBY('order', 'desc')->select('title', 'slug', 'content', 'featured_image', 'created_at', 'updated_at', 'publish_date', 'location')->paginate(3);

// Start Highlighted Category
        $highlightedCategoryInfo = ProductCategory::where('status', 1)
                ->where('highlighted', '1')
                ->select('id', 'name', 'image')
                ->orderBy('order', 'asc')
                ->get();
// End Highlighted Category
// start Special Product 
        $skuIdArr = [];
        $productSkuInfo = ProductOffer::pluck('sku_data', 'category')
                ->toArray();

        if (!empty($productSkuInfo)) {
            foreach ($productSkuInfo as $category => $skuData) {
                $skuInfo = !empty($skuData) ? json_decode($skuData, true) : [];
                if (!empty($skuInfo)) {
                    foreach ($skuInfo as $skuId => $info) {
                        $skuIdArr[$category][$skuId] = $skuId;
                    }
                }
            }
        }



        $specialProductInfo = DB::table('product_sku_code')
                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->leftJoin('product_to_product_offer', 'product_to_product_offer.product_id', 'product.id')
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                ->whereIn('product_sku_code.id', $skuIdArr[3])
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                        , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                ->inRandomOrder()
                ->get();

        if (!$specialProductInfo->isEmpty()) {
            foreach ($specialProductInfo as $data) {
                $data->productImage = json_decode($data->productImage, true);

                $attributeIdArr = !empty($data->attribute) ? explode(',', $data->attribute) : [];

                $data->productAttribute = '';

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $data->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }
            }
        }
        // end Special Product
        // start Latest Product

        $latestProductInfo = DB::table('product_sku_code')
                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->leftJoin('product_to_product_offer', 'product_to_product_offer.product_id', 'product.id')
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                ->whereIn('product_sku_code.id', $skuIdArr[2])
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                        , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                ->inRandomOrder()
                ->get();
        if (!$latestProductInfo->isEmpty()) {
            foreach ($latestProductInfo as $latest) {
                $latest->productImage = json_decode($latest->productImage, true);

                $attributeIdLArr = !empty($latest->attribute) ? explode(',', $latest->attribute) : [];
                $latest->productAttribute = '';
                if (!empty($attributeIdLArr)) {
                    foreach ($attributeIdLArr as $keyL => $attrIdL) {
                        $latest->productAttribute .= (!empty($attrLList[$attrIdL]) ? $attrLList[$attrIdL] . ' ' : ' ');
                    }
                }
            }
        }

        // end Latest Product
        // start Featured Product

        $featuredProductInfo = DB::table('product_sku_code')
                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->leftJoin('product_to_product_offer', 'product_to_product_offer.product_id', 'product.id')
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                ->whereIn('product_sku_code.id', $skuIdArr[1])
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                        , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                ->inRandomOrder()
                ->get();
        if (!$featuredProductInfo->isEmpty()) {
            foreach ($featuredProductInfo as $featured) {
                $featured->productImage = json_decode($featured->productImage, true);

                $attributeIdFArr = !empty($featured->attribute) ? explode(',', $featured->attribute) : [];
                $featured->productAttribute = '';
                if (!empty($attributeIdFArr)) {
                    foreach ($attributeIdFArr as $keyF => $attrIdF) {
                        $featured->productAttribute .= (!empty($attrFList[$attrIdF]) ? $attrFList[$attrIdF] . ' ' : ' ');
                    }
                }
            }
        }
        if (!empty($request->search)) {

            $searchInfo = ProductCategory::where('name', 'LIKE', '%' . $request->search . '%')->first();

            return redirect('shop/category/' . $searchInfo->id . '/?search=' . $request->search);
        }
        // end Featured Product
// end bakibillah
        return view('frontend.index')->with(compact('target', 'featuredProductInfo'
                                , 'bannerArr', 'advertisementArr', 'newsAndEvents', 'specialProductInfo', 'latestProductInfo'
                                , 'highlightedCategoryInfo'));
    }

    public function aboutUs() {

        return view('frontend.aboutUs');
    }

    public function contactUs() {

        return view('frontend.contactUs');
    }

    public function thankYou() {

        return view('frontend.thankYou');
    }

    public function shop(Request $request) {
        $target = DB::table('product_sku_code')
                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                        , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                ->paginate(12);

        $attrList = ProductAttribute::where('status', '1')
                ->pluck('name', 'id')
                ->toArray();

        if (!$target->isEmpty()) {
            foreach ($target as $data) {
                $data->productImage = json_decode($data->productImage, true);

                $attributeIdArr = !empty($data->attribute) ? explode(',', $data->attribute) : [];
                $data->productAttribute = '';

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $data->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }
            }
        }


        $skuIdArr = [];
        $productSkuInfo = ProductOffer::pluck('sku_data', 'category')
                ->toArray();

        if (!empty($productSkuInfo)) {
            foreach ($productSkuInfo as $category => $skuData) {
                $skuInfo = !empty($skuData) ? json_decode($skuData, true) : [];
                if (!empty($skuInfo)) {
                    foreach ($skuInfo as $skuId => $info) {
                        $skuIdArr[$category][$skuId] = $skuId;
                    }
                }
            }
        }


        $specialProductInfo = DB::table('product_sku_code')
                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->leftJoin('product_to_product_offer', 'product_to_product_offer.product_id', 'product.id')
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                ->whereIn('product_sku_code.id', $skuIdArr[3])
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                        , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                ->inRandomOrder()
                ->limit(15)
                ->get();
        if (!$specialProductInfo->isEmpty()) {
            foreach ($specialProductInfo as $data) {
                $data->productImage = json_decode($data->productImage, true);

                $attributeIdArr = !empty($data->attribute) ? explode(',', $data->attribute) : [];

                $data->productAttribute = '';

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $data->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }
            }
        }

        // advice
        // bakibillah 
        $advertisementInfo = Advertisement::where('show_advertise', 2)->orderBy('advertisement.order', 'asc')->first();

        $productCategoryArr = ['' => __('label.ALL_CATEGORIES')] + Common::getAllProductCategory();

        $categoryArr = ProductCategory::where('status', 1)->orderBy('order', 'asc')->select('name', 'id', 'parent_id')->get();
        $categoryList = ProductCategory::where('status', 1)->pluck('name', 'id')->toArray();

        $leftCategoryArr = $parentCatArr = $parentIdArr = [];
        foreach ($categoryArr as $category) {
            if (empty($category->parent_id)) {
                $parentCatArr[$category->id] = $category->id;
                $childArr = Self::getChildCategory($category->id);
                $leftCategoryArr[$category->id] = (!empty($childArr)) ? $childArr : '';
            }
        }

//        echo '<pre>';
//        print_r($leftCategoryArr);
//        exit;

        $id = '';
        $categoryInfo = [];
        if (!empty($request->search)) {

            $searchInfo = ProductCategory::where('name', 'LIKE', '%' . $request->search . '%')->first();
            $searchInfoId = !empty($searchInfo->id) ? $searchInfo->id : 0;
            return redirect('shop/category/' . $searchInfoId . '/?search=' . $request->search);
        }

        return view('frontend.shop')->with(compact('target', 'specialProductInfo', 'advertisementInfo'
                                , 'productCategoryArr', 'id', 'leftCategoryArr', 'categoryList'
                                , 'categoryInfo', 'parentCatArr','parentIdArr'));
    }

    public function searchedProduct(Request $request) {

        $searchedText = $request->search;
        $productInfo = Product::where('name', 'LIKE', '%' . $request->search . '%')
                        ->pluck('name', 'id')->toArray();

        $productIdArr = [];
        if (!empty($productInfo)) {
            foreach ($productInfo as $id => $info) {
                $productIdArr[$id] = $id;
            }
        }


        $target = DB::table('product_sku_code')
                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                ->whereIn('product.id', $productIdArr)
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                        , 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                ->orderBy('product.name', 'asc')
                ->paginate(12);

        $attrList = ProductAttribute::where('status', '1')
                ->pluck('name', 'id')
                ->toArray();

        if (!$target->isEmpty()) {
            foreach ($target as $data) {
                $data->productImage = json_decode($data->productImage, true);

                $attributeIdArr = !empty($data->attribute) ? explode(',', $data->attribute) : [];
                $data->productAttribute = '';

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $data->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }
            }
        }

        $productPopularProduct = DB::table('product_sku_code')
                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->leftJoin('product_to_product_offer', 'product_to_product_offer.product_id', 'product.id')
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                ->where('product_to_product_offer.popular_product', '1')
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage', DB::raw('MAX(selling_price) as price'))
                ->groupBy('product.id', 'product.name', 'brand.name', 'product_image.image')
                ->inRandomOrder()
                ->limit(4)
                ->get();
        if (!$productPopularProduct->isEmpty()) {
            foreach ($productPopularProduct as $data) {
                $data->productImage = json_decode($data->productImage, true);
            }
        }

        $skuIdArr = [];
        $productSkuInfo = ProductOffer::pluck('sku_data', 'category')
                ->toArray();

        if (!empty($productSkuInfo)) {
            foreach ($productSkuInfo as $category => $skuData) {
                $skuInfo = !empty($skuData) ? json_decode($skuData, true) : [];
                if (!empty($skuInfo)) {
                    foreach ($skuInfo as $skuId => $info) {
                        $skuIdArr[$category][$skuId] = $skuId;
                    }
                }
            }
        }


        $specialProductInfo = DB::table('product_sku_code')
                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->leftJoin('product_to_product_offer', 'product_to_product_offer.product_id', 'product.id')
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                ->whereIn('product_sku_code.id', $skuIdArr[3])
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                        , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                ->inRandomOrder()
                ->limit(15)
                ->get();
        if (!$specialProductInfo->isEmpty()) {
            foreach ($specialProductInfo as $data) {
                $data->productImage = json_decode($data->productImage, true);

                $attributeIdArr = !empty($data->attribute) ? explode(',', $data->attribute) : [];

                $data->productAttribute = '';

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $data->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }
            }
        }

        return view('frontend.search')->with(compact('target', 'productPopularProduct', 'specialProductInfo', 'searchedText'));
    }

    public function getChildCategory($id) {
        $childIdList = ProductCategory::where('parent_id', $id)->pluck('id', 'id')->toArray();
        $childIdArr = [];
        if (!empty($childIdList)) {
            foreach ($childIdList as $id => $id) {
				$childs = Self::getChildCategory($id);
				$childIdArr[$id] = !empty($childs) ? $childs : '';
            }
        }

        return $childIdArr;
    }
	
	public function getChildCategoryList($id, $childIdArr = []) {
        $childIdList = ProductCategory::where('parent_id', $id)->pluck('id', 'id')->toArray();
        
        if (!empty($childIdList)) {
            foreach ($childIdList as $id => $id) {
				$childIdArr[$id] = $id;
				$childIdArr = Self::getChildCategoryList($id, $childIdArr);
            }
        }

        return $childIdArr;
    }
	
	

    public function getParentCategory($id, $parentIdArr=[]) {
        $parent = ProductCategory::where('id', $id)->select('parent_id')->first();
        
        if (!empty($parent->parent_id)) {
            $parentIdArr[$parent->parent_id] = $parent->parent_id;
            $parentIdArr = self::getParentCategory($parent->parent_id, $parentIdArr);
        }

        return $parentIdArr;
    }

    public function categoryWiseProduct($id) {
        $categoryInfo = ProductCategory::find($id);

		$childIdArr[$id] = $id;
        $childIdArr = Self::getChildCategoryList($id, $childIdArr);
        //$childIdArr[$id] = !empty($childs) ? $childs : $id;
		
		//echo '<pre>';
		//print_r($childIdArr);
		//exit;
        
        $parentIdArr = self::getParentCategory($id);
        
        $advertisementInfo = Advertisement::where('show_advertise', 2)->orderBy('advertisement.order', 'asc')->first();
        $productCategoryArr = ['' => __('label.ALL_CATEGORIES')] + Common::getAllProductCategory();

        $categoryArr = ProductCategory::where('status', 1)->orderBy('order', 'asc')->select('name', 'id', 'parent_id')->get();
        $categoryList = ProductCategory::where('status', 1)->pluck('name', 'id')->toArray();

        $leftCategoryArr = $parentCatArr = [];
        foreach ($categoryArr as $category) {
            if (empty($category->parent_id)) {
                $parentCatArr[$category->id] = $category->id;
                $childArr = Self::getChildCategory($category->id);
                $leftCategoryArr[$category->id] = (!empty($childArr)) ? $childArr : '';
            }
        }
//        echo '<pre>';
//        print_r($parentIdArr);
//        exit;

        if (!is_null($categoryInfo)) {
            $target = DB::table('product_sku_code')
                    ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                    ->whereIn('product.product_category_id', $childIdArr)
                    ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                            , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                    ->paginate(12);

            $attrList = ProductAttribute::where('status', '1')
                    ->pluck('name', 'id')
                    ->toArray();

            if (!$target->isEmpty()) {
                foreach ($target as $data) {
                    $data->productImage = json_decode($data->productImage, true);

                    $attributeIdArr = !empty($data->attribute) ? explode(',', $data->attribute) : [];
                    $data->productAttribute = '';

                    if (!empty($attributeIdArr)) {
                        foreach ($attributeIdArr as $key => $attrId) {
                            $data->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                        }
                    }
                }
            }

            $productPopularProduct = DB::table('product_sku_code')
                    ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                    ->leftJoin('product_to_product_offer', 'product_to_product_offer.product_id', 'product.id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                    ->where('product_to_product_offer.popular_product', '1')
                    ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage', DB::raw('MAX(selling_price) as price'))
                    ->groupBy('product.id', 'product.name', 'brand.name', 'product_image.image')
                    ->inRandomOrder()
                    ->limit(4)
                    ->get();
            if (!$productPopularProduct->isEmpty()) {
                foreach ($productPopularProduct as $data) {
                    $data->productImage = json_decode($data->productImage, true);
                }
            }

            $skuIdArr = [];
            $productSkuInfo = ProductOffer::pluck('sku_data', 'category')
                    ->toArray();

            if (!empty($productSkuInfo)) {
                foreach ($productSkuInfo as $category1 => $skuData) {
                    $skuInfo = !empty($skuData) ? json_decode($skuData, true) : [];
                    if (!empty($skuInfo)) {
                        foreach ($skuInfo as $skuId => $info) {
                            $skuIdArr[$category1][$skuId] = $skuId;
                        }
                    }
                }
            }


            $specialProductInfo = DB::table('product_sku_code')
                    ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                    ->leftJoin('product_to_product_offer', 'product_to_product_offer.product_id', 'product.id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                    ->whereIn('product_sku_code.id', $skuIdArr[3])
                    ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                            , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                    ->inRandomOrder()
                    ->limit(15)
                    ->get();
            if (!$specialProductInfo->isEmpty()) {
                foreach ($specialProductInfo as $data) {
                    $data->productImage = json_decode($data->productImage, true);

                    $attributeIdArr = !empty($data->attribute) ? explode(',', $data->attribute) : [];

                    $data->productAttribute = '';

                    if (!empty($attributeIdArr)) {
                        foreach ($attributeIdArr as $key => $attrId) {
                            $data->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                        }
                    }
                }
            }
            if (!empty($request->search)) {

                $searchInfo = ProductCategory::where('name', 'LIKE', '%' . $request->search . '%')->first();

                return redirect('shop/category/' . $searchInfo->id . '/?search=' . $request->search);
            }

            return view('frontend.shop')->with(compact('target', 'productPopularProduct', 'specialProductInfo'
                                    , 'advertisementInfo', 'productCategoryArr', 'id', 'leftCategoryArr'
                                    , 'categoryList', 'categoryInfo', 'parentCatArr', 'parentIdArr'));
        } else {
            return redirect()->back();
        }
    }

    public function productDetail($id, $sku) {

        $target = Product::leftJoin('product_image', 'product_image.product_id', 'product.id')
                ->leftJoin('brand', 'brand.id', 'product.brand_id')
                ->join('product_sku_code', 'product_sku_code.product_id', 'product.id')
                ->where('product.id', $id)
                ->where('product_sku_code.sku', $sku)
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName'
                        , 'product_image.image as productImage', 'product.description as productDescription'
                        , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price'
                        , 'product_sku_code.sku', 'product_sku_code.id as sku_id', 'product_sku_code.attribute', 'product_sku_code.available_quantity'
                )
                ->first();

        $attrList = ProductAttribute::where('status', '1')
                ->pluck('name', 'id')
                ->toArray();

        if (!empty($target)) {
            $productImageArr = json_decode($target->productImage, true);

            $attributeIdArr = !empty($target->attribute) ? explode(',', $target->attribute) : [];

            $target->productAttribute = '';
            if (!empty($attributeIdArr)) {
                foreach ($attributeIdArr as $key => $attrId) {
                    $target->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                }
            }
        }


        $similarProductInfo = Product::leftJoin('product_image', 'product_image.product_id', 'product.id')
                ->leftJoin('brand', 'brand.id', 'product.brand_id')
                ->leftJoin('product_sku_code', 'product_sku_code.product_id', 'product.id')
                ->where('product.id', $id)
                ->where('product_sku_code.sku', '!=', $sku)
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage', 'product.description as productDescription'
                        , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                ->get();

        if (!$similarProductInfo->isEmpty()) {
            foreach ($similarProductInfo as $productInfo) {
                $productInfo->productImage = json_decode($productInfo->productImage, true);

                $attributeIdArr = !empty($productInfo->attribute) ? explode(',', $productInfo->attribute) : [];

                $productInfo->productAttribute = '';
                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $productInfo->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }
            }
        }


        $productArr = DB::table('product_sku_code')
                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                        , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                ->get();

        if (!$productArr->isEmpty()) {
            foreach ($productArr as $data) {
                $data->productImage = json_decode($data->productImage, true);

                $attributeIdArr = !empty($data->attribute) ? explode(',', $data->attribute) : [];
                $data->productAttribute = '';
                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $data->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }
            }
        }

        $check = [];
        if (Auth::Check() && (Auth::user()->group_id == 9)) {
            $customerId = Customer::select('id')->where('user_id', $userId = Auth::user()->id)->first();
            $check = Wishlist::where('sku_id', $id)->where('customer_id', $customerId->id)->first();
        }

        $skuIdArr = [];
        $latestProductSkuInfo = ProductOffer::where('category', 2)
                ->select('sku_data')
                ->first();
        if (!empty($latestProductSkuInfo)) {
            $skuInfo = json_decode($latestProductSkuInfo->sku_data, true);
        }
        if (!empty($skuInfo)) {
            foreach ($skuInfo as $skuId => $info) {
                $skuIdArr[$skuId] = $skuId;
            }
        }

        $latestProductInfo = DB::table('product_sku_code')
                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->leftJoin('product_to_product_offer', 'product_to_product_offer.product_id', 'product.id')
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                ->whereIn('product_sku_code.id', $skuIdArr)
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                        , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                ->inRandomOrder()
                ->limit(5)
                ->get();
        if (!$latestProductInfo->isEmpty()) {
            foreach ($latestProductInfo as $data) {
                $data->productImage = json_decode($data->productImage, true);

                $attributeIdArr = !empty($data->attribute) ? explode(',', $data->attribute) : [];
                $data->productAttribute = '';
                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $data->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }
            }
        }

        return view('frontend.productDetail', compact('target', 'productImageArr', 'check', 'productArr', 'latestProductInfo'
                        , 'similarProductInfo'));
    }

    public function register() {
        if (Auth::check()) {
            return redirect('/');
        }
        $typeList = ['0' => __('label.SELECT_TYPE_OPT'), '1' => __('label.RETAILER_'), '2' => __('label.DISTRIBUTOR_')];
        $clusterList = ['0' => __('label.SELECT_CLUSTER_OPT')] + Cluster::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $zoneList = array('0' => __('label.SELECT_ZONE_OPT'));
        $infrastructureTypeList = ['0' => __('label.SELECT_INFRASTRUCTURE_TYPE_OPT'), '1' => __('label.PERMANENT'), '2' => __('label.TEMPORARY')];
        $divisionList = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::where('country_id', 18)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $districtList = ['0' => __('label.SELECT_DISTRICT_OPT')];
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')];
        $order = count(Helper::getOrderList("Retailer", 1));
        return view('frontend.register', compact('typeList', 'clusterList', 'zoneList', 'divisionList', 'infrastructureTypeList', 'thanaList', 'districtList', 'order'));
    }

    public function getDistrict(Request $request) {
        $districtList = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', $request->divisionId)->pluck('name', 'id')->toArray();
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')];
        //rendering views
//        return $districtList;
        $html = view('frontend.showDistrict', compact('districtList'))->render();
        $html2 = view('frontend.showThana', compact('thanaList'))->render();
        return Response::json(['html' => $html, 'html2' => $html2]);
    }

    public function getThana(Request $request) {
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')] + Thana::where('district_id', $request->thanaId)->pluck('name', 'id')->toArray();
        //rendering view
        $html = view('frontend.showThana', compact('thanaList'))->render();
        return Response::json(['html' => $html]);
    }

    public function getZone(Request $request) {
        $zoneList = ['0' => __('label.SELECT_ZONE_OPT')] + Zone::where('cluster_id', $request->cluster_id)->pluck('name', 'id')->toArray();
        //rendering view
        $html = view('frontend.showZone', compact('zoneList'))->render();
        return Response::json(['html' => $html]);
    }

    public function login() {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('frontend.login');
    }

    public function loginAndRegister() {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('frontend.loginAndRegister');
    }

    public function productQuickView(Request $request) {

        $target = DB::table('product_sku_code')
                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->join('product_category', 'product_category.id', 'product.product_category_id')
                ->leftJoin('product_image', 'product_image.product_id', 'product.id')
                ->leftJoin('brand', 'brand.id', 'product.brand_id')
                ->where('product.id', $request->product_id)
                ->where('product_sku_code.sku', $request->sku_code)
                ->select('product.id as productId', 'product.name as productName'
                        , 'brand.name as brandName', 'product_image.image as productImage', 'product_category.name as categoryName'
                        , 'product.description as productDescription', 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku'
                        , 'product_sku_code.attribute', 'product_sku_code.available_quantity'
                        , 'product_sku_code.id as sku_id')
                ->first();

        $localProductQtys = WarehouseStore::join('warehouse', 'warehouse.id', '=', 'wh_store.warehouse_id')
                        ->select('warehouse.name as wh_name', 'wh_store.quantity as local_quantity')
                        ->where('wh_store.sku_id', $target->sku_id)->get();

        $attrList = ProductAttribute::where('status', '1')
                ->pluck('name', 'id')
                ->toArray();

        if (!empty($target)) {
            $target->productImage = json_decode($target->productImage, true);

            $attributeTypeInfo = ProductToAttribute::where('product_id', $request->product_id)
                            ->join('attribute_type', 'attribute_type.id', 'product_to_attribute.attribute_type_id')
                            ->select('attribute_type.name', 'attribute_type.id')->get();

            $attributeTypeWiseProductAttribute = [];
            if (!$attributeTypeInfo->isEmpty()) {
                foreach ($attributeTypeInfo as $item) {
                    $attributeTypeWiseProductAttribute[$item->id]['attribute_type_name'] = $item->name;
                    $attributeTypeWiseProductAttribute[$item->id]['attribute_type_id'] = $item->id;
                }
                $productAttributeInfo = ProductToAttribute::where('product_id', $request->product_id)
                        ->join('product_attribute', 'product_attribute.id', 'product_to_attribute.attribute_id')
                        ->select('product_to_attribute.attribute_type_id', 'product_to_attribute.attribute_id', 'product_attribute.name')
                        ->get();
                if (!$productAttributeInfo->isEmpty()) {
                    foreach ($productAttributeInfo as $item) {
                        $attributeTypeWiseProductAttribute[$item->attribute_type_id]['attribute'][$item->attribute_id]['attribute_id'] = $item->attribute_id;
                        $attributeTypeWiseProductAttribute[$item->attribute_type_id]['attribute'][$item->attribute_id]['attribute_name'] = $item->name;
                    }
                }
            }

//            $target->productAttribute = $attributeTypeWiseProductAttribute;
            $attributeIdArr = !empty($target->attribute) ? explode(',', $target->attribute) : [];
            $target->productAttribute = '';
            if (!empty($attributeIdArr)) {
                foreach ($attributeIdArr as $key => $attrId) {
                    $target->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                }
            }
        }


        $view = view('frontend.showProductQuickView', compact('target', 'localProductQtys', 'request'))->render();
        return response()->json(['html' => $view]);
    }

    public function openCaptcha(Request $request) {
        $message = [];
        $rules = [
            'email' => 'required|email|unique:subscribe,email',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $varOne = rand(1, 9);
        $varTwo = rand(1, 9);
        $sum = $varOne + $varTwo;
        $view = view('frontend.showCaptcha', compact('varOne', 'varTwo', 'sum', 'request'))->render();
        return response()->json(['html' => $view]);
    }

    public function subscribe(Request $request) {
//        echo '<pre>';
//        print_r($request->all());
//        exit;
        $message = [];
        $rules = [
            'sum' => 'required',
            'sum_val' => 'required',
            'subscriber_email' => 'required|email|unique:subscribe,email',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target = new Subscribe;
        $target->email = $request->subscriber_email;
        $target->created_at = date("Y-m-d H:i:s");
        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.SUBSCRIBED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.ERROR')), 401);
        }
    }

    public function newsDetails(Request $request, $slug) {

        $postDetail = NewsAndEvents::where('slug', $slug)->first();
        $otherPost = NewsAndEvents::where('slug', '!=', $slug)->get();
        return view('frontend.newsDetails', compact('postDetail', 'otherPost'));
    }

    public function PostDetails(Request $request, $slug) {

        $postDetail = FooterMenu::where('slug', $slug)->first();
        return view('frontend.postDetails', compact('postDetail'));
    }

    public function inDepoProducts(Request $request) {
        //echo '<pre>';print_r(Auth::user());exit;
        $target = DB::table('wh_store')->join('warehouse_to_sr', 'warehouse_to_sr.warehouse_id', 'wh_store.warehouse_id')
                ->join('product_sku_code', 'product_sku_code.id', '=', 'wh_store.sku_id')
                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                ->where('warehouse_to_sr.sr_id', Auth::user()->id)
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                        , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                ->paginate(12);

        $attrList = ProductAttribute::where('status', '1')
                ->pluck('name', 'id')
                ->toArray();

        if (!$target->isEmpty()) {
            foreach ($target as $data) {
                $data->productImage = json_decode($data->productImage, true);

                $attributeIdArr = !empty($data->attribute) ? explode(',', $data->attribute) : [];
                $data->productAttribute = '';

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $data->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }
            }
        }


        $skuIdArr = [];
        $productSkuInfo = ProductOffer::pluck('sku_data', 'category')
                ->toArray();

        if (!empty($productSkuInfo)) {
            foreach ($productSkuInfo as $category => $skuData) {
                $skuInfo = !empty($skuData) ? json_decode($skuData, true) : [];
                if (!empty($skuInfo)) {
                    foreach ($skuInfo as $skuId => $info) {
                        $skuIdArr[$category][$skuId] = $skuId;
                    }
                }
            }
        }


        $specialProductInfo = DB::table('product_sku_code')
                ->join('product', 'product.id', '=', 'product_sku_code.product_id')
                ->leftJoin('product_to_product_offer', 'product_to_product_offer.product_id', 'product.id')
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
                ->whereIn('product_sku_code.id', $skuIdArr[3])
                ->select('product.id as productId', 'product.name as productName', 'brand.name as brandName', 'product_image.image as productImage'
                        , 'product_sku_code.distributor_price as distributor_price', 'product_sku_code.selling_price as price', 'product_sku_code.sku', 'product_sku_code.attribute')
                ->inRandomOrder()
                ->limit(15)
                ->get();

        if (!$specialProductInfo->isEmpty()) {
            foreach ($specialProductInfo as $data) {
                $data->productImage = json_decode($data->productImage, true);

                $attributeIdArr = !empty($data->attribute) ? explode(',', $data->attribute) : [];

                $data->productAttribute = '';

                if (!empty($attributeIdArr)) {
                    foreach ($attributeIdArr as $key => $attrId) {
                        $data->productAttribute .= (!empty($attrList[$attrId]) ? $attrList[$attrId] . ' ' : ' ');
                    }
                }
            }
        }

        // advice
        // bakibillah 
        $advertisementInfo = Advertisement::where('show_advertise', 2)->orderBy('advertisement.order', 'asc')->first();

        $productCategoryArr = ['' => __('label.ALL_CATEGORIES')] + Common::getAllProductCategory();

        $categoryArr = ProductCategory::where('status', 1)->orderBy('order', 'asc')->select('name', 'id', 'parent_id')->get();
        $categoryList = ProductCategory::where('status', 1)->pluck('name', 'id')->toArray();

        $leftCategoryArr = $parentCatArr = $parentIdArr = [];
        foreach ($categoryArr as $category) {
            if (empty($category->parent_id)) {
                $parentCatArr[$category->id] = $category->id;
                $childArr = Self::getChildCategory($category->id);
                $leftCategoryArr[$category->id] = (!empty($childArr)) ? $childArr : '';
            }
        }

        $id = '';
        $categoryInfo = [];
        if (!empty($request->search)) {

            $searchInfo = ProductCategory::where('name', 'LIKE', '%' . $request->search . '%')->first();
            $searchInfoId = !empty($searchInfo->id) ? $searchInfo->id : 0;
            return redirect('shop/category/' . $searchInfoId . '/?search=' . $request->search);
        }

        return view('frontend.inDepoProducts')->with(compact('target', 'specialProductInfo', 'advertisementInfo'
                                , 'productCategoryArr', 'id', 'leftCategoryArr', 'categoryList'
                                , 'categoryInfo', 'parentCatArr','parentIdArr'));
    }

}
