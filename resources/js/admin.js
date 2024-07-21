document.addEventListener('DOMContentLoaded', () => {
    const logoutButton = document.querySelector('.logout-button');
    logoutButton.addEventListener("click", e => {
        e.preventDefault();

        fetch("/admin-dashboard/logout", {
            method: "POST"
        });

        window.location.reload();
    })
});

