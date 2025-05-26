<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentWell</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">
</head>

<body>
    <div class="container-fluid p-0">
        <!-- Header -->
        <header class="main-header d-flex align-items-center justify-content-between border-bottom">
            <!-- Hamburger -->
            <button class="navbar-toggler" type="button" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Centered logo -->
            <div class="navbar-brand-container">
                <!-- Link to home ("/") -->
                <a href="/">
                    <img src="{{ asset('assets/images/studentwell-logo.png') }}" alt="StudentWell Logo"
                        style="height: 30px;" class="navbar-brand img-fluid">
                </a>
            </div>

            <!-- Right side -->
            @guest
                <div class="d-flex align-items-center">
                    <!-- Sign In Button -->
                    <a href="{{ route('login') }}" class="btn add-btn me-4 text-white"
                        style="background-color: var(--secondary-colour);">
                        Sign In
                    </a>

                    <!-- Sign Up Button -->
                    <a href="{{ route('register') }}" class="btn add-btn me-2 text-white"
                        style="background-color: var(--secondary-colour);">
                        Sign Up
                    </a>
                </div>
            @endguest
        </header>
    </div>
