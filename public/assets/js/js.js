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

// Admin - Add Resource js
if (window.location.pathname === "/admin/add-resource") {
    //
}
