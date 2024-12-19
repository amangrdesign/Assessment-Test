<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{


    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $sortBy = $request->input('sortBy', 'created_at'); 

        if (!in_array($sortBy, ['name', 'email', 'created_at'])) {
            return response()->json(['error' => 'Invalid sortBy parameter'], 400);
        }

        $usersQuery = User::query()
            ->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->withCount('orders')
            ->orderBy($sortBy)
            ->paginate(10, ['id', 'email', 'name', 'created_at']); 

        return response()->json([
            'page' => $page,
            'users' => $usersQuery->items() 
        ]);
    }


    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'name' => 'required|string|min:3|max:50',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'name' => $request->name,
        ]);

        Mail::to($user->email)->send(new UserCreatedMail($user));

        Mail::to('admin@example.com')->send(new UserCreatedMail($user));

        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'created_at' => $user->created_at,
        ]);
    }
}
