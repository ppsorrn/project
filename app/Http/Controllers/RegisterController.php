<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class RegisterController extends Controller
{
    const ALLOWED_ROLES = [ 'user' , 'admin'];

    private string $title = 'User';

    function createForm() 
    {
        $users = User::orderBy('email')->get();

        return view('register-form', [
        'roles' => self::ALLOWED_ROLES,
        ]);
    }

    function create(Request $request) {

        try {
            $user = new User();
            $data = $request->getParsedBody();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->role = $data['role'];
            $user->email_verified_at = new \DateTimeImmutable();

            $user->save();

            return redirect()->route('login')
                ->with('status', "User {$user->email} was created.");
        } 

        catch(QueryException $excp) {
            return redirect()->back()->withInput()->withErrors(['error' => $excp->errorInfo[2],]);
        }

    }
}
