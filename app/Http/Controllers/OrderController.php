<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Database\QueryException;
class OrderController extends Controller
{
    private string $title = 'Product';

    function getQuery(): Builder
    {
        return Order::orderBy('number');
    }

    function createForm() 
    {
        $this->authorize('create', Order::class);

        return view('orders.create-form', [
            'title' => "{$this->title} : Create",
            ]);
    }

    function create(Request $request) 
    {
        $this->authorize('create', Order::class);

        try {
            $order = Order::create($request->getParsedBody());
            
            return redirect()->route('order-view');
        } 
        
        catch(QueryException $excp) {
            return redirect()->back()->withInput()->withErrors(['error' => $excp->errorInfo[2],]);
        }

    }
}
