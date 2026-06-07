// Brand Page Specific Scripts

function openModal(id) {
    const modal = document.getElementById(id);
    modal.style.display = 'flex';
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
}

function closeModal(id) {
    const modal = document.getElementById(id);
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        closeModal(event.target.id);
    }
}

function openEditModal(id, name) {
    document.getElementById('edit_name').value = name;
    
    // reset file input visually
    document.getElementById('edit_logo').value = '';

    const form = document.getElementById('editForm');
    form.action = `/admin/brands/${id}`;

    openModal('editBrandModal');
}
