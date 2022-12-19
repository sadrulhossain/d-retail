<?php
$controllerName = Request::segment(1);
$controllerName = Request::route()->getName();
$currentControllerFunction = Route::currentRouteAction();
$currentCont = preg_match('/([a-z]*)@/i', request()->route()->getActionName(), $currentControllerFunction);
$controllerName = str_replace('controller', '', strtolower($currentControllerFunction[1]));
//dd($controllerName);
$routeName = strtolower(Route::getFacadeRoot()->current()->uri());

//Admin setup menus
$adminSetupMenu = [
    !empty($userAccessArr[1][1]) ? 1 : '', !empty($userAccessArr[2][1]) ? 1 : '', !empty($userAccessArr[3][1]) ? 1 : ''
    , !empty($userAccessArr[3][7]) ? 1 : '', !empty($userAccessArr[4][1]) ? 1 : '', !empty($userAccessArr[5][1]) ? 1 : ''
    , !empty($userAccessArr[6][1]) ? 1 : '', !empty($userAccessArr[7][1]) ? 1 : '', !empty($userAccessArr[9][1]) ? 1 : ''
    , !empty($userAccessArr[10][1]) ? 1 : '', !empty($userAccessArr[11][1]) ? 1 : '', !empty($userAccessArr[12][1]) ? 1 : ''
    , !empty($userAccessArr[13][1]) ? 1 : '', !empty($userAccessArr[14][1]) ? 1 : '', !empty($userAccessArr[15][1]) ? 1 : ''
    , !empty($userAccessArr[16][1]) ? 1 : '', !empty($userAccessArr[17][1]) ? 1 : '', !empty($userAccessArr[18][1]) ? 1 : ''
    , !empty($userAccessArr[20][1]) ? 1 : '', !empty($userAccessArr[21][1]) ? 1 : '', !empty($userAccessArr[24][1]) ? 1 : ''
    , !empty($userAccessArr[25][1]) ? 1 : '', !empty($userAccessArr[22][1]) ? 1 : '', !empty($userAccessArr[32][1]) ? 1 : ''
    , !empty($userAccessArr[33][1]) ? 1 : '', !empty($userAccessArr[36][1]) ? 1 : ''
    , !empty($userAccessArr[38][1]) ? 1 : '', !empty($userAccessArr[39][1]) ? 1 : '', !empty($userAccessArr[40][1]) ? 1 : ''
    , !empty($userAccessArr[41][1]) ? 1 : '', !empty($userAccessArr[42][1]) ? 1 : '', !empty($userAccessArr[43][1]) ? 1 : ''
    , !empty($userAccessArr[44][1]) ? 1 : '', !empty($userAccessArr[45][1]) ? 1 : '', !empty($userAccessArr[66][1]) ? 1 : ''
    , !empty($userAccessArr[8][1]) ? 1 : ''
];
//access control menu
$accessControlMenu = [!empty($userAccessArr[3][1]) ? 1 : '', !empty($userAccessArr[3][7]) ? 1 : ''];
//user setup menu
$userSetupMenu = [
    !empty($userAccessArr[1][1]) ? 1 : '', !empty($userAccessArr[2][1]) ? 1 : ''
    , !empty($userAccessArr[4][1]) ? 1 : '', !empty($userAccessArr[5][1]) ? 1 : ''
    , !empty($userAccessArr[6][1]) ? 1 : ''
];
//product setup menu
$productSetupMenu = [
    !empty($userAccessArr[7][1]) ? 1 : '', !empty($userAccessArr[9][1]) ? 1 : '', !empty($userAccessArr[11][1]) ? 1 : ''
    , !empty($userAccessArr[15][1]) ? 1 : '', !empty($userAccessArr[93][1]) ? 1 : '', !empty($userAccessArr[88][1]) ? 1 : ''
    , !empty($userAccessArr[87][1]) ? 1 : '', !empty($userAccessArr[91][1]) ? 1 : '', !empty($userAccessArr[92][1]) ? 1 : ''
];

//product setup menu
$buyerSetupMenu = [
    !empty($userAccessArr[10][1]) ? 1 : '', !empty($userAccessArr[24][1]) ? 1 : '', !empty($userAccessArr[18][1]) ? 1 : ''
    , !empty($userAccessArr[19][1]) ? 1 : '', !empty($userAccessArr[17][1]) ? 1 : '', !empty($userAccessArr[22][1]) ? 1 : ''
    , !empty($userAccessArr[66][1]) ? 1 : ''
];

//Courier Management setup menu
$courierSetupMenu = [
    !empty($userAccessArr[86][1]) ? 1 : ''
];

//bank setup Menu
$bankSetupMenu = [!empty($userAccessArr[38][1]) ? 1 : '', !empty($userAccessArr[40][1]) ? 1 : ''];

