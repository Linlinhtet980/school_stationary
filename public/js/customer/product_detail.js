// js/product.js

document.addEventListener('DOMContentLoaded', () => {

    // --- 1. Image Gallery Logic ---
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.thumbnail');

    if (mainImage && thumbnails.length > 0) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                // Remove active class from all
                thumbnails.forEach(t => t.classList.remove('active'));
                // Add active class to clicked thumbnail
                this.classList.add('active');
                
                // Change main image source
                const newSrc = this.getAttribute('data-src');
                if(newSrc) {
                    mainImage.style.opacity = 0.5; // Fade effect
                    setTimeout(() => {
                        mainImage.src = newSrc;
                        mainImage.style.opacity = 1;
                    }, 150);
                }
            });
        });
    }

    // --- 2. Quantity Selector Logic ---
    const btnMinus = document.getElementById('btnMinus');
    const btnPlus = document.getElementById('btnPlus');
    const qtyInput = document.getElementById('qtyInput');

    if (btnMinus && btnPlus && qtyInput) {
        btnMinus.addEventListener('click', () => {
            let currentValue = parseInt(qtyInput.value);
            // Validation: အနည်းဆုံး 1 ထက် နည်းလို့မရပါ
            if (!isNaN(currentValue) && currentValue > 1) {
                qtyInput.value = currentValue - 1;
            }
        });

        btnPlus.addEventListener('click', () => {
            let currentValue = parseInt(qtyInput.value);
            // Validation: အများဆုံး 10 ခုသာ ဝယ်ခွင့်ပြုမည် (Stock limit ဥပမာ)
            if (!isNaN(currentValue) && currentValue < 10) {
                qtyInput.value = currentValue + 1;
            }
        });
    }

    // Global changeQty function for onclick attributes
    window.changeQty = function(delta) {
        const qtyInput = document.getElementById('qtyInput');
        if (qtyInput) {
            let currentValue = parseInt(qtyInput.value);
            const newValue = currentValue + delta;
            const max = parseInt(qtyInput.getAttribute('max')) || 10;
            
            if (newValue >= 1 && newValue <= max) {
                qtyInput.value = newValue;
            }
        }
    };

    // --- 3. Tabs Logic ---
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    if (tabBtns.length > 0 && tabContents.length > 0) {
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                
                // Reset all tabs
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));

                // Set active tab
                this.classList.add('active');
                const targetContent = document.getElementById(targetId);
                if(targetContent) {
                    targetContent.classList.add('active');
                }
            });
        });
    }

    // ShowTab function for direct onclick calls
    window.showTab = function(tabId) {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => btn.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));

        const targetContent = document.getElementById(tabId);
        if(targetContent) {
            targetContent.classList.add('active');
        }

        // Find and activate the corresponding button
        const targetBtn = Array.from(tabBtns).find(btn => 
            btn.textContent.toLowerCase().includes(tabId.replace('Tab', '').toLowerCase())
        );
        if(targetBtn) {
            targetBtn.classList.add('active');
        }
    };

    // --- 4. Variant Selection Logic ---
    window.selectVariant = function(element) {
        const variantOptions = document.querySelectorAll('.variant-option');
        variantOptions.forEach(opt => opt.classList.remove('selected'));
        
        element.classList.add('selected');
        
        const variantId = element.dataset.variantId;
        const price = element.dataset.price;
        const stock = element.dataset.stock;
        
        const selectedVariantId = document.getElementById('selectedVariantId');
        const displayPrice = document.getElementById('displayPrice');
        
        if (selectedVariantId) {
            selectedVariantId.value = variantId;
        }
        
        if (displayPrice) {
            displayPrice.textContent = price + ' Ks';
        }
        
        // Update stock status
        const qtyInput = document.getElementById('qtyInput');
        if (qtyInput) {
            qtyInput.max = stock;
        }
    };
});