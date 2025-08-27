/**
 * Cloudinary Image Upload Handler
 * Xử lý upload và quản lý hình ảnh với Cloudinary
 */

class CloudinaryUploader {
  constructor() {
    this.initializeEventListeners();
  }

  initializeEventListeners() {
    // File input change handler
    document.addEventListener("change", (e) => {
      if (e.target.classList.contains("cloudinary-upload")) {
        this.handleFileSelect(e);
      }
    });

    // Upload button click handler
    document.addEventListener("click", (e) => {
      if (e.target.classList.contains("cloudinary-upload-btn")) {
        this.uploadImage(e);
      }
    });

    // Delete image button handler
    document.addEventListener("click", (e) => {
      if (e.target.classList.contains("cloudinary-delete-btn")) {
        this.deleteImage(e);
      }
    });
  }

  handleFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Validate file type
    const allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];
    if (!allowedTypes.includes(file.type)) {
      this.showError("Chỉ chấp nhận file ảnh (JPEG, PNG, GIF, WebP)");
      return;
    }

    // Validate file size (5MB max)
    const maxSize = 5 * 1024 * 1024; // 5MB
    if (file.size > maxSize) {
      this.showError("File không được vượt quá 5MB");
      return;
    }

    // Preview image
    this.previewImage(file, event.target);
  }

  previewImage(file, input) {
    const reader = new FileReader();
    reader.onload = (e) => {
      const previewContainer = input
        .closest(".upload-container")
        .querySelector(".image-preview");
      if (previewContainer) {
        previewContainer.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                    <p class="mt-2 text-sm text-gray-600">${file.name}</p>
                `;
      }
    };
    reader.readAsDataURL(file);
  }

  async uploadImage(event) {
    const button = event.target;
    const container = button.closest(".upload-container");
    const fileInput = container.querySelector(".cloudinary-upload");
    const projectId = button.dataset.projectId;

    if (!fileInput.files[0]) {
      this.showError("Vui lòng chọn một file ảnh");
      return;
    }

    const formData = new FormData();
    formData.append("image", fileInput.files[0]);
    formData.append("project_id", projectId);

    // Show loading state
    button.disabled = true;
    button.textContent = "Đang upload...";

    try {
      // Use dynamic API URL from config
      const response = await fetch(CONFIG.getApiUrl('upload-image'), {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        this.showSuccess("Upload thành công!");
        this.updateImageDisplay(container, result.url, result.thumbnail);
        fileInput.value = ""; // Clear input
      } else {
        this.showError(result.error || "Upload thất bại");
      }
    } catch (error) {
      this.showError("Lỗi kết nối: " + error.message);
    } finally {
      button.disabled = false;
      button.textContent = "Upload";
    }
  }

  async deleteImage(event) {
    const button = event.target;
    const publicId = button.dataset.publicId;
    const projectId = button.dataset.projectId;

    if (!confirm("Bạn có chắc muốn xóa ảnh này?")) {
      return;
    }

    button.disabled = true;
    button.textContent = "Đang xóa...";

    try {
      // Use dynamic API URL from config  
      const response = await fetch(CONFIG.getApiUrl('delete-image'), {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          public_id: publicId,
          project_id: projectId,
        }),
      });

      const result = await response.json();

      if (result.success) {
        this.showSuccess("Xóa ảnh thành công!");
        // Remove image from display
        const container = button.closest(".image-container");
        if (container) {
          container.remove();
        }
      } else {
        this.showError(result.error || "Xóa ảnh thất bại");
      }
    } catch (error) {
      this.showError("Lỗi kết nối: " + error.message);
    } finally {
      button.disabled = false;
      button.textContent = "Xóa";
    }
  }

  updateImageDisplay(container, fullUrl, thumbnailUrl) {
    const displayArea =
      container.querySelector(".uploaded-images") ||
      this.createImageDisplayArea(container);

    const imageHtml = `
            <div class="image-container mb-3">
                <img src="${thumbnailUrl}" alt="Uploaded image" class="img-thumbnail" style="max-width: 200px;">
                <div class="mt-2">
                    <a href="${fullUrl}" target="_blank" class="btn btn-sm btn-primary">Xem full</a>
                    <button class="btn btn-sm btn-danger cloudinary-delete-btn" 
                            data-public-id="${this.extractPublicId(fullUrl)}" 
                            data-project-id="${
                              container.querySelector(".cloudinary-upload-btn")
                                .dataset.projectId
                            }">
                        Xóa
                    </button>
                </div>
            </div>
        `;

    displayArea.innerHTML += imageHtml;
  }

  createImageDisplayArea(container) {
    const displayArea = document.createElement("div");
    displayArea.className = "uploaded-images mt-3";
    container.appendChild(displayArea);
    return displayArea;
  }

  extractPublicId(cloudinaryUrl) {
    // Extract public_id from Cloudinary URL
    const parts = cloudinaryUrl.split("/");
    const uploadIndex = parts.indexOf("upload");
    if (uploadIndex !== -1 && uploadIndex + 1 < parts.length) {
      return parts
        .slice(uploadIndex + 1)
        .join("/")
        .split(".")[0];
    }
    return "";
  }

  showSuccess(message) {
    this.showNotification(message, "success");
  }

  showError(message) {
    this.showNotification(message, "error");
  }

  showNotification(message, type) {
    // Create notification element
    const notification = document.createElement("div");
    notification.className = `alert alert-${
      type === "success" ? "success" : "danger"
    } notification-popup`;
    notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        `;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification);
      }
    }, 5000);
  }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  new CloudinaryUploader();
});

// Helper functions for templates
function generateCloudinaryUploadForm(projectId, existingImages = []) {
  return `
        <div class="upload-container border rounded p-3 mb-3">
            <h5>Upload hình ảnh</h5>
            <div class="mb-3">
                <input type="file" class="form-control cloudinary-upload" accept="image/*">
                <div class="image-preview mt-2"></div>
            </div>
            <button type="button" class="btn btn-primary cloudinary-upload-btn" data-project-id="${projectId}">
                Upload
            </button>
            
            ${
              existingImages.length > 0
                ? `
                <div class="uploaded-images mt-3">
                    <h6>Hình ảnh hiện tại:</h6>
                    ${existingImages
                      .map(
                        (img) => `
                        <div class="image-container mb-3">
                            <img src="${img.thumbnail}" alt="Project image" class="img-thumbnail" style="max-width: 200px;">
                            <div class="mt-2">
                                <a href="${img.full}" target="_blank" class="btn btn-sm btn-primary">Xem full</a>
                                <button class="btn btn-sm btn-danger cloudinary-delete-btn" 
                                        data-public-id="${img.public_id}" 
                                        data-project-id="${projectId}">
                                    Xóa
                                </button>
                            </div>
                        </div>
                    `
                      )
                      .join("")}
                </div>
            `
                : ""
            }
        </div>
    `;
}
