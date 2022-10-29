<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    const ALLOWED_ROLES = [ 'user' , 'admin'];
    const ITEM_PER_PAGE = 5;

    private string $title = 'User';

    function getQuery() : Builder {
        return User::orderBy('email');
    }

    function filterByTerm(Builder|Relation $query, ?string $term) : Builder|Relation  {
        if(!empty($term)) {
            foreach(\preg_split('/\s+/', \trim($term)) as $word) {
                $query->where(function(Builder $innerQuery) use ($word) {
                $innerQuery
                ->where('email', 'LIKE', "%{$word}%")
                ->orWhere('name', 'LIKE', "%{$word}%")
                ->orWhere('role', 'LIKE', "%{$word}%");
        
                });
            }
        }
        
        return $query;
    }

    function list(Request $request) {
        $this->authorize('view', User::class);

        $search = $this->prepareSearch($request->getQueryParams());
        $query = $this->search($search);

        return view('users.list', [
            'title' => "{$this->title} : List",
            'search' => $search,
            'users' => $query->paginate(5),
        ]);
    }

    function show($email) {
        $user = $this->find($email);

        return view('users.view', [
            'title'=>"{$this->title} : View",
            'user'=>$user,
        ]);
    }

    function createForm() {
        $this->authorize('create', User::class);

        $users = User::orderBy('email')->get();

        return view('users.create-form', [
        'title' => "{$this->title} : Create",
        'roles' => self::ALLOWED_ROLES,
        ]);
    }
        
    function create(Request $request) {
        $this->authorize('create', User::class);

        try {
            $user = new User();
            $data = $request->getParsedBody();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->role = $data['role'];
            $user->email_verified_at = new \DateTimeImmutable();

            $user->save();
        
            return redirect()->route('user-list')
                ->with('status', "User {$user->email} was created.");
        } 
        
        catch(QueryException $excp) {
            return redirect()->back()->withInput()->withErrors(['error' => $excp->errorInfo[2],]);
        }
        
    }

    function updateForm($email)
    {
        $user = $this->find($email);
        $this->authorize('update', User::class);

        return view('users.update-form', [
            'title' => "{$this->title} : Update",
            'user' => $user,

        ]);
    }

    function update(Request $request, $email)
    {
        $this->authorize('update', User::class);

        try {
             $user = $this->find($email);
            $data = $request->getParsedBody();
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->fill($data);
            $user->role = $data['role'];
            $user->save();

            return redirect()->route('user-view', ['user' => $user->email])
                ->with('status', "User {$user->email} was updated.");
        } 
        
        catch(QueryException $excp) {
            return redirect()->back()->withInput()->withErrors(['error' => $excp->errorInfo[2],]);
        }

    }

    function delete($email){
        $this->authorize('delete', User::class);

        try {
            $user = $this->find($email);
            $user->delete();

            return redirect()->route('user-list')
                ->with('status', "User {$user->email} was deleted.");
        }
        
        catch(QueryException $excp) {
            return redirect()->back()->withErrors(['error' => $excp->errorInfo[2],]);
        }
       
    }

    function find(string $email)
    {
        return $this->getQuery()->where('email', $email)->firstOrFail();
    }
}
