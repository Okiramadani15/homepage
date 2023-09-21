<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backoffice;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('login', [UserController::class, 'login']);
Route::get('login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    // Auth
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('profile', [UserController::class, 'profile']);

    // Dashboard
    Route::get('condition-asset', [Backoffice\DashboardController::class, 'index']);
    Route::get('chart', [Backoffice\DashboardController::class, 'chart']);

    // User
    Route::get('all-user', [UserController::class, 'user']);
    Route::post('register', [UserController::class, 'register']);
    Route::get('detail-user', [UserController::class, 'detail']);
    Route::post('update-user', [UserController::class, 'update']);
    Route::delete('delete-user', [UserController::class, 'delete']);

    // Test
    Route::get('get-test', [Backoffice\Test\TestController::class, 'index']);
    Route::get('detail-test', [Backoffice\Test\TestController::class, 'getDetail']);
    Route::post('create-test', [Backoffice\Test\TestController::class, 'createTest']);
    Route::put('update-test', [Backoffice\Test\TestController::class, 'updateTest']);
    Route::delete('delete-test', [Backoffice\Test\TestController::class, 'deleteTest']);

    // Assets Location
    Route::get('all-location', [Backoffice\Assets\LocationController::class, 'index']);
    Route::get('location-with-pagination', [Backoffice\Assets\LocationController::class, 'pagination']);
    Route::post('create-location', [Backoffice\Assets\LocationController::class, 'createLocation']);
    Route::get('get-detail', [Backoffice\Assets\LocationController::class, 'getDetailLocation']);
    Route::put('update-location', [Backoffice\Assets\LocationController::class, 'updateLocation']);
    Route::delete('delete-location', [Backoffice\Assets\LocationController::class, 'deleteLocation']);

    // Assets Condition
    Route::get('all-condition', [Backoffice\Assets\ConditionController::class, 'index']);
    Route::post('create-condition', [Backoffice\Assets\ConditionController::class, 'createCondition']);
    Route::get('detail-condition', [Backoffice\Assets\ConditionController::class, 'detailCondition']);
    Route::put('update-condition', [Backoffice\Assets\ConditionController::class, 'updateCondition']);
    Route::delete('delete-condition', [Backoffice\Assets\ConditionController::class, 'deleteCondition']);

    // Gender
    Route::get('all-gender', [Backoffice\Setting\GenderController::class, 'index']);

    // Position
    Route::get('all-position', [Backoffice\Setting\PositionController::class, 'index']);
    Route::get('all-position-with-pagination', [Backoffice\Setting\PositionController::class, 'pagination']);
    Route::post('create-position', [Backoffice\Setting\PositionController::class, 'create']);
    Route::get('detail-position', [Backoffice\Setting\PositionController::class, 'detail']);
    Route::put('update-position', [Backoffice\Setting\PositionController::class, 'update']);
    Route::delete('delete-position', [Backoffice\Setting\PositionController::class, 'delete']);

    // Group Code
    Route::get('group-code/all', [Backoffice\Setting\GroupCodeController::class, 'index']);
    Route::get('group-code/pagination', [Backoffice\Setting\GroupCodeController::class, 'pagination']);
    Route::post('group-code/create', [Backoffice\Setting\GroupCodeController::class, 'create']);
    Route::get('group-code/detail', [Backoffice\Setting\GroupCodeController::class, 'detail']);
    Route::put('group-code/update', [Backoffice\Setting\GroupCodeController::class, 'update']);
    Route::delete('group-code/delete', [Backoffice\Setting\GroupCodeController::class, 'delete']);

    // Purchase Location
    Route::get('purchase-location/all', [Backoffice\Setting\PurchaseLocationController::class, 'index']);
    Route::get('purchase-location/pagination', [Backoffice\Setting\PurchaseLocationController::class, 'pagination']);
    Route::post('purchase-location/create', [Backoffice\Setting\PurchaseLocationController::class, 'create']);
    Route::get('purchase-location/detail', [Backoffice\Setting\PurchaseLocationController::class, 'detail']);
    Route::put('purchase-location/update', [Backoffice\Setting\PurchaseLocationController::class, 'update']);
    Route::delete('purchase-location/delete', [Backoffice\Setting\PurchaseLocationController::class, 'delete']);

    // Asset
    Route::get('all-assets', [Backoffice\Assets\AssetController::class, 'index']);
    Route::get('list-assets', [Backoffice\Assets\AssetController::class, 'listAsset']);
    Route::post('create-asset', [Backoffice\Assets\AssetController::class, 'create']);
    Route::get('detail-asset', [Backoffice\Assets\AssetController::class, 'detail']);
    Route::post('update-asset', [Backoffice\Assets\AssetController::class, 'update']);
    Route::delete('delete-asset', [Backoffice\Assets\AssetController::class, 'delete']);
    Route::get('export-assets', [Backoffice\Assets\AssetController::class, 'exportAsset']);
    Route::post('import-assets', [Backoffice\Assets\AssetController::class, 'importAsset']);

    // Asset Procurement
    Route::get('all-procurement', [Backoffice\Assets\AssetProcurementController::class, 'index']);
    Route::post('create-procurement', [Backoffice\Assets\AssetProcurementController::class, 'create']);
    Route::delete('delete-procurement', [Backoffice\Assets\AssetProcurementController::class, 'delete']);
    Route::get('detail-procurement', [Backoffice\Assets\AssetProcurementController::class, 'detail']);
    Route::post('update-procurement', [Backoffice\Assets\AssetProcurementController::class, 'update']);
    Route::post('update-detail-procurement', [Backoffice\Assets\AssetProcurementController::class, 'updateDetailProcurement']);
    Route::post('procurement/on-process', [Backoffice\Assets\AssetProcurementController::class, 'onProcess']);
    Route::post('procurement/on-approve', [Backoffice\Assets\AssetProcurementController::class, 'onApprove']);
    Route::post('procurement/on-decline', [Backoffice\Assets\AssetProcurementController::class, 'onDecline']);
    Route::post('procurement/on-decline', [Backoffice\Assets\AssetProcurementController::class, 'onDecline']);
    Route::post('procurement/on-completed', [Backoffice\Assets\AssetProcurementController::class, 'onCompleted']);
    Route::post('procurement/change-status', [Backoffice\Assets\AssetProcurementController::class, 'changeStatus']);
    Route::post('procurement/finish-detail-procurement', [Backoffice\Assets\AssetProcurementController::class, 'finishDetailProcurement']);
    Route::post('procurement/export', [Backoffice\Assets\AssetProcurementController::class, 'exportProcurement']);
    Route::get('procurement/document', [Backoffice\Assets\AssetProcurementController::class, 'documentProcurement']);

    // Asset Loan
    Route::get('all-loan', [Backoffice\Assets\AssetLoanController::class, 'index']);
    Route::post('create-loan', [Backoffice\Assets\AssetLoanController::class, 'create']);
    Route::delete('delete-loan', [Backoffice\Assets\AssetLoanController::class, 'delete']);
    Route::get('detail-loan', [Backoffice\Assets\AssetLoanController::class, 'detail']);
    Route::post('change-status-loan', [Backoffice\Assets\AssetLoanController::class, 'changeStatus']);
    Route::post('update-loan', [Backoffice\Assets\AssetLoanController::class, 'update']);
    Route::post('update-detail-loan', [Backoffice\Assets\AssetLoanController::class, 'updateDetailLoan']);
    Route::post('loan/on-decline', [Backoffice\Assets\AssetLoanController::class, 'onDecline']);

    // Asset Repair
    Route::get('all-repair', [Backoffice\Assets\AssetRepairController::class, 'index']);
    Route::post('create-repair', [Backoffice\Assets\AssetRepairController::class, 'create']);
    Route::get('repair/detail', [Backoffice\Assets\AssetRepairController::class, 'detail']);
    Route::post('repair/update', [Backoffice\Assets\AssetRepairController::class, 'update']);
    Route::post('repair/change-status', [Backoffice\Assets\AssetRepairController::class, 'changeStatus']);
    Route::delete('repair/delete', [Backoffice\Assets\AssetRepairController::class, 'delete']);

    // Asset Bleaching
    Route::get('bleaching', [Backoffice\Assets\AssetBleachingController::class, 'index']);
    Route::post('bleaching/create', [Backoffice\Assets\AssetBleachingController::class, 'create']);
    Route::post('bleaching/change-status', [Backoffice\Assets\AssetBleachingController::class, 'changeStatus']);

    // Term
    Route::get('term', [Backoffice\Setting\TermController::class, 'index']);
    Route::get('term-detail', [Backoffice\Setting\TermController::class, 'detail']);

    // WorkUnit
    Route::get('all-work-unit', [Backoffice\Setting\WorkUnitController::class, 'index']);
    Route::get('all-work-unit-with-pagination', [Backoffice\Setting\WorkUnitController::class, 'pagination']);
    Route::post('create-work-unit', [Backoffice\Setting\WorkUnitController::class, 'create']);
    Route::get('detail-work-unit', [Backoffice\Setting\WorkUnitController::class, 'detail']);
    Route::put('update-work-unit', [Backoffice\Setting\WorkUnitController::class, 'update']);
    Route::delete('delete-work-unit', [Backoffice\Setting\WorkUnitController::class, 'delete']);

    // Semester
    Route::get('get-semester', [Backoffice\Setting\SemesterController::class, 'index']);

    // Educational Calendar
    Route::get('educational-calendar', [Backoffice\EducationalCalendarController::class, 'index']);
    Route::post('educational-calendar/create', [Backoffice\EducationalCalendarController::class, 'create']);
    Route::get('educational-calendar/detail', [Backoffice\EducationalCalendarController::class, 'detail']);
    Route::put('educational-calendar/update', [Backoffice\EducationalCalendarController::class, 'update']);
    Route::delete('educational-calendar/delete', [Backoffice\EducationalCalendarController::class, 'delete']);

    // Activity
    Route::get('all-activity', [Backoffice\ActivityController::class, 'index']);
    Route::post('activity/create', [Backoffice\ActivityController::class, 'create']);
    Route::get('activity/detail', [Backoffice\ActivityController::class, 'detail']);
    Route::post('activity/update', [Backoffice\ActivityController::class, 'update']);
    Route::delete('activity/delete', [Backoffice\ActivityController::class, 'delete']);

    // Messages
    Route::get('all-messages', [Backoffice\MessageController::class, 'index']);
    Route::post('message/read', [Backoffice\MessageController::class, 'readMessage']);
    Route::get('count-messages-unread', [Backoffice\MessageController::class, 'countMessageUnRead']);

    // Galery
    Route::get('galery', [Backoffice\GaleryController::class, 'index']);
    Route::post('galery/update', [Backoffice\GaleryController::class, 'updateGalery']);

    // Banner
    Route::get('banner', [Backoffice\BannerController::class, 'index']);
    Route::post('banner/update', [Backoffice\BannerController::class, 'updateBanner']);
});
