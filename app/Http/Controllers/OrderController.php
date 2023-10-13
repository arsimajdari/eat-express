<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // if ($removedItems) {
        //     return response('Some items were removed from your shopping cart because they have been changed or are no longer available', 400);
        // }   //TODO : removedItems is always true because database seeders make some products that are not available

        // Create the order
        $order = Order::create([
            'number' => (new Order())->generateUniqueCode(),
            'user_id' => $user->id,
            'comment' => $validated['comment'],
        ]);

        // Check if the user provided a shipping address
        if (!empty($validated['address'])) {
            // Create a new shipping address associated with the order
            $address = ShippingAddress::create([
                'user_id' => $user->id,
                'order_id' => $order->id, // Associate the address with the order
                'number' => (new ShippingAddress())->generateUniqueCode(),
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'phone' => $validated['phone'],
                'city' => $validated['city'],
                'zip' => $validated['zip'],
                'address' => $validated['address'],
                'country' => $validated['country']
            ]);
        } elseif (!empty($validated['user-address'])) {
            // Use an existing shipping address associated with the user
            $address = ShippingAddress::where('number', $validated['user-address'])
                ->where('user_id', $user->id)
                ->firstOrFail();

            // Associate the existing address with the current order
            $address->order_id = $order->id;
            $address->save();
        }

        // Create order items
        foreach ($user->items as $item) {
            $order->items()->create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        // Clear the user's cart
        $user->items()->delete();

        return response()->json([
            "message" => "Order created successfully",
            "order_number" => $order->number,
        ], 200);
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
