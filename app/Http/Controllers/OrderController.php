<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $user = Auth::user();
        $user->load('items.product');

        $validated = $request->validate([
            'firstname' => ['nullable', 'string'],
            'lastname' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'zip' => ['nullable', 'string'],
            'country' => ['nullable', 'string'],
            'save' => ['nullable'],
            'comment' => ['nullable', 'string'],
            'user-address' => ['nullable', 'string', 'exists:shipping_addresses,number'],
        ]);

        // Safety checks
        $removedItems = 0;

        foreach ($user->items as $item) {
            // Check if product is still available
            if (!$item->product->available) {
                $item->delete();
                $removedItems++;
                break;
            }

            // Check if discount or price has not changed
            if ($item->product->discount) {
                if ($item->price != $item->product->discount) {
                    $item->delete();
                    $removedItems++;
                }
            } else {
                if ($item->price != $item->product->price) {
                    $item->delete();
                    $removedItems++;
                }
            }
        }

        if ($removedItems) {
            return response('Some items were removed from your shopping cart because they have been changed or are no longer available', 400);
        }

        if (!empty($validated['user-address'])) {
            $address = ShippingAddress::where('number', $validated['user-address'])->firstOrFail();
        } else {
            $address = ShippingAddress::create(array_merge($validated, [
                'number' => (new ShippingAddress())->generateUniqueCode()
            ]));
        }

        $order = Order::create([
            'number' => (new Order())->generateUniqueCode(),
            'user_id' => $user->id,
            'comment' => $validated['comment'],
        ]);

        foreach ($user->items as $item) {
            $order->items()->create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        $orderAddress = $address->replicate()->fill([
            'order_id' => $order->id,
            'number' => $address->generateUniqueCode(),
        ]);

        $orderAddress->save();

        if ($validated['save'] ?? null != null) {
            $address->user_id = $request->user()->id;
            $address->save();
        }

        // Delete items in the customers cart
        $user->items()->delete();

        // Send emails

        // Mail::to($user)->queue(new OrderProcessed($order));


        // Mail::to(User::has('roles')->get())->queue(new NewOrder($order));



        return response('Order created successfully', 200)->json([
            "order_number" => $order->number,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
        $user = Auth::user();

        abort_unless($order->user()->is($user), 404);

        $order->load('items.product');

        return response()->json([
            "order" => $order,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