//CRM menu
$crmMenu = [
    !empty($userAccessArr[67][1]) ? 1 : '', !empty($userAccessArr[68][1]) ? 1 : ''
    , !empty($userAccessArr[69][1]) ? 1 : '', !empty($userAccessArr[70][1]) ? 1 : ''
    , !empty($userAccessArr[71][1]) ? 1 : '', !empty($userAccessArr[72][1]) ? 1 : ''
    , !empty($userAccessArr[74][1]) ? 1 : '', !empty($userAccessArr[75][1]) ? 1 : ''
    , !empty($userAccessArr[76][1]) ? 1 : '', !empty($userAccessArr[78][1]) ? 1 : ''
    , !empty($userAccessArr[79][1]) ? 1 : '', !empty($userAccessArr[80][1]) ? 1 : ''
];

//CRM opportunity menu
$crmOpportunityMenu = [
    !empty($userAccessArr[69][1]) ? 1 : '', !empty($userAccessArr[71][1]) ? 1 : ''
    , !empty($userAccessArr[72][1]) ? 1 : '', !empty($userAccessArr[74][1]) ? 1 : ''
    , !empty($userAccessArr[75][1]) ? 1 : '', !empty($userAccessArr[76][1]) ? 1 : ''
];

//CRM opportunity distribution menu
$crmOpportunityDistributionMenu = [
    !empty($userAccessArr[70][1]) ? 1 : '', !empty($userAccessArr[78][1]) ? 1 : ''
    , !empty($userAccessArr[79][1]) ? 1 : ''
];

//sales service menus
$salesServiceMenu = [
    !empty($userAccessArr[23][1]) ? 1 : '', !empty($userAccessArr[26][1]) ? 1 : '', !empty($userAccessArr[27][1]) ? 1 : ''
    , !empty($userAccessArr[28][1]) ? 1 : '', !empty($userAccessArr[30][1]) ? 1 : '', !empty($userAccessArr[29][1]) ? 1 : ''
    , !empty($userAccessArr[31][1]) ? 1 : '', !empty($userAccessArr[48][2]) ? 1 : '', !empty($userAccessArr[77][1]) ? 1 : ''
];
//inquiry menu
$inquiryMenu = [
    !empty($userAccessArr[23][1]) ? 1 : '', !empty($userAccessArr[29][1]) ? 1 : '', !empty($userAccessArr[77][1]) ? 1 : ''
];

$offerMenu = [
    !empty($userAccessArr[105][1]) ? 1 : ''
    , !empty($userAccessArr[106][1]) ? 1 : ''
    , !empty($userAccessArr[107][1]) ? 1 : ''
];

//billing setup menus
$billingSetupMenu = [
    !empty($userAccessArr[41][2]) ? 1 : '', !empty($userAccessArr[41][1]) ? 1 : '', !empty($userAccessArr[81][1]) ? 1 : ''
];

//payment setup menus
$paymentSetupMenu = [
    !empty($userAccessArr[58][2]) ? 1 : '', !empty($userAccessArr[59][1]) ? 1 : ''
    , !empty($userAccessArr[60][2]) ? 1 : '', !empty($userAccessArr[61][1]) ? 1 : ''
    , !empty($userAccessArr[62][1]) ? 1 : '', !empty($userAccessArr[63][2]) ? 1 : ''
    , !empty($userAccessArr[64][1]) ? 1 : '', !empty($userAccessArr[65][1]) ? 1 : ''
];

//sales person payment setup menus
$salesPersonPaymentSetupMenu = [
    !empty($userAccessArr[60][2]) ? 1 : '', !empty($userAccessArr[61][1]) ? 1 : ''
    , !empty($userAccessArr[62][1]) ? 1 : ''
];

//buyer payment setup menus
$buyerPaymentSetupMenu = [
    !empty($userAccessArr[63][2]) ? 1 : '', !empty($userAccessArr[64][1]) ? 1 : ''
    , !empty($userAccessArr[65][1]) ? 1 : ''
];

//Customer menus
$customerSetupMenu = [
    !empty($userAccessArr[85][1]) ? 1 : ''
];

//supplier setup menu
$supplierSetupMenu = [
    !empty($userAccessArr[94][1]) ? 1 : '', !empty($userAccessArr[90][1]) ? 1 : ''
];

