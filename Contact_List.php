<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List & Registration</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
        body { margin: 0; padding: 0; background-color: #f4f7f6; }
        .main-container { width: 95%; margin: 20px auto; }
        
        /* Alert Message */
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; text-align: center; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* Form & Card Styling */
        .form-card, .table-card { background: #ffffff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 30px; border: 1px solid #e1e8ed; }
        .form-card h2 { margin-top: 0; color: #003366; font-size: 24px; border-bottom: 2px solid #003366; padding-bottom: 10px; margin-bottom: 20px; }
        
        /* Table Header Box & Search Bar */
        .table-header-box { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #003366; padding-bottom: 10px; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
        .table-header-box h2 { margin: 0; color: #003366; font-size: 24px; }
        .search-input { padding: 9px 15px; border: 1px solid #ccc; border-radius: 20px; font-size: 14px; width: 300px; outline: none; transition: all 0.2s ease; }
        .search-input:focus { border-color: #003366; box-shadow: 0 0 5px rgba(0, 51, 102, 0.2); }

        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; }
        .form-group { display: flex; flex-direction: column; margin-bottom: 12px; }
        .form-group label { font-weight: 600; margin-bottom: 6px; color: #333; font-size: 14px; }
        .form-group input[type="text"], .form-group input[type="email"], .form-group input[type="file"] { padding: 10px; border: 1px solid #ccc; border-radius: 6px; outline: none; }
        .radio-group { display: flex; gap: 15px; align-items: center; margin-top: 8px; }
        .btn-submit { background-color: #003366; color: white; padding: 12px; border: none; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 20px; width: 100%; }
        .btn-submit:hover { background-color: #002244; }
        
        .preview-img-box { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #003366; display: none; margin-top: 8px; }

        /* Table Styling */
        .contact-table { width: 100%; border-collapse: collapse; text-align: left; }
        .contact-table th, .contact-table td { padding: 12px 15px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .contact-table th { background-color: #003366; color: white; font-size: 13px; text-transform: uppercase; }
        .contact-table tr:hover { background-color: #f8fafc; }
        .avatar-img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd; }
        .avatar-placeholder { width: 50px; height: 50px; border-radius: 50%; background: #e2e8f0; color: #475569; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: bold; }

        /* Favorite Styling */
        .fav-row { background-color: #fffdf0 !important; }
        .fav-badge { font-size: 11px; background: #fef08a; color: #854d0e; padding: 2px 7px; border-radius: 10px; margin-left: 6px; font-weight: 600; display: inline-block; }
        .btn-star { background-color: #ffffff !important; color: #d97706 !important; border: 2px solid #f59e0b !important; font-size: 13px !important; font-weight: 800 !important; border-radius: 8px !important; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2) !important; }
        .btn-star:hover { background-color: #fef3c7 !important; color: #b45309 !important; border-color: #d97706 !important; }
        .btn-star.active { background-color: #fef08a !important; color: #92400e !important; border: 2px solid #d97706 !important; box-shadow: 0 2px 6px rgba(217, 119, 6, 0.35) !important; }

        /* Action Buttons */
        .action-buttons { display: flex; gap: 6px; flex-wrap: wrap; }
        .btn-act { padding: 6px 10px; border-radius: 6px; color: white; text-decoration: none; font-size: 12px; font-weight: 600; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: transform 0.1s ease; }
        .btn-act:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-wa { background-color: #25D366; }
        .btn-email { background-color: #007bff; }
        .btn-call { background-color: #28a745; }
        .btn-edit { background-color: #f59e0b; }
        .btn-delete { background-color: #ef4444; }
        .no-data { text-align: center; padding: 30px; color: #777; font-style: italic; }

        /* Edit Modal Popup */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(3px); align-items: center; justify-content: center; }
        .modal-content { background: #fff; width: 92%; max-width: 550px; border-radius: 12px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #003366; padding-bottom: 10px; margin-bottom: 20px; }
        .modal-header h3 { margin: 0; color: #003366; font-size: 20px; }
        .close-modal { font-size: 24px; font-weight: bold; cursor: pointer; color: #888; border: none; background: none; }
        .close-modal:hover { color: #333; }
    </style>
</head>
<body>

    <?php include('header.php'); ?>
    <?php include('menu.php'); ?>

    <div class="main-container">
        <div id="msgAlert"></div>

        <!-- Contact Entry Form -->
        <div class="form-card">
            <h2>📇 Add New Contact</h2>
            <form id="contactForm" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="name" placeholder="Enter Full Name" required>
                    </div>

                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" placeholder="Enter Email Address" required>
                    </div>

                    <div class="form-group">
                        <label>Mobile Number *</label>
                        <input type="text" name="mobile_no" placeholder="Enter Mobile No (e.g., 03001234567)" required>
                    </div>

                    <div class="form-group">
                        <label>Gender *</label>
                        <div class="radio-group">
                            <label><input type="radio" name="gender" value="Male" required> Male</label>
                            <label><input type="radio" name="gender" value="Female" required> Female</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Profile Picture (Optional)</label>
                        <input type="file" name="myfile" accept="image/*" onchange="previewFile(this, '#imgPreview')">
                        <img id="imgPreview" class="preview-img-box" alt="Profile Preview">
                    </div>
                </div>

                <button type="submit" id="btnSave" class="btn-submit">💾 Save Contact</button>
            </form>
        </div>

        <!-- Contact List Table with Search Filter -->
        <div class="table-card">
            <div class="table-header-box">
                <h2>📋 Contacts Directory</h2>
                <input type="text" id="searchContact" class="search-input" placeholder="🔍 Search Name, Email, or Mobile...">
            </div>
            
            <table class="contact-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Profile Picture</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile No</th>
                        <th>Gender</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="contactTableBody">
                    <!-- Table rows loaded dynamically via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Contact Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>✏️ Edit Contact Details</h3>
                <button type="button" class="close-modal" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editForm" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <input type="hidden" id="edit_id" name="edit_id">

                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>

                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" id="edit_email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Mobile Number *</label>
                    <input type="text" id="edit_mobile_no" name="mobile_no" required>
                </div>

                <div class="form-group">
                    <label>Gender *</label>
                    <div class="radio-group">
                        <label><input type="radio" id="edit_gender_male" name="gender" value="Male" required> Male</label>
                        <label><input type="radio" id="edit_gender_female" name="gender" value="Female" required> Female</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Change Profile Picture (Optional)</label>
                    <input type="file" name="edit_myfile" accept="image/*" onchange="previewFile(this, '#edit_imgPreview')">
                    <img id="edit_imgPreview" class="preview-img-box" alt="Current Profile Picture">
                </div>

                <button type="submit" id="btnUpdate" class="btn-submit" style="background-color: #f59e0b;">💾 Update Contact</button>
            </form>
        </div>
    </div>

    <script>
    // Live Image Preview Handler
    function previewFile(input, imgElementId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(imgElementId).attr('src', e.target.result).show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Call Button Handler
    function makeCall(phoneNumber) {
        var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || (window.innerWidth <= 768);
        if (isMobile) {
            window.location.href = "tel:" + phoneNumber;
        } else {
            alert("⚠️ Call Functionality Alert!\n\nYou are on a PC / Laptop. Kindly open this website on your Mobile Phone to call: " + phoneNumber);
        }
    }

    // Load Table Rows via AJAX
    function loadContactsTable() {
        $.ajax({
            url: 'Contact_BE.php?action=fetch',
            type: 'GET',
            success: function(tableHtml) {
                $('#contactTableBody').html(tableHtml);
            }
        });
    }

    // ⭐ Toggle Favorite Contact
    function toggleFavorite(id) {
        $.ajax({
            url: 'Contact_BE.php?action=toggle_favorite&id=' + id,
            type: 'GET',
            success: function() {
                loadContactsTable();
            }
        });
    }

    // 🗑️ Delete Contact Handler
    function deleteContact(id, name) {
        if (confirm("Are you sure you want to delete contact: " + name + "?")) {
            $.ajax({
                url: 'Contact_BE.php',
                type: 'POST',
                data: { action: 'delete', id: id },
                success: function(res) {
                    if (res.trim() === 'success') {
                        $('#msgAlert').html('<div class="alert alert-success">Contact deleted successfully! 🗑️</div>');
                        loadContactsTable();
                    } else {
                        alert(res);
                    }
                }
            });
        }
    }

    // ✏️ Open Edit Modal and load single contact data
    function openEditModal(id) {
        $.ajax({
            url: 'Contact_BE.php?action=get_single&id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data && !data.error) {
                    $('#edit_id').val(data.id);
                    $('#edit_name').val(data.name);
                    $('#edit_email').val(data.email);
                    $('#edit_mobile_no').val(data.mobile_no);
                    
                    if (data.gender === 'Male') {
                        $('#edit_gender_male').prop('checked', true);
                    } else {
                        $('#edit_gender_female').prop('checked', true);
                    }

                    if (data.profile_pic && data.profile_pic !== '') {
                        $('#edit_imgPreview').attr('src', data.profile_pic).show();
                    } else {
                        $('#edit_imgPreview').hide().attr('src', '');
                    }

                    $('#editModal').css('display', 'flex');
                } else {
                    alert('Error loading contact details!');
                }
            }
        });
    }

    // Close Edit Modal
    function closeEditModal() {
        $('#editModal').hide();
    }

    $(document).ready(function(){
        loadContactsTable(); // Initial Load

        // 🔍 Instant Live Search Filter
        $('#searchContact').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#contactTableBody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Add New Contact AJAX
        $('#contactForm').on('submit', function(e){
            e.preventDefault();
            $('#btnSave').html('Saving... ⏳').prop('disabled', true);

            $.ajax({
                url: 'Contact_BE.php',
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(response){
                    $('#btnSave').html('💾 Save Contact').prop('disabled', false);

                    if (response.trim() === 'success') {
                        $('#msgAlert').html('<div class="alert alert-success">Contact Saved Successfully! 🎉</div>');
                        $('#contactForm')[0].reset();
                        $('#imgPreview').hide();
                        $('#searchContact').val('');
                        loadContactsTable();
                    } else {
                        $('#msgAlert').html('<div class="alert alert-error">⚠️ ' + response + '</div>');
                    }
                }
            });
        });

        // Update Contact AJAX (Edit Form Submit)
        $('#editForm').on('submit', function(e){
            e.preventDefault();
            $('#btnUpdate').html('Updating... ⏳').prop('disabled', true);

            $.ajax({
                url: 'Contact_BE.php',
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(response){
                    $('#btnUpdate').html('💾 Update Contact').prop('disabled', false);

                    if (response.trim() === 'success') {
                        $('#msgAlert').html('<div class="alert alert-success">Contact Updated Successfully! ✏️</div>');
                        closeEditModal();
                        loadContactsTable();
                    } else {
                        alert(response);
                    }
                }
            });
        });
    });
    </script>

    <?php include('footer.php'); ?>

</body>
</html>
