<!-- Overview content -->
<div class="row">
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Your Weekly Wellness Activity</h5>
                <span> (log counts across features)</span>
            </div>
            <!-- bar graph of logs across features (mood, exercise, sleep, goals) -->
            <canvas style="max-height:275px;" id="logsBarChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">How Consistent Your Sleep Has Been</h5>
                <span> (daily hours of sleep)</span>
            </div>
            <!-- donut graph of sleep consistency (daily hours of sleep) need to change -->
            <canvas style="max-height:275px;" id="sleepDonutChart"></canvas>
        </div>
    </div>
</div>
<div class="row mt-0 mt-md-4">
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">How Your Mood Has Shifted</h5>
                <span> (14-day trend)</span>
            </div>
            <!-- line graph of mood over time (14 days)-->
            <canvas style="max-height:275px;" id="moodLineChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Where Your Goals Are Focused</h5>
                <span> (goals by category)</span>
            </div>
            <!-- pie chart of goals by category (exercise, sleep, mood) -->
            <canvas style="max-height:275px;" id="goalsPieChart"></canvas>
        </div>
    </div>
</div>
