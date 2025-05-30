setTimeout(() => {
    const alert = document.getElementById("alert-success");
    if (alert) {
        alert.style.transition = "opacity 0.5s ease-out";
        alert.style.opacity = "0";
        setTimeout(() => alert.remove(), 500); // fully remove after fade out
    }
}, 10000); // 10000 ms = 10 seconds

// Sidebar toggle functionality
function toggleSidebar() {
    const sidebar = document.getElementById("sidebarMenu");
    const backdrop = document.getElementById("sidebar-backdrop");

    const isOpen = sidebar.classList.toggle("show");
    backdrop.classList.toggle("show", isOpen);

    if (isOpen) {
        document.addEventListener("click", handleClickOutside);
    } else {
        document.removeEventListener("click", handleClickOutside);
    }
}

function handleClickOutside(event) {
    const sidebar = document.getElementById("sidebarMenu");
    const toggleButton = document.querySelector(".navbar-toggler");
    const backdrop = document.getElementById("sidebar-backdrop");

    if (
        !sidebar.contains(event.target) &&
        !toggleButton.contains(event.target)
    ) {
        sidebar.classList.remove("show");
        backdrop.classList.remove("show");
        document.removeEventListener("click", handleClickOutside);
    }
}

// Admin js

document.addEventListener("DOMContentLoaded", function () {
    const addResourceBtn = document.getElementById("add-resource-btn");

    if (!addResourceBtn) return;

    const toggleButtonVisibility = (targetId) => {
        if (targetId === "#resources") {
            addResourceBtn.style.display = "";
        } else {
            addResourceBtn.style.display = "none";
        }
    };

    // Handle initial load
    const activeTab = document.querySelector("#adminTabs .nav-link.active");
    if (activeTab) {
        toggleButtonVisibility(activeTab.getAttribute("href"));
    }

    // Handle tab switching
    document.querySelectorAll("#adminTabs .nav-link").forEach((tab) => {
        tab.addEventListener("shown.bs.tab", function (event) {
            const targetId = event.target.getAttribute("href");
            toggleButtonVisibility(targetId);
        });
    });
});

// nvert emoji to string
function emojiToString(emoji) {
    switch (emoji) {
        case "😄":
            return "Great";
        case "😊":
            return "Good";
        case "😐":
            return "Okay";
        case "😔":
            return "Down";
        case "😢":
            return "Sad";
        default:
            return "?";
    }
}

if (
    window.location.pathname === "/sleep/log-sleep" ||
    window.location.pathname === "/sleep/log-sleep/" ||
    window.location.pathname === "/sleep/edit-sleep" ||
    window.location.pathname === "/sleep/edit-sleep/"
) {
    const bedTimeInput = document.getElementById("BedTime");
    const wakeTimeInput = document.getElementById("WakeTime");
    const durationDisplayDiv = document.getElementById(
        "SleepDurationDisplayDiv"
    );
    const durationDisplay = document.getElementById("SleepDurationDisplay");

    function updateSleepDuration() {
        const bedTime = bedTimeInput.value;
        const wakeTime = wakeTimeInput.value;

        if (!bedTime || !wakeTime) {
            durationDisplayDiv.classList.add("d-none");
            return;
        }

        const now = new Date();
        const bed = new Date(now.toDateString() + " " + bedTime);
        let wake = new Date(now.toDateString() + " " + wakeTime);

        if (wake <= bed) {
            wake.setDate(wake.getDate() + 1);
        }

        const durationMs = wake - bed;
        const durationMin = Math.floor(durationMs / (1000 * 60));
        const hours = Math.floor(durationMin / 60);
        const minutes = durationMin % 60;

        durationDisplay.textContent = `${hours}h ${minutes
            .toString()
            .padStart(2, "0")}m`;
        durationDisplayDiv.classList.remove("d-none");
    }

    bedTimeInput.addEventListener("change", updateSleepDuration);
    wakeTimeInput.addEventListener("change", updateSleepDuration);
}
