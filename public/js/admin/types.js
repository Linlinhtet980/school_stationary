// Type Page Specific Scripts

// Open Modal by ID
function openModal(id) {
    const modal = document.getElementById(id);
    modal.style.display = 'flex';
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
}

// Close Modal by ID
function closeModal(id) {
    const modal = document.getElementById(id);
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        closeModal(event.target.id);
    }
}

// Open Edit Modal and fill data
function openEditModal(id, categoryId, name, status) {
    document.getElementById('edit_category_id').value = categoryId;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_status').value = status;

    const form = document.getElementById('editForm');
    form.action = `/admin/types/${id}`;

    openModal('editTypeModal');
}
