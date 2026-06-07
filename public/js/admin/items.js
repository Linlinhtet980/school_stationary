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
