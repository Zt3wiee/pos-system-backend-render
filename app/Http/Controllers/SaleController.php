<?php

namespace App\Http\Controllers;

use App\Http\Resources\SaleResource;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{

       public function store(Request $request){
           // Validate the request data item is array and has at least one item, and each item has a valid product_id and quantity
           $validateData = $request->validate([
            // 'user_id' => 'required|exists:users,id', //remove this part
            'items' => 'required|array|min:1', 
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
           ]);
             DB::beginTransaction();

             try {
                $total = 0;
                $sale = Sale::create([
                    // 'user_id' => $validateData['user_id'],
                     'user_id' => Auth::id(),
                    'total_amount' => 0
                ]);   

                foreach ($validateData['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    if ($product->stock < $item['quantity']){
                        // return response()->json([
                        //     'message' => 'Not enough stock for product:' . $product->name
                        // ], 400);
                          throw new \Exception('Not enough stock for product: ' . $product->name);
                    }
                   $subtotal = $product->price * $item['quantity'];
                   $total += $subtotal;

                   $sale->saleItems()->create([
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $product->price,
                    'subtotal'   => $subtotal
                   ]);
                   //decrease the stock column by the quantity sold
                   $product->decrement('stock', $item['quantity']);
                }
                $sale->update(['total_amount' => $total]);
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    //Attach all sale items to this sale, and also attach each item's product 
                    // 'data' => $sale->load('saleItems.product')
                     'data' => new SaleResource($sale->load('user','saleItems.product'))
                ], 201);
             }catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Sale could not be processed',
                    'error' => $e->getMessage()
                ], 400);
             }

       }

}
