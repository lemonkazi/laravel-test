<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserController extends Controller
{

    /**
     * Display a listing of users.
     *
     * Gets a list of users.
     *
     * @queryParam page_size int Size per page. Defaults to 20. Example: 20
     * @queryParam page int Page to view. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $users = User::query()->paginate($request->page_size ?? 20);

        return UserResource::collection($users);
    }

    /**
     * Display the specified user.
     *
     * @urlParam id int required User ID
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     *
     * @param \App\Models\User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    // Show Register/Create Form
    public function create() {
        return view('users.register');
    }

    // Create New User
    public function store(Request $request, UserRepository $repository) {

        if ($request->is('api/*')) {
            // if the request is an API request
            $created = $repository->create($request->only([
                'name',
                'email',
            ]));

            return new UserResource($created);
        } else {
            $formFields = $request->validate([
                'name' => ['required', 'min:3'],
                'email' => ['required', 'email', Rule::unique('users', 'email')],
                'password' => 'required|confirmed|min:6'
            ]);

            // Hash Password
            $formFields['password'] = bcrypt($formFields['password']);

            // Create User
            $user = User::create($formFields);

            // Login
            auth()->login($user);
            // if the request is a web request
            return redirect('/')->with('message', 'User created and logged in');
        }


    }

    // Logout User
    public function logout(Request $request) {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out!');

    }

    // Show Login Form
    public function login() {
        return view('users.login');
    }

    // Authenticate User
    public function authenticate(Request $request) {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        if(auth()->attempt($formFields)) {
            $request->session()->regenerate();

            return redirect('/')->with('message', 'You are now logged in!');
        }

        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }


    /**
     * Update the specified user in storage.
     * @bodyParam name string Name of the user. Example: John Doe
     * @bodyParam email string Email of the user. Example: doe@doe.com
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return UserResource | JsonResponse
     */
    public function update(Request $request, User $user, UserRepository $repository)
    {
        $user = $repository->update($user, $request->only([
            'name',
            'email',
        ]));

        return new UserResource($user);
    }

    /**
     * Remove the specified user from storage.
     * @response 200 {
        "data": "success"
     * }
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user, UserRepository $repository)
    {
        $deleted = $repository->forceDelete($user);
        return new \Illuminate\Http\JsonResponse([
            'data' => 'success',
        ]);
    }
}

