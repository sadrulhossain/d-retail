<?php

namespace App\Http\Controllers;

use Validator;
use App\Customer; //model class
use App\Country;
use App\Division;
use App\User;
use DB;
use Common;
use Session;
use Redirect;
use Auth;
use File;
use Response;
use Image;
use Helper;
use Hash;
use Illuminate\Http\Request;

class CustomerController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
//        0


        $targetArr = Customer::select('customer.*')
                ->orderBy('customer.id', 'desc');


        //begin filtering
        $searchText = $request->search;
        $nameArr = Customer::select('name')->orderBy('name', 'asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('customer.name', 'LIKE', '%' . $searchText . '%');
            });
        }
//        if (!empty($request->buyer_category_id)) {
//            $targetArr = $targetArr->where('buyer.buyer_category_id', '=', $request->buyer_category_id);
//        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('customer.status', '=', $request->status);
        }
        //end filtering

        $buyerIdArr = $targetArr->pluck('customer.id', 'customer.id')->toArray();

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/customer?page=' . $page);
        }


        return view('customer.index')->with(compact('qpArr', 'targetArr', 'nameArr'
                                , 'status'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        return view('customer.create')->with(compact('qpArr'));
    }

    public function getDivision(Request $request) {
        return Common::getDivision($request);
    }

//    public function store(Request $request) {
//        //passing param for custom function
////        $qpArr = $request->all();
////
////        $pageNumber = $qpArr['filter'];
////                echo '<pre>';
////        print_r($request->all());
////        exit;
//        $rules = $message = array();
//        $rules = [
//            'name' => 'required',
//            'email' => 'required',
//            'phone' => 'required',
//            'password' => 'min:6|required_with:conf_password|same:conf_password',
//            'conf_password' => 'min:6'
//        ];
//
////        $messages = array(
////            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
////        );
//
//        $validator = Validator::make($request->all(), $rules, $message);
//        if ($validator->fails()) {
//            return redirect('/frontend/register')
//                            ->withInput()
//                            ->withErrors($validator);
//        }
//
//        $target = new Customer;
//        $target->name = $request->name;
//        $target->email = $request->email;
//        $target->phone = $request->phone;
//        $target->status = 1;
//        $target->password = Hash::make($request->password);
//        DB::beginTransaction();
//        try {
//            if ($target->save()) {
//                $newTarget = new User;
//                $newTarget->username = $request->name;
//                $newTarget->email = $request->email;
//                $newTarget->phone = $request->phone;
//                $newTarget->status = 1;
//                $newTarget->group_id = 9;
//                $newTarget->password = Hash::make($request->password);
//                $newTarget->save();
//            }
//            DB::commit();
//            Session::flash('success', __('label.CUSTOMER_CREATED_SUCCESSFULLY'));
//            return back();
//        } catch (\Throwable $e) {
//            DB::rollback();
//            Session::flash('success', __('label.CUSTOMER_COULD_NOT_BE_CREATED'));
//            return back();
//        }
//    }

    public function edit(Request $request, $id) {
        //passing param for custom function
        $qpArr = $request->all();
        $target = Customer::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('customer');
        }

        $countryList = ['0' => __('label.SELECT_COUNTRY_OPT')] + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $divisionList = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        return view('customer.edit')->with(compact('qpArr', 'target', 'divisionList', 'countryList'));
    }

    public function update(Request $request) {
        $target = Customer::find($request->id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update
        $rules = $message = array();
        $rules = [
            'country_id' => 'required|not_in:0',
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ];


        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect('customer/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }


        $target->country_id = $request->country_id;
        $target->division_id = $request->country_id == 18 ? $request->division_id : 0;
        $target->name = $request->name;
        $target->email = $request->email;
        $target->phone = $request->phone;
        $target->code = $request->code;
        $target->gmap_embed_code = $request->gmap_embed_code;
        $target->status = $request->status;


        if ($target->save()) {
            Session::flash('success', __('label.CUSTOMER_UPDATED_SUCCESSFULLY'));
            return redirect('customer');
        } else {
            Session::flash('success', __('label.CUSTOMER_COULD_NOT_BE_UPDATED'));
            return back();
        }
    }

    public function destroy(Request $request, $id) {
        $target = Customer::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        if ($target->delete()) {
            Session::flash('error', __('label.BUYER_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.BUYER_COULD_NOT_BE_DELETED'));
        }
        return redirect('customer' . $pageNumber);
    }

    public function newContactPersonToCreate() {
        return Common::buyerContactPerson();
    }

    public function newContactPersonToEdit() {
        return Common::buyerContactPerson();
    }

    public function getDetailsOfContactPerson(Request $request) {
        $target = Buyer::find($request->buyer_id);
        $buyerName = $target->name;
        $contactPersonArr = json_decode($target->contact_person_data, true);

        $view = view('buyer.showContactPersonDetails', compact('contactPersonArr', 'request', 'buyerName'))->render();
        return response()->json(['html' => $view]);
    }

    public function showLocationView(Request $request) {
        $target = Buyer::find($request->buyer_id);
        $view = view('buyer.showMapView', compact('target'))->render();
        return response()->json(['html' => $view]);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status . '&division_id=' . $request->division_id . '&country_id=' . $request->country_id;
        return Redirect::to('customer?' . $url);
    }

    public function manageBuyer(Request $request, $id) {
        $qpArr = $request->all();
        $target = Buyer::find($id);

        $buyerCatArr = BuyerCategory::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $countryList = Country::pluck('name', 'id')->toArray();
        $divisionList = Division::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $competitorsProductArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->where('competitors_product', '1')
                        ->select('product.name', 'product.id'
                                , 'product_category.name as product_category')->get();

        $finishedGoodsArr = FinishedGoods::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $packagingMachineArr = Helper::getMachineType();
        $customerTypeArr = Helper::getCustomerType();
        $customerTypes = $machineTypes = '';
        if (!empty($target->customer_type)) {
            $customerTypes = explode(',', $target->customer_type);
        }

        if (!empty($target->machine_type)) {
            $machineTypes = explode(',', $target->machine_type);
        }
        //Get Previous Related Product
        $prevRelatedFinishedGoods = json_decode($target->related_finished_goods, true);
        $prevRelatedComProducts = [];
        if (!empty($target->related_competitors_product)) {
            $prevRelatedComProducts = json_decode($target->related_competitors_product, true);
        }
        //set gsm & volumes data
        $prevGsmValues = BuyerToGsmVolume::select('buyer_to_gsm_volume.set_gsm_volume', 'buyer_to_gsm_volume.product_id')
                        ->where('buyer_id', $id)->get();

        $tempGsmValues = [];
        if (!empty($prevGsmValues)) {
            foreach ($prevGsmValues as $item) {
                $tempGsmValues[$item['product_id']] = $item['set_gsm_volume'];
            }
        }

        //get data of buyer to product
        $addedBuyerToProduct = BuyerToProduct::where('buyer_id', $id)->select('product_id')->get();

        $relatedBuyerToProduct = [];
        if (!$addedBuyerToProduct->isEmpty()) {
            foreach ($addedBuyerToProduct as $addedProduct) {
                $relatedBuyerToProduct[$addedProduct->product_id] = $addedProduct->product_id;
            }
        }
        //get products for set volumes individual buyer
        $buyerProducts = array_merge($relatedBuyerToProduct, $prevRelatedComProducts);
        $productsArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                ->whereIn('product.id', $buyerProducts)
                ->orderBy('product.competitors_product', 'asc')
                ->orderBy('product.name', 'asc')
                ->select('product.name', 'product.id', 'product_category.name as category_name'
                        , 'product.competitors_product')
                ->get();



        return view('buyer.manageBuyer')->with(compact('target', 'competitorsProductArr', 'qpArr'
                                , 'prevRelatedComProducts', 'finishedGoodsArr', 'prevRelatedFinishedGoods', 'packagingMachineArr'
                                , 'customerTypeArr', 'customerTypes', 'machineTypes'
                                , 'tempGsmValues', 'productsArr', 'buyerCatArr'
                                , 'countryList', 'divisionList'));
    }

    public function saveCompetitorProduct(Request $request) {
        $rules = [
            'product_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $productIdArr = $request->product_id;
        //Prepare Buyer to Competitor's Product as Array
        $comProductArr = [];
        if (!empty($productIdArr)) {
            foreach ($productIdArr as $key => $productId) {
                $comProductArr[] = $productId;
            }
        }
        $relatedProduct['related_competitors_product'] = json_encode($comProductArr);
        $updateComProduct = Buyer::where('id', $request->buyer_id)->update($relatedProduct);
        if ($updateComProduct) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.BUYERS_COMPETITOR_PRODUCT_ASSIGNED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.SORRY_BUYERS_COMPETITOR_PRODUCT_NOT_ASSIGNED_SUCCESSFULLY')], 401);
        }
    }

    public function saveFinishedProduct(Request $request) {
        $rules = [
            'finished_goods' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $goodsArr = $request->finished_goods;
        //Prepare Buyer to Finished Goods as Array
        $buyersGoodsArr = [];
        if (!empty($goodsArr)) {
            foreach ($goodsArr as $key => $goodsId) {
                $buyersGoodsArr[] = $goodsId;
            }
        }
        $relatedGoods['related_finished_goods'] = json_encode($buyersGoodsArr);
        $updateGoods = Buyer::where('id', $request->buyer_id)->update($relatedGoods);
        if ($updateGoods) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.BUYERS_FINISHED_GOODS_ASSIGNED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.SORRY_BUYERS_FINISHED_GOODS_NOT_ADDED_SUCCESSFULLY')], 401);
        }
    }

    public function saveOthers(Request $request) {
        $target = Buyer::find($request->buyer_id);
        $target->fsc_certified = $request->fsc_certified;
        $target->iso_certified = $request->iso_certified;
        $target->machine_type = !empty($request->machine_type) ? implode(',', $request->machine_type) : NULL;
        $target->machine_brand = $request->machine_brand;
        $target->machine_length = $request->machine_length;
        $target->customer_type = !empty($request->customer_type) ? implode(',', $request->customer_type) : NULL;

        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.BUYERS_OTHERS_INFORMATION_CREATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.BUYERS_OTHERS_INFORMATION_COULD_NOT_BE_CREATED')], 401);
        }
    }

    public function getGsmVolume(Request $request) {
        $gsmValues = BuyerToGsmVolume::join('product', 'product.id', '=', 'buyer_to_gsm_volume.product_id')
                ->select('set_gsm_volume')->where('buyer_id', $request->buyer_id)
                ->where('product_id', $request->product_id)
                ->first();
        $prevBuyerGsmValues = [];
        if (!empty($gsmValues)) {
            $prevBuyerGsmValues = json_decode($gsmValues->set_gsm_volume, true);
        }

        $buyerId = $request->buyer_id;
        $productId = $request->product_id;
        $product = Product::select('name')->where('id', $productId)->first();
        $view = view('buyer.showGsmVolume', compact('buyerId', 'productId', 'prevBuyerGsmValues', 'product'))->render();
        return response()->json(['html' => $view]);
    }

    public function addGsmVolume() {
        $view = view('buyer.addGsmVolume')->render();
        return response()->json(['html' => $view]);
    }

    public function saveGsmVolume(Request $request) {
        $rules = $message = array();

        if (!empty($request->gsm)) {
            $row = 0;
            foreach ($request->gsm as $key => $name) {
                $rules['gsm.' . $key] = 'required';
                $rules['volume.' . $key] = 'required';

                //set messages for error
                $message['gsm.' . $key . '.required'] = __('label.GSM_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $message['volume.' . $key . '.required'] = __('label.VOLUME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }


        $infoArr = [];

        //Prepare Gsm Volume Data as Array
        if (!empty($request->gsm)) {
            foreach ($request->gsm as $uniqueKey => $val) {
                $infoArr[$uniqueKey]['gsm'] = $val;
                $infoArr[$uniqueKey]['volume'] = !empty($request->volume[$uniqueKey]) ? $request->volume[$uniqueKey] : '';
            }
        }

        BuyerToGsmVolume::where('buyer_id', $request->buyer_id)
                ->where('product_id', $request->product_id)->delete();

        if (!empty($infoArr)) {
            $target = new BuyerToGsmVolume;
            $target->buyer_id = $request->buyer_id;
            $target->product_id = $request->product_id;
            $target->set_gsm_volume = json_encode($infoArr);

            if ($target->save()) {
                return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.BUYERS_IMPORT_VOLUME_CREATED_SUCCESSFULLY')], 200);
            } else {
                return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.BUYERS_IMPORT_VOLUME_COULD_NOT_BE_CREATED')], 401);
            }
        } else {
            return Response::json(['success' => true, 'heading' => __('label.WARNING'), 'message' => __('label.BUYERS_IMPORT_VOLUME_HAS_BEEN_DELETED_SUCCESSFULLY')], 200);
        }
    }

    public function volumeDetails(Request $request) {
        $gsmValues = BuyerToGsmVolume::join('product', 'product.id', '=', 'buyer_to_gsm_volume.product_id')
                ->select('set_gsm_volume')->where('buyer_id', $request->buyer_id)
                ->where('product_id', $request->product_id)
                ->first();
        $prevBuyerGsmValues = [];
        if (!empty($gsmValues)) {
            $prevBuyerGsmValues = json_decode($gsmValues->set_gsm_volume, true);
        }
        $buyer = Buyer::select('name')->where('id', $request->buyer_id)->first();
        $product = Product::select('name')->where('id', $request->product_id)->first();
        $view = view('buyer.volumeDetails', compact('request', 'buyer', 'product', 'prevBuyerGsmValues'))->render();
        return response()->json(['html' => $view]);
    }

    public function removeGsm(Request $request) {

        $deleteGsmData = BuyerToGsmVolume::where('buyer_id', $request->buyer_id)
                        ->where('product_id', $request->product_id)->delete();
        if ($deleteGsmData) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.BUYERS_IMPORT_VOLUME_DELETED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.BUYERS_IMPORT_VOLUME_COULD_NOT_BE_DELETED')], 401);
        }
    }

    public function addPhoneNumber(Request $request) {

        $view = view('buyer.addPhoneNumber', compact('request'))->render();
        return response()->json(['html' => $view]);
    }

    //start :: machine type setup
    public function getMachineType(Request $request) {
        $productArr = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                        ->join('product_category', 'product_category.id', 'product.product_category_id')
                        ->where('buyer_to_product.buyer_id', $request->buyer_id)
                        ->where('product_category.has_machine', '1')
                        ->pluck('product.name', 'buyer_to_product.product_id')->toArray();

        $productList = ['0' => __('label.SELECT_PRODUCT_OPT')] + $productArr;

        $buyer = Buyer::select('name')->where('id', $request->buyer_id)->first();

        $view = view('buyer.setMachineType.showSetMachineType', compact('request', 'productList', 'buyer'))->render();
        return response()->json(['html' => $view]);
    }

    public function getBrandForMachineType(Request $request) {
        $machineTypeList = [
            '0' => __('label.SELECT_MACHINE_TYPE_OPT')
            , '1' => __('label.MANUAL')
            , '2' => __('label.AUTOMATIC')
            , '3' => __('label.BOTH_MANUAL_N_AUTOMATIC')
        ];

        $brandArr = BuyerToProduct::join('brand', 'brand.id', 'buyer_to_product.brand_id')
                        ->select('buyer_to_product.brand_id', 'brand.name as name', 'brand.logo as logo')
                        ->where('buyer_id', $request->buyer_id)
                        ->where('product_id', $request->product_id)
                        ->orderBy('brand.name', 'asc')->get();

        $buyerMachineTypeArr = BuyerMachineType::where('buyer_id', $request->buyer_id)
                        ->where('product_id', $request->product_id)
                        ->select('machine_type_id', 'brand_id', 'machine_length')->get();

        $machineTypeBrandArr = [];
        if (!$buyerMachineTypeArr->isEmpty()) {
            foreach ($buyerMachineTypeArr as $type) {
                $machineTypeBrandArr[$type->brand_id]['machine_type_id'] = $type->machine_type_id;
                $machineTypeBrandArr[$type->brand_id]['machine_length'] = $type->machine_length;
            }
        }

        $view = view('buyer.setMachineType.showBrand', compact('request', 'brandArr', 'machineTypeList'
                        , 'machineTypeBrandArr'))->render();
        $footer = view('buyer.setMachineType.showFooter', compact('request', 'brandArr'))->render();
        return response()->json(['html' => $view, 'footer' => $footer]);
    }

    public static function setMachineType(Request $request) {
        //validation
        $rules = $messages = [];
        $brandName = $request->brand_name;

        $machineTypeId = $request->machine_type_id;
        $machineLength = $request->machine_length;

        if (!empty($request->brand)) {
            foreach ($request->brand as $brandId) {
                if (empty($machineTypeId[$brandId])) {
                    $rules['machine_type_id.' . $brandId] = 'required|not_in:0';
                    $machineTypeIdMessage = __('label.MACHINE_TYPE_IS_REQUIRED_FOR_BRAND', ['brand' => $brandName[$brandId]]);
                    $messages['machine_type_id.' . $brandId . '.not_in'] = $machineTypeIdMessage;
                }
            }
        } else {
            $rules['brand'] = 'required';
            $messages['brand.required'] = __('label.PLEASE_SET_MACHINE_TYPE_TO_ATLEAST_ONE_BRAND');
        }


        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $item = [];
        $i = 0;
        if (!empty($request->brand)) {
            foreach ($request->brand as $brandId) {
                $item[$i]['buyer_id'] = $request->buyer_id;
                $item[$i]['product_id'] = $request->product_id;
                $item[$i]['brand_id'] = $brandId;
                $item[$i]['machine_type_id'] = $machineTypeId[$brandId];
                $item[$i]['machine_length'] = $machineLength[$brandId];
                $item[$i]['updated_by'] = Auth::user()->id;
                $item[$i]['updated_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

//        echo '<pre>';
//        print_r($item);
//        exit;

        BuyerMachineType::where('buyer_id', $request->buyer_id)->where('product_id', $request->product_id)->delete();

        if (BuyerMachineType::insert($item)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.MACHINE_TYPE_ADDED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_ADD_MACHINE_TYPE')), 401);
        }
    }

    //end :: machine type setup
    //****************************** start :: buyer profile ********************************//
    public function profile(Request $request, $id) {
        $loadView = 'buyer.profile.show';
        return Common::buyerProfile($request, $id, $loadView);
    }

    public function printProfile(Request $request, $id) {
        $loadView = 'buyer.profile.print.show';
        $modueId = 18;
        return Common::buyerPrintProfile($request, $id, $loadView, $modueId);
    }

    public static function getInvolvedOrderList(Request $request) {
        $loadView = 'buyer.profile.showInvolvedOrderList';
        return Common::getInvolvedOrderList($request, $loadView);
    }

    public static function printInvolvedOrderList(Request $request) {
        $loadView = 'buyer.profile.print.showInvolvedOrderList';
        $modueId = 18;
        return Common::printInvolvedOrderList($request, $loadView, $modueId);
    }

    //****************************** end :: buyer profile *********************************//
}
