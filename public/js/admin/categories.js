// Category Page Specific Scripts

// Open Modal by ID
function openModal(id) {
    const modal = document.getElementById(id);
    modal.style.display = 'flex';
    // Small delay to allow display block to apply before adding opacity class
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
    }, 300); // Matches CSS transition duration
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        closeModal(event.target.id);
    }
}

// Open Edit Modal and fill data
function openEditModal(id, name, description, status) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_status').value = status;
    
    // Set form action URL dynamically
    const form = document.getElementById('editForm');
    // Using simple replacement assuming admin/categories URL structure
    form.action = `/admin/categories/${id}`;
    
    openModal('editCategoryModal');
}
