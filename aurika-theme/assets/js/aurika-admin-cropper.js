/**
 * Aurika Luxury Product Image Cropper & Framing Tool (Admin JS)
 *
 * Allows merchants to manually crop, rotate, zoom, and frame product images
 * from all sides (top, bottom, left, right) directly inside the WordPress
 * product edit screen, ensuring all product images have an identical,
 * perfectly uniform luxury card display.
 *
 * @package Aurika
 */
(function($) {
  'use strict';

  var cropperInstance = null;
  var currentAttachmentId = null;

  $(document).ready(function() {
    initCropButtons();
    bindModalEvents();
    observeFeaturedImageChanges();
  });

  /**
   * Inject crop button into #postimagediv if an image is present
   */
  function initCropButtons() {
    var thumbId = $('#_thumbnail_id').val();
    var $inside = $('#postimagediv .inside');

    if ($inside.length && thumbId && thumbId !== '-1' && thumbId !== '0') {
      if (!$inside.find('.aurika-crop-trigger-wrap').length) {
        var $btnWrap = $('<div class="aurika-crop-trigger-wrap">' +
          '<button type="button" class="button aurika-btn-crop" id="aurika-open-crop-postimagediv">' +
            '<span class="dashicons dashicons-image-crop"></span> ' +
            '✂️ Crop & Frame Image (All Sides)' +
          '</button>' +
        '</div>');

        // Insert after remove-post-thumbnail link or append
        var $removeLink = $('#remove-post-thumbnail');
        if ($removeLink.length) {
          $btnWrap.insertAfter($removeLink.parent());
        } else {
          $inside.append($btnWrap);
        }
      }
    }

    // Bind click handlers
    $(document).off('click', '#aurika-open-crop-postimagediv, #aurika-open-crop-metabox')
      .on('click', '#aurika-open-crop-postimagediv, #aurika-open-crop-metabox', function(e) {
        e.preventDefault();
        openCropperForCurrentProduct();
      });

    $(document).off('click', '#aurika-select-and-crop-metabox')
      .on('click', '#aurika-select-and-crop-metabox', function(e) {
        e.preventDefault();
        openMediaUploaderAndCrop();
      });
  }

  /**
   * Observe changes to #_thumbnail_id or #postimagediv so the crop button
   * appears automatically when an image is selected via standard WP Media Library
   */
  function observeFeaturedImageChanges() {
    var target = document.getElementById('postimagediv');
    if (!target) return;

    var observer = new MutationObserver(function(mutations) {
      setTimeout(function() {
        initCropButtons();
        updateMetaboxPreview();
      }, 250);
    });

    observer.observe(target, {
      childList: true,
      subtree: true
    });
  }

  /**
   * Update the sidebar metabox preview image
   */
  function updateMetaboxPreview() {
    var thumbId = $('#_thumbnail_id').val();
    var $box = $('#aurika-metabox-preview-box');
    if (!$box.length) return;

    if (thumbId && thumbId !== '-1' && thumbId !== '0') {
      var $img = $('#postimagediv img');
      var src = $img.attr('src');
      if (src) {
        $box.html('<img id="aurika-metabox-preview-img" src="' + src + '" alt="Product Image">');
        $('#aurika-metabox-has-image').show();
        $('#aurika-metabox-no-image').hide();
      }
    } else {
      $box.html('<span class="no-image-text">📷 No product image set yet.<br>Click below to select and crop.</span>');
      $('#aurika-metabox-has-image').hide();
      $('#aurika-metabox-no-image').show();
    }
  }

  /**
   * If no image is set, open WP Media frame, and once selected, open Cropper
   */
  function openMediaUploaderAndCrop() {
    if (typeof wp === 'undefined' || !wp.media) {
      alert('WordPress Media Library is not available.');
      return;
    }

    var frame = wp.media({
      title: 'Select or Upload Product Image to Crop',
      button: { text: 'Use this Image & Open Cropper' },
      multiple: false,
      library: { type: 'image' }
    });

    frame.on('select', function() {
      var attachment = frame.state().get('selection').first().toJSON();
      if (attachment && attachment.id) {
        $('#_thumbnail_id').val(attachment.id);
        currentAttachmentId = attachment.id;

        var fullUrl = (attachment.sizes && attachment.sizes.full) ? attachment.sizes.full.url : attachment.url;
        launchCropperModal(fullUrl, attachment.id);
      }
    });

    frame.open();
  }

  /**
   * Open Cropper for currently assigned product image
   */
  function openCropperForCurrentProduct() {
    var thumbId = $('#_thumbnail_id').val();
    if (!thumbId || thumbId === '-1' || thumbId === '0') {
      openMediaUploaderAndCrop();
      return;
    }

    currentAttachmentId = thumbId;

    // Try fetching full-res URL from WP media attachment cache
    if (typeof wp !== 'undefined' && wp.media && wp.media.attachment) {
      var att = wp.media.attachment(thumbId);
      att.fetch().done(function() {
        var json = att.toJSON();
        var fullUrl = (json.sizes && json.sizes.full) ? json.sizes.full.url : json.url;
        launchCropperModal(fullUrl, thumbId);
      }).fail(function() {
        fallbackOpenFromDOM();
      });
    } else {
      fallbackOpenFromDOM();
    }

    function fallbackOpenFromDOM() {
      var $img = $('#postimagediv img');
      var src = $img.attr('src');
      if (src) {
        launchCropperModal(src, thumbId);
      } else {
        openMediaUploaderAndCrop();
      }
    }
  }

  /**
   * Launch Cropper Modal with the image source
   */
  function launchCropperModal(imageSrc, attachmentId) {
    var $modal = $('#aurika-cropper-modal');
    var $cropImg = $('#aurika-cropper-image');

    if (!$modal.length || !$cropImg.length) {
      console.error('Aurika Cropper Modal markup is missing.');
      return;
    }

    // Destroy existing cropper if any
    if (cropperInstance) {
      cropperInstance.destroy();
      cropperInstance = null;
    }

    // Show modal
    $modal.addClass('is-active');
    $('body').css('overflow', 'hidden');

    // Reset button states
    $('.aurika-ratio-btn').removeClass('is-active');
    $('.aurika-ratio-btn[data-ratio="1"]').addClass('is-active');
    $('#aurika-save-crop-btn').removeClass('is-saving').prop('disabled', false);

    // Set image source and initialize once loaded
    $cropImg.attr('src', imageSrc);

    $cropImg.off('load').on('load', function() {
      initCropperJS();
    });

    // In case image was already cached/loaded
    if ($cropImg[0].complete && $cropImg[0].naturalWidth > 0) {
      initCropperJS();
    }
  }

  /**
   * Initialize Cropper.js instance
   */
  function initCropperJS() {
    var imageEl = document.getElementById('aurika-cropper-image');
    if (!imageEl) return;

    if (cropperInstance) {
      cropperInstance.destroy();
    }

    cropperInstance = new Cropper(imageEl, {
      aspectRatio: 1, // Default 1:1 Square
      viewMode: 1,
      dragMode: 'crop',
      autoCropArea: 0.9,
      restore: false,
      guides: true,
      center: true,
      highlight: true,
      cropBoxMovable: true,
      cropBoxResizable: true, // Allows dragging any of the 4 sides and 4 corners
      toggleDragModeOnDblclick: false,
      preview: '#aurika-cropper-preview-container',
      crop: function(e) {
        var data = e.detail;
        var w = Math.round(data.width);
        var h = Math.round(data.height);
        $('#aurika-crop-dimensions').text(w + ' × ' + h + ' px');
      }
    });
  }

  /**
   * Bind Modal Toolbar, Ratio Buttons, and Save Action
   */
  function bindModalEvents() {
    // Close Modal
    $(document).on('click', '#aurika-close-cropper-modal, #aurika-cancel-crop-btn', function(e) {
      e.preventDefault();
      closeCropperModal();
    });

    // Close on overlay backdrop click
    $(document).on('click', '#aurika-cropper-modal', function(e) {
      if ($(e.target).is('#aurika-cropper-modal')) {
        closeCropperModal();
      }
    });

    // Aspect Ratio Buttons
    $(document).on('click', '.aurika-ratio-btn', function(e) {
      e.preventDefault();
      if (!cropperInstance) return;

      $('.aurika-ratio-btn').removeClass('is-active');
      $(this).addClass('is-active');

      var ratio = $(this).data('ratio');
      if (ratio === 'free') {
        cropperInstance.setAspectRatio(NaN); // Freeform: drag all sides freely
      } else if (ratio === 'portrait') {
        cropperInstance.setAspectRatio(0.77); // 1:1.3 matching Aurika product card aspect ratio
      } else {
        cropperInstance.setAspectRatio(1); // 1:1 Square
      }
    });

    // Transform Tools
    $(document).on('click', '#aurika-tool-rotate-left', function(e) {
      e.preventDefault();
      if (cropperInstance) cropperInstance.rotate(-90);
    });

    $(document).on('click', '#aurika-tool-rotate-right', function(e) {
      e.preventDefault();
      if (cropperInstance) cropperInstance.rotate(90);
    });

    $(document).on('click', '#aurika-tool-zoom-in', function(e) {
      e.preventDefault();
      if (cropperInstance) cropperInstance.zoom(0.1);
    });

    $(document).on('click', '#aurika-tool-zoom-out', function(e) {
      e.preventDefault();
      if (cropperInstance) cropperInstance.zoom(-0.1);
    });

    $(document).on('click', '#aurika-tool-reset', function(e) {
      e.preventDefault();
      if (cropperInstance) cropperInstance.reset();
    });

    // Save & Apply Crop
    $(document).on('click', '#aurika-save-crop-btn', function(e) {
      e.preventDefault();
      if (!cropperInstance) return;

      var $saveBtn = $(this);
      $saveBtn.addClass('is-saving').prop('disabled', true);

      // Get high-res cropped canvas
      var canvas = cropperInstance.getCroppedCanvas({
        maxWidth: 2400,
        maxHeight: 2400,
        fillColor: '#ffffff',
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high'
      });

      if (!canvas) {
        alert('Could not generate cropped canvas. Please try again.');
        $saveBtn.removeClass('is-saving').prop('disabled', false);
        return;
      }

      // Convert to high quality JPEG
      var dataUrl = canvas.toDataURL('image/jpeg', 0.93);
      var postId = aurika_cropper_vars.post_id || $('#post_ID').val() || 0;

      $.ajax({
        url: aurika_cropper_vars.ajax_url,
        type: 'POST',
        data: {
          action: 'aurika_save_cropped_product_image',
          nonce: aurika_cropper_vars.nonce,
          post_id: postId,
          original_attachment_id: currentAttachmentId || 0,
          image_data: dataUrl
        },
        dataType: 'json'
      }).done(function(response) {
        $saveBtn.removeClass('is-saving').prop('disabled', false);

        if (response && response.success) {
          var data = response.data;

          // 1. Update the hidden _thumbnail_id field
          $('#_thumbnail_id').val(data.attachment_id);

          // 2. Update #postimagediv .inside content with official WP markup
          if (data.full_html) {
            $('#postimagediv .inside').html(data.full_html);
          } else if (data.image_url) {
            var $img = $('#postimagediv img');
            if ($img.length) {
              $img.attr('src', data.image_url).removeAttr('srcset');
            }
          }

          // 3. Update the sidebar metabox preview
          $('#aurika-metabox-preview-box').html('<img id="aurika-metabox-preview-img" src="' + data.image_url + '" alt="Product Image">');
          $('#aurika-metabox-has-image').show();
          $('#aurika-metabox-no-image').hide();

          // 4. Re-inject the crop button into #postimagediv
          setTimeout(function() {
            initCropButtons();
          }, 200);

          // 5. Close modal
          closeCropperModal();

          // 6. Show dismissible notice at top of page
          showAdminNotice('✅ Product image successfully cropped and updated! Image box size remains perfectly uniform across your catalog.', 'success');
        } else {
          var err = (response && response.data) ? response.data : 'Failed to save cropped image.';
          alert('Error: ' + err);
        }
      }).fail(function(xhr, status, error) {
        $saveBtn.removeClass('is-saving').prop('disabled', false);
        alert('Server communication error: ' + error);
      });
    });
  }

  /**
   * Close Cropper Modal and clean up
   */
  function closeCropperModal() {
    var $modal = $('#aurika-cropper-modal');
    $modal.removeClass('is-active');
    $('body').css('overflow', '');

    if (cropperInstance) {
      cropperInstance.destroy();
      cropperInstance = null;
    }
  }

  /**
   * Display a clean dismissible admin notice
   */
  function showAdminNotice(message, type) {
    $('.aurika-dynamic-notice').remove();
    var noticeType = type || 'success';
    var $notice = $('<div class="notice notice-' + noticeType + ' is-dismissible aurika-dynamic-notice" style="margin: 15px 0 10px; padding: 12px 14px; font-size: 13.5px; font-weight: 500; border-left-color: #b8860b;">' +
      '<p style="margin: 0;">' + message + '</p>' +
      '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>' +
    '</div>');

    var $anchor = $('#wpbody-content .wrap > h1, #wpbody-content .wrap > hr').first();
    if ($anchor.length) {
      $notice.insertAfter($anchor);
    } else {
      $('#wpbody-content').prepend($notice);
    }

    $notice.find('.notice-dismiss').on('click', function() {
      $notice.fadeTo(100, 0, function() {
        $notice.slideUp(100, function() {
          $notice.remove();
        });
      });
    });
  }

})(jQuery);
