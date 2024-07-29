document.addEventListener('DOMContentLoaded', () => {
    const categoryOption = document.querySelector("#products-wrapper #category");
    const productWrapper = document.querySelector("#products-wrapper .all-products");

    let categoriesMap = {};

    const populateOptions = (element, data, defaultOption, selectedId = null) => {
        element.innerHTML = `<option disabled selected>${defaultOption}</option>`;
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            if (item.id === selectedId) {
                option.selected = true;
            }
            element.appendChild(option);

            categoriesMap[item.id] = item.name;
        });
    };

    const createTableCell = (content) => {
        const td = document.createElement("td");
        td.textContent = content;
        return td;
    };

    const formatDateTime = (dateTimeString) => {
        const date = new Date(dateTimeString);
        return date.toLocaleString();
    };

    const fetchData = (url, callback, errorMessage) => {
        fetch(url)
            .then(response => response.json())
            .then(data => callback(data))
            .catch(error => console.error(errorMessage, error));
    };

    const loadCategories = (data) => {
        populateOptions(categoryOption, data, 'Select Category');
    };

    const loadProducts = (data) => {
        data.forEach(product => {
            const tr = document.createElement("tr");
            tr.appendChild(createTableCell(product.id));
            tr.appendChild(createTableCell(product.name));
            tr.appendChild(createTableCell(product.description));
            tr.appendChild(createTableCell(product.price));
            tr.appendChild(createTableCell(product.stockQuantity));
            tr.appendChild(createTableCell(categoriesMap[product.category_id]));
            tr.appendChild(createTableCell(formatDateTime(product.createdAt.date)));
            tr.appendChild(createTableCell(formatDateTime(product.updatedAt.date)));
            const actionsTd = document.createElement("td");
            actionsTd.innerHTML = `<button class="edit-product admin-edit-button" data-product='${JSON.stringify(product)}'>Edit <i class="bi bi-pencil"></i></button> <button data-id="${product.id}" data-name="${product.name}" class="delete-product admin-delete-button">Delete <i class="bi bi-trash"></i></button>`;
            tr.appendChild(actionsTd);
            productWrapper.appendChild(tr);
        });
    };

    fetchData('/admin-dashboard/categories/load', (data) => {
        loadCategories(data);
        fetchData('/admin-dashboard/products/load', loadProducts, 'Error fetching products:');
    }, 'Error fetching categories:');

    const popup = document.querySelector('#edit-popup');
    const popupForm = document.querySelector('#edit-popup form');
    const closePopupBtn = document.querySelector('#closePopupBtn');

    document.addEventListener("click", e => {
        if (e.target.classList.contains("delete-product")) {
            e.preventDefault();

            const productId = e.target.getAttribute('data-id');
            const productName = e.target.getAttribute('data-name');
            const confirmPrompt = confirm(`Are you sure you want to delete ${productName}`);

            if (confirmPrompt) {
                fetch(`/admin-dashboard/products/${productId}`, {
                    method: "DELETE"
                }).then(data => {
                    window.location.reload();
                });
            }
        }

        if (e.target.classList.contains('edit-product')) {
            const product = JSON.parse(e.target.getAttribute('data-product'));

            document.querySelector('#edit-popup #name').value = product.name;
            document.querySelector('#edit-popup #description').value = product.description;
            fetchData('/admin-dashboard/categories/load', data => {
                populateOptions(document.querySelector('#edit-popup #category'), data, 'Select Category', product.category_id);
            }, 'Error fetching categories:');
            document.querySelector('#edit-popup #price').value = product.price;
            document.querySelector('#edit-popup #stockQuantity').value = product.stockQuantity;
            popupForm.setAttribute("action", `/admin-dashboard/products/update/${product.id}`);
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
