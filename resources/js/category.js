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
                actionsTd.innerHTML = `<button class="edit-category admin-edit-button" data-category='${JSON.stringify(category)}'>Edit <i class="bi bi-pencil"></i></button> <button data-id="${category.id}" data-name="${data.name}" class="delete-category admin-delete-button">Delete <i class="bi bi-trash"></i></button>`;
                tr.appendChild(actionsTd);

                categoriesWrapper.appendChild(tr);
            });
        })
        .catch(error => console.error('Error fetching categories:', error));

    const popup = document.querySelector('#edit-popup');
    const popupForm = document.querySelector('#edit-popup form');
    const closePopupBtn = document.querySelector('#closePopupBtn');

    document.addEventListener("click", e => {
        if (e.target.classList.contains('delete-category')) {

            e.preventDefault();

            const categoryId = e.target.getAttribute('data-id');
            const categoryName = e.target.getAttribute('data-name');
            const confirmPrompt = confirm(`Are you sure you want to delete ${categoryName}`);

            if (confirmPrompt) {
                fetch(`/admin-dashboard/categories/${categoryId}`, {
                    method: "DELETE"
                }).then(data => {
                    window.location.reload();
                });
            }
        }

        if (e.target.classList.contains('edit-category')) {
            const category = JSON.parse(e.target.getAttribute('data-category'));
            console.log("category: ",  JSON.parse(e.target.getAttribute('data-category')));

            document.querySelector('#edit-popup #name').value = category.name;
            popupForm.setAttribute("action", `/admin-dashboard/categories/update/${category.id}`);
            popup.style.display = 'flex';
        }

        if (e.target.classList.contains('close') || e.target === popup) {
            popup.style.display = 'none';
        }
    });

    closePopupBtn.addEventListener('click', () => {
        popup.style.display = 'none';
    });
});
