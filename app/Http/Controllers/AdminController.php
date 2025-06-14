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
    /**
     * Show the admin dashboard with resources and users.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
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

    /**
     * Show the add resource page.
     *
     * @return \Illuminate\View\View
     */
    public function addResourcePage()
    {
        $resource_categories = ResourceCategory::all(); // Fetch all resource categories

        return view('admin.add-resource', compact('resource_categories'));
    }


    public function addResource(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'ResourceTitle' => ['required', 'string', 'min:5', 'max:100'],
            'ResourceCategory' => ['required', 'integer', 'exists:resource_categories,ResourceCategoryID'],
            'Phone' => ['required', 'regex:/^\+?[0-9]{7,15}$/'],
            'Location' => ['required', 'string', 'min:5', 'max:255'],
            'Description' => ['required', 'string', 'min:10', 'max:200'],
        ], [
            'ResourceTitle.required' => 'Title is required.',
            'ResourceTitle.min' => 'Title must be at least 5 characters.',
            'ResourceTitle.max' => 'Title cannot exceed 100 characters.',

            'ResourceCategory.required' => 'Category is required.',
            'ResourceCategory.exists' => 'Selected category does not exist.',

            'Phone.required' => 'Phone number is required.',
            'Phone.regex' => 'Phone number must contain only numbers and may start with +. Must be 7–15 digits.',

            'Location.required' => 'Location is required.',
            'Location.min' => 'Location must be at least 5 characters.',
            'Location.max' => 'Location cannot exceed 255 characters.',

            'Description.required' => 'Description is required.',
            'Description.min' => 'Description must be at least 10 characters.',
            'Description.max' => 'Description cannot exceed 200 characters.',
        ]);

        SupportResource::create($validatedData);

        return redirect()->route('admin.dashboard')->with('success', 'Resource added successfully.');
    }

    /**
     * Show the edit resource page.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editResourcePage($id)
    {
        // Fetch the resource and its categories
        $resource = SupportResource::findOrFail($id);
        $resource_categories = ResourceCategory::all();

        return view('admin.edit-resource', compact('resource', 'resource_categories'));
    }

    public function updateResource(Request $request, $SupportResourceID)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'ResourceTitle' => ['required', 'string', 'min:5', 'max:100'],
            'ResourceCategory' => ['required', 'integer', 'exists:resource_categories,ResourceCategoryID'],
            'Phone' => ['required', 'regex:/^\+?[0-9]{7,15}$/'],
            'Location' => ['required', 'string', 'min:5', 'max:255'],
            'Description' => ['required', 'string', 'min:10', 'max:200'],
        ], [
            'ResourceTitle.required' => 'Title is required.',
            'ResourceTitle.min' => 'Title must be at least 5 characters.',
            'ResourceTitle.max' => 'Title cannot exceed 100 characters.',

            'ResourceCategory.required' => 'Category is required.',
            'ResourceCategory.exists' => 'Selected category does not exist.',

            'Phone.required' => 'Phone number is required.',
            'Phone.regex' => 'Phone number must contain only numbers and may start with +. Must be 7–15 digits.',

            'Location.required' => 'Location is required.',
            'Location.min' => 'Location must be at least 5 characters.',
            'Location.max' => 'Location cannot exceed 255 characters.',

            'Description.required' => 'Description is required.',
            'Description.min' => 'Description must be at least 10 characters.',
            'Description.max' => 'Description cannot exceed 200 characters.',
        ]);

        // Find the resource by ID and update it
        $resource = SupportResource::findOrFail($SupportResourceID);
        $resource->update($validatedData);

        // Redirect back to the admin dashboard with a success message
        return redirect()->route('admin.dashboard')->with('success', 'Resource updated successfully.');
    }

    /**
     * Delete a support resource.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteResource($id)
    {
        // Validate the ID and find the resource
        $resource = SupportResource::findOrFail($id);
        $resource->delete();

        // Redirect back to the admin dashboard with a success message
        return redirect()->route('admin.dashboard')->with('success', 'Support Resource deleted successfully.');
    }

    /**
     * Show the edit user page.
     *
     * @return \Illuminate\View\View
     */
    public function editUserPage($id)
    {
        // Validate the ID and find the user
        $user = User::findOrFail($id);

        // Pass the user data to the view
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Update user details.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, $id)
    {
        // Validate the request data
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

        // Find the user by ID
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

        // Save the user
        $user->save();

        // Redirect back to the admin dashboard with a success message
        return redirect()
            ->route('admin.dashboard', ['tab' => 'users'])
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete a user.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser($id)
    {
        // Validate the ID and find the user
        $user = User::findOrFail($id);
        $user->delete();

        // Redirect back to the admin dashboard with a success message
        return redirect()
            ->route('admin.dashboard', ['tab' => 'users'])
            ->with('success', 'User deleted successfully.');
    }
}
