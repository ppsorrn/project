<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Type;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Shop;
use Illuminate\Database\QueryException;

class ProductController extends SearchableController
{
    const ITEM_PER_PAGE = 5;

    private string $title = 'Product';

    function getQuery(): Builder
    {
        return Product::orderBy('code');
    }

    function filterByTerm(Builder|Relation $query, ?string $term): Builder|Relation
    {
        if (!empty($term)) {
            foreach (\preg_split('/\s+/', \trim($term)) as $word) {
                $query->where(function (Builder $innerQuery) use ($word) {
                    $innerQuery
                        ->where('code', 'LIKE', "%{$word}%")
                        ->orWhere('name', 'LIKE', "%{$word}%")
                        ->orWhereHas('type', function (Builder $catQuery) use ($word) {
                            $catQuery->where('name', 'LIKE', "%{$word}%");
                        });
                });
            }
        }

        return $query;
    }

    function list(Request $request)
    {

        $search = $this->prepareSearch($request->getQueryParams());
        $query = $this->search($search)->withCount('shops');

        session()->put('bookmark.product-view', $request->getUri());

        return view('products.list', [
            'title' => "{$this->title} : List",
            'search' => $search,
            'products' => $query->paginate(5),
        ]);
    }

    function show($productCode)
    {
        $product = $this->find($productCode);

        return view('products.view', [
            'title' => "{$this->title} : View",
            'product' => $product,
        ]);
    }

    function createForm()
    {
        
        $types = Type::orderBy('code')->get();

        return view('products.create-form', [
            'title' => "{$this->title} : Create",
            'types' => $types,
        ]);
    }

    function create(Request $request)
    {


        try {
            $product = new Product();
            $data = $request->getParsedBody();
            $product->fill($data);
            $type = Type::where('code', $data['type'])->firstOrFail();
            $product->type()->associate($type);

            $product->save();

            return redirect()->route('product-list')
                ->with('status', "Product {$product->code} was created.");
        } 
        
        catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'error' => $excp->errorInfo[2],
            ]);
        }
    }

    function updateForm($productCode)
    {
        $this->authorize('update', Product::class);

        $product = $this->find($productCode);
        $types = Type::orderBy('code')->get();

        return view('products.update-form', [
            'title' => "{$this->title} : Update",
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    function update(Request $request, $productCode)
    {
        $this->authorize('update', Product::class);

        try {
            $product = $this->find($productCode);
            $data = $request->getParsedBody();
            $product->fill($data);
            $type = Type::where('code', $data['type'])->firstOrFail();
            $product->type()->associate($type);

            $product->save();

            return redirect()->route('product-view', [
                'product' => $product->code,
                'type' => $type
        ])
            ->with('status', "Product {$product->code} was update.");

        } 
        
        catch(QueryException $excp) {
            return redirect()->back()->withInput()->withErrors(['error' => $excp->errorInfo[2],]);
        }
    }

    function delete($productCode)
    {
        $this->authorize('delete', Product::class);
        
        try {
            $product = $this->find($productCode);
            $product->delete();

            return redirect(session()->get('bookmark.product-view', route('product-list')))
                ->with('status', "Product {$product->code} was deleted.");
        } 
        
        catch(QueryException $excp) {
            return redirect()->back()->withErrors(['error' => $excp->errorInfo[2],]);
        }
    }

    function prepareSearch(array $search): array
    {
        $search = parent::prepareSearch($search);
        $search = \array_merge([
            'minPrice' => null,
            'maxPrice' => null,
        ], $search);

        return $search;
    }

    function filterByPrice(
        Builder|Relation $query,
        ?float $minPrice,
        ?float $maxPrice
    ): Builder|Relation {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    function filterBysearch(Builder|Relation $query, array $search): Builder|Relation
    {
        $query = parent::filterBysearch($query, $search);
        $query = $this->filterByPrice(
            $query,
            ($search['minPrice'] === null) ? null : (float) $search['minPrice'],
            ($search['maxPrice'] === null) ? null : (float) $search['maxPrice'],
        );

        return $query;
    }

    function showShop(
        Request $request,
        ShopController $shopController,
        $productCode
    ) {
        $product = $this->find($productCode);
        $search = $shopController->prepareSearch($request->getQueryParams());
        $query = $shopController->filterBySearch($product->shops(), $search);

        session()->put('bookmark.shop-view', $request->getUri());

        return view('products.view-shop', [
            'title' => "{$this->title} {$product->code} : Shop",
            'product' => $product,
            'search' => $search,
            'shops' => $query->paginate(5),
        ]);
    }

    function addShopForm(
        Request $request,
        ShopController $shopController,
        $productCode
    ) {
        $this->authorize('update', Product::class);

        $product = $this->find($productCode);

        $shopQuery = Shop::orderBy('code')
            ->whereDoesntHave('products', function (Builder $innerQuery) use ($product) {
                return $innerQuery->where('code', $product->code);
            });

        $search = $shopController->prepareSearch($request->getQueryParams());
        $shopQuery = $shopController->filterBySearch($shopQuery, $search);

        session()->put('bookmark.shop-view', $request->getUri());

        return view('products.add-shop-form', [
            'title' => "{$this->title} {$product->code} : Add Shop",
            'search' => $search,
            'product' => $product,
            'shops' => $shopQuery->paginate(5),
        ]);
    }

    function addShop(Request $request, $productCode)
    {
        $this->authorize('update', Product::class);

        try {
            $product = $this->find($productCode);
            $data = $request->getParsedBody();
            $shop = Shop::whereDoesntHave('products', function (Builder $innerQuery) use ($product) {
            return $innerQuery->where('code', $product->code);
                })->where('code', $data['shop'])->firstOrFail();
            $product->shops()->attach($shop);

            return redirect()->back()
                ->with('status', "Shop {$shop->code} was add to Product {$product->code}.");
        } 
        
        catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'error' => $excp->errorInfo[2],
            ]);
        }
    }

    function removeShop($productCode, $shopCode)
    {
        $this->authorize('update', Product::class);

        try {
            $product = $this->find($productCode);
            $shop = $product->shops()->where('code', $shopCode)->firstOrFail();
            $product->shops()->detach($shop);

            return redirect()->back()
                ->with('status', "Shop {$shop->code} was removed from Product {$product->code}.");
        }
        
        catch(QueryException $excp) {
            return redirect()->back()->withErrors(['error' => $excp->errorInfo[2],]);
        }
    }
}
