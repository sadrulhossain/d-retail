<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\AclUserGroupToAccess;
use App\Subscribe; //model class
use App\ContactInfo;
use App\SocialNetwork;
use App\Hotline;
use App\CompanyInformation;
use App\Speciality;
use App\FooterMenu;
use App\WhToLocalWhManager;
use App\TmToWarehouse;
use App\ProductTransferMaster;
use App\Order;
use App\Delivery;
use App\Menu;
use App\Product;
use DB;
use Route;
use Common;
use Auth;
use Helper;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        view()->composer('*', function ($view) {

            $konitaInfo = CompanyInformation::first();

            $phoneNumber = '';
            if (!empty($konitaInfo)) {
                $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
                $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
            }

            //get request notification number on topnavber in all views
            $contractArr = ContactInfo::orderBy('order', 'asc')->get();
            $socialArr = SocialNetwork::orderBy('order', 'asc')->get();
            $hotlineInfo = Hotline::first();
            $specialityArr = Speciality::where('status_id', '1')->orderBy('order', 'asc')->get();
            $footerMenuArr = FooterMenu::where('status_id', 1)->orderBy('order', 'asc')->get();
            $menuArr = Menu::where('status_id', 1)->orderBy('order', 'asc');
            $productList = Product::pluck('name', 'name')->toArray();
            $user = Auth::user();
            if (!empty($user) && $user->group_id == '14') {
                $menuArr = $menuArr->get();
            } else {
                $menuArr = $menuArr->where('for_logged_in_users', '0')->get();
            }

            if (Auth::check()) {
                $toDayDate = date('Y-m-d');
                $todayStart = date('Y-m-d 00:00:00');
                $todayEnd = date('Y-m-d 23:59:59');

                //ACL ACCESS LIST
                $userAccessArr = Common::userAccess();

                $currentControllerFunction = Route::currentRouteAction();
                $controllerName = $currentCont = '';
                if (!empty($currentControllerFunction[1])) {
                    $currentCont = preg_match('/([a-z]*)@/i', request()->route()->getActionName(), $currentControllerFunction);
                    $controllerName = str_replace('controller', '', strtolower($currentControllerFunction[1]));
                }

                //order notification

                $pendingTransferCount = 0;
				$mgWarehouseId = 0;
                if (!empty($userAccessArr[46][1])) {
                    if (!empty($userAccessArr[103][1])) {
                        if (Auth::user()->group_id == 12) {
                            $warehouse = WhToLocalWhManager::where('lwm_id', Auth::id())->first();
							$mgWarehouseId = $warehouse->warehouse_id ?? 0;
                            $pendingTransfer = ProductTransferMaster::where('warehouse_id', $warehouse->warehouse_id ?? 0)
                                            ->where('approval_status', '0')->get();

                            if (!empty($pendingTransfer)) {
                                $pendingTransferCount = $pendingTransfer->count();
                            }
                        }
                    }
                }

                $orderCount = [];
                if (!empty($userAccessArr[103][1])) {
                    $order = Order::select(DB::raw("COUNT(id) as total"), 'status')
                            ->whereIn('status', ['0', '2'])
                            ->groupBy('status');
                    if (Auth::user()->group_id == 12) {
                        $wh = WhToLocalWhManager::where('lwm_id', Auth::user()->id)->select('warehouse_id as wh_id')->first();
						$mgWarehouseId = $wh->wh_id ?? 0;
                        $order = $order->where('warehouse_id', $wh->wh_id ?? 0);
                    } elseif (Auth::user()->group_id == 15) {
                        $whList = TmToWarehouse::where('tm_id', Auth::user()->id)->pluck('warehouse_id', 'warehouse_id')->toArray();
                        $order = $order->whereIn('warehouse_id', $whList);
                    }
                    $order = $order->pluck('total', 'status')->toArray();

                    $orderCount['total'] = !empty($order) ? array_sum($order) : 0;
                    $orderCount['partially_delivered'] = !empty($order['2']) ? $order['2'] : 0;
                    $orderCount['pending'] = !empty($order['0']) ? $order['0'] : 0;
                }




                $view->with([
                    'userAccessArr' => $userAccessArr,
                    'controllerName' => $controllerName,
                    'contractArr' => $contractArr,
                    'socialArr' => $socialArr,
                    'hotlineInfo' => $hotlineInfo,
                    'konitaInfo' => $konitaInfo,
                    'phoneNumber' => $phoneNumber,
                    'specialityArr' => $specialityArr,
                    'footerMenuArr' => $footerMenuArr,
                    'menuArr' => $menuArr,
                    'frontProductSearchList' => $productList,
                    'orderCount' => $orderCount,
                    'pendingTransferCount' => $pendingTransferCount,
					'mgWarehouseId' => $mgWarehouseId,
                ]);
            } else {
                $view->with([
                    'contractArr' => $contractArr,
                    'socialArr' => $socialArr,
                    'hotlineInfo' => $hotlineInfo,
                    'konitaInfo' => $konitaInfo,
                    'phoneNumber' => $phoneNumber,
                    'specialityArr' => $specialityArr,
                    'footerMenuArr' => $footerMenuArr,
                    'menuArr' => $menuArr,
                    'frontProductSearchList' => $productList,
                ]);
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        //
    }

}
