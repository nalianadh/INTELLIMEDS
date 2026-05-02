<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockReceive;
use App\Http\Controllers\StockRequestController;
use App\Http\Controllers\SubdeptStockRequestController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\Inbox;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\UserController;


// Redirect root URL to login
Route::get('/', function () {
    return redirect('/login');
});

// Show login page
Route::get('/login', function () {
    return view('login');
});

// Handle login form submission
/*Route::post('/login', function (Request $request) {
    $user = User::where('u_username', $request->username)->first();

    if (!$user || !Hash::check($request->password, $user->u_password)) {
        return back()->with('error', 'Invalid username or password.');
    }

    // Store user info in session
    session(['loggedUser' => $user]);

    // Redirect based on role
    if ($user->u_role === 'main_store') {
        return redirect()->route('mainstore.dashboard');
    } elseif ($user->u_role === 'sub_department') {
        return redirect()->route('subdept.dashboard');
    } else {
        return back()->with('error', 'Unauthorized user role.');
    }
})->name('customLogin');*/

//test for RAILWAY
Route::post('/login', function (Request $request) {
    \Log::info('DB Host: ' . config('database.connections.mysql.host'));
    \Log::info('DB Name: ' . config('database.connections.mysql.database'));
    \Log::info('Table: ' . (new \App\Models\User())->getTable());
    
    // Try raw DB query instead of Eloquent
    $userRaw = \DB::select("SELECT * FROM users WHERE u_username = ?", [$request->username]);
    \Log::info('Raw query result: ' . json_encode($userRaw));
    
    $user = User::where('u_username', $request->username)->first();
    \Log::info('User found: ' . ($user ? 'yes' : 'no'));
    \Log::info('Username input: ' . $request->username);

    if (!$user || !Hash::check($request->password, $user->u_password)) {
        \Log::info('Hash check: ' . ($user ? Hash::check($request->password, $user->u_password) : 'no user'));
        return back()->with('error', 'Invalid username or password.');
    }

    // Store user info in session
    session(['loggedUser' => $user]);

    // Redirect based on role
    if ($user->u_role === 'main_store') {
        return redirect()->route('mainstore.dashboard');
    } elseif ($user->u_role === 'sub_department') {
        return redirect()->route('subdept.dashboard');
    } else {
        return back()->with('error', 'Unauthorized user role.');
    }
})->name('customLogin');

// 🟢 Main Store Dashboard
Route::get('/main-store/dashboard', [UserController::class, 'mainStoreDashboard'])
    ->name('mainstore.dashboard');

// 🔵 Sub Department Dashboard
Route::get('/sub-department/dashboard', [UserController::class, 'subDeptDashboard'])
    ->name('subdept.dashboard');

// 🟢 Main Store Dashboard (named route)
/*Route::get('/main-store/dashboard', function () {
    if (!session()->has('loggedUser')) {
        return redirect('/login');
    }

    $user = session('loggedUser');

    if ($user->u_role !== 'main_store') {
        return redirect('/login');
    }

    return view('main store.dashboard', compact('user'));
})->name('mainstore.dashboard');

// 🔵 Sub Department Dashboard (named route)
Route::get('/sub-department/dashboard', function () {
    if (!session()->has('loggedUser')) {
        return redirect('/login');
    }

    $user = session('loggedUser');

    if ($user->u_role !== 'sub_department') {
        return redirect('/login');
    }

    return view('sub_department.dashboard', compact('user'));
})->name('subdept.dashboard');*/

// 🔴 Logout Route
Route::get('/logout', function () {
    session()->forget('loggedUser');
    return redirect('/login');
})->name('logout');

// Show the form
Route::get('/main-store/item/register', [ItemController::class, 'create'])->name('items.create');

// Handle form submission
Route::post('/main-store/item/register', [ItemController::class, 'store'])->name('items.store');

// Stock Receive Form (GET)
Route::get('/main-store/stock-receive', function () {
    if (!session()->has('loggedUser')) {
        return redirect('/login');
    }
    $user = session('loggedUser');
    if ($user->u_role !== 'main_store') {
        return redirect('/login');
    }
    $items = \App\Models\Item::orderByDesc('created_at')->get();
    return view('main store.stock-receive', compact('user', 'items'));
})->name('mainstore.stock.receive'); // sebelum ni stock.receive

// Handle Stock Receive Form Submission
//Route::post('/main-store/stock-receive', [StockReceive::class, 'store'])->name('stock.receive.store');

// Stock Receive
Route::get('/stock-receive', [App\Http\Controllers\StockReceive::class, 'create'])->name('stock.receive');
Route::post('/stock-receive', [App\Http\Controllers\StockReceive::class, 'store'])->name('stock.receive.store');

// GRN Invoice View
Route::get('/stock-receive/grn', [StockReceive::class, 'grn'])->name('stock.receive.grn');

// GRN List View
Route::get('/main-store/grn-list', [StockReceive::class, 'grnList'])->name('stock.receive.grnlist');
Route::get('/main-store/grn/search', [StockReceive::class, 'searchGRN'])->name('grn.search');

// Item List View
Route::get('/main-store/item-list', [ItemController::class, 'itemList'])->name('items.list');
Route::get('/sync-items', [ItemController::class, 'syncImportedItems'])->name('items.sync'); //nak sync imported data dengan existing data items

