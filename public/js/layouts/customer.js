// ၁.cart drawer ပွင့်/ပိတ် စနစ်
document.addEventListener('DOMContentLoaded', function() {
    
    // Elements ကို ရွေးချယ်ခြင်း
    const cartIconBtn = document.getElementById('cartIconBtn');
    const closeCartBtn = document.getElementById('closeCartBtn');
    const cartDrawer = document.getElementById('cartDrawer');
    const cartOverlay = document.getElementById('cartOverlay');

    // Cart ဖွင့်ရန် Function
    function openCart(e) {
        if(e) e.preventDefault(); // <a> tag ရဲ့ default အလုပ်လုပ်မှုကို တားရန်
        cartDrawer.classList.add('open');
        cartOverlay.classList.add('open');
    }

    // Cart ပိတ်ရန် Function
    function closeCart() {
        cartDrawer.classList.remove('open');
        cartOverlay.classList.remove('open');
    }

    // Event Listeners ချိတ်ဆက်ခြင်း
    if (cartIconBtn) {
        cartIconBtn.addEventListener('click', openCart);
    }

    if (closeCartBtn) {
        closeCartBtn.addEventListener('click', closeCart);
    }

    // အမည်းရောင် Overlay ကို နှိပ်ရင်လည်း Cart ပိတ်သွားအောင် လုပ်ခြင်း
    if (cartOverlay) {
        cartOverlay.addEventListener('click', closeCart);
    }

});