//content menus
$contentMenu = [
    !empty($userAccessArr[8][1]) ? 1 : '', !empty($userAccessArr[104][1]) ? 1 : ''
    , !empty($userAccessArr[112][1]) ? 1 : '', !empty($userAccessArr[113][1]) ? 1 : ''
    , !empty($userAccessArr[114][1]) ? 1 : '', !empty($userAccessArr[115][1]) ? 1 : ''
    , !empty($userAccessArr[116][1]) ? 1 : '', !empty($userAccessArr[117][1]) ? 1 : ''
];
//report menus
$reportMenu = [
    !empty($userAccessArr[108][1]) ? 1 : '', !empty($userAccessArr[109][1]) ? 1 : ''
    , !empty($userAccessArr[110][1]) ? 1 : '', !empty($userAccessArr[111][1]) ? 1 : ''
    , !empty($userAccessArr[121][1]) ? 1 : '', !empty($userAccessArr[118][1]) ? 1 : ''
    , !empty($userAccessArr[119][1]) ? 1 : '', !empty($userAccessArr[140][1]) ? 1 : ''
    , !empty($userAccessArr[108][1]) ? 1 : '', !empty($userAccessArr[128][1]) ? 1 : ''
    , !empty($userAccessArr[129][1]) ? 1 : '' ,!empty($userAccessArr[130][1]) ? 1 : ''
    , !empty($userAccessArr[131][1]) ? 1 : '' ,!empty($userAccessArr[119][1]) ? 1 : ''
    , !empty($userAccessArr[118][1]) ? 1 : '' ,!empty($userAccessArr[110][1]) ? 1 : ''
    , !empty($userAccessArr[111][1]) ? 1 : '' ,!empty($userAccessArr[138][1]) ? 1 : ''
    , !empty($userAccessArr[139][1]) ? 1 : '' ,!empty($userAccessArr[140][1]) ? 1 : ''
];
//report menus
//$productLedgerMenu = [
//    !empty($userAccessArr[118][1]) ? 1 : '', !empty($userAccessArr[119][1]) ? 1 : ''
//    , !empty($userAccessArr[110][1]) ? 1 : '', !empty($userAccessArr[120][1]) ? 1 : ''
//];
//CRM report menu
$crmReportMenu = [
    !empty($userAccessArr[83][1]) ? 1 : '', !empty($userAccessArr[84][1]) ? 1 : ''
];

//procurement menus
$procurementMenu = [
    !empty($userAccessArr[134][2]) ? 1 : ''
    , !empty($userAccessArr[135][1]) ? 1 : ''
];
//product checkin menus
$productCheckInMenu = [
    !empty($userAccessArr[95][2]) ? 1 : ''
    , !empty($userAccessArr[99][1]) ? 1 : ''
];

//stock transfer menus
$stockTransferMenu = [
    !empty($userAccessArr[45][2]) ? 1 : ''
    , !empty($userAccessArr[46][1]) ? 1 : ''
];
//stock return menus
$stockReturnMenu = [
    !empty($userAccessArr[47][2]) ? 1 : ''
    , !empty($userAccessArr[48][1]) ? 1 : ''
];

//stock transfer menus
$returnProductMenu = [
    !empty($userAccessArr[136][2]) ? 1 : ''
    , !empty($userAccessArr[137][1]) ? 1 : ''
];

//product adjustment menus
$productAdjustmentMenu = [
    !empty($userAccessArr[100][2]) ? 1 : ''
    , !empty($userAccessArr[101][1]) ? 1 : ''
];