// Item detail view
Route::get('/main-store/item/{item}/view', [App\Http\Controllers\ItemController::class, 'view'])->name('items.view');
Route::get('/main-store/item/search', [ItemController::class, 'searchItem'])->name('items.search');
// Edit Item Page
Route::get('/main-store/item/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
// Update Item
Route::put('/main-store/item/{item}/update', [ItemController::class, 'update'])->name('items.update');
//delete item from the list
Route::delete('/main-store/item/{item}/delete', [ItemController::class, 'destroy'])->name('items.delete');
// Item Transaction Log
Route::get('/items/{item}/transaction-log', [ItemController::class, 'transactionLog'])->name('items.transaction-log');



// Stock Transfer In (GET)
Route::get('/main-store/stock-transfer-in', [App\Http\Controllers\StockTransferController::class, 'transferIn'])->name('stock.transfer.in');
// Stock Transfer Out (GET)
Route::get('/main-store/stock-transfer-out', [App\Http\Controllers\StockTransferController::class, 'transferOut'])->name('stock.transfer.out');
// Handle Stock Transfer In Form Submission
Route::post('/main-store/stock-transfer-in', [App\Http\Controllers\StockTransferController::class, 'storeTransferIn'])->name('stock.transfer.in.store');
// Handle Stock Transfer Out Form Submission
Route::post('/main-store/stock-transfer-out', [App\Http\Controllers\StockTransferController::class, 'storeTransferOut'])->name('stock.transfer.out.store');
// Stock Transfer List
Route::get('/main-store/stock-transfer-list', [App\Http\Controllers\StockTransferController::class, 'transferList'])->name('stock.transfer.list');
Route::get('/main-store/stock-transfer/search', [App\Http\Controllers\StockTransferController::class, 'searchTransfers'])->name('stock.transfer.search');
Route::get('/transfer-slip/{id}', [StockTransferController::class, 'show'])->name('transferSlip.show');
// Main Store Inbox
// 📌 Show inbox list
Route::get('/main-store/inbox', [Inbox::class, 'indexMS'])->name('mainstore.inbox');
Route::get('/main-store/inbox/{id}', [Inbox::class, 'showMS'])->name('mainstore.inbox.show');
Route::post('/main-store/inbox/{id}/accept', [Inbox::class, 'acceptMS'])->name('mainstore.inbox.accept');
Route::post('/main-store/inbox/{id}/reject', [Inbox::class, 'rejectMS'])->name('mainstore.inbox.reject');
Route::get('/main-store/inbox/mark-read/{type}/{id}', [Inbox::class, 'markAsRead'])->name('mainstore.inbox.mark-read');

// Stock Request List
Route::get('/main-store/stock-request-list', [StockRequestController::class, 'showRequestList'])
    ->name('stock.request.list');

Route::get('/stock-request/pending', [StockRequestController::class, 'listPendingRequests'])->name('stock-request.pending');
Route::get('/stock-request/{id}/view', [StockRequestController::class, 'view'])->name('stock-request.view');
Route::get('/stock-request/{id}', [StockRequestController::class, 'show'])->name('stock-request.show');
Route::put('/stock-request/{id}/update', [StockRequestController::class, 'update'])->name('stock-request.update');






// Sub Department Stock Request Form
Route::get('/sub-department/stock-request', [StockRequestController::class, 'create'])->name('subdept.request');
Route::get('/stock-request/create', [StockRequestController::class, 'create'])->name('stock-request.create');
Route::post('/stock-request/store', [StockRequestController::class, 'store'])->name('stock-request.store');
//Sub department in-hand stock
Route::get('/subdept/inhand-stock', [SubdeptStockRequestController::class, 'inHandStock'])->name('subdept.inhand.stock');
// Stock Transfer (Sub Dept)
Route::get('/stock-transfer', [StockTransferController::class, 'createSubDept'])->name('stock.transfer.subdept');
Route::post('/stock-transfer', [StockTransferController::class, 'storeSubDept'])->name('stock.transfer.subdept.store');
//Sub Department Inbox
// 📌 Show inbox list
Route::get('/sub-department/inbox', [Inbox::class, 'index'])->name('subdept.inbox');

// 📌 Show a specific message (transfer detail)
Route::get('/sub-department/inbox/{id}', [Inbox::class, 'show'])->name('subdept.inbox.show');

// 📌 Accept a transfer
Route::post('/sub-department/inbox/{id}/accept', [Inbox::class, 'accept'])->name('subdept.inbox.accept');

// 📌 Reject a transfer
Route::post('/sub-department/inbox/{id}/reject', [Inbox::class, 'reject'])->name('subdept.inbox.reject');


//REPORT
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/supply-transaction', [ReportController::class, 'supplyTransaction'])->name('reports.supply-transaction');
Route::get('/reports/stock-request/list', [ReportController::class, 'SRlist'])->name('reports.stock-request.list');
Route::get('/reports/stock-request/view/{date}', [ReportController::class, 'showStockRequestSlip'])->name('reports.stock-request.view');

// DEMAND – Predict ALL stocks
Route::get('/predict-all-demand', [DemandController::class, 'predict'])->name('demand.predict');



