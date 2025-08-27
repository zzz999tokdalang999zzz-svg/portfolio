<?php

/**
 * Cloudinary PHP SDK integration
 * Simple wrapper for Cloudinary functionality
 */

class CloudinaryHelper {
    private $cloudName;
    private $apiKey;
    private $apiSecret;
    
    public function __construct($cloudName, $apiKey, $apiSecret) {
        $this->cloudName = $cloudName;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }
    
    /**
     * Upload image to Cloudinary
     */
    public function uploadImage($imagePath, $publicId = null, $folder = null) {
        $uploadUrl = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload";
        
        $params = [
            'file' => curl_file_create($imagePath),
            'api_key' => $this->apiKey,
            'timestamp' => time(),
        ];
        
        if ($publicId) {
            $params['public_id'] = $publicId;
        }
        
        if ($folder) {
            $params['folder'] = $folder;
        }
        
        // Generate signature
        $params['signature'] = $this->generateSignature($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uploadUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            return json_decode($response, true);
        } else {
            throw new Exception("Upload failed: " . $response);
        }
    }
    
    /**
     * Generate URL for image transformation
     */
    public function imageUrl($publicId, $options = []) {
        $baseUrl = "https://res.cloudinary.com/{$this->cloudName}/image/upload";
        
        $transformations = [];
        
        if (isset($options['width'])) {
            $transformations[] = "w_{$options['width']}";
        }
        
        if (isset($options['height'])) {
            $transformations[] = "h_{$options['height']}";
        }
        
        if (isset($options['crop'])) {
            $transformations[] = "c_{$options['crop']}";
        }
        
        if (isset($options['quality'])) {
            $transformations[] = "q_{$options['quality']}";
        }
        
        if (isset($options['format'])) {
            $transformations[] = "f_{$options['format']}";
        }
        
        $transformationString = empty($transformations) ? '' : implode(',', $transformations) . '/';
        
        return "{$baseUrl}/{$transformationString}{$publicId}";
    }
    
    /**
     * Delete image from Cloudinary
     */
    public function deleteImage($publicId) {
        $deleteUrl = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy";
        
        $params = [
            'public_id' => $publicId,
            'api_key' => $this->apiKey,
            'timestamp' => time(),
        ];
        
        $params['signature'] = $this->generateSignature($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $deleteUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            return json_decode($response, true);
        } else {
            throw new Exception("Delete failed: " . $response);
        }
    }
    
    /**
     * Generate signature for API calls
     */
    private function generateSignature($params) {
        // Remove signature and file from params for signature generation
        unset($params['signature']);
        unset($params['file']);
        unset($params['api_key']); // API key should not be included in signature
        
        // Sort parameters alphabetically
        ksort($params);
        
        // Build query string without URL encoding
        $stringToSign = '';
        foreach ($params as $key => $value) {
            if (!empty($value)) { // Skip empty values
                $stringToSign .= $key . '=' . $value . '&';
            }
        }
        $stringToSign = rtrim($stringToSign, '&');
        
        // Add API secret at the end
        $stringToSign .= $this->apiSecret;
        
        return sha1($stringToSign);
    }
    
    /**
     * Get all images from a folder
     */
    public function getImagesFromFolder($folder = null, $maxResults = 10) {
        $searchUrl = "https://api.cloudinary.com/v1_1/{$this->cloudName}/resources/search";
        
        $params = [
            'expression' => $folder ? "folder:{$folder}" : "resource_type:image",
            'max_results' => $maxResults,
            'api_key' => $this->apiKey,
            'timestamp' => time(),
        ];
        
        $params['signature'] = $this->generateSignature($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $searchUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            return json_decode($response, true);
        } else {
            throw new Exception("Search failed: " . $response);
        }
    }
}
