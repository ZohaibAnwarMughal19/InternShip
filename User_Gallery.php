<?php
session_start();
if (!isset($_SESSION['User'])) {
    header("Location: login.php");
    exit();
}
$userData = $_SESSION['User'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Photo Gallery - <?php echo htmlspecialchars($userData['Name']); ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #faf5ef;
            color: #3b0764;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-wrapper {
            width: 95%;
            max-width: 1200px;
            margin: 10px auto 40px auto;
            flex: 1;
        }

        /* Top Header Navigation */
        .dash-gallery-header {
            background: linear-gradient(135deg, #2e1065 0%, #4c1d95 50%, #6b21a8 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 25px -5px rgba(76, 29, 149, 0.25);
            border: 1px solid #c084fc;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .dash-gallery-header h1 {
            font-size: 26px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-back-dash {
            background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
            transition: all 0.25s ease;
            border: 1px solid #fbbf24;
        }

        .btn-back-dash:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(245, 158, 11, 0.45);
        }

        /* Alert Box */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-weight: 600;
            font-size: 15px;
            display: none;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* Upload Card Styling */
        .upload-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 10px 25px -5px rgba(76, 29, 149, 0.08);
            border: 1px solid #e9d5ff;
            margin-bottom: 35px;
            position: relative;
        }

        .upload-card h2 {
            color: #3b0764;
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 20px;
            border-bottom: 2px solid #f3e8ff;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 22px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-weight: 700;
            font-size: 14px;
            color: #4c1d95;
            margin-bottom: 8px;
        }

        .form-group textarea,
        .form-group input[type="file"] {
            padding: 12px;
            border: 1px solid #c084fc;
            border-radius: 10px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
            background: #faf5ef;
        }

        .form-group textarea:focus {
            border-color: #7e22ce;
            background: white;
            box-shadow: 0 0 8px rgba(126, 34, 206, 0.15);
        }

        /* Radio Buttons Box */
        .radio-options {
            display: flex;
            gap: 20px;
            align-items: center;
            padding: 10px 15px;
            background: #faf5ef;
            border-radius: 10px;
            border: 1px solid #e9d5ff;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            color: #374151;
        }

        .radio-option input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: #7e22ce;
            cursor: pointer;
        }

        /* Image Preview Box */
        .preview-box {
            margin-top: 15px;
            display: none;
            align-items: center;
            gap: 15px;
            background: #f3e8ff;
            padding: 12px;
            border-radius: 12px;
            border: 1px dashed #7e22ce;
        }

        .preview-thumb {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #7e22ce;
        }

        .btn-upload-submit {
            background: linear-gradient(135deg, #6b21a8 0%, #3b0764 100%);
            color: white;
            border: none;
            padding: 14px 28px;
            font-size: 16px;
            font-weight: 800;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(107, 33, 168, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-upload-submit:hover {
            background: linear-gradient(135deg, #7e22ce 0%, #4c1d95 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 33, 168, 0.4);
        }

        /* My Gallery Section */
        .gallery-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .gallery-section-header h2 {
            font-size: 24px;
            font-weight: 800;
            color: #3b0764;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            background: #e9d5ff;
            padding: 5px;
            border-radius: 10px;
        }

        .tab-btn {
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            background: transparent;
            color: #4c1d95;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab-btn.active {
            background: #6b21a8;
            color: white;
            box-shadow: 0 2px 8px rgba(107, 33, 168, 0.3);
        }

        /* Gallery Grid */
        .user-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .photo-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(76, 29, 149, 0.08);
            border: 1px solid #e9d5ff;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            position: relative;
        }

        .photo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(76, 29, 149, 0.15);
            border-color: #c084fc;
        }

        .photo-img-wrap {
            width: 100%;
            height: 220px;
            position: relative;
            background: #f3e8ff;
            cursor: pointer;
            overflow: hidden;
        }

        .photo-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .photo-card:hover .photo-img-wrap img {
            transform: scale(1.06);
        }

        .visibility-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 800;
            color: white;
            backdrop-filter: blur(4px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.25);
        }

        .badge-priv { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); }
        .badge-pub { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); }

        .photo-card-body {
            padding: 18px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .photo-desc {
            font-size: 15px;
            color: #374151;
            line-height: 1.5;
            margin-bottom: 14px;
            flex: 1;
            word-break: break-word;
        }

        .photo-date {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 15px;
            border-top: 1px solid #f3e8ff;
            padding-top: 10px;
        }

        .photo-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .btn-action-full {
            grid-column: 1 / -1;
        }

        .btn-act-photo {
            padding: 8px 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        .btn-gdrive-link {
            background: #2563eb;
            color: white;
        }
        .btn-gdrive-link:hover { background: #1d4ed8; }

        .btn-dl-link {
            background: #059669;
            color: white;
        }
        .btn-dl-link:hover { background: #047857; }

        .btn-del-link {
            background: #dc2626;
            color: white;
            grid-column: 1 / -1;
            margin-top: 4px;
        }
        .btn-del-link:hover { background: #b91c1c; }

        .empty-gallery {
            grid-column: 1 / -1;
            text-align: center;
            padding: 50px 20px;
            background: white;
            border-radius: 16px;
            border: 2px dashed #c084fc;
            color: #6b21a8;
        }

        /* Lightbox Preview */
        .lightbox-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .lightbox-content {
            max-width: 90%;
            max-height: 85vh;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <?php include('header.php'); ?>
    <?php include('menu.php'); ?>

    <div class="main-wrapper">
        
        <!-- Header -->
        <div class="dash-gallery-header">
            <h1>🖼️ My Photo Gallery & Drive</h1>
            <a href="Dashbord.php" class="btn-back-dash">⬅️ Back to Dashboard</a>
        </div>

        <div id="alertBox" class="alert"></div>

        <!-- UPLOAD MODULE CARD -->
        <div class="upload-card">
            <h2>📤 Upload New Image to Gallery</h2>
            
            <form id="uploadGalleryForm" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload">
                
                <div class="form-grid">
                    <!-- Image Description -->
                    <div class="form-group full-width">
                        <label for="imgDescription">📝 Image Description *</label>
                        <textarea id="imgDescription" name="description" rows="3" placeholder="Enter detailed image description..." required></textarea>
                    </div>

                    <!-- File Upload -->
                    <div class="form-group">
                        <label for="imgFile">📁 Choose Image File *</label>
                        <input type="file" id="imgFile" name="gallery_image" accept="image/*" required onchange="previewUploadImage(this)">
                        
                        <div id="previewBox" class="preview-box">
                            <img id="imgPreviewThumb" class="preview-thumb" src="" alt="Selected Preview">
                            <div>
                                <strong id="previewFileName" style="color:#4c1d95; font-size:13px;"></strong><br>
                                <span style="font-size:12px; color:#6b7280;">Ready for Upload</span>
                            </div>
                        </div>
                    </div>

                    <!-- Visibility Selection (Radio Buttons) -->
                    <div class="form-group">
                        <label>🔒 Privacy & Visibility *</label>
                        <div class="radio-options">
                            <label class="radio-option">
                                <input type="radio" name="visibility" value="public" checked>
                                🌐 Public (Show on Main Gallery)
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="visibility" value="private">
                                🔒 Private (Dashboard Only)
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" id="btnSubmitUpload" class="btn-upload-submit">
                    📤 Upload Image & Sync to Google Drive
                </button>
            </form>
        </div>

        <!-- USER GALLERY LISTING -->
        <div class="gallery-section-header">
            <h2>🖼️ My Uploaded Photos</h2>
            <div class="filter-tabs">
                <button type="button" class="tab-btn active" onclick="switchFilter('all', this)">All Photos</button>
                <button type="button" class="tab-btn" onclick="switchFilter('private', this)">🔒 Private Only</button>
                <button type="button" class="tab-btn" onclick="switchFilter('public', this)">🌐 Public Only</button>
            </div>
        </div>

        <div class="user-gallery-grid" id="userGalleryGrid">
            <!-- Loaded dynamically via AJAX -->
        </div>

    </div>

    <!-- Lightbox Modal -->
    <div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox()">
        <span class="lightbox-close">&times;</span>
        <img id="lightboxImg" class="lightbox-content" src="" alt="Enlarged view">
    </div>

    <script>
    var currentFilter = 'all';

    function previewUploadImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imgPreviewThumb').attr('src', e.target.result);
                $('#previewFileName').text(input.files[0].name);
                $('#previewBox').css('display', 'flex');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            $('#previewBox').hide();
        }
    }

    function openLightbox(src) {
        $('#lightboxImg').attr('src', src);
        $('#lightboxModal').css('display', 'flex');
    }

    function closeLightbox() {
        $('#lightboxModal').hide();
    }

    function switchFilter(filterType, btn) {
        currentFilter = filterType;
        $('.tab-btn').removeClass('active');
        $(btn).addClass('active');
        loadUserGallery();
    }

    function loadUserGallery() {
        $.ajax({
            url: 'Gallery_BE.php?action=fetch_user&filter=' + currentFilter,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var images = response.data;
                    var html = '';

                    if (images.length === 0) {
                        html = `
                            <div class="empty-gallery">
                                <h3>📷 No Photos Found!</h3>
                                <p>You have not uploaded any photos under '${currentFilter}' view yet. Use the upload box above to add your first photo.</p>
                            </div>
                        `;
                    } else {
                        $.each(images, function(i, img) {
                            var dateStr = new Date(img.created_at).toLocaleDateString('en-US', {
                                month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit'
                            });

                            var isPriv = img.visibility === 'private';
                            var badgeClass = isPriv ? 'badge-priv' : 'badge-pub';
                            var badgeText = isPriv ? '🔒 Private' : '🌐 Public';

                            html += `
                                <div class="photo-card" id="card-${img.id}">
                                    <div class="photo-img-wrap" onclick="openLightbox('${img.image_path}')">
                                        <img src="${img.image_path}" alt="User Photo">
                                        <span class="visibility-badge ${badgeClass}">${badgeText}</span>
                                    </div>
                                    <div class="photo-card-body">
                                        <div class="photo-desc">${escapeHtml(img.description)}</div>
                                        <div class="photo-date">📅 Uploaded: ${dateStr}</div>
                                        
                                        <div class="photo-actions">
                                            <a href="${img.gdrive_link}" target="_blank" class="btn-act-photo btn-gdrive-link" title="View on Google Drive">
                                                ☁️ Google Drive
                                            </a>
                                            <a href="${img.image_path}" download class="btn-act-photo btn-dl-link" title="Download Photo">
                                                ⬇️ Download
                                            </a>
                                            <button type="button" onclick="deleteGalleryPhoto(${img.id})" class="btn-act-photo btn-del-link" title="Delete Photo">
                                                🗑️ Delete Photo
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    }
                    $('#userGalleryGrid').html(html);
                } else {
                    $('#userGalleryGrid').html('<div class="empty-gallery">Error loading your gallery!</div>');
                }
            }
        });
    }

    function deleteGalleryPhoto(id) {
        if (confirm("Are you sure you want to delete this photo from Server and Database?")) {
            $.ajax({
                url: 'Gallery_BE.php',
                type: 'POST',
                data: { action: 'delete', id: id },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        showAlert('success', res.message);
                        loadUserGallery();
                    } else {
                        showAlert('danger', res.message);
                    }
                }
            });
        }
    }

    function showAlert(type, msg) {
        var alertBox = $('#alertBox');
        alertBox.removeClass('alert-success alert-danger')
                .addClass('alert-' + type)
                .html(msg)
                .fadeIn();
        
        $('html, body').animate({ scrollTop: 0 }, 'fast');

        setTimeout(function() {
            alertBox.fadeOut();
        }, 5000);
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    $(document).ready(function() {
        loadUserGallery();

        // AJAX Form Submit
        $('#uploadGalleryForm').on('submit', function(e) {
            e.preventDefault();
            $('#btnSubmitUpload').html('Uploading & Syncing to Google Drive... ⏳').prop('disabled', true);

            var formData = new FormData(this);

            $.ajax({
                url: 'Gallery_BE.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    $('#btnSubmitUpload').html('📤 Upload Image & Sync to Google Drive').prop('disabled', false);

                    if (response.status === 'success') {
                        showAlert('success', response.message);
                        $('#uploadGalleryForm')[0].reset();
                        $('#previewBox').hide();
                        loadUserGallery();
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    $('#btnSubmitUpload').html('📤 Upload Image & Sync to Google Drive').prop('disabled', false);
                    showAlert('danger', 'An unexpected error occurred during upload. Please try again.');
                }
            });
        });
    });
    </script>

    <?php include('footer.php'); ?>
</body>
</html>
