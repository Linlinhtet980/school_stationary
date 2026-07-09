<!-- Custom Confirm Modal -->
<div id="customConfirmModal" class="custom-confirm-modal" style="display: none;">
    <div class="custom-confirm-overlay" onclick="closeConfirmModal()"></div>
    <div class="custom-confirm-box">
        <div class="custom-confirm-header">
            <h5 class="custom-confirm-title">Confirmation</h5>
            <button type="button" class="custom-confirm-close" onclick="closeConfirmModal()">&times;</button>
        </div>
        <div class="custom-confirm-body">
            <p id="customConfirmMessage">Are you sure?</p>
        </div>
        <div class="custom-confirm-footer">
            <button type="button" class="btn btn-secondary custom-confirm-cancel" onclick="closeConfirmModal()">Cancel</button>
            <button type="button" id="customConfirmBtn" class="btn btn-danger custom-confirm-ok">OK</button>
        </div>
    </div>
</div>

<style>
    .custom-confirm-modal {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .custom-confirm-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
    }
    .custom-confirm-box {
        position: relative;
        background: #fff;
        border-radius: 8px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        animation: modalScaleIn 0.2s ease-out;
        font-family: inherit;
    }
    .custom-confirm-header {
        padding: 1rem;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .custom-confirm-title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
    }
    .custom-confirm-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #999;
        padding: 0;
        line-height: 1;
    }
    .custom-confirm-body {
        padding: 1.5rem 1rem;
        text-align: center;
        font-size: 1rem;
        color: #555;
    }
    .custom-confirm-footer {
        padding: 1rem;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }
    .custom-confirm-footer .btn {
        padding: 0.5rem 1.2rem;
        border-radius: 4px;
        cursor: pointer;
        border: none;
        font-weight: 500;
    }
    .custom-confirm-cancel {
        background: #e0e0e0;
        color: #333;
    }
    .custom-confirm-cancel:hover {
        background: #d0d0d0;
    }
    .custom-confirm-ok {
        background: #dc3545;
        color: white;
    }
    .custom-confirm-ok:hover {
        background: #c82333;
    }
    @keyframes modalScaleIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>

<script>
    let confirmModalCallback = null;

    function showConfirmModal(message, callback) {
        document.getElementById('customConfirmMessage').innerText = message;
        document.getElementById('customConfirmModal').style.display = 'flex';
        confirmModalCallback = callback;
    }

    function closeConfirmModal() {
        document.getElementById('customConfirmModal').style.display = 'none';
        confirmModalCallback = null;
    }

    document.getElementById('customConfirmBtn').addEventListener('click', function() {
        if (confirmModalCallback && typeof confirmModalCallback === 'function') {
            confirmModalCallback();
        }
        closeConfirmModal();
    });
</script>
