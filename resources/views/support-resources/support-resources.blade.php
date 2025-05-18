@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Support Resources";
</script>
<div class="content-area py-4">
    <div class="container">
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col d-flex justify-content-between align-items-center mt-4">
                <div>
                    <h1 class="page-title mb-1">Support Resources</h1>
                </div>
            </div>
        </div>

        <!-- may add a search bar here in the future -->
        @php
            $mentalHealth = $resources->filter(fn($r) => $r->category && $r->category->Name === 'Mental Health');
            $physicalHealth = $resources->filter(fn($r) => $r->category && $r->category->Name === 'Physical Health');
            $academicSupport = $resources->filter(fn($r) => $r->category && $r->category->Name === 'Academic Support');
            $emergency = $resources->filter(fn($r) => $r->category && $r->category->Name === 'Emergency');
        @endphp

        <div class="row gx-3 mb-4">
            <div class="col-md-3">
                <h2 class="resource-subtitle"><i class="fas fa-brain me-2"></i>Mental Health</h2>
                @forelse ($mentalHealth as $resource)
                    @include('support-resources.resource-card', ['resource' => $resource])
                @empty
                    @include('support-resources.no-resources-card', ['category' => 'Mental Health'])
                @endforelse
            </div>

            <div class="col-md-3 mt-md-0 mt-3">
                <h2 class="resource-subtitle"><i class="fas fa-dumbbell me-2"></i>Physical Health</h2>
                @forelse ($physicalHealth as $resource)
                    @include('support-resources.resource-card', ['resource' => $resource])
                @empty
                    @include('support-resources.no-resources-card', ['category' => 'Physical Health'])
                @endforelse
            </div>

            <div class="col-md-3 mt-md-0 mt-3">
                <h2 class="resource-subtitle"><i class="fas fa-book me-2"></i>Academic Support</h2>
                @forelse ($academicSupport as $resource)
                    @include('support-resources.resource-card', ['resource' => $resource])
                @empty
                    @include('support-resources.no-resources-card', ['category' => 'Academic Support'])
                @endforelse
            </div>

            <div class="col-md-3 mt-md-0 mt-3">
                <h2 class="resource-subtitle"><i class="fas fa-suitcase-medical me-2"></i>Emergency</h2>
                @forelse ($emergency as $resource)
                    @include('support-resources.resource-card', ['resource' => $resource])
                @empty
                    @include('support-resources.no-resources-card', ['category' => 'Emergency'])
                @endforelse
            </div>
        </div>
    </div>
</div>
@include('main.footer')
