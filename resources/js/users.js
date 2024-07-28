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
        const deleteButtonDataId = item.id; // Assuming 'id' is the key for the admin/user ID
        const deleteButtonDataName = item.name; // Assuming 'id' is the key for the admin/user ID
        actionsTd.innerHTML = `<button>Edit</button> <button class="${deleteButtonClass}" data-id="${deleteButtonDataId}" data-name="${deleteButtonDataName}">Delete</button>`;
        tr.appendChild(actionsTd);

        return tr;
    };

    loadData('/admin-dashboard/users/load', usersWrapper, 'user');
    loadData('/admin-dashboard/admin/load', adminWrapper, 'admin');


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
    })

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
    })
});
