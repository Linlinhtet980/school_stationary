<!-- Custom Alert Modal -->
<div id="customAlertModal" class="custom-confirm-modal" style="display: none;">
    <div class="custom-confirm-overlay" onclick="closeAlertModal()"></div>
    <div class="custom-confirm-box">
        <div class="custom-confirm-header">
            <h5 class="custom-confirm-title" id="customAlertTitle">Notification</h5>
            <button type="button" class="custom-confirm-close" onclick="closeAlertModal()">&times;</button>
        </div>
        <div class="custom-confirm-body">
            <p id="customAlertMessage">Alert Message</p>
        </div>
        <div class="custom-confirm-footer">
            <button type="button" class="btn btn-primary" onclick="closeAlertModal()" style="padding: 0.5rem 1.2rem; border-radius: 4px; border: none; font-weight: 500; cursor: pointer; color: white; background-color: #0d6efd;">OK</button>
        </div>
    </div>
</div>

<script>
    function showAlertModal(message, title = 'Notification') {
        document.getElementById('customAlertTitle').innerText = title;
        document.getElementById('customAlertMessage').innerText = message;
        document.getElementById('customAlertModal').style.display = 'flex';
    }

    function closeAlertModal() {
        document.getElementById('customAlertModal').style.display = 'none';
    }
</script>
