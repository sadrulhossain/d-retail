<?php

global $prefix;
$prefix = env('PREFIX');
//exit;
//frontend
// login start
Route::get('/', 'FrontendController@index');
Route::get('admin', function () {
    return redirect('admin/login');
});

Route::get('admin/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('admin/login', 'Auth\LoginController@login');
Route::post('admin/logout', 'Auth\LoginController@logout')->name('logout');

// end
Route::get('/aboutUs', 'FrontendController@aboutUs')->name('aboutUs');
Route::get('/contactUs', 'FrontendController@contactUs')->name('contactUs');
Route::get('/inDepoProducts', 'FrontendController@inDepoProducts')->name('inDepoProducts');
Route::get('/allProducts', 'FrontendController@shop')->name('shop');
Route::get('/search/product', 'FrontendController@searchedProduct')->name('searched.product.show');
Route::get('/allProducts/category/{id}', 'FrontendController@categoryWiseProduct')->name('category.products.show');
Route::get('/productDetail/{id}/{sku}', 'FrontendController@productDetail')->name('productDetail');
Route::get('/register', 'FrontendController@register')->name('register');
Route::get('/login', 'FrontendController@login')->name('customerLogin');
Route::post('/productQuickView', 'FrontendController@productQuickView');
Route::get('/thankYou', 'FrontendController@thankYou')->name('thankYou');

//retailer
Route::post('admin/retailer/getDistrict', 'RetailerController@getDistrict')->name('retailer.getDistrict');
Route::post('admin/retailer/getThana', 'RetailerController@getThana')->name('retailer.getThana');
Route::post('admin/retailer/getZone', 'RetailerController@getZone')->name('retailer.getZone');

Route::get('/loginAndRegister', 'FrontendController@loginAndRegister')->name('loginAndRegister');
//wishlist
Route::get('/wishlist', 'WishlistController@index')->name('wishlist');
Route::get('/addWishlist/{id}', 'WishlistController@add')->name('addToWishlist');
Route::get('/wishlist/removeItem/{id}', 'WishlistController@remove')->name('wishlist.destroy');

//cart
Route::post('/cart', 'CartController@index')->name('cart');
Route::post('/addToCart/{id}', 'CartController@addToCart')->name('addToCart');
Route::post('/removeCart/{rowId}', 'CartController@removeCart')->name('removeCart');
Route::post('/updateCart', 'CartController@updateCart');
Route::post('/updateSrCart', 'CartController@updateSrCart');
Route::post('/clearCart', 'CartController@clearCart');

Route::post('subscribe', 'FrontendController@subscribe');
Route::post('subscribe/openCaptcha', 'FrontendController@openCaptcha');

Route::get('news-and-events/{slug}', 'FrontendController@newsDetails');
Route::get('footer-menu/{slug}', 'FrontendController@postDetails');

//Customer Authentication
Route::post('/registerCustomer', 'CustomerAuthenticationController@registerCustomer')->name('customer.store');
Route::post('/authenticateCustomer', 'CustomerAuthenticationController@authenticateCustomer')->name('customer.authenticate');
Route::get('/logoutCustomer', 'CustomerAuthenticationController@logoutCustomer')->name('customer.logout');

// Forgot Password
Route::get('requestForgotPassword', 'ForgotPasswordController@requestForgotPassword');
Route::post('forgotPassword', 'ForgotPasswordController@forgotPassword');
Route::get('recoverPassword/{id}', 'ForgotPasswordController@recoverPassword');
Route::post('resetPassword', 'ForgotPasswordController@resetPassword');

Route::post('/googleLogin', 'CustomerAuthenticationController@googleLogin')->name('customer.googleLogin');
Route::post('/facebookLogin', 'CustomerAuthenticationController@facebookLogin')->name('customer.facebookLogin');
Route::post('/showVerifyNumber', 'CustomerAuthenticationController@showVerifyNumber')->name('customer.showVerifyNumber');

//CheckOut
Route::get('/checkout', 'CheckoutController@index')->name('checkout');
Route::post('/placeOrder', 'CheckoutController@placeOrder')->name('placeOrder');
//Order Tracking
Route::get('/orderList', 'OrderTrackingController@index')->name('orderList');
Route::post('admin/showPaymentInfo', 'ProcessingOrderController@showPaymentInfo');
Route::post('admin/confirmDelivery', 'ProcessingOrderController@showConfirmDelivery');

// payment due by retailer
Route::get('admin/paymentDueByRetailerReport', 'PaymentDueByRetailerReportController@index');
Route::post('admin/paymentDueByRetailerReport/filter', 'PaymentDueByRetailerReportController@filter');

// Registration
Route::post('frontend/getZone', 'FrontendController@getZone');
Route::post('frontend/getDistrict', 'FrontendController@getDistrict')->name('frontend.getDistrict');
Route::post('frontend/getThana', 'FrontendController@getThana')->name('frontend.getThana');

Route::group(['middleware' => ['customer']], function () use ($prefix) {
    //myOrder
    Route::get('/myOrder', 'MyOrderController@index')->name('myOrder');
    Route::post('/myOrder/getOrderDetails', 'MyOrderController@getOrderDetails');
    //my Account
    Route::get('/myProfile', 'MyProfileController@index')->name('myProfile');
    Route::post('/editMyProfile', 'MyProfileController@editMyProfile');
    Route::post('/updateProfile', 'MyProfileController@updateProfile');
});

Route::group(['middleware' => 'auth'], function () use ($prefix) {
    //my profile
    Route::get('admin/myProfile', 'UserController@myProfile');
    //retailerDistributorOrder
    Route::get('admin/retailerDistributorOrder', 'RetailerDistributorOrderController@index')->name('order.index');
    Route::post('admin/retailerDistributorOrder/filter/', 'RetailerDistributorOrderController@filter');

    //warehouse
    Route::post('admin/warehouse/getCheckCwh', 'WarehouseController@getCheckCwh')->name('warehouse.getCheckCwh');
    Route::post('admin/warehouse/getDistrictToCreate', 'WarehouseController@getDistrictToCreate');
    Route::post('admin/warehouse/getThanaToCreate', 'WarehouseController@getThanaToCreate');

    //    Retailer
    Route::post('admin/retailer/showProfileCompitionStatus', 'RetailerController@showProfileCompitionStatus')->name('retailer.showProfileCompitionStatus');

    //    Product Return
    Route::post('admin/stockWhReturn/purchaseNew/', 'ProductWhReturnController@purchaseNew');
    Route::post('admin/stockWhReturn/getSkuList', 'ProductWhReturnController@getSkuList');
    Route::post('admin/stockWhReturn/getProductName/', 'ProductWhReturnController@getProductName');

    Route::get('dashboard', 'Admin\DashboardController@index')->name('dashboard');

    Route::post('dashboard/getProductPricing', 'Admin\DashboardController@getProductPricing');
    Route::post('dashboard/getPendingOrder', 'Admin\DashboardController@getPendingOrder');
    Route::post('dashboard/getWaitingForProcessing', 'Admin\DashboardController@getWaitingForProcessing');
    Route::post('dashboard/getPlacedInDelivery', 'Admin\DashboardController@getPlacedInDelivery');
    Route::post('dashboard/getTodaysOrder', 'Admin\DashboardController@getTodaysOrder');
    Route::post('dashboard/getTotalRetailer', 'Admin\DashboardController@getTotalRetailer');
    Route::post('dashboard/getLowQuantitySKU', 'Admin\DashboardController@getLowQuantitySKU');
    Route::post('dashboard/getMonthlyOrderState', 'Admin\DashboardController@getMonthlyOrderState');

    //*********************** Start :: Default Service **********************//
    //go to confirmed or accomplished order page
//    Route::post('admin/defaultService/getConfirmedOrAccomplishedRedirect', 'Admin\DefaultServiceController@getConfirmedOrAccomplishedRedirect');
    //*********************** End :: Default Service **********************//
    //setRecordPerPage
    Route::post('admin/setRecordPerPage', 'UserController@setRecordPerPage');
    Route::get('admin/{id}/changePassword', 'UserController@changePassword');
    Route::post('admin/changePassword', 'UserController@updatePassword');

    /* Acl Access To Methods */
    Route::get('admin/aclAccessToMethods', 'AclAccessToMethodsController@index');
    Route::get('admin/aclAccessToMethods/addAccessMethod', 'AclAccessToMethodsController@addAccessMethod');
    Route::post('admin/aclAccessToMethods/accessToMethodSave', 'AclAccessToMethodsController@accessToMethodSave');
    Route::post('admin/aclAccessToMethods/getAccessMethod', 'AclAccessToMethodsController@getAccessMethod');

    //user
    //product check in
//    Route::post('admin/productCheckIn/getSupplierBrand/', 'ProductCheckInController@getSupplierBrand');
    Route::post('admin/productCheckIn/getProductBrand/', 'ProductCheckInController@getProductBrand');
    Route::post('admin/productCheckIn/getSupplierAddress/', 'ProductCheckInController@getSupplierAddress');
    Route::post('admin/productCheckIn/productHints', 'ProductCheckInController@productHints');
    Route::post('admin/productCheckIn/purchaseNew/', 'ProductCheckInController@purchaseNew');
    //procurement
    Route::post('admin/procurement/getProcurementUnitPrice', 'ProcurementController@getProcurementUnitPrice');
    Route::post('admin/procurement/getProcurement', 'ProcurementController@getProcurement');

    //product adjustment
    Route::post('admin/productAdjustment/productHints', 'ProductAdjustmentController@productHints');
    Route::post('admin/productAdjustment/purchaseNew/', 'ProductAdjustmentController@purchaseNew');
    Route::post('admin/productAdjustment/getProductName/', 'ProductAdjustmentController@getProductName');

    //configuration
    Route::post('admin/configuration/addPhoneNumber', 'ConfigurationController@newPhoneNumberRow');

    //processing order
    Route::post('admin/processingOrder/getHeadOffice', 'ProcessingOrderController@getHeadOffice');
    Route::post('admin/processingOrder/getBranch', 'ProcessingOrderController@getBranch');
    Route::post('admin/processingOrder/getBranchDetail', 'ProcessingOrderController@getBranchDetail');

    //return Product
    Route::post('admin/returnProduct/getProduct', 'ReturnProductController@getProduct');

    //return Product List
    Route::post('admin/returnProductList/getReturnProductModal', 'ReturnProductListController@getReturnProductModal');

    // Product Transfer
    Route::post('admin/stockTransfer/purchaseNew/', 'ProductTransferController@purchaseNew');
    Route::post('admin/stockTransfer/getProductName/', 'ProductTransferController@getProductName');

    //New Product Image
    Route::post('admin/product/newProductImage', 'ProductController@newProductImage')->name('product.newProductImage');

    //wh stock summary report
    Route::post('admin/whStockSummaryReport/getProduct', 'WhStockSummaryReportController@getProduct');
    //Show PaymentInformation
});

//ACL ACCESS GROUP MIDDLEWARE
Route::group(['middleware' => ['auth', 'aclgroup']], function () use ($prefix) {

    //user
    Route::post('admin/user/filter/', 'UserController@filter');
    Route::get('admin/user', 'UserController@index')->name('user.index');
    Route::get('admin/user/create', 'UserController@create')->name('user.create');
    Route::post('admin/user', 'UserController@store')->name('user.store');
    Route::get('admin/user/{id}/edit', 'UserController@edit')->name('user.edit');
    Route::patch('admin/user/{id}', 'UserController@update')->name('user.update');
    Route::delete('admin/user/{id}', 'UserController@destroy')->name('user.destroy');
    // tuhin
    Route::post('admin/user/getUserAdditionalInfo', 'UserController@getUserAdditionalInfo')->name('user.getUserAdditionalForm');
    Route::post('admin/user/setUserAdditionalInfo', 'UserController@setUserAdditionalInfo')->name('user.setUserAdditionalInfo');

    //department
    Route::post('admin/department/filter/', 'DepartmentController@filter');
    Route::get('admin/department', 'DepartmentController@index')->name('department.index');
    Route::get('admin/department/create', 'DepartmentController@create')->name('department.create');
    Route::post('admin/department', 'DepartmentController@store')->name('department.store');
    Route::get('admin/department/{id}/edit', 'DepartmentController@edit')->name('department.edit');
    Route::patch('admin/department/{id}', 'DepartmentController@update')->name('department.update');
    Route::delete('admin/department/{id}', 'DepartmentController@destroy')->name('department.destroy');

    //designation
    Route::post('admin/designation/filter/', 'DesignationController@filter');
    Route::get('admin/designation', 'DesignationController@index')->name('designation.index');
    Route::get('admin/designation/create', 'DesignationController@create')->name('designation.create');
    Route::post('admin/designation', 'DesignationController@store')->name('designation.store');
    Route::get('admin/designation/{id}/edit', 'DesignationController@edit')->name('designation.edit');
    Route::patch('admin/designation/{id}', 'DesignationController@update')->name('designation.update');
    Route::delete('admin/designation/{id}', 'DesignationController@destroy')->name('designation.destroy');

    //branch
    Route::post('admin/branch/filter/', 'BranchController@filter');
    Route::get('admin/branch', 'BranchController@index')->name('branch.index');
    Route::get('admin/branch/create', 'BranchController@create')->name('branch.create');
    Route::post('admin/branch/getDivisionToCreate', 'BranchController@getDivisionToCreate');
    Route::post('admin/branch/getDistrictToCreate', 'BranchController@getDistrictToCreate');
    Route::post('admin/branch/getThanaToCreate', 'BranchController@getThanaToCreate');
    Route::post('admin/branch', 'BranchController@store')->name('branch.store');
    Route::get('admin/branch/{id}/edit', 'BranchController@edit')->name('branch.edit');
    Route::post('admin/branch/getDivisionToEdit', 'BranchController@getDivisionToEdit');
    Route::post('admin/branch/getDistrictToEdit', 'BranchController@getDistrictToEdit');
    Route::post('admin/branch/getThanaToEdit', 'BranchController@getThanaToEdit');
    Route::patch('admin/branch/{id}', 'BranchController@update')->name('branch.update');
    Route::delete('admin/branch/{id}', 'BranchController@destroy')->name('branch.destroy');

    //user group
    Route::post('admin/userGroup/filter/', 'UserGroupController@filter');
    Route::get('admin/userGroup', 'UserGroupController@index')->name('userGroup.index');
    Route::get('admin/userGroup/create', 'UserGroupController@create')->name('userGroup.create');
    Route::post('admin/userGroup', 'UserGroupController@store')->name('userGroup.store');
    Route::get('admin/userGroup/{id}/edit', 'UserGroupController@edit')->name('userGroup.edit');
    Route::patch('admin/userGroup/{id}', 'UserGroupController@update')->name('userGroup.update');
    Route::delete('admin/userGroup/{id}', 'UserGroupController@destroy')->name('userGroup.destroy');

    //news
    Route::get('admin/newsAndEvents', 'NewsAndEventsController@index')->name('newsAndEvents.index');
    Route::get('admin/newsAndEvents/create', 'NewsAndEventsController@create')->name('newsAndEvents.create');
    Route::post('admin/newsAndEvents', 'NewsAndEventsController@store')->name('newsAndEvents.store');
    Route::get('admin/newsAndEvents/{id}/edit', 'NewsAndEventsController@edit')->name('newsAndEvents.edit');
    Route::patch('admin/newsAndEvents/{id}', 'NewsAndEventsController@update')->name('newsAndEvents.update');
    Route::delete('admin/newsAndEvents/{id}', 'NewsAndEventsController@destroy')->name('newsAndEvents.destroy');

    //banner
    Route::get('admin/banner', 'BannerController@index')->name('banner.index');
    Route::get('admin/banner/create', 'BannerController@create')->name('banner.create');
    Route::post('admin/banner', 'BannerController@store')->name('banner.store');
    Route::get('admin/banner/{id}/edit', 'BannerController@edit')->name('banner.edit');
    Route::patch('admin/banner/{id}', 'BannerController@update')->name('banner.update');
    Route::delete('admin/banner/{id}', 'BannerController@destroy')->name('banner.destroy');

    //advertisement
    Route::get('admin/advertisement', 'AdvertisementController@index')->name('advertisement.index');
    Route::get('admin/advertisement/create', 'AdvertisementController@create')->name('advertisement.create');
    Route::post('admin/advertisement', 'AdvertisementController@store')->name('advertisement.store');
    Route::get('admin/advertisement/{id}/edit', 'AdvertisementController@edit')->name('advertisement.edit');
    Route::patch('admin/advertisement/{id}', 'AdvertisementController@update')->name('advertisement.update');
    Route::delete('admin/advertisement/{id}', 'AdvertisementController@destroy')->name('advertisement.destroy');

    //social network
    Route::get('admin/socialNetwork', 'SocialNetworkController@index')->name('socialNetwork.index');
    Route::get('admin/socialNetwork/create', 'SocialNetworkController@create')->name('socialNetwork.create');
    Route::post('admin/socialNetwork', 'SocialNetworkController@store')->name('socialNetwork.store');
    Route::get('admin/socialNetwork/{id}/edit', 'SocialNetworkController@edit')->name('socialNetwork.edit');
    Route::patch('admin/socialNetwork/{id}', 'SocialNetworkController@update')->name('socialNetwork.update');
    Route::delete('admin/socialNetwork/{id}', 'SocialNetworkController@destroy')->name('socialNetwork.destroy');

    //speciality
    Route::get('admin/speciality', 'SpecialityController@index')->name('speciality.index');
    Route::get('admin/speciality/create', 'SpecialityController@create')->name('speciality.create');
    Route::post('admin/speciality', 'SpecialityController@store')->name('speciality.store');
    Route::get('admin/speciality/{id}/edit', 'SpecialityController@edit')->name('speciality.edit');
    Route::patch('admin/speciality/{id}', 'SpecialityController@update')->name('speciality.update');
    Route::delete('admin/speciality/{id}', 'SpecialityController@destroy')->name('speciality.destroy');

    //acl User Group To Access
    Route::get('admin/aclUserGroupToAccess/moduleAccessControl', 'AclUserGroupToAccessController@moduleAccessControl');
    Route::post('admin/aclUserGroupToAccess/relateUserGroupToAccess/', 'AclUserGroupToAccessController@relateUserGroupToAccess');
    Route::post('admin/aclUserGroupToAccess/getAccessControl/', 'AclUserGroupToAccessController@getAccess');
    Route::get('admin/aclUserGroupToAccess/userGroupToAccess', 'AclUserGroupToAccessController@userGroupToAccess');
    Route::post('admin/aclUserGroupToAccess/getUserGroupListToRevoke', 'AclUserGroupToAccessController@getUserGroupListToRevoke');
    Route::post('admin/aclUserGroupToAccess/revokeUserGroupAccess', 'AclUserGroupToAccessController@revokeUserGroupAccess');

    //customer
    Route::post('admin/customer/filter/', 'CustomerController@filter');
    Route::get('admin/customer', 'CustomerController@index')->name('customer.index');
    Route::get('admin/customer/create', 'CustomerController@create')->name('customer.create');

    Route::get('admin/customer/{id}/edit', 'CustomerController@edit')->name('customer.edit');
    Route::post('admin/customer/edit', 'CustomerController@update')->name('customer.update');
    Route::delete('admin/customer/{id}', 'CustomerController@destroy')->name('customer.destroy');

    //product category
    Route::post('admin/productCategory/filter/', 'ProductCategoryController@filter');
    Route::get('admin/productCategory', 'ProductCategoryController@index')->name('productCategory.index');
    Route::get('admin/productCategory/create', 'ProductCategoryController@create')->name('productCategory.create');
    Route::post('admin/productCategory', 'ProductCategoryController@store')->name('productCategory.store');
    Route::get('admin/productCategory/{id}/edit', 'ProductCategoryController@edit')->name('productCategory.edit');
    Route::patch('admin/productCategory/update', 'ProductCategoryController@update')->name('productCategory.update');
    Route::delete('admin/productCategory/{id}', 'ProductCategoryController@destroy')->name('productCategory.destroy');

    //product type tuhin
    Route::post('admin/productType/filter/', 'ProductTypeController@filter');
    Route::get('admin/productType', 'ProductTypeController@index');
    Route::get('admin/productType/create', 'ProductTypeController@create')->name('productType.create');
    Route::post('admin/productType', 'ProductTypeController@store')->name('productType.store');
    Route::get('admin/productType/{id}/edit', 'ProductTypeController@edit')->name('productType.edit');
    Route::patch('admin/productType/{id}', 'ProductTypeController@update')->name('productType.update');
    Route::delete('admin/productType/{id}', 'ProductTypeController@destroy')->name('productType.destroy');

    //product Container tuhin
    Route::post('admin/productContainer/filter/', 'ProductContainerController@filter');
    Route::get('admin/productContainer', 'ProductContainerController@index')->name('productContainer.index');
    Route::get('admin/productContainer/create', 'ProductContainerController@create')->name('productContainer.create');
    Route::post('admin/productContainer', 'ProductContainerController@store')->name('productContainer.store');
    Route::get('admin/productContainer/{id}/edit', 'ProductContainerController@edit')->name('productContainer.edit');
    Route::patch('admin/productContainer/{id}', 'ProductContainerController@update')->name('productContainer.update');
    Route::delete('admin/productContainer/{id}', 'ProductContainerController@destroy')->name('productContainer.destroy');

    //cluster
    Route::post('admin/cluster/filter/', 'ClusterController@filter');
    Route::get('admin/cluster', 'ClusterController@index')->name('cluster.index');
    Route::get('admin/cluster/create', 'ClusterController@create')->name('cluster.create');
    Route::post('admin/cluster', 'ClusterController@store')->name('cluster.store');
    Route::get('admin/cluster/{id}/edit', 'ClusterController@edit')->name('cluster.edit');
    Route::patch('admin/cluster/{id}', 'ClusterController@update')->name('cluster.update');
    Route::delete('admin/cluster/{id}', 'ClusterController@destroy')->name('cluster.destroy');

    //zone
    Route::post('admin/zone/filter/', 'ZoneController@filter');
    Route::get('admin/zone', 'ZoneController@index')->name('zone.index');
    Route::get('admin/zone/create', 'ZoneController@create')->name('zone.create');
    Route::post('admin/zone', 'ZoneController@store')->name('zone.store');
    Route::get('admin/zone/{id}/edit', 'ZoneController@edit')->name('zone.edit');
    Route::patch('admin/zone/{id}', 'ZoneController@update')->name('zone.update');
    Route::delete('admin/zone/{id}', 'ZoneController@destroy')->name('zone.destroy');

    //Bank tuhin
    Route::post('admin/bank/filter/', 'BankController@filter');
    Route::get('admin/bank', 'BankController@index')->name('bank.index');
    Route::get('admin/bank/create', 'BankController@create')->name('bank.create');
    Route::post('admin/bank', 'BankController@store')->name('bank.store');
    Route::get('admin/bank/{id}/edit', 'BankController@edit')->name('bank.edit');
    Route::patch('admin/bank/{id}', 'BankController@update')->name('bank.update');
    Route::delete('admin/bank/{id}', 'BankController@destroy')->name('bank.destroy');

    //product unit
    Route::post('admin/productUnit/filter/', 'ProductUnitController@filter');
    Route::get('admin/productUnit', 'ProductUnitController@index')->name('productUnit.index');
    Route::get('admin/productUnit/create', 'ProductUnitController@create')->name('productUnit.create');
    Route::post('admin/productUnit', 'ProductUnitController@store')->name('productUnit.store');
    Route::get('admin/productUnit/{id}/edit', 'ProductUnitController@edit')->name('productUnit.edit');
    Route::patch('admin/productUnit/{id}', 'ProductUnitController@update')->name('productUnit.update');
    Route::delete('admin/productUnit/{id}', 'ProductUnitController@destroy')->name('productUnit.destroy');

    //product attribute
    Route::post('admin/productAttribute/filter/', 'ProductAttributeController@filter');
    Route::get('admin/productAttribute', 'ProductAttributeController@index')->name('productAttribute.index');
    Route::get('admin/productAttribute/create', 'ProductAttributeController@create')->name('productAttribute.create');
    Route::post('admin/productAttribute', 'ProductAttributeController@store')->name('productAttribute.store');
    Route::get('admin/productAttribute/{id}/edit', 'ProductAttributeController@edit')->name('productAttribute.edit');
    Route::patch('admin/productAttribute/{id}', 'ProductAttributeController@update')->name('productAttribute.update');
    Route::delete('admin/productAttribute/{id}', 'ProductAttributeController@destroy')->name('productAttribute.destroy');

    //product type
    Route::post('admin/attributeType/filter/', 'AttributeTypeController@filter');
    Route::get('admin/attributeType', 'AttributeTypeController@index')->name('attributeType.index');
    Route::get('admin/attributeType/create', 'AttributeTypeController@create')->name('attributeType.create');
    Route::post('admin/attributeType', 'AttributeTypeController@store')->name('attributeType.store');
    Route::get('admin/attributeType/{id}/edit', 'AttributeTypeController@edit')->name('attributeType.edit');
    Route::patch('admin/attributeType/{id}', 'AttributeTypeController@update')->name('attributeType.update');
    Route::delete('admin/attributeType/{id}', 'AttributeTypeController@destroy')->name('attributeType.destroy');

    //product
    Route::post('admin/product/filter/', 'ProductController@filter');
    Route::get('admin/product', 'ProductController@index')->name('product.index');
    Route::get('admin/product/create', 'ProductController@create')->name('product.create');
    Route::post('admin/product/loadProductNameCreate', 'ProductController@loadProductNameCreate');
    Route::post('admin/product/store', 'ProductController@store')->name('product.store');
    Route::get('admin/product/{id}/edit', 'ProductController@edit')->name('product.edit');
    Route::post('admin/product/loadProductNameEdit', 'ProductController@loadProductNameEdit');
    Route::post('admin/product/update', 'ProductController@update')->name('product.update');
    Route::delete('admin/product/{id}', 'ProductController@destroy')->name('product.destroy');
    Route::post('admin/product/getProductPricing', 'ProductController@getProductPricing');
    Route::post('admin/product/setProductPricing', 'ProductController@setProductPricing');
    Route::post('admin/product/showPricingHistory', 'ProductController@showPricingHistory');
    Route::post('admin/product/getProductAttribute', 'ProductController@getProductAttribute');
    Route::post('admin/product/setProductAttribute', 'ProductController@setProductAttribute');
    Route::post('admin/product/getProductSKU', 'ProductController@getProductSKU');
    Route::post('admin/product/setProductSKU', 'ProductController@setProductSKU');
    Route::post('admin/product/getProductTag', 'ProductController@getProductTag');
    Route::post('admin/product/setProductTag', 'ProductController@setProductTag');
    Route::post('admin/product/trackProductPricingHistory', 'ProductController@trackProductPricingHistory');
    Route::post('admin/product/brandDetails', 'ProductController@brandDetails');
    Route::post('admin/product/getProductOffer', 'ProductController@getProductOffer');
    Route::post('admin/product/setProductOffer', 'ProductController@setProductOffer');
    //set product image
    Route::get('admin/product/{id}/getProductImage', 'ProductController@getProductImage');
    Route::post('admin/product/setProductImage', 'ProductController@setProductImage');
    //tuhin
    Route::post('admin/product/setProductPublishorUnpublish', 'ProductController@setProductPublishorUnpublish')->name('product.setProductPublishorUnpublish');

    //brand
    Route::get('admin/brand', 'BrandController@index')->name('brand.index');
    Route::post('admin/brand/filter', 'BrandController@filter');
    Route::get('admin/brand/create', 'BrandController@create')->name('brand.create');
    Route::post('admin/brand', 'BrandController@store')->name('brand.store');
    Route::get('admin/brand/{id}/edit', 'BrandController@edit')->name('brand.edit');
    Route::post('admin/brandUpdate', 'BrandController@update')->name('brand.update');
    Route::delete('admin/brand/{id}', 'BrandController@destroy')->name('brand.destroy');

    //Contact Designation Managment
    Route::post('admin/contactDesignation/filter/', 'ContactDesignationController@filter');
    Route::get('admin/contactDesignation', 'ContactDesignationController@index')->name('contactDesignation.index');
    Route::get('admin/contactDesignation/create', 'ContactDesignationController@create')->name('contactDesignation.create');
    Route::post('admin/contactDesignation', 'ContactDesignationController@store')->name('contactDesignation.store');
    Route::get('admin/contactDesignation/{id}/edit', 'ContactDesignationController@edit')->name('contactDesignation.edit');
    Route::patch('admin/contactDesignation/{id}', 'ContactDesignationController@update')->name('contactDesignation.update');
    Route::delete('admin/contactDesignation/{id}', 'ContactDesignationController@destroy')->name('contactDesignation.destroy');

    //courier Service Management
    Route::post('admin/courierService/filter/', 'CourierServiceController@filter');
    Route::get('admin/courierService', 'CourierServiceController@index')->name('courierService.index');
    Route::get('admin/courierService/create', 'CourierServiceController@create')->name('courierService.create');
    Route::post('admin/courierService', 'CourierServiceController@store')->name('courierService.store');
    Route::get('admin/courierService/{id}/edit', 'CourierServiceController@edit')->name('courierService.edit');
    Route::post('admin/courierService/edit', 'CourierServiceController@update')->name('courierService.update');
    Route::delete('admin/courierService/{id}', 'CourierServiceController@destroy')->name('courierService.destroy');
    Route::post('admin/courierService/newContactPersonToCreate', 'CourierServiceController@newContactPersonToCreate')->name('courierService.createContactPerson');
    Route::post('admin/courierService/addPhoneNumber', 'CourierServiceController@addPhoneNumber');
    Route::post('admin/courierService/showContactPersonDetails', 'CourierServiceController@getDetailsOfContactPerson')->name('courierService.detailsOfContactPerson');
    Route::post('admin/courierService/newContactPersonToEdit', 'CourierServiceController@newContactPersonToEdit')->name('courierService.editContactPerson');

    //supplier
    Route::post('admin/supplier/showContactPersonDetails', 'SupplierController@getDetailsOfContactPerson')->name('supplier.detailsOfContactPerson');
    Route::post('admin/supplier/newContactPersonToCreate', 'SupplierController@newContactPersonToCreate')->name('supplier.contactPersonToCreate');
    Route::post('admin/supplier/newContactPersonToEdit', 'SupplierController@newContactPersonToEdit')->name('supplier.contactPersonToEdit');
    Route::post('admin/supplier/filter/', 'SupplierController@filter');
    Route::get('admin/supplier', 'SupplierController@index')->name('supplier.index');
    Route::get('admin/supplier/create', 'SupplierController@create')->name('supplier.create');
    Route::post('admin/supplier', 'SupplierController@store')->name('supplier.store');
    Route::get('admin/supplier/{id}/edit', 'SupplierController@edit')->name('supplier.edit');
    Route::post('admin/supplier/edit', 'SupplierController@update')->name('supplier.update');
    Route::delete('admin/supplier/{id}', 'SupplierController@destroy')->name('supplier.destroy');
    Route::get('admin/supplier/{id}/profile', 'SupplierController@profile');
    Route::get('admin/supplier/{id}/printProfile', 'SupplierController@printProfile');
    Route::post('admin/supplier/addPhoneNumber', 'SupplierController@addPhoneNumber');
    //tuhin
    Route::post('admin/supplier/getSupplierAdditionalInfo', 'SupplierController@getSupplierAdditionalInfo')->name('supplier.getSupplierAdditionalInfo');
    Route::post('admin/supplier/setSupplierAdditionalInfo', 'SupplierController@setSupplierAdditionalInfo')->name('supplier.setSupplierAdditionalInfo');

    //product to brand
    Route::get('admin/productToBrand', 'ProductToBrandController@index')->name('productToBrand.index');
    Route::post('admin/productToBrand/getBrandsToRelate', 'ProductToBrandController@getBrandsToRelate');
    Route::post('admin/productToBrand/getRelatedBrands', 'ProductToBrandController@getRelatedBrands');
    Route::post('admin/productToBrand/relateProductToBrand', 'ProductToBrandController@relateProductToBrand');

    //product to attribute
    Route::get('admin/productToAttribute', 'ProductToAttributeController@index')->name('productToAttribute.index');
    Route::post('admin/productToAttribute/getAttributesToRelate', 'ProductToAttributeController@getAttributesToRelate');
    Route::post('admin/productToAttribute/getRelatedAttributes', 'ProductToAttributeController@getRelatedAttributes');
    Route::post('admin/productToAttribute/relateProductToAttribute', 'ProductToAttributeController@relateProductToAttribute');

    //product sku code
    Route::get('admin/productSKUCode', 'ProductSKUCodeController@index')->name('productSKUCode.index');
    Route::post('admin/productSKUCode/getCategoryBrand', 'ProductSKUCodeController@getCategoryBrand');
    Route::post('admin/productSKUCode/relateProductToSKUCode', 'ProductSKUCodeController@relateProductToSKUCode');
    Route::post('admin/productSKUCode/getAssignedSKUCodes', 'ProductSKUCodeController@getAssignedSKUCodes');

    //product unit
    Route::post('admin/productTag/filter/', 'ProductTagController@filter');
    Route::get('admin/productTag', 'ProductTagController@index')->name('productTag.index');
    Route::get('admin/productTag/create', 'ProductTagController@create')->name('productTag.create');
    Route::post('admin/productTag', 'ProductTagController@store')->name('productTag.store');
    Route::get('admin/productTag/{id}/edit', 'ProductTagController@edit')->name('productTag.edit');
    Route::patch('admin/productTag/{id}', 'ProductTagController@update')->name('productTag.update');
    Route::delete('admin/productTag/{id}', 'ProductTagController@destroy')->name('productTag.destroy');

    //product to attribute
    Route::get('admin/supplierToProduct', 'SupplierToProductController@index')->name('supplierToProduct.index');
    Route::post('admin/supplierToProduct/getProductsToRelate', 'SupplierToProductController@getProductsToRelate');
    Route::post('admin/supplierToProduct/getRelatedProducts', 'SupplierToProductController@getRelatedProducts');
    Route::post('admin/supplierToProduct/relateSupplierToProduct', 'SupplierToProductController@relateSupplierToProduct');
    Route::post('admin/supplierToProduct/getRelatedSuppliers', 'SupplierToProductController@getRelatedSuppliers');

    //purchase product tuhin
    Route::post('admin/productCheckIn/filter/', 'ProductCheckInController@filter');
    Route::get('admin/productCheckIn', 'ProductCheckInController@create');
    Route::post('admin/productCheckIn/purchaseProduct/', 'ProductCheckInController@purchaseProduct');

    //product purchase list tuhin
    Route::get('admin/productCheckInList', 'ProductCheckInListController@index');
    Route::post('admin/productCheckInList/getProductDetails', 'ProductCheckInListController@getProductDetails');
    Route::post('admin/productCheckInList/filter', 'ProductCheckInListController@filter');
    Route::post('admin/productCheckInList/approve', 'ProductCheckInListController@approve');
    Route::post('admin/productCheckInList/deny', 'ProductCheckInListController@deny');

    //Warehouse
    Route::get('admin/warehouse', 'WarehouseController@index')->name('warehouse.index');
    Route::get('admin/warehouse/create', 'WarehouseController@create')->name('warehouse.create');
    Route::post('admin/warehouse/store', 'WarehouseController@store')->name('warehouse.store');
    Route::get('admin/warehouse/{id}/edit', 'WarehouseController@edit')->name('warehouse.edit');
    Route::patch('admin/warehouse/{id}', 'WarehouseController@update')->name('warehouse.update');
    Route::delete('admin/warehouse/{id}', 'WarehouseController@destroy')->name('warehouse.destroy');
    Route::post('admin/warehouse/filter', 'WarehouseController@filter');
    Route::post('admin/warehouse/changeCwh', 'WarehouseController@changeCwh')->name('warehouse.changeCwh');

    //Retailer
    Route::get('admin/retailer', 'RetailerController@index')->name('retailer.index');
    Route::get('admin/retailer/create', 'RetailerController@create')->name('retailer.create');
    Route::post('admin/retailer/store', 'RetailerController@store')->name('retailer.store');
    Route::get('admin/retailer/{id}/edit', 'RetailerController@edit')->name('retailer.edit');
    Route::post('admin/retailer/edit', 'RetailerController@update')->name('retailer.update');
    Route::delete('admin/retailer/{id}', 'RetailerController@destroy')->name('retailer.destroy');
    Route::post('admin/retailer/filter', 'RetailerController@filter');
    Route::post('admin/retailer/approve', 'RetailerController@approve')->name('retailer.approve');
    Route::post('admin/retailer/deny', 'RetailerController@deny')->name('retailer.deny');
    //retailer tuhin

    Route::post('admin/retailer/getRetailerAdditionalInfo', 'RetailerController@getRetailerAdditionalInfo')->name('retailer.getRetailerAdditionalInfo');
    Route::post('admin/retailer/setRetailerAdditionalInfo', 'RetailerController@setRetailerAdditionalInfo')->name('retailer.setRetailerAdditionalInfo');

    Route::post('admin/retailer/getRetailerLoginInformation', 'RetailerController@getRetailerLoginInformation')->name('retailer.getRetailerLoginInformation');
    Route::post('admin/retailer/setRetailerLoginInformation', 'RetailerController@setRetailerLoginInformation')->name('retailer.setRetailerLoginInformation');
    Route::post('admin/retailer/showContactPersonDetails', 'RetailerController@getDetailsOfContactPerson')->name('retailer.detailsOfContactPerson');
    Route::post('admin/retailer/newContactPersonToCreate', 'RetailerController@newContactPersonToCreate')->name('retailer.createContactPerson');
    Route::post('admin/retailer/newContactPersonToEdit', 'RetailerController@newContactPersonToEdit')->name('retailer.editContactPerson');

    //Product Transfer
    Route::get('admin/stockTransfer', 'ProductTransferController@create');
    Route::post('admin/stockTransfer/transferProduct/', 'ProductTransferController@transferProduct');

    //Product Transfer List
    Route::get('admin/stockTransferList', 'ProductTransferListController@index');
    Route::post('admin/stockTransferList/filter/', 'ProductTransferListController@filter');
    Route::post('admin/stockTransferList/getProductDetails', 'ProductTransferListController@getProductDetails');
    Route::post('admin/stockTransferList/approve', 'ProductTransferListController@approve');
    Route::post('admin/stockTransferList/deny', 'ProductTransferListController@deny');

    //procurment
    Route::get('admin/procurement', 'ProcurementController@create');
    Route::post('admin/procurement/store', 'ProcurementController@store');
    //procurment List
    Route::get('admin/procurementList', 'ProcurementListController@index');
    Route::post('admin/procurementList/filter', 'ProcurementListController@filter');
    Route::post('admin/procurementList/approve', 'ProcurementListController@approve');
    Route::post('admin/procurementList/deny', 'ProcurementListController@deny');
    Route::post('admin/procurementList/getProcurementModal', 'ProcurementListController@getProcurementModal');
    Route::get('admin/procurementList/workOrder/{id}', 'ProcurementListController@workOrder');
    Route::post('admin/procurementList/workOrderInsert', 'ProcurementListController@workOrderInsert');
    Route::post('admin/procurementList/getWorkOrderModal', 'ProcurementListController@getWorkOrderModal');
    Route::get('admin/procurementList/workOrderPrint', 'ProcurementListController@workOrderPrint');
    Route::get('admin/procurementList/workOrderPdf', 'ProcurementListController@workOrderPdf');

    //product return to supplier
    Route::get('admin/returnProduct', 'ReturnProductController@create');
    Route::post('admin/returnProduct/store', 'ReturnProductController@store');

    Route::get('admin/returnProductList', 'ReturnProductListController@index');
    Route::post('admin/returnProductList/filter', 'ReturnProductListController@filter');

    //Product Return
    Route::get('admin/stockWhReturn', 'ProductWhReturnController@create');
    Route::post('admin/stockWhReturn/returnProduct/', 'ProductWhReturnController@returnProduct');

    //Product Return List
    Route::get('admin/stockWhReturnList', 'ProductWhReturnListController@index');
    Route::post('admin/stockWhReturnList/filter/', 'ProductWhReturnListController@filter');
    Route::post('admin/stockWhReturnList/getProductDetails', 'ProductWhReturnListController@getProductDetails');

    //Product Adjustment
    Route::get('admin/productAdjustment', 'ProductAdjustmentController@create');
    Route::post('admin/productAdjustment/adjustProduct/', 'ProductAdjustmentController@adjustProduct');
//    Route::post('admin/productAdjustment/purchaseNew/', 'ProductAdjustmentController@purchaseNew');
    //Adjustment list
    Route::get('admin/productAdjustmentList', 'ProductAdjustmentListController@index');
    Route::post('admin/productAdjustmentList/filter/', 'ProductAdjustmentListController@filter');
    Route::post('admin/productAdjustmentList/getProductDetails', 'ProductAdjustmentListController@getProductAdjustmentDetails');

    //Order
    //pending order
    Route::get('admin/pendingOrder', 'PendingOrderController@index')->name('pendingOrder.index');
    Route::get('admin/pendingOrder/create', 'PendingOrderController@create')->name('pendingOrder.create');
    Route::post('admin/pendingOrder/filter/', 'PendingOrderController@filter');
    Route::post('admin/pendingOrder/confirmOrder', 'PendingOrderController@confirmOrder');
    Route::post('admin/pendingOrder/startProcessing', 'PendingOrderController@startProcessing');
    Route::post('admin/pendingOrder/cancel', 'PendingOrderController@cancel');
    Route::post('admin/pendingOrder/viewStockDemand', 'PendingOrderController@viewStockDemand');

    //New Order
    Route::get('admin/pendingOrder', 'PendingOrderController@index')->name('pendingOrder.index');
    Route::get('admin/pendingOrder/{id}/edit', 'PendingOrderController@edit')->name('pendingOrder.edit');
    Route::post('admin/pendingOrder/filter/', 'PendingOrderController@filter');
    Route::post('admin/pendingOrder/confirmOrder', 'PendingOrderController@confirmOrder');
    Route::post('admin/pendingOrder/startProcessing', 'PendingOrderController@startProcessing');
    Route::post('admin/pendingOrder/cancel', 'PendingOrderController@cancel');
    Route::post('admin/pendingOrder/viewStockDemand', 'PendingOrderController@viewStockDemand');
    Route::post('admin/pendingOrder/updateOrder', 'PendingOrderController@update');
    Route::delete('admin/pendingOrder/{id}', 'PendingOrderController@destroy')->name('pendingOrder.destroy');
    //Order
    Route::get('admin/order', 'OrderController@index')->name('order.index');
    Route::get('admin/order/create', 'OrderController@create')->name('order.create');
    Route::post('admin/order/saveNewOrder', 'OrderController@store');
    Route::post('admin/order/retailerWisePrice', 'OrderController@retailerWisePrice');
    Route::post('admin/order/filter/', 'OrderController@filter');

    //processing order 
    Route::get('admin/processingOrder', 'ProcessingOrderController@index')->name('processingOrder.index');
    Route::post('admin/processingOrder/filter/', 'ProcessingOrderController@filter');
    //Route::get('admin/processingOrder/{id}/getSetDelivery', 'ProcessingOrderController@getSetDelivery');
    //Route::post('admin/processingOrder/saveSetDelivery', 'ProcessingOrderController@saveSetDelivery');
    Route::get('admin/processingOrder/{id}/getInvoice', 'ProcessingOrderController@getInvoice');
    Route::get('admin/processingOrder/{id}/printInvoice', 'ProcessingOrderController@printInvoice');
    Route::post('admin/processingOrder/invoiceGenerate', 'ProcessingOrderController@invoiceGenerate');
    Route::post('admin/processingOrder/storeInvoice', 'ProcessingOrderController@storeInvoice');
    Route::post('admin/processingOrder/getProductReturn', 'ProcessingOrderController@getProductReturn');
    Route::post('admin/processingOrder/setProductReturn', 'ProcessingOrderController@setProductReturn');
    Route::post('admin/processingOrder/getDeliveryDetails', 'ProcessingOrderController@getDeliveryDetails');
    Route::post('admin/processingOrder/cancel', 'ProcessingOrderController@cancel');
    Route::post('admin/processingOrder/confirmDelivery', 'ProcessingOrderController@confirmDelivery');
    Route::post('admin/processingOrder/viewStockDemand', 'ProcessingOrderController@viewStockDemand');
    Route::post('admin/processingOrder/getSetDelivery', 'ProcessingOrderController@getSetDelivery');
    Route::post('admin/processingOrder/showPaymentInfo', 'ProcessingOrderController@showPaymentInfo');
    Route::post('admin/processingOrder/saveSetDelivery', 'ProcessingOrderController@saveSetDelivery');
    Route::post('admin/processingOrder/markAsDelivered', 'ProcessingOrderController@markAsDelivered');

    // confirmed Order
    Route::get('admin/confirmedOrder', 'ConfirmedOrderController@index')->name('confirmedOrder.index');
    Route::post('admin/confirmedOrder/filter/', 'ConfirmedOrderController@filter');
    Route::post('admin/confirmedOrder/startProcessing', 'ConfirmedOrderController@startProcessing');
    Route::post('admin/confirmedOrder/cancel', 'ConfirmedOrderController@cancel');
    Route::post('admin/confirmedOrder/viewStockDemand', 'ConfirmedOrderController@viewStockDemand');

    // order placed in delivery
    Route::get('admin/orderPlacedInDelivery', 'OrderPlacedInDeliveryController@index')->name('orderPlacedInDelivery.index');
    Route::post('admin/orderPlacedInDelivery/filter/', 'OrderPlacedInDeliveryController@filter');
    Route::get('admin/orderPlacedInDelivery/{id}/getInvoice', 'OrderPlacedInDeliveryController@getInvoice');
    Route::get('admin/orderPlacedInDelivery/{id}/printInvoice', 'OrderPlacedInDeliveryController@printInvoice');
    Route::post('admin/orderPlacedInDelivery/invoiceGenerate', 'OrderPlacedInDeliveryController@invoiceGenerate');
    Route::post('admin/orderPlacedInDelivery/storeInvoice', 'OrderPlacedInDeliveryController@storeInvoice');
    Route::post('admin/orderPlacedInDelivery/getProductReturn', 'OrderPlacedInDeliveryController@getProductReturn');
    Route::post('admin/orderPlacedInDelivery/setProductReturn', 'OrderPlacedInDeliveryController@setProductReturn');
    Route::post('admin/orderPlacedInDelivery/confirmDelivery', 'OrderPlacedInDeliveryController@confirmDelivery');

    // returned order
    Route::get('admin/returnedOrder', 'ReturnedOrderController@index')->name('returnedOrder.index');
    Route::post('admin/returnededOrder/filter', 'ReturnedOrderController@filter');

    //delivered  order
    Route::get('admin/deliveredOrder', 'DeliveredOrderController@index')->name('deliveredOrder.index');
    Route::get('admin/deliveredOrder/{id}/printInvoice', 'DeliveredOrderController@printInvoice');
    Route::post('admin/deliveredOrder/getDeliveryDetails', 'DeliveredOrderController@getDeliveryDetails');
    Route::post('admin/deliveredOrder/filter/', 'DeliveredOrderController@filter');

    //footer Menu
    Route::get('admin/footerMenu', 'FooterMenuController@index')->name('footerMenu.index');
    Route::get('admin/footerMenu/create', 'FooterMenuController@create')->name('footerMenu.create');
    Route::post('admin/footerMenu', 'FooterMenuController@store')->name('footerMenu.store');
    Route::get('admin/footerMenu/{id}/edit', 'FooterMenuController@edit')->name('footerMenu.edit');
    Route::patch('admin/footerMenu/{id}', 'FooterMenuController@update')->name('footerMenu.update');
    Route::delete('admin/footerMenu/{id}', 'FooterMenuController@destroy')->name('footerMenu.destroy');
    // Menu
    Route::get('admin/menu', 'MenuController@index')->name('menu.index');
    Route::get('admin/menu/create', 'MenuController@create')->name('menu.create');
    Route::post('admin/menu', 'MenuController@store')->name('menu.store');
    Route::get('admin/menu/{id}/edit', 'MenuController@edit')->name('menu.edit');
    Route::patch('admin/menu/{id}', 'MenuController@update')->name('menu.update');
    Route::delete('admin/menu/{id}', 'MenuController@destroy')->name('menu.destroy');

    //featured products
    Route::get('admin/featuredProducts', 'FeaturedProductsController@index')->name('featuredProducts.index');
    Route::post('admin/featuredProducts/saveProducts', 'FeaturedProductsController@saveProducts');
    Route::post('admin/featuredProducts/getSelectedSKU', 'FeaturedProductsController@getSelectedSKU');

    //latest products
    Route::get('admin/latestProducts', 'LatestProductsController@index')->name('latestProducts.index');
    Route::post('admin/latestProducts/saveProducts', 'LatestProductsController@saveProducts');
    Route::post('admin/latestProducts/getSelectedSKU', 'LatestProductsController@getSelectedSKU');

    //special products
    Route::get('admin/specialProducts', 'SpecialProductsController@index')->name('specialProducts.index');
    Route::post('admin/specialProducts/saveProducts', 'SpecialProductsController@saveProducts');
    Route::post('admin/specialProducts/getSelectedSKU', 'SpecialProductsController@getSelectedSKU');

    //central stock summary report
    Route::get('admin/centralStockSummaryReport', 'CentralStockSummaryReportController@index');
    Route::post('admin/centralStockSummaryReport/filter', 'CentralStockSummaryReportController@filter');

    ///admin/salesStatusReport
    Route::get('admin/salesStatusReport', 'SalesStatusReportController@index');
    Route::post('admin/salesStatusReport/filter', 'SalesStatusReportController@filter');

    //admin/statusWiseOrderListReport
    Route::get('admin/statusWiseOrderListReport', 'StatusWiseOrderListReportController@index');
    Route::post('admin/statusWiseOrderListReport/filter', 'StatusWiseOrderListReportController@filter');

    // admin cluster performance report
    Route::get('admin/clusterPerformanceReport', 'ClusterPerformanceReportController@index');
    Route::post('admin/clusterPerformanceReport/filter', 'ClusterPerformanceReportController@filter');

    // admin Zone performance report
    Route::get('admin/zonalPerformanceReport', 'ZonalPerformanceReportController@index');
    Route::post('admin/zonalPerformanceReport/filter', 'ZonalPerformanceReportController@filter');

    //admin/supplierWisePurchaseReport
    Route::get('admin/supplierWisePurchaseReport', 'SupplierWisePurchaseReportController@index');
    Route::post('admin/supplierWisePurchaseReport/filter', 'SupplierWisePurchaseReportController@filter');

    ///admin/supplierWisePurchaseReport
    Route::get('admin/supplierBankAccountReport', 'SupplierBankAccountReportController@index');
    Route::post('admin/supplierBankAccountReport/filter', 'SupplierBankAccountReportController@filter');

    //wh stock summary report
    Route::get('admin/whStockSummaryReport', 'WhStockSummaryReportController@index');
    Route::post('admin/whStockSummaryReport/filter', 'WhStockSummaryReportController@filter');

    // supplier summary report
    Route::get('admin/supplierSummaryReport', 'SupplierSummaryReportController@index');
    Route::post('admin/supplierSummaryReport/filter', 'SupplierSummaryReportController@filter');

    // wh stock Ledger report
    Route::get('admin/whStockLedgerReport', 'WhStockLedgerReportController@index');
    Route::post('admin/whStockLedgerReport/filter', 'WhStockLedgerReportController@filter');

    // central stock Ledger report
    Route::get('admin/centralStockLedgerReport', 'CentralStockLedgerReportController@index');
    Route::post('admin/centralStockLedgerReport/filter', 'CentralStockLedgerReportController@filter');

    // retailer Ledger report
    Route::get('admin/retailerLedgerReport', 'RetailerLedgerReportController@index');
    Route::post('admin/retailerLedgerReport/filter', 'RetailerLedgerReportController@filter');

    // checkin Ledger report
    Route::get('admin/checkinLedgerReport', 'CheckinLedgerReportController@index');
    Route::post('admin/checkinLedgerReport/filter', 'CheckinLedgerReportController@filter');

    // damage Ledger report
    Route::get('admin/damageLedgerReport', 'DamageLedgerReportController@index');
    Route::post('admin/damageLedgerReport/filter', 'DamageLedgerReportController@filter');

    // return Ledger report
    Route::get('admin/returnLedgerReport', 'ReturnLedgerReportController@index');
    Route::post('admin/returnLedgerReport/filter', 'ReturnLedgerReportController@filter');

    //CONFIGURATION
    Route::get('admin/configuration', 'ConfigurationController@index')->name('configuration.index');
    Route::get('admin/configuration/create', 'ConfigurationController@create')->name('configuration.create');
    Route::post('admin/configuration', 'ConfigurationController@store')->name('configuration.store');
    Route::get('admin/configuration/{id}/edit', 'ConfigurationController@edit')->name('configuration.edit');
    Route::patch('admin/configuration/{id}', 'ConfigurationController@update')->name('configuration.update');
    Route::post('admin/configuration/saveCompanyInfo', 'ConfigurationController@saveCompanyInfo');

    // subscriber report
    Route::get('admin/subscriberLogReport', 'SubscriberLogReportController@index');
    Route::post('admin/subscriberLogReport/filter', 'SubscriberLogReportController@Filter');

    //cancelled Order
    Route::get('admin/cancelledOrder', 'CancelledOrderController@index')->name('cancelledOrder.index');
    Route::post('admin/cancelledOrder/filter/', 'CancelledOrderController@filter');

    // TM(Territorial Manager) To Warehouse
    Route::get('admin/tmToWarehouse', 'TmToWarehouseController@index')->name('tmToWarehouse.index');
    Route::post('admin/tmToWarehouse/getWarehouseToRelate', 'TmToWarehouseController@getWarehouseToRelate');
    Route::post('admin/tmToWarehouse/relateTmTowarehouse', 'TmToWarehouseController@relateTmToWarehouse');
    Route::post('admin/tmToWarehouse/getRelatedWarehouse', 'TmToWarehouseController@getRelatedWarehouse');

    // Warehouse To SR(Sales Representative)
    Route::get('admin/warehouseToSr', 'WarehouseToSrController@index')->name('warehouseToSr.index');
    Route::post('admin/warehouseToSr/getSrToRelate', 'WarehouseToSrController@getSrToRelate');
    Route::post('admin/warehouseToSr/relateWarehouseToSr', 'WarehouseToSrController@relateWarehouseToSr');
    Route::post('admin/warehouseToSr/getRelatedSr', 'WarehouseToSrController@getRelatedSr');

    // Warehouse To Retailer
    Route::get('admin/warehouseToRetailer', 'WarehouseToRetailerController@index')->name('warehouseToRetailer.index');
    Route::post('admin/warehouseToRetailer/getRetailerToRelate', 'WarehouseToRetailerController@getRetailerToRelate');
    Route::post('admin/warehouseToRetailer/relateWarehouseToRetailer', 'WarehouseToRetailerController@relateWarehouseToRetailer');
    Route::post('admin/warehouseToRetailer/getRelatedRetailer', 'WarehouseToRetailerController@getRelatedRetailer');

    // SR To Retailer
    Route::get('admin/srToRetailer', 'SrToRetailerController@index')->name('warehouseToRetailer.index');
    Route::post('admin/srToRetailer/getRetailerToRelate', 'SrToRetailerController@getRetailerToRelate');
    Route::post('admin/srToRetailer/relateSrToRetailer', 'SrToRetailerController@relateSrToRetailer');
    Route::post('admin/srToRetailer/getRelatedRetailer', 'SrToRetailerController@getRelatedRetailer');

    // Warehouse To local WH manager
    Route::get('admin/whToLocalWhManager', 'WhToLocalWhManagerController@index')->name('whToLocalWhManager.index');
    Route::post('admin/whToLocalWhManager/relateWhToLWM', 'WhToLocalWhManagerController@relateWhToLWM');
    Route::post('admin/whToLocalWhManager/showRelatedLWhManager', 'WhToLocalWhManagerController@showRelatedLWhManager')->name('whToLocalWhManager.showRelatedLWhManager');

    //recieve
    Route::get('admin/receive', 'ReceiveController@create');
    Route::post('admin/receive/getReceiveData', 'ReceiveController@getReceiveData');
    Route::post('admin/receive/previewReceiveData', 'ReceiveController@previewReceiveData');
    Route::post('admin/receive/setReceiveData', 'ReceiveController@setReceiveData');
});

