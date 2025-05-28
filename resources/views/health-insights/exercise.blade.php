<!-- Overview content -->
<div class="row">
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Daily Exercise Duration</h5>
                <span> (Last 30 days)</span>
            </div>
            <!-- line graph of exercise duration over time (30 days) -->
            <canvas style="max-height:275px;" id="exerciseLineChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Weekly Exercise Duration</h5>
                <span> (Last 4 weeks)</span>
            </div>
            <!-- bar graph of weekly exercise duration (last 4 weeks) -->
            <canvas style="max-height:275px;" id="exerciseBarChart"></canvas>
        </div>
    </div>
</div>
<div class="row mt-0 mt-md-4">
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Exercise Completion Rate</h5>
                <span> (Last 30 days)</span>
            </div>
            <!-- donut chart of exercise completion rate (if exercise is Completed, Missed or Partially)-->
            <canvas style="max-height:275px;" id="exerciseDonutChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Exercise Type Breakdown</h5>
                <span> (Last 30 days)</span>
            </div>
            <!--  Pie chart of exercise types (Running, Boxing, Football) -->
            <canvas style="max-height:275px;" id="exercisePieChart"></canvas>
        </div>
    </div>
</div>
