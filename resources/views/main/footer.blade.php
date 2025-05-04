<footer id="footer" class="footer mt-auto py-4 border-top" style="">
    <div class="container">
        <div class="row align-items-start">
            <!-- Left column -->
            <div class="col-md-4 mb-3">
                <div class="d-flex align-items-center mb-2">
                    <img src="{{ asset('assets/images/studentwell-logo.png') }}" alt="Studentwell Logo"
                        style="height: 32px; margin-right: 8px;">
                </div>
                <p class="mb-1 fw-bold">Email:</p>
                <p class="fw-semibold">info@studentwell.com</p>
                <p class="mb-1 fw-bold">Address:</p>
                <p class="fw-semibold">123 Wellbeing Street</p>
            </div>

            <!-- Center column -->
            <div class="col-md-4 text-center mb-3 d-none d-md-block">
                <p class="fw-semibold mb-0">© 2025 Studentwell All rights reserved.</p>
            </div>

            <!-- Right column -->
            <div class="col-md-4 mb-3">
                <h6 class="fw-bold">Links:</h6>
                <ul class="list-unstyled">
                    <li><a href="/" class="text-decoration-none" style="color: var(--secondary-colour);">Home</a>
                    </li>
                    <li><a href="/mood" class="text-decoration-none" style="color: var(--secondary-colour);">Mood
                            Tracking</a></li>
                    <li><a href="/exercise" class="text-decoration-none"
                            style="color: var(--secondary-colour);">Exercise Planning & Logging</a></li>
                    <li><a href="/sleep" class="text-decoration-none" style="color: var(--secondary-colour);">Sleep
                            Logging</a></li>
                    <li><a href="/goals" class="text-decoration-none" style="color: var(--secondary-colour);">Goal
                            Setting</a></li>
                    <li><a href="/insights" class="text-decoration-none" style="color: var(--secondary-colour);">Health
                            Insights</a></li>
                    <li><a href="/resources" class="text-decoration-none"
                            style="color: var(--secondary-colour);">Support Resources</a></li>
                    <li><a href="/forum" class="text-decoration-none" style="color: var(--secondary-colour);">Forum</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Mobile copyright -->
        <div class="text-center d-md-none mt-3">
            <p class="mb-0 fw-semibold">© 2025 Studentwell All rights reserved.</p>
        </div>
    </div>
</footer>
<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sidebar Toggle Script -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebarMenu');
        sidebar.classList.toggle('show');
    }
</script>

</body>

</html>
