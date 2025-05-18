<?php

namespace App\Http\Controllers;

use App\Models\SupportResource;
use App\Models\ResourceCategory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $searchResources = $request->input('search_resources');
        $searchUsers = $request->input('search_users');

        $resources = SupportResource::with('category')
            ->when($searchResources, function ($query, $searchResources) {
                $query->where('ResourceTitle', 'like', "%{$searchResources}%")
                    ->orWhereHas('category', function ($query) use ($searchResources) {
                        $query->where('Name', 'like', "%{$searchResources}%");
                    })
                    ->orWhere('Location', 'like', "%{$searchResources}%")
                    ->orWhere('Phone', 'like', "%{$searchResources}%")
                    ->orWhere('Description', 'like', "%{$searchResources}%");
            })
            ->paginate(10, ['*'], 'resources_page')
            ->appends([
                'tab' => 'resources',
                'search_resources' => $searchResources
            ]);

        $users = User::when($searchUsers, function ($query, $searchUsers) {
            $query->where('first_name', 'like', "%{$searchUsers}%")
                ->orWhere('last_name', 'like', "%{$searchUsers}%")
                ->orWhere('role', 'like', "%{$searchUsers}%")
                ->orWhere('email', 'like', "%{$searchUsers}%");
        })
            ->paginate(10, ['*'], 'users_page') // custom page name
            ->appends([
                'tab' => 'users', // stay in users tab
                'search_users' => $searchUsers // keep search term
            ]);

        return view('admin.dashboard', compact('resources', 'users', 'searchResources', 'searchUsers'));
    }

    public function addResourcePage()
    {
        $resource_categories = ResourceCategory::all(); // Fetch all resource categories

        return view('admin.add-resource', compact('resource_categories'));
    }

    public function addResource(Request $request)
    {
        $request->validate([
            'ResourceTitle' => 'required|string|min:5|max:100',
            'ResourceCategory' => 'required|integer|exists:resource_categories,ResourceCategoryID',
            'Phone' => 'required|string|min:7|max:15',
            'Location' => 'required|string|min:5|max:255',
            'Description' => 'required|string|min:10|max:200',
        ]);

        SupportResource::create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Resource added successfully.');
    }

    public function editResourcePage($id)
    {
        // Fetch the resource and its categories
        $resource = SupportResource::findOrFail($id);
        $resource_categories = ResourceCategory::all();

        return view('admin.edit-resource', compact('resource', 'resource_categories'));
    }

    public function updateResource(Request $request, $SupportResourceID)
    {
        // Validation (same as addResource)
        $request->validate([
            'ResourceTitle' => 'required|string|min:5|max:100',
            'ResourceCategory' => 'required|integer|exists:resource_categories,ResourceCategoryID',
            'Phone' => 'required|string|min:7|max:15',
            'Location' => 'required|string|min:5|max:255',
            'Description' => 'required|string|min:10|max:200',
        ]);

        // Find the resource
        $resource = SupportResource::findOrFail($SupportResourceID);

        // Update the resource with validated data
        $resource->update([
            'ResourceTitle' => $request->input('ResourceTitle'),
            'ResourceCategory' => $request->input('ResourceCategory'),
            'Phone' => $request->input('Phone'),
            'Location' => $request->input('Location'),
            'Description' => $request->input('Description'),
        ]);

        // Redirect with success message
        return redirect()->route('admin.dashboard')->with('success', 'Resource updated successfully.');
    }



    public function deleteResource($id)
    {
        $resource = SupportResource::findOrFail($id);
        $resource->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Support Resource deleted successfully.');
    }

    /* User Management */
    public function editUserPage($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $validatedData = $request->validate([
            'first_name' => ['required', 'min:2', 'max:30'],
            'last_name' => ['required', 'min:2', 'max:30'],
            'email' => ['required', 'email', 'min:5', 'max:255'],
            'role' => ['required', Rule::in(['Admin', 'Student'])],
            'password' => ['nullable', 'string', 'min:8', 'max:255', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', 'confirmed']
        ], [
            'first_name.required' => 'First name is required.',
            'first_name.min' => 'First name must be at least 2 characters.',
            'first_name.max' => 'First name cannot exceed 30 characters.',
            'last_name.required' => 'Last name is required.',
            'last_name.min' => 'Last name must be at least 2 characters.',
            'last_name.max' => 'Last name cannot exceed 30 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.min' => 'Email must be at least 5 characters.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'role.required' => 'User role is required.',
            'role.in' => 'Selected role is invalid.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password cannot exceed 255 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'password.regex' => 'Password must be at least 8 characters and include an uppercase letter, a number, and a special character.'
        ]);

        $user = User::findOrFail($id);

        // Fill only the validated fields (excluding password for now)
        $user->fill([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'role' => $validatedData['role'],
        ]);

        // Handle password update only if it's filled
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'users'])
            ->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'users'])
            ->with('success', 'User deleted successfully.');
    }
}
