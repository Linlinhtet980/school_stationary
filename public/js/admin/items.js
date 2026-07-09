// Items Image Preview Script

document.addEventListener('DOMContentLoaded', function() {
    // စာမျက်နှာ စတင်ပွင့်ချိန်တွင် လက်ရှိရှိနေသော Variant Row အရေအတွက်ကို တွက်ချက်ခြင်း
    updateRowIndices();
});

// ၁။ Variant Row အသစ်ထည့်ခြင်း
function addVariantRow() {
    const tbody = document.getElementById('variantsBody');
    const currentIndex = tbody.querySelectorAll('.variant-row').length;

    const newRow = document.createElement('tr');
    newRow.classList.add('variant-row');

    newRow.innerHTML = `
        <td><input type="text" name="variants[${currentIndex}][unit_label]" class="form-control" placeholder="e.g. Box" required></td>
        <td><input type="number" name="variants[${currentIndex}][unit_qty]" class="form-control" placeholder="10"></td>
        <td><input type="text" name="variants[${currentIndex}][color]" class="form-control" placeholder="Red"></td>
        <td><input type="text" name="variants[${currentIndex}][size]" class="form-control" placeholder="XL"></td>
        <td><input type="number" name="variants[${currentIndex}][price]" class="form-control" placeholder="0.00" step="0.01" required></td>
        <td><input type="number" name="variants[${currentIndex}][stock_qty]" class="form-control" placeholder="100" min="0"></td>
        <td><input type="text" name="variants[${currentIndex}][sku]" class="form-control" placeholder="SKU-00${currentIndex + 1}"></td>
        <td>
            <button type="button" class="btn-delete" onclick="removeVariantRow(this)">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(newRow);
}

// ၂။ Variant Row ပြန်ဖျက်ခြင်း
function removeVariantRow(button) {
    const tbody = document.getElementById('variantsBody');
    const rows = tbody.querySelectorAll('.variant-row');

    // စည်းကမ်းချက် - အနည်းဆုံး Variant တစ်ခုတော့ ရှိရမည်
    if (rows.length > 1) {
        button.closest('tr').remove();
        updateRowIndices(); // ဖျက်ပြီးပါက Index များကို ပြန်ညှိပေးရန်
    } else {
        alert("Warning: At least one variant is required for an item.");
    }
}

// ၃။ ဒေတာဘေ့စ်သို့ ပို့မည့် Array Index များကို စနစ်တကျ ပြန်စီပေးခြင်း (Gaps မရှိစေရန်)
function updateRowIndices() {
    const tbody = document.getElementById('variantsBody');
    const rows = tbody.querySelectorAll('.variant-row');

    rows.forEach((row, index) => {
        const inputs = row.querySelectorAll('input');
        inputs.forEach(input => {
            const nameAttr = input.getAttribute('name');
            if (nameAttr) {
                // Regular Expression ဖြင့် 'variants[X][field]' မှ X ကို အသစ်ပြန်စီပေးခြင်း
                const updatedName = nameAttr.replace(/variants\[\d+\]/, `variants[${index}]`);
                input.setAttribute('name', updatedName);
            }
        });
    });
}

// ၄။ Main Image Preview ပြသခြင်း
function previewMainImage(input) {
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('imagePlaceholder');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '';
        preview.style.display = 'none';
        placeholder.style.display = 'flex';
    }
}

// ၅။ Existing Gallery Image ဖျက်ခြင်း (AJAX)
function deleteGalleryImage(imageId, csrfToken) {
    showConfirmModal('Are you sure you want to delete this gallery image?', () => {
        fetch(`/admin/items/image/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const galleryBtn = document.querySelector(`button[onclick*="deleteGalleryImage(${imageId}"]`);
                if (galleryBtn) {
                    const galleryItem = galleryBtn.closest('.gallery-item');
                    if (galleryItem) {
                        galleryItem.style.transition = 'all 0.3s ease';
                        galleryItem.style.opacity = '0';
                        galleryItem.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            galleryItem.remove();
                        }, 300);
                    }
                }
            } else {
                alert(data.message || 'Failed to delete image');
            }
        })
        .catch(error => {
            console.error('Error deleting gallery image:', error);
            alert('An error occurred while deleting the image.');
        });
    });
}