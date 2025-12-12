// ===== Tanggal Minimal Hari Ini =====
const today = new Date().toISOString().split("T")[0];
document.getElementById("tanggal_ambil").setAttribute("min", today);

// ===== Tampilkan Alamat Pengiriman Berdasarkan Tipe =====
const tipeOrder = document.getElementById("tipe_order");
const alamatField = document.getElementById("alamatField");

tipeOrder.addEventListener("change", () => {
    alamatField.style.display = tipeOrder.value === "kirim" ? "block" : "none";
    document.getElementById("alamat").required = tipeOrder.value === "kirim";
});

// ===== Informasi Metode Pembayaran =====
const metodeSelect = document.getElementById("metode_pembayaran");
const paymentInfo = document.getElementById("paymentInfo");
const bankInfo = document.getElementById("bankInfo");
const qrisInfo = document.getElementById("qrisInfo");

metodeSelect.addEventListener("change", () => {
    paymentInfo.classList.remove("d-none");
    bankInfo.style.display = "none";
    qrisInfo.style.display = "none";

    if (metodeSelect.value === "bank_transfer") {
        bankInfo.style.display = "block";
    } else if (metodeSelect.value === "qris") {
        qrisInfo.style.display = "block";
    }
});

// ===== Kupon Functionality =====
document.getElementById("apply_coupon").addEventListener("click", function () {
    const couponCode = document.getElementById("coupon_code").value.trim();
    const messageDiv = document.getElementById("coupon_message");
    const totalDisplay = document.getElementById("total_display");
    const originalTotal = parseFloat(
        document.querySelector('input[name="original_total"]').value
    );

    if (!couponCode) {
        messageDiv.innerHTML =
            '<div class="text-danger fw-semibold">Masukkan kode kupon</div>';
        return;
    }

    fetch("/api/coupons/validate", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            code: couponCode,
            total: originalTotal,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.valid) {
                const discount = data.discount;
                const finalTotal = originalTotal - discount;

                totalDisplay.textContent = `Rp ${finalTotal.toLocaleString(
                    "id-ID"
                )}`;
                document.querySelector('input[name="final_total"]').value =
                    finalTotal;
                document.querySelector('input[name="discount_amount"]').value =
                    discount;
                document.querySelector('input[name="coupon_id"]').value =
                    data.coupon_id;

                messageDiv.innerHTML = `<div class="text-success fw-semibold">Kupon berhasil diterapkan! Diskon: Rp ${discount.toLocaleString(
                    "id-ID"
                )}</div>`;
            } else {
                messageDiv.innerHTML = `<div class="text-danger fw-semibold">${data.message}</div>`;
                // Reset values
                totalDisplay.textContent = `Rp ${originalTotal.toLocaleString(
                    "id-ID"
                )}`;
                document.querySelector('input[name="final_total"]').value =
                    originalTotal;
                document.querySelector(
                    'input[name="discount_amount"]'
                ).value = 0;
                document.querySelector('input[name="coupon_id"]').value = "";
            }
        })
        .catch((error) => {
            messageDiv.innerHTML =
                '<div class="text-danger fw-semibold">Terjadi kesalahan saat memvalidasi kupon</div>';
        });
});
const tanggalField = document.getElementById("tanggalField");
const tanggalInput = document.getElementById("tanggal_ambil");

tipeOrder.addEventListener("change", () => {
    if (tipeOrder.value === "ambil") {
        tanggalField.style.display = "block";
        tanggalInput.required = true;
    } else {
        tanggalField.style.display = "none";
        tanggalInput.required = false;
        tanggalInput.value = "";
    }
});
