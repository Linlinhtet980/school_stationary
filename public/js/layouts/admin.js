function toggleDropdown(id) {
    document.getElementById(id).classList.toggle('show');
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        sidebar.classList.toggle('collapsed');
    }
}

// ၂။ Light & Dark Theme ပြောင်းလဲသည့် စနစ်
function toggleTheme() {
    const html = document.documentElement;
    const isDark = html.getAttribute('data-theme') === 'dark';
    const newTheme = isDark ? 'light' : 'dark';

    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('admin-theme', newTheme); // User စိတ်ကြိုက် သတ်မှတ်ချက်ကို သိမ်းဆည်းရန်

    // Popup ထဲရှိ Checkbox အခြေအနေကို လိုက်ပြောင်းပေးရန်
    const themeSwitch = document.getElementById('darkModeSwitch');
    if (themeSwitch) {
        themeSwitch.checked = !isDark;
    }
}

// ၃။ Profile Popup Menu ကို ပြသ/ဖျောက် လုပ်သည့် စနစ်
function toggleProfilePopup(event) {
    if (event) event.stopPropagation(); // Document click event သို့ ဆက်မရောက်သွားစေရန် တားဆီးခြင်း
    const popup = document.getElementById('sbPopup');
    const chevron = document.getElementById('sbChevron');

    if (popup) {
        popup.classList.toggle('open');

        // မြှားခေါင်းကို အပေါ်/အောက် လှည့်ပေးရန်
        if (popup.classList.contains('open')) {
            chevron.style.transform = 'rotate(180deg)';
        } else {
            chevron.style.transform = 'rotate(0deg)';
        }
    }
}

// ၃.၁။ Topbar Profile Popup ကို ပြသ/ဖျောက် လုပ်သည့် စနစ်
function toggleTopbarProfile(event) {
    if (event) event.stopPropagation();
    const popup = document.getElementById('topbarPopup');
    if (popup) {
        popup.classList.toggle('open');
    }
}

// ၄။ စနစ်စတင်ချိန် အလိုအလျောက် အလုပ်လုပ်မည့် စနစ်များ
document.addEventListener('DOMContentLoaded', function () {

    // သိမ်းဆည်းထားခဲ့သော Theme အဟောင်းအား ရှာဖွေပြီး အလိုအလျောက် သတ်မှတ်ပေးရန်
    const savedTheme = localStorage.getItem('admin-theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);

    const themeSwitch = document.getElementById('darkModeSwitch');
    if (themeSwitch) {
        themeSwitch.checked = (savedTheme === 'dark');
    }

    // Popup အပြင်ဘက်ကို နှိပ်လိုက်လျှင် Popup အလိုအလျောက် ပြန်ပိတ်သွားစေရန်
    document.addEventListener('click', function (event) {
        const popup = document.getElementById('sbPopup');
        const trigger = document.getElementById('sbUserTrigger');
        const chevron = document.getElementById('sbChevron');

        if (popup && popup.classList.contains('open')) {
            // နှိပ်လိုက်သည့်နေရာသည် Popup သို့မဟုတ် Trigger Area မဟုတ်ခဲ့လျှင်
            if (!popup.contains(event.target) && !trigger.contains(event.target)) {
                popup.classList.remove('open');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }

        const topbarPopup = document.getElementById('topbarPopup');
        const topbarTrigger = document.getElementById('topbarProfileTrigger');
        if (topbarPopup && topbarPopup.classList.contains('open')) {
            if (!topbarPopup.contains(event.target) && !topbarTrigger.contains(event.target)) {
                topbarPopup.classList.remove('open');
            }
        }
    });

    // ၅။ JS Frontend Search Logic
    const searchInput = document.getElementById('topbarSearchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            // ဇယား (Table) အောက်က Data တွေကိုပဲ လိုက်ရှာပါမယ်
            const rows = document.querySelectorAll('.main-content table tbody tr');
            
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                // စာသား ပါ/မပါ စစ်ဆေးပြီး ပြ/ဖျောက် လုပ်မယ်
                if (text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
