document.addEventListener('DOMContentLoaded', () => {
    const usersWrapper = document.querySelector('.all-users');
    const adminWrapper = document.querySelector('.all-admins');

    const loadData = (url, wrapper, type) => {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                data.forEach(item => {
                    const tr = createTableRow(item, type);
                    wrapper.appendChild(tr);
                });
            })
            .catch(error => console.error(`Error fetching data from ${url}:`, error));
    };

    const createTableRow = (item, type) => {
        const tr = document.createElement("tr");

        Object.keys(item).forEach(key => {
            const td = document.createElement("td");
            if (key === 'createdAt' || key === 'updatedAt') {
                td.textContent = item[key].date;
            } else {
                td.textContent = item[key];
            }
            tr.appendChild(td);
        });

        const actionsTd = document.createElement("td");
        const deleteButtonClass = type === 'admin' ? 'delete-admin' : 'delete-user';
        const editButtonClass = type === 'admin' ? 'edit-admin' : 'edit-user';
        const deleteButtonDataId = item.id; // Assuming 'id' is the key for the admin/user ID
        const deleteButtonDataName = item.name; // Assuming 'name' is the key for the admin/user name
        actionsTd.innerHTML = `<button class="admin-edit-button ${editButtonClass}" data-id="${item.id}">Edit <i class="bi bi-pencil"></i></button> <button class="${deleteButtonClass} admin-delete-button" data-id="${deleteButtonDataId}" data-name="${deleteButtonDataName}">Delete <i class="bi bi-trash"></i></button>`;
        tr.appendChild(actionsTd);

        return tr;
    };

    loadData('/admin-dashboard/users/load', usersWrapper, 'user');
    loadData('/admin-dashboard/admin/load', adminWrapper, 'admin');

    const popup = document.querySelector('#edit-popup');
    const popupForm = document.querySelector('#edit-popup form');
    const closePopupBtn = document.querySelector('#closePopupBtn');

    document.addEventListener("click", e => {
        if (e.target.classList.contains("delete-user")) {
            e.preventDefault();

            const userId = e.target.getAttribute('data-id');
            const userName = e.target.getAttribute('data-name');
            const confirmPrompt = confirm(`Are you sure you want to delete ${userName}`);

            if (confirmPrompt) {
                fetch(`/admin-dashboard/users/${userId}`, {
                    method: "DELETE"
                }).then(data => {
                    window.location.reload();
                });
            }
        }

        if (e.target.classList.contains('edit-user')) {
            const userId = e.target.getAttribute('data-id');
            fetch(`/admin-dashboard/users/${userId}`)
                .then(response => response.json())
                .then(user => {
                    popupForm.querySelector('input[name="name"]').value = user.name;
                    popupForm.querySelector('input[name="email"]').value = user.email;
                    popupForm.querySelector('input[name="type"]').value = 'user'; // Add hidden input for type
                    popupForm.action = `/admin-dashboard/users/update/${userId}`;
                    popup.style.display = 'flex';
                })
                .catch(error => console.error(`Error fetching user data:`, error));
        }

        if (e.target.classList.contains('edit-admin')) {
            const adminId = e.target.getAttribute('data-id');
            fetch(`/admin-dashboard/admin/${adminId}`)
                .then(response => response.json())
                .then(admin => {
                    popupForm.querySelector('input[name="name"]').value = admin.name;
                    popupForm.querySelector('input[name="email"]').value = admin.email;
                    popupForm.querySelector('input[name="type"]').value = 'admin'; // Add hidden input for type
                    popupForm.action = `/admin-dashboard/admin/update/${adminId}`;
                    popup.style.display = 'flex';
                })
                .catch(error => console.error(`Error fetching admin data:`, error));
        }

        if (e.target.classList.contains('close') || e.target === popup) {
            popup.style.display = 'none';
        }
    });

    document.addEventListener("click", e => {
        if (e.target.classList.contains("delete-admin")) {
            e.preventDefault();

            const adminId = e.target.getAttribute('data-id');
            const adminName = e.target.getAttribute('data-name');
            const confirmPrompt = confirm(`Are you sure you want to delete ${adminName}`);

            if (confirmPrompt) {
                fetch(`/admin-dashboard/admin/${adminId}`, {
                    method: "DELETE"
                }).then(data => {
                    window.location.reload();
                });
            }
        }

        if (e.target.classList.contains('close') || e.target === popup) {
            popup.style.display = 'none';
        }
    });

    closePopupBtn.addEventListener('click', () => {
        popup.style.display = 'none';
    });
});
