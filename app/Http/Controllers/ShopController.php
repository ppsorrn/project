<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\QueryException;

class ShopController extends SearchableController
{
    const ITEM_PER_PAGE = 5;

    private string $title = 'Shop';
    
    function getQuery() : Builder {
        return Shop::orderBy('code');
    }
    function list(Request $request) {
        $search = $this->prepareSearch($request->getQueryParams());
        $query = $this->search($search)->withCount('products');

        session()->put('bookmark.shop-view', $request->getUri());

        return view('shops.list', [
            'title' => "{$this->title} : List",
            'search' => $search,
            'shops' => $query->paginate(5),
        ]);
    }

    function show($shopCode) {
        $shop = $this->find($shopCode);

        return view('shops.view', [
            'title'=>"{$this->title} : View",
            'shop'=>$shop,
        ]);
    }

    function filterByTerm(Builder|Relation $query, ?string $term) : Builder|Relation {
        if(!empty($term)) {
            foreach(\preg_split('/\s+/', \trim($term)) as $word) {
                $query->where(function(Builder $innerQuery) use ($word) {
                $innerQuery
                ->where('code', 'LIKE', "%{$word}%")
                ->orWhere('name', 'LIKE', "%{$word}%")
                ->orWhere('address', 'LIKE', "%{$word}%");
                });
            }
        }
        
        return $query;
        }

    function createForm() {
        $this->authorize('create', Shop::class);

        return view('shops.create-form', [
            'title' => "{$this->title} : Create",
            ]);
        }
            
    function create(Request $request) {
        $this->authorize('create', Shop::class);

        try {
            $shop = Shop::create($request->getParsedBody());
            
            return redirect()->route('shop-list')
                ->with('status', "Shop {$shop->code} was created.");
        } 
        
        catch(QueryException $excp) {
            return redirect()->back()->withInput()->withErrors(['error' => $excp->errorInfo[2],]);
        }

    }
    
    function updateForm($shopCode) {
        $this->authorize('update', Shop::class);

        $shop = $this->find($shopCode);
            
        return view('shops.update-form', [
        'title' => "{$this->title} : Update",
        'shop' => $shop,
        ]);
    }
    
    function update(Request $request, $shopCode) {
        $this->authorize('update', Shop::class);

        try {
            $shop = $this->find($shopCode);
            $shop->fill($request->getParsedBody());
            $shop->save();
            
            return redirect()->route('shop-view', [
                'shop' => $shop->code,])
                ->with('status', "Shop {$shop->code} was updated.");
        } 
        
        catch(QueryException $excp) {
            return redirect()->back()->withInput()->withErrors(['error' => $excp->errorInfo[2],]);
        }

    }
    
    function delete($shopCode) {
        $this->authorize('delete', Shop::class);

        try {
            $shop = $this->find($shopCode);
            $shop->delete();
            
            return redirect(session()->get('bookmark.shop-view', route('shop-list')))
                ->with('status', "Shop {$shop->code} was deleted.");
        }
        
        catch(QueryException $excp) {
            return redirect()->back()->withErrors(['error' => $excp->errorInfo[2],]);
        }
        
    }

    function showProduct(
        Request $request,
        ProductController $productController,
        $shopCode
        ) {
        $shop = $this->find($shopCode);
        $search = $productController->prepareSearch($request->getQueryParams());
        $query = $productController->filterBySearch($shop->products(), $search);

        session()->put('bookmark.product-view', $request->getUri());
        
        return view('shops.view-product', [
        'title' => "{$this->title} {$shop->code} : Product",
        'shop' => $shop,
        'search' => $search,
        'products' => $query->paginate(5),
        ]);
    }
    
    function addProductForm(
        Request $request,
        ProductController $productController,
        $shopCode) 
        {
            $this->authorize('update', Shop::class);

            $shop = $this->find($shopCode);

            $productQuery = Product::orderBy('code')
                ->whereDoesntHave('shops', function (Builder $innerQuery) use ($shop) { 
                    return $innerQuery -> where('code', $shop->code); 
                })
            ;

            $search = $productController->prepareSearch($request->getQueryParams());
            $productQuery = $productController->filterBySearch($productQuery, $search);

            session()->put('bookmark.product-view', $request->getUri());

            return view('shops.add-product-form', [
                'title' => "{$this->title} {$shop->code} : Add Product",
                'search' => $search,
                'shop' => $shop,
                'products' => $productQuery->paginate(5),
            ]);
    }
    
    function addProduct(Request $request, $shopCode) {
        $this->authorize('update', Shop::class);

        try {
            $shop = $this->find($shopCode);
            $data = $request->getParsedBody();
            $product = Product::whereDoesntHave('shops', function(Builder $innerQuery) use ($shop) {
            return $innerQuery->where('code', $shop->code);
                })->where('code', $data['product'])->firstOrFail();
            $shop->products()->attach($product);
        
            return redirect()->back()
                ->with('status', "Product {$product->code} was added to Shop {$shop->code}.");
        } 
        
        catch(QueryException $excp) {
            return redirect()->back()->withInput()->withErrors(['error' => $excp->errorInfo[2],]);
        }

    }

    function removeProduct($shopCode, $productCode) {
        $this->authorize('update', Shop::class);

        try {
            $shop = $this->find($shopCode);
            $product = $shop->products()->where('code', $productCode)->firstOrFail();
            $shop->products()->detach($product);
        
            return redirect()->back()
                ->with('status', "Product {$product->code} was removed to Shop {$shop->code}.");
        }
        
        catch(QueryException $excp) {
            return redirect()->back()->withErrors(['error' => $excp->errorInfo[2],]);
        } 
        
    }
}
