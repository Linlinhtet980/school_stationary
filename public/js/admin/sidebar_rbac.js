document.addEventListener("DOMContentLoaded", function() {
    // ၁။ User ရဲ့ Role ကို ဖတ်ယူမယ်
    const roleElement = document.querySelector('.sb-user-role');
    if (!roleElement) return;
    
    // Role နာမည်ကို ယူမယ် (ဥပမာ "Super Admin", "Inventory Manager")
    const userRole = roleElement.textContent.trim().toLowerCase(); // Normalize to lowercase and use textContent to avoid CSS text-transform issue

    // ၂။ Role အလိုက် ဝင်ခွင့်ရှိတဲ့ Menu တွေကို သတ်မှတ်မယ် (Menu Label အတိုင်း)
    const permissions = {
        "super admin": ["ALL"], // Super Admin က အားလုံးရတယ်
        
        "inventory manager": [
            "Dashboard", "All Products", "Categories", "Brands", "Types", "Banners"
        ],
        "order staff": [
            "Dashboard", "Orders", "Customers"
        ],
        "finance manager": [
            "Dashboard", "Orders"
        ],
        "customer support": [
            "Dashboard", "Customers", "Orders", "Reviews"
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

    // ၅။ အထဲမှာ Menu မရှိတော့တဲ့ Section/Dropdown တွေကိုပါ ဖျောက်မယ်
    const dropdownTriggers = document.querySelectorAll('.sb-dropdown-trigger');
    dropdownTriggers.forEach(trigger => {
        const menu = trigger.nextElementSibling;
        if (menu && menu.classList.contains('sb-dropdown-menu')) {
            // Find any visible .sb-item inside this dropdown menu
            const visibleItems = Array.from(menu.querySelectorAll('.sb-item')).filter(item => item.style.display !== 'none');
            if (visibleItems.length === 0) {
                trigger.style.display = 'none';
                menu.style.display = 'none';
                // Hide the divider before the trigger
                const divider = trigger.previousElementSibling;
                if (divider && divider.classList.contains('sb-divider')) {
                    divider.style.display = 'none';
                }
            }
        }
    });

    // ၆။ Dropdown Toggle Logic
    window.toggleSidebarDropdown = function(trigger) {
        console.log('toggleSidebarDropdown triggered on:', trigger);
        trigger.classList.toggle('open');
        const menu = trigger.nextElementSibling;
        console.log('Next sibling is:', menu);
        if (menu && menu.classList.contains('sb-dropdown-menu')) {
            menu.classList.toggle('open');
            console.log('Toggled open class on menu. Current classes:', menu.className);
        } else {
            console.error('Error: sb-dropdown-menu sibling not found!');
        }
    };

    // ၇။ Page load တွင် Active Item ပါရှိသော Dropdown များကို အလိုအလျောက် ဖွင့်ပေးမယ်
    const activeItem = document.querySelector('.sb-item.active');
    if (activeItem) {
        const parentMenu = activeItem.closest('.sb-dropdown-menu');
        if (parentMenu) {
            parentMenu.classList.add('open');
            const trigger = parentMenu.previousElementSibling;
            if (trigger && trigger.classList.contains('sb-dropdown-trigger')) {
                trigger.classList.add('open');
            }
        }
    }
});
