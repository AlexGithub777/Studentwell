<div class="resource-card card mb-3">
    <div class="card-body">
        <h5 class="card-title">{{ $resource->ResourceTitle }}</h5>
        @if (!empty($resource->Phone))
            <p class="card-text">
                <i class="fas fa-phone me-2"></i>{{ $resource->Phone }}
            </p>
        @endif

        @if (!empty($resource->Location))
            <p class="card-text">
                <i class="fas fa-location-dot me-2"></i>{{ $resource->Location }}
            </p>
        @endif

        @if (!empty($resource->Description))
            <p class="card-text">{{ $resource->Description }}</p>
        @endif
    </div>
</div>
