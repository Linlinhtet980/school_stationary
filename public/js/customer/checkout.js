// js/checkout.js

document.addEventListener('DOMContentLoaded', () => {

    // 1. Elements များကို ဆွဲယူခြင်း
    const paymentRadios = document.querySelectorAll('.payment-radio');
    const paymentCards = document.querySelectorAll('.payment-card');
    const kpayDetails = document.getElementById('kpayDetails');
    const checkoutForm = document.getElementById('checkoutForm');

    // 2. Payment Method ပြောင်းလဲမှုကို နားထောင်ခြင်း
    if (paymentRadios.length > 0) {
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                
                // Card အားလုံးဆီမှ active class ကို ဖြုတ်မည်
                paymentCards.forEach(card => card.classList.remove('active'));
                
                // ရွေးချယ်လိုက်သော Radio ၏ အပြင်ဘက် Card ကို active class တပ်မည်
                e.target.closest('.payment-card').classList.add('active');

                // KPay ရွေးထားလျှင် QR Section ကို ပြမည်၊ မဟုတ်လျှင် ဖျောက်မည်
                if (e.target.value === 'kpay') {
                    kpayDetails.style.display = 'block';
                } else {
                    kpayDetails.style.display = 'none';
                }
            });
        });
    }

    // 3. Form Submit လုပ်ရာတွင် Validation စစ်ဆေးခြင်း
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // Default page refresh ဖြစ်ခြင်းကို တားမည်

            try {
                // Form data များကို ယူမည်
                const formData = new FormData(checkoutForm);
                const phone = formData.get('phone');

                // ရိုးရှင်းသော Validation (ဖုန်းနံပါတ် အနည်းဆုံး ၉ လုံး ရှိရမည်)
                if (phone.length < 9) {
                    throw new Error("Phone number is invalid. Please check again.");
                }


                alert("Order Placed Successfully! Thank you for shopping with us.");
                window.location.href = "index.html"; // အောင်မြင်ပါက ပင်မစာမျက်နှာသို့ ပြန်ပို့မည်

            } catch (error) {
                // Error ရှိပါက User ကို ပြမည်
                alert(error.message);
                console.error("Checkout Error:", error);
            }
        });
    }

});