<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create redirects to the user registration page.
     *
     * @return View
     */
    public function create(): View
    {
        return view('users.register');
    }

    /**
     * Store saves a new user to the database.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $formFields = $request->validate([
            'username' => ['required', 'min:5', 'max:20', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8', 'max:255'],
            'name' => ['required', 'min:5', 'max:50'],
            'age' => ['required', 'integer'],
        ]);

        $formFields['password'] = bcrypt($formFields['password']);

        User::create($formFields);

        return redirect()
            ->route('login')
            ->with('success', 'Account created successfully! Please login using your new account.');
    }

    /**
     * Login redirects to the user login page.
     *
     * @return View
     */
    public function login(): View
    {
        return view('users.login');
    }

    /**
     * Auth authenticates user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function auth(Request $request): RedirectResponse
    {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'max:255'],
        ]);

        if (auth()->attempt($formFields)) {
            $request->session()->regenerate();

            return redirect()
                ->route('home')
                ->with('success', 'Login successful!');
        }

        return back()
            ->withErrors([
                'email' => 'Incorrect email or password.',
            ])
            ->onlyInput('email');
    }

    /**
     * Logout logs out user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'You have been logged out!');
    }

    /**
     * Profile redirects to the current user profile page.
     *
     * @param Request $request
     * @return View
     */
    public function profile(Request $request): View
    {
        $user = $request->user();

        return view('users.profile', compact('user'));
    }

    /**
     * Edit redirects to the current user edit page.
     *
     * @return View
     */
    public function edit(): View
    {
        $user = auth()->user();

        return view('users.edit', compact('user'));
    }

    /**
     * Update updates the current user.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'email' => ['sometimes', 'required', 'email', 'unique:users,email,' . $user->id],
            'name' => ['sometimes', 'required', 'min:5', 'max:50'],
            'age' => ['sometimes', 'required', 'integer'],
            'amount' => ['sometimes', 'required', 'integer'],
        ]);

        $user = auth()->user();

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('age')) {
            $user->age = $request->age;
        }

        if ($request->has('amount')) {
            $user->increment('balance', $request->amount);
        }

        $user->save();

        return redirect()
            ->route('profile')
            ->with('success', 'Profile updated successfully!');
    }
}
