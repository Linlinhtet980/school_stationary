// Items Image Preview Script

document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const placeholderContent = document.getElementById('placeholderContent');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = this.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    if(placeholderContent) {
                        placeholderContent.style.display = 'none';
                    }
                }
                
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
                if(placeholderContent) {
                    placeholderContent.style.display = 'block';
                }
            }
        });
    }
});

// Image Preview Logic
function previewMainImage(input) {
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('placeholderContent') || document.getElementById('imagePlaceholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '';
        preview.style.display = 'none';
        if (placeholder) placeholder.style.display = 'block';
    }
}

// Dynamic Variants Row Logic
function addVariantRow() {
    const tbody = document.getElementById('variantsBody');
    // Unique index ဖန်တီးရန် Date.now() ကို သုံးထားပါတယ် (အဟောင်းတွေဖျက်လိုက်တဲ့အခါ Index ထပ်မသွားအောင်ပါ)
    const index = Date.now(); 
    
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" name="variants[${index}][unit_label]" class="form-control" placeholder="Box"></td>
        <td><input type="number" name="variants[${index}][unit_qty]" class="form-control" placeholder="1" min="1"></td>
        <td><input type="text" name="variants[${index}][color]" class="form-control" placeholder="Red"></td>
        <td><input type="text" name="variants[${index}][size]" class="form-control" placeholder="L"></td>
        <td><input type="number" step="0.01" name="variants[${index}][price]" class="form-control" required placeholder="0.00" min="0"></td>
        <td><input type="number" name="variants[${index}][stock_qty]" class="form-control" required placeholder="0" min="0"></td>
        <td><input type="text" name="variants[${index}][sku]" class="form-control" placeholder="SKU-123"></td>
        <td style="text-align: center;">
            <button type="button" class="btn-remove-row" onclick="removeVariantRow(this)" title="Remove Row">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

function removeVariantRow(button) {
    const tbody = document.getElementById('variantsBody');
    if (tbody.querySelectorAll('tr').length > 1) {
        button.closest('tr').remove();
    } else {
        alert('At least one variant row is required.');
    }
}

// Edit Form အတွက် Gallery Image ဖျက်သည့် Logic
function deleteGalleryImage(id, csrfToken) {
    if (confirm('Are you sure you want to remove this gallery image?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/items/image/${id}`;
        form.style.display = 'none';

        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';

        form.appendChild(tokenInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Create Page အတွက် အစတည်းက Variant တစ်ကြောင်း အလိုအလျောက် ပေါ်နေစေရန်
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('variantsBody');
    // tbody ရှိပြီး အတွင်းမှာ row တစ်ခုမှ မရှိမှသာ အသစ်ဖန်တီးမည် (Edit page နဲ့ မငြိအောင်)
    if (tbody && tbody.querySelectorAll('tr').length === 0) {
        addVariantRow();
    }
});