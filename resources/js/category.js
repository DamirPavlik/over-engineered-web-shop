document.addEventListener('DOMContentLoaded', () => {
    const categoriesWrapper = document.querySelector('.all-categories');

    fetch('/admin-dashboard/categories/load')
        .then(response => response.json())
        .then(data => {
            data.forEach(category => {
                const tr = document.createElement("tr");

                const idTd = document.createElement("td");
                idTd.textContent = category.id;
                tr.appendChild(idTd);

                const nameTd = document.createElement("td");
                nameTd.textContent = category.name;
                tr.appendChild(nameTd);

                const actionsTd = document.createElement("td");
                // Add any actions you want, like edit or delete buttons
                actionsTd.innerHTML = `<button>Edit</button> <button>Delete</button>`;
                tr.appendChild(actionsTd);

                categoriesWrapper.appendChild(tr);
            });
        })
        .catch(error => console.error('Error fetching categories:', error));
});
