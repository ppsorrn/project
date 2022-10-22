<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Type;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\QueryException;

class TypeController extends Controller
{
    const ITEM_PER_PAGE = 5;

    private string $title = 'Type';

    function getQuery() : Builder {
        return Type::orderBy('code');
    }

    function list(Request $request) {

        $search = $this->prepareSearch($request->getQueryParams());
        $query = $this->search($search)->withCount('products');

        session()->put('bookmark.type-view', $request->getUri());

        return view('types.list', [
            'title' => "{$this->title} : List",
            'search' => $search,
            'types' => $query->paginate(5),
        ]);
    }

    function show($typeCode) {
        $type = $this->find($typeCode);

        return view('types.view', [
            'title'=>"{$this->title} : View",
            'type'=>$type,
        ]);
    }

    function createForm() {
        $this->authorize('create', Type::class);

        return view('types.create-form', [
        'title' => "{$this->title} : Create",
        ]);
    }
        
    function create(Request $request) {
        $this->authorize('create', Type::class);

        try {
            $type = Type::create($request->getParsedBody());
        
            return redirect()->route('type-list')
                ->with('status', "type {$type->code} was created.");
        } 
        
        catch(QueryException $excp) {
            return redirect()->back()->withInput()->withErrors(['error' => $excp->errorInfo[2],]);
        }
         
    }

    function updateForm($typeCode) {
        $this->authorize('update', Type::class);

        $type = $this->find($typeCode);
        
        return view('types.update-form', [
        'title' => "{$this->title} : Update",
        'type' => $type,
        ]);
    }

    function update(Request $request, $typeCode) {
        $this->authorize('update', Type::class);

        try {
             $type = $this->find($typeCode);
            $type->fill($request->getParsedBody());
            $type->save();
        
            return redirect()->route('types.view', [
            'type' => $type->code,])
                ->with('status', "Type {$type->code} was updated.");

        } 
        
        catch(QueryException $excp) {
            return redirect()->back()->withInput()->withErrors(['error' => $excp->errorInfo[2],]);
        }

    }

    function delete($typeCode) {
        $type = $this->find($typeCode);

        try {
            $this->authorize('delete', $type);

            $type->delete();
        
            return redirect()->route('type-list')
                ->with('status', "Type {$type->code} was deleted.");
        } 
        
        catch(QueryException $excp) {
            return redirect()->back()->withErrors(['error' => $excp->errorInfo[2],]);
        }

    }

    function prepareSearch(array $search) : array {
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
        ) : Builder|Relation {
        if($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }
        
        if($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }
        
        return $query;
    }

    function filterBysearch(Builder|Relation $query, array $search) : Builder|Relation {
        $query = parent::filterBysearch($query, $search);
        $query = $this->filterByPrice($query,
        ($search['minPrice'] === null)? null : (float) $search['minPrice'],
        ($search['maxPrice'] === null)? null : (float) $search['maxPrice'],
        );
        
        return $query;
    }

    function showProduct(
        Request $request,
        TypeController $typeController,
        $typeCode
        ) {
        $type = $this->find($typeCode);
        $search = $typeController->prepareSearch($request->getQueryParams());
        $query = $typeController->filterBySearch($type->products(), $search);

        session()->put('bookmark.product-view', $request->getUri());
        
        return view('types.view-product', [
        'title' => "{$this->title} {$type->code} : Product",
        'type' => $type,
        'search' => $search,
        'products' => $query->paginate(5),
        ]);
    }
    
    function addProductForm(
        Request $request,
        ProductController $productController,
        $categoryCode) 
        {
            $this->authorize('update', Type::class);

            try {
                $type = $this->find($typeCode);

                $productQuery = Product::orderBy('code')
                ->whereDoesntHave('type', function (Builder $innerQuery) use ($type) { 
                    return $innerQuery -> where('code', $type->code); 
                });

                $search = $productController->prepareSearch($request->getQueryParams());
                $productQuery = $productController->filterBySearch($productQuery, $search);

                session()->put('bookmark.product-view', $request->getUri());

                return view('types.add-product-form', [
                    'title' => "{$this->title} {$type->code} : Add Product",
                    'search' => $search,
                    'type' => $type,
                    'products' => $productQuery->paginate(5),
                ]);

            } 
            
            catch(QueryException $excp) {
                return redirect()->back()->withInput()->withErrors(['error' => $excp->errorInfo[2],]);
            }
            
    }
    
    function addProduct(Request $request, $typeCode) {
        $this->authorize('update', Type::class);

        $type = $this->find($typeCode);
        $data = $request->getParsedBody();
        $product = Product::whereDoesntHave('type', function(Builder $innerQuery) use ($type) {
            return $innerQuery->where('code', $type->code);
                })->where('code', $data['product'])->firstOrFail();
        $type->products()->save($product);
        
        return redirect()->back()
            ->with('status', "Product {$product->code} was added to Type {$type->code}.");
    }
}
