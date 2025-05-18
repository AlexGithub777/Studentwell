@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Home";
</script>
<div class="content-area m-0 p-0">
    @if (session('success'))
        <div class="alert alert-success" id="alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger" id="alert-success">
            {{ session('error') }}
        </div>
    @endif
    <!-- BODY SECTION -->
    <section class="wellness-section">
        <div class="container">
            <h1>Your Complete Wellness Companion</h1>
            <p id="home-subtite" class="lead mb-5">
                Track your physical and mental health, set goals, and access resources to improve your overall
                wellbeing.
            </p>

            <div class="row g-4">
                <div class="col-md-4">
                    <a href="/mood" class="feature-link text-decoration-none">
                        <div class="feature-box h-100">
                            <div class="icon-circle"><i class="fas fa-brain"></i></div>
                            <div class="feature-title">Mood Tracking</div>
                            <p class="feature-desc">Log your daily mood and emotions with guided reflection prompts.</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="/exercise" class="feature-link text-decoration-none">
                        <div class="feature-box h-100">
                            <div class="icon-circle"><i class="fas fa-dumbbell"></i></div>
                            <div class="feature-title">Exercise Planning & Logging</div>
                            <p class="feature-desc">Schedule workouts, log completion, and monitor your fitness
                                progress.</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="/sleep" class="feature-link text-decoration-none">
                        <div class="feature-box h-100">
                            <div class="icon-circle"><i class="fas fa-moon"></i></div>
                            <div class="feature-title">Sleep Logging</div>
                            <p class="feature-desc">Record your sleep hours and quality to improve your rest.</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="/goals" class="feature-link text-decoration-none">
                        <div class="feature-box h-100">
                            <div class="icon-circle"><i class="fas fa-bullseye"></i></div>
                            <div class="feature-title">Goal Setting</div>
                            <p class="feature-desc">Set and track your physical and mental health goals effectively.</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="/health-insights" class="feature-link text-decoration-none">
                        <div class="feature-box h-100">
                            <div class="icon-circle"><i class="fas fa-chart-line"></i></div>
                            <div class="feature-title">Health Insights</div>
                            <p class="feature-desc">Visualise your progress with personalised graphs and reports.</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="/support-resources" class="feature-link text-decoration-none">
                        <div class="feature-box h-100">
                            <div class="icon-circle"><i class="fas fa-address-card"></i></div>
                            <div class="feature-title">Support Resources</div>
                            <p class="feature-desc">Access health resources and connect with wellness services.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    setTimeout(() => {
        const alert = document.getElementById('alert-success');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500); // fully remove after fade out
        }
    }, 10000); // 10000 ms = 10 seconds
</script>

@include('main.footer')
