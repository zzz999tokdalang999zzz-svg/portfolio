<?php

require_once __DIR__ . '/../../lib/cloudinary.php';
require_once __DIR__ . '/../../config/config.php';

/**
 * Cloudinary Service
 * Quản lý upload và xử lý hình ảnh với Cloudinary
 */
class CloudinaryService {
    private $cloudinary;
    
    public function __construct() {
        $this->cloudinary = new CloudinaryHelper(
            CLOUDINARY_CLOUD_NAME,
            CLOUDINARY_API_KEY,
            CLOUDINARY_API_SECRET
        );
    }
    
    /**
     * Upload image lên Cloudinary
     */
    public function uploadProjectImage($file, $projectId) {
        try {
            $tempFile = $file['tmp_name'];
            $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
            $publicId = "projects/{$projectId}_{$originalName}_" . time();
            
            $result = $this->cloudinary->uploadImage($tempFile, $publicId, 'projects');
            
            return [
                'success' => true,
                'url' => $result['secure_url'],
                'public_id' => $result['public_id']
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Upload avatar/profile image
     */
    public function uploadAvatar($file, $userId) {
        try {
            $tempFile = $file['tmp_name'];
            $publicId = "avatars/user_{$userId}_" . time();
            
            $result = $this->cloudinary->uploadImage($tempFile, $publicId, 'avatars');
            
            return [
                'success' => true,
                'url' => $result['secure_url'],
                'public_id' => $result['public_id']
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Lấy URL của image với transformations
     */
    public function getImageUrl($publicId, $width = null, $height = null, $crop = 'fill', $quality = 'auto') {
        $options = [];
        
        if ($width) $options['width'] = $width;
        if ($height) $options['height'] = $height;
        if ($crop) $options['crop'] = $crop;
        if ($quality) $options['quality'] = $quality;
        
        return $this->cloudinary->imageUrl($publicId, $options);
    }
    
    /**
     * Lấy thumbnail cho project
     */
    public function getProjectThumbnail($publicId, $width = 300, $height = 200) {
        return $this->getImageUrl($publicId, $width, $height, 'fill', 'auto');
    }
    
    /**
     * Lấy image full size cho project detail
     */
    public function getProjectFullImage($publicId, $width = 800) {
        return $this->getImageUrl($publicId, $width, null, 'scale', 'auto');
    }
    
    /**
     * Xóa image từ Cloudinary
     */
    public function deleteImage($publicId) {
        try {
            $result = $this->cloudinary->deleteImage($publicId);
            return $result['result'] === 'ok';
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Lấy tất cả images trong một folder
     */
    public function getProjectImages($folder = 'projects', $maxResults = 20) {
        try {
            $result = $this->cloudinary->getImagesFromFolder($folder, $maxResults);
            return $result['resources'] ?? [];
        } catch (Exception $e) {
            return [];
        }
    }
}
