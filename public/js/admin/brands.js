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

function editBrand(id, name, status) {
    const form = document.getElementById('editForm');
    form.action = `/admin/brands/${id}`;

    document.getElementById('edit_name').value = name;
    document.getElementById('edit_status').value = status;
    
    document.getElementById('edit_logo').value = '';

    openModal('editBrandModal');
}