$orderMenu = [
    !empty($userAccessArr[52][1]) ? 1 : ''
    , !empty($userAccessArr[103][1]) ? 1 : ''
    , !empty($userAccessArr[53][1]) ? 1 : ''
    , !empty($userAccessArr[54][1]) ? 1 : ''
    , !empty($userAccessArr[55][1]) ? 1 : ''
    , !empty($userAccessArr[56][1]) ? 1 : ''
    , !empty($userAccessArr[57][1]) ? 1 : ''
];
?>
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul id="addsidebarFullMenu" class="page-sidebar-menu page-header-fixed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" >
            <!--li class="sidebar-toggler-wrapper hide">
            <div class="sidebar-toggler">
                <span></span>
            </div>
        </li-->

            <!-- start dashboard menu -->
            <li <?php $current = ( in_array($controllerName, array('dashboard'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/admin')}}" class="nav-link ">
                    <i class="icon-home"></i>
                    <span class="title"> @lang('label.DASHBOARD')</span>
                </a>
            </li>
            @if (in_array(Auth::user()->group_id, [18,19]))
            <li <?php $current = ( in_array($controllerName, array('dashboard'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/admin/retailerDistributorOrder')}}" class="nav-link ">
                    <i class="fa fa-cart-arrow-down"></i>
                    <span class="title"> @lang('label.MY_ORDER')</span>
                </a>
            </li>

            <li <?php $current = ( in_array($controllerName, array('dashboard'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/admin/myProfile')}}" class="nav-link ">
                    <i class="icon-user"></i>
                    <span class="title"> @lang('label.MY_PROFILE')</span>
                </a>
            </li>
            @endif


            <!-- User Group wise common feature set up -->
            @if(in_array(1, $adminSetupMenu))
            <li <?php
            $current = ( in_array($controllerName, array('user', 'usergroup', 'department', 'designation', 'warehouse', 'retailer',
                        'certificate', 'customer', 'factory', 'brand', 'supplierclassification', 'supplier',
                        'productcategory', 'cluster', 'zone', 'producttype', 'bank', 'measureunit', 'product', 'branch', 'causeoffailure',
                        'color', 'aclusergrouptoaccess', 'buyercategory', 'salespersontobuyer', 'productcontainer',
                        'buyerfactory', 'suppliertoproduct', 'buyertoproduct', 'producttobrand'
                        , 'contactdesignation', 'shippingline', 'grade', 'certificate', 'producttograde', 'followupstatus'
                        , 'buyerfollowup', 'productunit', 'producttag', 'attributetype', 'productattribute', 'producttoattribute', 'courierservice'
                        , 'productskucode', 'tmtowarehouse', 'warehousetosr', 'warehousetoretailer', 'srtoretailer'
                        , 'whtolocalwhmanager'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cogs"></i>
                    <span class="title">@lang('label.ADMINISTRATIVE_SETUP')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <!-- access control setup -->
                    @if(in_array(1, $accessControlMenu))
                    <li <?php $current = ( in_array($controllerName, array('aclusergrouptoaccess'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.ACCESS_CONTROL_SETUP')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[3][7]))
                            <li <?php $current = ( in_array($controllerName, array('aclusergrouptoaccess')) && ($routeName != 'aclusergrouptoaccess/moduleaccesscontrol' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/admin/aclUserGroupToAccess/userGroupToAccess')}}" class="nav-link ">
                                    <span class="title">@lang('label.USER_GROUP_ACCESS')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[3][1]))
                            <li <?php $current = ($routeName == 'aclusergrouptoaccess/moduleaccesscontrol' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('admin/aclUserGroupToAccess/moduleAccessControl/')}}" class="nav-link">
                                    <span class="title">@lang('label.MODULE_WISE_ACCESS')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <!-- User setup -->
                    @if(in_array(1, $userSetupMenu))
                    <li <?php
                    $current = ( in_array($controllerName, array('department', 'designation', 'branch'
                                , 'usergroup', 'user'))) ? 'start active open' : '';
                    ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.USER_SETUP')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[2][1]))
                            <li <?php $current = ( in_array($controllerName, array('usergroup'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/admin/userGroup')}}" class="nav-link ">
                                    <span class="title">@lang('label.USER_GROUP')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[4][1]))
                            <li <?php $current = ( in_array($controllerName, array('department'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/admin/department')}}" class="nav-link ">
                                    <span class="title">@lang('label.DEPARTMENT')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[5][1]))
                            <li <?php $current = ( in_array($controllerName, array('designation'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/admin/designation')}}" class="nav-link ">
                                    <span class="title">@lang('label.DESIGNATION')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[1][1]))
                            <li <?php $current = ( in_array($controllerName, array('user'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/admin/user')}}" class="nav-link ">
                                    <span class="title">@lang('label.USER')</span>
                                </a>
                            </li>
                            @endif

                        </ul>
                    </li>
                    @endif


                    @if(!empty($userAccessArr[132][1]))
                    <li <?php $current = ( in_array($controllerName, array('cluster'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/admin/cluster')}}" class="nav-link ">
                            <span class="title">@lang('label.CLUSTER')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[133][1]))
                    <li <?php $current = ( in_array($controllerName, array('zone'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/admin/zone')}}" class="nav-link ">
                            <span class="title">@lang('label.ZONE')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[49][1]))
                    <li <?php $current = ( in_array($controllerName, array('warehouse'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/admin/warehouse')}}" class="nav-link ">
                            <span class="title">@lang('label.WAREHOUSE')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[50][1]))
                    <li <?php $current = ( in_array($controllerName, array('retailer'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/admin/retailer')}}" class="nav-link ">
                            <span class="title">@lang('label.RETAILER')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[127][1]))
                    <li <?php $current = ( in_array($controllerName, array('bank'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/admin/bank')}}" class="nav-link ">
                            <span class="title">@lang('label.BANK')</span>
                        </a>
                    </li>
                    @endif



                    <!-- contact designation -->
                    @if(!empty($userAccessArr[39][1]))
                    <li <?php $current = ( in_array($controllerName, array('contactdesignation'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/contactDesignation')}}" class="nav-link ">
                            <span class="title">@lang('label.CONTACT_DESIGNATION')</span>
                        </a>
                    </li>
                    @endif
                    <!-- Contact Designation Ends -->



                    <!-- product setup -->
                    @if(in_array(1, $productSetupMenu))
                    <li <?php
                    $current = ( in_array($controllerName, array('productcategory', 'producttype', 'productcontainer', 'productunit', 'brand', 'producttag', 'attributetype', 'productattribute'
                                , 'product', 'salespersontoproduct', 'producttobrand', 'producttoattribute', 'productskucode'))) ? 'start active open' : '';
                    ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.PRODUCT_SETUP')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[7][1]))
                            <li <?php $current = ( in_array($controllerName, array('productcategory'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/admin/productCategory')}}" class="nav-link ">
                                    <span class="title">@lang('label.PRODUCT_CATEGORY')</span>
                                </a>
                            </li>
                            @endif

                            @if(!empty($userAccessArr[9][1]))
                            <li <?php $current = ( in_array($controllerName, array('productunit'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/admin/productUnit')}}" class="nav-link ">
                                    <span class="title">@lang('label.PRODUCT_UNIT')</span>
                                </a>
                            </li>
                            @endif

                            @if(!empty($userAccessArr[93][1]))
                            <li <?php $current = ( in_array($controllerName, array('producttag'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/admin/productTag')}}" class="nav-link ">
                                    <span class="title">@lang('label.PRODUCT_TAG')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[125][1]))
                            <li <?php $current = ( in_array($controllerName, array('producttype'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('admin/productType')}}" class="nav-link ">
                                    <span class="title">@lang('label.PRODUCT_TYPE')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[126][1]))
                            <li <?php $current = ( in_array($controllerName, array('productcontainer'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('admin/productContainer')}}" class="nav-link ">
                                    <span class="title">@lang('label.PRODUCT_CONTAINER')</span>
                                </a>
                            </li>
                            @endif

                            @if(!empty($userAccessArr[88][1]))
                            <li <?php $current = ( in_array($controllerName, array('attributetype'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/admin/attributeType')}}" class="nav-link ">
                                    <span class="title">@lang('label.ATTRIBUTE_TYPE')</span>
                                </a>
                            </li>
                            @endif

                            @if(!empty($userAccessArr[87][1]))
                            <li <?php $current = ( in_array($controllerName, array('productattribute'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/admin/productAttribute')}}" class="nav-link ">
                                    <span class="title">@lang('label.PRODUCT_ATTRIBUTE')</span>
                                </a>
                            </li>
                            @endif

                            @if(!empty($userAccessArr[11][1]))
                            <li <?php $current = ( in_array($controllerName, array('brand'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/admin/brand')}}" class="nav-link ">
                                    <span class="title">@lang('label.BRAND')</span>
                                </a>
                            </li>
                            @endif

                            @if(!empty($userAccessArr[15][1]))
                            <li <?php $current = ( in_array($controllerName, array('product')) && ($routeName != 'product/approvalproduct' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/admin/product')}}" class="nav-link ">
                                    <span class="title">@lang('label.PRODUCT')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <!-- supplier setup -->
                    @if(in_array(1, $supplierSetupMenu))
                    <li <?php
                    $current = ( in_array($controllerName, array('supplierclassification', 'supplier', 'suppliertoproduct', 'beneficiarybank'))) ? 'start active open' : '';
                    ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.SUPPLIER_SETUP')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">

                            @if(!empty($userAccessArr[90][1]))
                            <li <?php $current = ( in_array($controllerName, array('supplier'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/admin/supplier')}}" class="nav-link ">
                                    <span class="title">@lang('label.SUPPLIER')</span>
                                </a>
                            </li>
                            @endif

                            @if(!empty($userAccessArr[94][1]))
                            <li <?php $current = ( in_array($controllerName, array('suppliertoproduct'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/admin/supplierToProduct')}}" class="nav-link ">
                                    <span class="title">@lang('label.SUPPLIER_TO_PRODUCT')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif


                    @if(!empty($userAccessArr[40][1]))
                    <li <?php $current = ( in_array($controllerName, array('tmtowarehouse'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/admin/tmToWarehouse')}}" class="nav-link ">
                            <span class="title">@lang('label.TM_TO_WAREHOUSE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[41][1]))
                    <li <?php $current = ( in_array($controllerName, array('warehousetosr'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/admin/warehouseToSr')}}" class="nav-link ">
                            <span class="title">@lang('label.WAREHOUSE_TO_SR')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[42][1]))
                    <li <?php $current = ( in_array($controllerName, array('warehousetoretailer'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/admin/warehouseToRetailer')}}" class="nav-link ">
                            <span class="title">@lang('label.WAREHOUSE_TO_RETAILER')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[43][1]))
                    <li <?php $current = ( in_array($controllerName, array('srtoretailer'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/admin/srToRetailer')}}" class="nav-link ">
                            <span class="title">@lang('label.SR_TO_RETAILER')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[44][1]))
                    <li <?php $current = ( in_array($controllerName, array('whtolocalwhmanager'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/admin/whToLocalWhManager')}}" class="nav-link ">
                            <span class="title">@lang('label.WAREHOUSE_TO_THANA_WH_MANAGER')</span>
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif
            <!--endof :: admin setup menus-->
            <!--Start :: Procurement-->
            @if(in_array(1, $procurementMenu))
            <li <?php
            $current = ( in_array($controllerName, array('procurement', 'procurementlist'))) ? 'start active open' : '';
            ?> class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cart-arrow-down"></i>
                    <span class="title">@lang('label.PROCUREMENT_SETUP')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[134][2]))
                    <li <?php $current = ( in_array($controllerName, array('procurement'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/admin/procurement')}}" class="nav-link ">
                            <span class="title"> @lang('label.PROCUREMENT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[135][1]))
                    <li <?php $current = ( in_array($controllerName, array('procurementlist'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/admin/procurementList')}}" class="nav-link ">
                            <span class="title"> @lang('label.PROCUREMENT_LIST')</span>
                        </a>
                    </li>
                    @endif



                </ul>
            </li>
            @endif
            <!--End :: Procurement-->

            <!-- Product check In and Initial Balance Setup -->
            @if(in_array(1, $productCheckInMenu))
            <li <?php $current = ( in_array($controllerName, array('productcheckin', 'productcheckinlist'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-check-square-o"></i>
                    <span class="title">@lang('label.PRODUCT_CHECK_IN')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[95][2]))
                    <li <?php $current = ( in_array($controllerName, array('productcheckin')) && ($routeName != 'productcheckinlist' ) && ($routeName != 'productcheckin/approvalcheckin' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/productCheckIn')}}" class="nav-link ">
                            <span class="title">@lang('label.PRODUCT_PURCHASE')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[99][1]))
                    <li <?php $current = ( $controllerName == 'productcheckinlist' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/productCheckInList')}}" class="nav-link">
                            <span class="title">@lang('label.PURCHASED_ITEM_LIST')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if(in_array(1, $stockTransferMenu))
            <li <?php $current = ( in_array($controllerName, array('producttransfer', 'producttransferlist'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-exchange"></i>
                    <span class="title">@lang('label.PRODUCT_TRANSFER')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[45][2]))
                    <li <?php $current = ( in_array($controllerName, array('producttransfer')) && ($routeName != 'producttransferlist' ) && ($routeName != 'producttransfer/approvalcheckin' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/stockTransfer')}}" class="nav-link ">
                            <span class="title">@lang('label.TRANSFER')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[46][1]))
                    <li <?php $current = ( $controllerName == 'producttransferlist' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/stockTransferList')}}" class="nav-link">
                            <span class="title">@lang('label.TRANSFER_LIST')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if(in_array(1, $stockReturnMenu))
            <li <?php $current = ( in_array($controllerName, array('productwhreturn', 'productwhreturnlist'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-reply"></i>
                    <span class="title">@lang('label.PRODUCT_RETURN')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[47][2]))
                    <li <?php $current = ( in_array($controllerName, array('productwhreturn')) && ($routeName != 'productwhreturnlist' ) && ($routeName != 'productwhreturn/approvalcheckin' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/stockWhReturn')}}" class="nav-link ">
                            <span class="title">@lang('label.RETURN')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[48][1]))
                    <li <?php $current = ( $controllerName == 'productwhreturnlist' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/stockWhReturnList')}}" class="nav-link">
                            <span class="title">@lang('label.RETURN_LIST')</span>
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif



            <!--endof :: Product check In and Initial Balance Setup-->

            <!-- Product Adjustment Setup -->
            @if(in_array(1, $productAdjustmentMenu))
            <li <?php $current = ( in_array($controllerName, array('productadjustment', 'productadjustmentlist'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-minus-square-o"></i>
                    <span class="title">@lang('label.PRODUCT_ADJUSTMENT')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[100][2]))
                    <li <?php $current = ( in_array($controllerName, array('productadjustment')) && ($routeName != 'productadjustmentlist' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/productAdjustment')}}" class="nav-link ">
                            <span class="title">@lang('label.ADJUSTMENT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[101][1]))
                    <li <?php $current = ( $controllerName == 'productadjustmentlist' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/productAdjustmentList')}}" class="nav-link">
                            <span class="title">@lang('label.ADJUSTMENT_LIST')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            <!-- Product Adjustment Setup -->

            <!--Return Product Setup-->
            @if(in_array(1, $returnProductMenu))
            <li <?php
            $current = ( in_array($controllerName, array('returnproduct', 'returnproductlist'))) ? 'start active open' : '';
            ?> class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cart-arrow-down"></i>
                    <span class="title">@lang('label.RETURN_PRODUCT')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[136][2]))
                    <li <?php $current = ( in_array($controllerName, array('returnproduct'))) ? 'start active open' : ''; ?>class="nav-item {{$current}}">
                        <a href="{{url('/admin/returnProduct')}}" class="nav-link ">
                            <span class="title"> @lang('label.RETURN_PRODUCT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[137][1]))
                    <li <?php $current = ( in_array($controllerName, array('returnproductlist'))) ? 'start active open' : ''; ?>class="nav-item {{$current}}">
                        <a href="{{url('/admin/returnProductList')}}" class="nav-link ">
                            <span class="title"> @lang('label.RETURN_PRO_LIST')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            <!--Return Product Setup-->

            <!-- Offer Setup -->
            @if(in_array(1, $offerMenu))
            <li <?php $current = ( in_array($controllerName, array('featuredproducts', 'latestproducts', 'specialproducts'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-certificate"></i>
                    <span class="title">@lang('label.OFFER')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[105][1]))
                    <li <?php $current = ( in_array($controllerName, array('featuredproducts'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/featuredProducts')}}" class="nav-link ">
                            <span class="title">@lang('label.FEATURED_PRODUCTS')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[106][1]))
                    <li <?php $current = ( in_array($controllerName, array('latestproducts'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/latestProducts')}}" class="nav-link ">
                            <span class="title">@lang('label.LATEST_PRODUCTS')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[107][1]))
                    <li <?php $current = ( in_array($controllerName, array('specialproducts'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/specialProducts')}}" class="nav-link ">
                            <span class="title">@lang('label.SPECIAL_PRODUCTS')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            <!--endof :: Order Setup-->

            <!--endof :: Product Adjustment Setup-->
            <!-- Order Setup -->
            @if(!empty($userAccessArr[102][1]))
            @if(Auth::user()->group_id == 14)

            <li <?php $current = ( in_array($controllerName, array('order'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                <a href="{{url('/admin/order')}}" class="nav-link ">
                    <i class="fa fa-cart-arrow-down"></i>
                    <span class="title">@lang('label.ORDER')</span>
                </a>
            </li>
            @endif
            @endif

            @if(in_array(1, $orderMenu))
            @if(Auth::user()->group_id != 14)
            <li <?php
            $current = ( in_array($controllerName, array('pendingorder', 'confirmedorder', 'processingorder'
                        , 'orderplacedindelivery', 'cancelledorder', 'returnedorder'
                        , 'deliveredorder'))) ? 'start active open' : '';
            ?> class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cart-arrow-down"></i>
                    <span class="title">@lang('label.ORDER')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[52][1]))
                    <!--<li <?php $current = ( in_array($controllerName, array('pendingorder'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/pendingOrder')}}" class="nav-link ">
                            <span class="title">@lang('label.PENDING_ORDER')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[54][1]))
                    <li <?php $current = ( in_array($controllerName, array('confirmedorder'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/confirmedOrder')}}" class="nav-link ">
                            <span class="title">@lang('label.CONFIRMED_ORDER')</span>
                        </a>
                    </li>-->
                    @endif

                    @if(!empty($userAccessArr[103][1]))
                    <li <?php $current = ( in_array($controllerName, array('processingorder'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/processingOrder')}}" class="nav-link ">
                            <span class="title">@lang('label.PENDING_ORDER')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[55][1]))
                    <!--<li <?php $current = ( in_array($controllerName, array('orderplacedindelivery'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/orderPlacedInDelivery')}}" class="nav-link ">
                            <span class="title">@lang('label.ORDER_PLACED_IN_DELIVERY')</span>
                        </a>
                    </li>-->
                    @endif

                    @if(!empty($userAccessArr[57][1]))
                    <li <?php $current = ( in_array($controllerName, array('deliveredorder'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/deliveredOrder')}}" class="nav-link ">
                            <span class="title">@lang('label.DELIVERED_ORDER')</span>
                        </a>
                    </li>
                    @endif

                    <!--                    @if(!empty($userAccessArr[56][1]))
                                        <li <?php $current = ( in_array($controllerName, array('returnedorder'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                            <a href="{{url('/admin/returnedOrder')}}" class="nav-link ">
                                                <span class="title">@lang('label.RETUREND_ORDER')</span>
                                            </a>
                                        </li>
                                        @endif-->
                    @if(!empty($userAccessArr[53][1]))
                    <li <?php $current = ( in_array($controllerName, array('cancelledorder'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/cancelledOrder')}}" class="nav-link ">
                            <span class="title">@lang('label.CANCELLED_ORDER')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @endif

            <!--Payment activities related menus-->
            @if(in_array(1, $paymentSetupMenu))
            @if(!empty($userAccessArr[58][2]))
            <li <?php
            $current = (in_array($controllerName, array('receive', 'supplierledger', 'salespersonpayment'
                        , 'salespersonpaymentvoucher', 'salespersonledger', 'buyerpayment', 'buyerpaymentvoucher'
                        , 'buyerledger'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-usd"></i>
                    <span class="title">@lang('label.PAYMENT_INFORMATION')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <!--start :: supplier payment-->

                    <li <?php $current = ( in_array($controllerName, array('receive'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('admin/receive')}}" class="nav-link ">
                            <span class="title">@lang('label.RECEIVE')</span>
                        </a>
                    </li>

                    @if(!empty($userAccessArr[59][1]))
                    <!--<li <?php $current = ( in_array($controllerName, array('supplierledger'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('admin/supplierLedger')}}" class="nav-link ">
                            <span class="title">@lang('label.SUPPLIER_LEDGER')</span>
                        </a>
                    </li>-->
                    @endif
                    <!--end :: supplier payment-->
                </ul>
            </li>
            @endif
            @endif
            <!--endof :: payment menus-->

            <!--endof :: Order Setup-->
            @if(in_array(1, $contentMenu))
            <li <?php
            $current = ( in_array($controllerName, array('socialnetwork', 'newsandevents', 'configuration'
                        , 'banner', 'advertisement', 'speciality', 'footermenu', 'menu'))) ? 'start active open' : '';
            ?> class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-pencil-square-o"></i>
                    <span class="title">@lang('label.CONTENT')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[8][1]))
                    <li <?php $current = ( in_array($controllerName, array('configuration'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/admin/configuration')}}" class="nav-link ">
                            <span class="title">@lang('label.CONFIGURATION')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[112][1]))
                    <li <?php $current = ( in_array($controllerName, array('socialnetwork'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/socialNetwork')}}" class="nav-link">
                            <span class="title">@lang('label.SOCIAL_NETWORK')</span>
                        </a>
                    </li>
                    @endif
                    <!--Start::News Management-->

                    @if(!empty($userAccessArr[104][1]))
                    <li <?php $current = ( in_array($controllerName, array('newsandevents'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/newsAndEvents')}}" class="nav-link ">
                            <span class="title">@lang('label.NEWS')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[113][1]))
                    <li <?php $current = ( in_array($controllerName, array('banner'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('admin/banner')}}" class="nav-link ">
                            <span class="title">@lang('label.BANNER')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[114][1]))
                    <li <?php $current = ( in_array($controllerName, array('advertisement'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('admin/advertisement')}}" class="nav-link ">
                            <span class="title">@lang('label.ADVERTISEMENT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[115][1]))
                    <li <?php $current = ( in_array($controllerName, array('speciality'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('admin/speciality')}}" class="nav-link ">
                            <span class="title">@lang('label.SPECIALITY')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[116][1]))
                    <li <?php $current = ( in_array($controllerName, array('menu'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('admin/menu')}}" class="nav-link ">
                            <span class="title">@lang('label.MENU')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[117][1]))
                    <li <?php $current = ( in_array($controllerName, array('footermenu'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('admin/footerMenu')}}" class="nav-link ">
                            <span class="title">@lang('label.FOOTER_MENU')</span>
                        </a>
                    </li>
                    @endif

                    <!--End::News Managerment-->
                </ul>
            </li>
            @endif

            <!-- Reports -->
            @if(in_array(1, $reportMenu))
            <li <?php
            $current = ( in_array($controllerName, array('centralstocksummaryreport', 'salesstatusreport', 'statuswiseorderlistreport', 'whstockledgerreport'
                        , 'retailerledgerreport', 'supplierwisepurchasereport', 'supplierbankaccountreport', 'whstocksummaryreport'
                        , 'centralstockledgerreport', 'clusterperformancereport', 'zonalperformancereport','paymentduebyretailerreport'))) ? 'start active open' : '';
            ?> class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-line-chart"></i>
                    <span class="title">@lang('label.REPORTS')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[108][1]))
                    <li <?php $current = ( in_array($controllerName, array('centralstocksummaryreport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/centralStockSummaryReport')}}" class="nav-link ">
                            <span class="title">@lang('label.STOCK_SUMMARY')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[128][1]))
                    <li <?php $current = ( in_array($controllerName, array('salesstatusreport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/salesStatusReport')}}" class="nav-link ">
                            <span class="title">@lang('label.SALES_STATUS_REPORT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[129][1]))
                    <li <?php $current = ( in_array($controllerName, array('statuswiseorderlistreport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/statusWiseOrderListReport')}}" class="nav-link ">
                            <span class="title">@lang('label.STATUS_WISE_ORDER_LIST_REPORT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[130][1]))
                    <li <?php $current = ( in_array($controllerName, array('supplierwisepurchasereport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/supplierWisePurchaseReport')}}" class="nav-link ">
                            <span class="title">@lang('label.SUPPLIER_WISE_PURCHASE_REPORT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[131][1]))
                    <li <?php $current = ( in_array($controllerName, array('supplierbankaccountreport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/supplierBankAccountReport')}}" class="nav-link ">
                            <span class="title">@lang('label.SUPPLIER_BANK_ACCOUNT_REPORT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[119][1]))
                    <li <?php $current = ( in_array($controllerName, array('whstocksummaryreport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/whStockSummaryReport')}}" class="nav-link ">
                            <span class="title">@lang('label.WH_STOCK_SUMMARY')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[118][1]))
                    <li <?php $current = ( in_array($controllerName, array('centralstockledgerreport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/centralStockLedgerReport')}}" class="nav-link ">
                            <span class="title">@lang('label.CENTRAL_STOCK_LEDGER')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[110][1]))
                    <li <?php $current = ( in_array($controllerName, array('whstockledgerreport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/whStockLedgerReport')}}" class="nav-link ">
                            <span class="title">@lang('label.WH_STOCK_LEDGER')</span>
                        </a>
                    </li>
                    @endif


                    @if(!empty($userAccessArr[111][1]))
                    <li <?php $current = ( in_array($controllerName, array('retailerledgerreport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/retailerLedgerReport')}}" class="nav-link ">
                            <span class="title">@lang('label.RETAILER_LEDGER')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[138][1]))
                    <li <?php $current = ( in_array($controllerName, array('clusterperformancereport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/clusterPerformanceReport')}}" class="nav-link ">
                            <span class="title">@lang('label.CLUSTER_PERFORMANCE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[139][1]))
                    <li <?php $current = ( in_array($controllerName, array('zonalperformancereport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/zonalPerformanceReport')}}" class="nav-link ">
                            <span class="title">@lang('label.ZONAL_PERFORMANCE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[140][1]))
                    <li <?php $current = ( in_array($controllerName, array('paymentduebyretailerreport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/admin/paymentDueByRetailerReport')}}" class="nav-link ">
                            <span class="title">@lang('label.RETAILER_DISTRIBUTOR_PAYMENT_DUE_REPORT')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif


            <!--endof :: Reports-->
        </ul>
    </div>
</div>
