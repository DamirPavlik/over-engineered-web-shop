document.addEventListener('DOMContentLoaded', () => {
    const categoryOption = document.querySelector("#products-wrapper #category");

    fetch('/admin-dashboard/categories/load')
        .then(response => response.json())
        .then(data => {
            // Clear the current options
            categoryOption.innerHTML = '<option selected disabled>Select Category</option>';

            data.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categoryOption.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching categories:', error));

    const logoutButton = document.querySelector('.logout-button');

    logoutButton.addEventListener("click", e => {
        e.preventDefault();

        fetch("/admin-dashboard/logout", {
            method: "POST"
        });

        window.location.reload();
    })
});

