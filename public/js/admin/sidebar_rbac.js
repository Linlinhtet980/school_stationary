document.addEventListener("DOMContentLoaded", function() {
    // ၁။ User ရဲ့ Role ကို ဖတ်ယူမယ်
    const roleElement = document.querySelector('.sb-user-role');
    if (!roleElement) return;
    
    // Role နာမည်ကို ယူမယ် (ဥပမာ "Super Admin", "Inventory Manager")
    const userRole = roleElement.innerText.trim();

    // ၂။ Role အလိုက် ဝင်ခွင့်ရှိတဲ့ Menu တွေကို သတ်မှတ်မယ် (Menu Label အတိုင်း)
    const permissions = {
        "Super Admin": ["ALL"], // Super Admin က အားလုံးရတယ်
        
        "Inventory Manager": [
            "Dashboard", "All Products", "Categories", "Brands", "Types", "Banners"
        ],
        "Order Staff": [
            "Dashboard", "Orders", "Customers"
        ],
        "Finance Manager": [
            "Dashboard", "Orders"
        ],
        "Customer Support": [
            "Dashboard", "Customers"
        ]
    };

    // ၃။ သတ်မှတ်ထားတဲ့ Permission List ကို ယူမယ်
    const allowedMenus = permissions[userRole] || [];

    // ၄။ Menu (sb-item) တွေကို လိုက်စစ်ပြီး ဝင်ခွင့်မရှိတာကို ဖျောက်မယ်
    const menuItems = document.querySelectorAll('.sb-item');
    
    menuItems.forEach(item => {
        const labelElement = item.querySelector('.sb-item-label');
        if (labelElement) {
            const menuName = labelElement.innerText.trim();
            
            // Super Admin မဟုတ်ဘူးဆိုရင် စစ်မယ်
            if (!allowedMenus.includes("ALL")) {
                // ခွင့်ပြုထားတဲ့ စာရင်းထဲမှာ မပါရင် ဖျောက်ထားမယ်
                if (!allowedMenus.includes(menuName)) {
                    item.style.display = 'none';
                }
            }
        }
    });

    // ၅။ အထဲမှာ Menu မရှိတော့တဲ့ Section Label တွေကိုပါ ဖျောက်မယ် (Optional)
    const sections = document.querySelectorAll('.sb-section-label');
    sections.forEach(section => {
        // Section တစ်ခုရဲ့ အောက်မှာရှိတဲ့ a.sb-item တွေကို ရှာမယ်
        let nextEl = section.nextElementSibling;
        let hasVisibleItems = false;
        
        while (nextEl && nextEl.classList.contains('sb-item')) {
            if (nextEl.style.display !== 'none') {
                hasVisibleItems = true;
                break;
            }
            nextEl = nextEl.nextElementSibling;
        }

        // ပြစရာ item တစ်ခုမှ မရှိရင် Section Label နဲ့ Divider ကိုပါ ဖျောက်မယ်
        if (!hasVisibleItems) {
            section.style.display = 'none';
            if (section.previousElementSibling && section.previousElementSibling.classList.contains('sb-divider')) {
                section.previousElementSibling.style.display = 'none';
            }
        }
    });
});
