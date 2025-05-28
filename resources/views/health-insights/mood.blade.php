<!-- Overview content -->
<div class="row">
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">How Your Mood Has Shifted</h5>
                <span> (Last 30 days)</span>
            </div>
            <!-- line graph of mood over time (14 days)-->
            <canvas style="max-height:275px;" id="moodLineChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Mood Distribution</h5>
                <span> (Last 30 days)</span>
            </div>
            <!-- pie chart of mood ratings -->
            <canvas style="max-height:275px;" id="moodDistributionPieChart"></canvas>
        </div>
    </div>
</div>
<div class="row mt-0 mt-md-4">
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Mood Logging Rate</h5>
                <span> (Last 30 days)</span>
            </div>
            <!-- donut chart of mood logging rate (days logged vs not last 30 days)-->
            <canvas style="max-height:275px;" id="moodLoggingRateDonut"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Emotion Distribution</h5>
                <span> (Top 10 over last 30 days)</span>
            </div>
            <!-- pie chart of goals by emotional distribution -->
            <canvas style="max-height:275px;" id="emotionPieChart"></canvas>
        </div>
    </div>
</div>
