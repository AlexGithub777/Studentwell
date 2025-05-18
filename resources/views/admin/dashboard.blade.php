@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Admin Dashboard";
</script>
@if (session('success'))
    <div class="alert alert-success" id="alert-success">
        {{ session('success') }}
    </div>
@elseif (session('error'))
    <div class="alert alert-danger" id="alert-success">
        {{ session('error') }}
    </div>
@endif
<div class="content-area">
    <div class="container">
        <!-- Page Title, Subtitle, and Add Resource Button -->
        <div class="row mb-4">
            <div class="col d-flex justify-content-between align-items-center mt-4">
                <div>
                    <h1 class="page-title mb-1">Admin Dashboard</h1>
                </div>
                <a href="{{ route('admin.add.resource') }}" class="btn add-btn text-white" id="add-resource-btn"
                    style="background-color: var(--secondary-colour); display: none;">
                    <i class="fas fa-plus me-1 fw-bold"></i>Add Resource
                </a>
            </div>
        </div>

        <!-- Tabs (Support Resources, User management) -->
        <ul class="nav nav-pills mb-4" id="adminTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ request('tab', 'resources') === 'resources' ? 'active' : '' }}" id="resources-tab"
                    data-bs-toggle="pill" href="#resources" role="tab" aria-controls="resources"
                    aria-selected="true">
                    Support Resources
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ request('tab') === 'users' ? 'active' : '' }}" id="users-tab"
                    data-bs-toggle="pill" href="#users" role="tab" aria-controls="users" aria-selected="false">
                    User Management
                </a>
            </li>
        </ul>



        <!-- Tab Content -->
        <div class="tab-content" id="adminTabContent">
            <div class="tab-pane fade {{ request('tab', 'resources') === 'resources' ? 'show active' : '' }}"
                id="resources" role="tabpanel">
                <!-- Support Resources content goes here -->

                <!-- Resource Search -->
                <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4">
                    <input type="hidden" name="tab" value="resources">
                    <div class="input-group">
                        <input id="search-box" type="text" name="search_resources" class="form-control"
                            placeholder="Search support resources..." value="{{ request('search_resources') }}">
                        <button id="search-btn" class="btn btn-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Table for Support Resources -->
                @if ($resources->isEmpty())
                    <div class="alert alert-info">
                        No resources found.
                    </div>
                @else
                    <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Category</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Location</th>
                                <th scope="col">Description</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($resources as $resource)
                                <tr>
                                    <td data-label="Title">{{ $resource->ResourceTitle }}</td>
                                    <td data-label="Category">
                                        <span class="badge rounded-pill category-badge">
                                            {{ $resource->category->Name }}
                                        </span>
                                    </td>
                                    <td data-label="Phone">{{ $resource->Phone }}</td>
                                    <td data-label="Location">{{ $resource->Location }}</td>
                                    <td data-label="Description">{{ $resource->Description }}</td>
                                    <td class="align-middle text-center" data-label="Actions">
                                        <div class="d-flex justify-content-center align-items-center h-100 gap-2">
                                            <a href="{{ route('admin.edit.resource', $resource->SupportResourceID) }}"
                                                class="btn btn btn-primary d-flex align-items-center justify-content-center"
                                                title="Edit">
                                                <i class="fas fa-pen text-white"></i>
                                            </a>

                                            <form
                                                action="{{ route('admin.delete.resource', $resource->SupportResourceID) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this support resource?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn btn-danger d-flex align-items-center justify-content-center"
                                                    title="Delete">
                                                    <i class="fas fa-trash text-white"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <div class="mt-4">
                    {{ $resources->links('pagination::bootstrap-5') }}
                </div>
            </div>
            <div class="tab-pane fade {{ request('tab') === 'users' ? 'show active' : '' }}" id="users"
                role="tabpanel">
                <!-- User Management content goes here -->

                <!-- User Search -->
                <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4">
                    <input type="hidden" name="tab" value="users">
                    <div class="input-group">
                        <input type="text" id="search-box" name="search_users" class="form-control"
                            placeholder="Search users..." value="{{ request('search_users') }}">
                        <button id="search-btn" class="btn btn-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>


                <!-- Table for User Mangement -->
                @if ($users->isEmpty())
                    <div class="alert alert-info">
                        No users found.
                    </div>
                @else
                    <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Role</th>
                                <th scope="col">Email</th>
                                <th scope="col">Join Date and Time</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td data-label="First Name">{{ $user->first_name }}</td>
                                    <td data-label="Last Name">{{ $user->last_name }}</td>
                                    <td data-label="Role">
                                        <span class="badge rounded-pill category-badge">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td data-label="Email">{{ $user->email }}</td>
                                    <td data-label="Join Date and Time">
                                        {{-- format join datetime like: Wednesday, 12 March 2025, 8:22 AM --}}
                                        {{ $user->created_at->format('l, d F Y, g:i A') }}
                                    </td>
                                    <td class="align-middle text-center" data-label="Actions">
                                        <div class="d-flex justify-content-center align-items-center h-100 gap-2">
                                            <a href="{{ route('admin.edit.user', $user->id) }}"
                                                class="btn btn btn-primary d-flex align-items-center justify-content-center"
                                                title="Edit">
                                                <i class="fas fa-pen text-white"></i>
                                            </a>

                                            <form action="{{ route('admin.delete.user', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn btn-danger d-flex align-items-center justify-content-center"
                                                    title="Delete">
                                                    <i class="fas fa-trash text-white"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <div class="mt-4">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@include('main.footer')
