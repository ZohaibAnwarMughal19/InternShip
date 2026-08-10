<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Photo Gallery - Baba Guru Nanak University</title>
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

        /* Hero Header Section */
        .gallery-hero {
            background: linear-gradient(135deg, #2e1065 0%, #4c1d95 50%, #6b21a8 100%);
            color: white;
            padding: 35px 25px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 10px 25px -5px rgba(76, 29, 149, 0.25);
            border: 1px solid #c084fc;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .gallery-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #d97706 0%, #f59e0b 50%, #fbbf24 100%);
        }

        .gallery-hero h1 {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .gallery-hero p {
            color: #e9d5ff;
            font-size: 16px;
            max-width: 600px;
            margin: 0 auto 20px auto;
        }

        /* Search & Filter Bar */
        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            border: 1px solid #e9d5ff;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 450px;
        }

        .search-box input {
            width: 100%;
            padding: 11px 18px 11px 40px;
            border: 1px solid #c084fc;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            transition: all 0.25s ease;
        }

        .search-box input:focus {
            border-color: #7e22ce;
            box-shadow: 0 0 10px rgba(126, 34, 206, 0.2);
        }

        .search-box::before {
            content: '🔍';
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
        }

        .gallery-count-badge {
            background: #fef08a;
            color: #854d0e;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            border: 1px solid #fde047;
        }

        /* Gallery Grid */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .gallery-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(76, 29, 149, 0.08);
            border: 1px solid #e9d5ff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .gallery-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 35px rgba(76, 29, 149, 0.18);
            border-color: #c084fc;
        }

        .img-container {
            width: 100%;
            height: 220px;
            position: relative;
            background: #f3e8ff;
            overflow: hidden;
            cursor: pointer;
        }

        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .gallery-card:hover .img-container img {
            transform: scale(1.06);
        }

        .badge-public {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(34, 197, 94, 0.9);
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 12px;
            backdrop-filter: blur(4px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .card-body {
            padding: 18px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .image-desc {
            font-size: 15px;
            color: #374151;
            line-height: 1.5;
            margin-bottom: 14px;
            font-weight: 500;
            word-break: break-word;
            flex: 1;
        }

        .uploader-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #f3e8ff;
            padding-top: 12px;
            margin-bottom: 15px;
        }

        .uploader-name {
            font-weight: 700;
            color: #6b21a8;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .card-actions {
            display: flex;
            gap: 8px;
        }

        .btn-card {
            flex: 1;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 13px;
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

        .btn-gdrive {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-gdrive:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        }

        .btn-download {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.35);
        }

        .no-data-box {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
            border: 2px dashed #c084fc;
            color: #6b21a8;
        }

        .no-data-box h3 {
            font-size: 22px;
            margin-bottom: 8px;
        }

        /* Lightbox Image Preview Modal */
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
            object-fit: contain;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s;
        }

        .lightbox-close:hover {
            color: #f59e0b;
        }
    </style>
</head>
<body>

    <?php include('header.php'); ?>
    <?php include('menu.php'); ?>

    <div class="main-wrapper">
        <!-- Hero Header -->
        <div class="gallery-hero">
            <h1>🖼️ Public Photo Gallery</h1>
            <p>Explore high-resolution images uploaded and shared publicly by university members.</p>
        </div>

        <!-- Filter & Search Bar -->
        <div class="filter-bar">
            <div class="search-box">
                <input type="text" id="searchPublic" placeholder="Search by description or uploader name...">
            </div>
            <div class="gallery-count-badge" id="publicCount">
                Loading Images... ⏳
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="gallery-grid" id="publicGalleryGrid">
            <!-- Loaded dynamically via AJAX -->
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox()">
        <span class="lightbox-close">&times;</span>
        <img id="lightboxImg" class="lightbox-content" src="" alt="Enlarged view">
    </div>

    <script>
    function openLightbox(src) {
        $('#lightboxImg').attr('src', src);
        $('#lightboxModal').css('display', 'flex');
    }

    function closeLightbox() {
        $('#lightboxModal').hide();
    }

    function loadPublicGallery() {
        $.ajax({
            url: 'Gallery_BE.php?action=fetch_public',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var images = response.data;
                    $('#publicCount').html('🌐 Total Public Photos: ' + images.length);
                    var html = '';

                    if (images.length === 0) {
                        html = `
                            <div class="no-data-box">
                                <h3>📷 No Public Photos Uploaded Yet!</h3>
                                <p>Login to your account and upload photos with 'Public' visibility to feature them here.</p>
                            </div>
                        `;
                    } else {
                        $.each(images, function(i, img) {
                            var dateStr = new Date(img.created_at).toLocaleDateString('en-US', {
                                month: 'short', day: 'numeric', year: 'numeric'
                            });
                            
                            html += `
                                <div class="gallery-card" data-search="${img.description.toLowerCase()} ${img.user_name.toLowerCase()}">
                                    <div class="img-container" onclick="openLightbox('${img.image_path}')">
                                        <img src="${img.image_path}" alt="Gallery Image" loading="lazy">
                                        <span class="badge-public">🌐 Public</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="image-desc">${escapeHtml(img.description)}</div>
                                        <div class="uploader-info">
                                            <span class="uploader-name">👤 ${escapeHtml(img.user_name)}</span>
                                            <span>📅 ${dateStr}</span>
                                        </div>
                                        <div class="card-actions">
                                            <a href="${img.gdrive_link}" target="_blank" class="btn-card btn-gdrive" title="View on Google Drive">
                                                ☁️ Google Drive
                                            </a>
                                            <a href="${img.image_path}" download class="btn-card btn-download" title="Download Image">
                                                ⬇️ Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    }
                    $('#publicGalleryGrid').html(html);
                } else {
                    $('#publicGalleryGrid').html('<div class="no-data-box">Error loading gallery photos!</div>');
                }
            },
            error: function() {
                $('#publicGalleryGrid').html('<div class="no-data-box">Failed to connect to backend server.</div>');
            }
        });
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
        loadPublicGallery();

        // Search Filter Logic
        $('#searchPublic').on('keyup', function() {
            var query = $(this).val().toLowerCase();
            $('.gallery-card').each(function() {
                var searchData = $(this).data('search');
                if (searchData.indexOf(query) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
    </script>

    <?php include('footer.php'); ?>
</body>
</html>
