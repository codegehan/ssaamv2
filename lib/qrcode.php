<?php
include '../inc/phpqrcode/qrlib.php';

class QRCodeGenerator
{
    private $data;
    private $size;
    private $margin;
    private $logoPath;

    public function __construct($data, $logoPath = '../img/ccs.png', $size = 300, $margin = 1)
    {
        $this->data = $data;
        $this->logoPath = $logoPath;
        $this->size = $size;
        $this->margin = $margin;
    }

    public function generateQRCode()
    {
        // Start output buffering
        ob_start();
        // Generate QR code and output directly to buffer
        QRcode::png($this->data, null, QR_ECLEVEL_H, $this->size / 30, $this->margin);
        // Get the PNG image data from the buffer
        $imageData = ob_get_contents();
        // End output buffering and clean up
        ob_end_clean();
        // If a logo is provided, add it to the image data
        if ($this->logoPath) {
            $imageData = $this->addLogoToImageData($imageData);
        }
        // Return base64-encoded image data
        return base64_encode($imageData);
    }

    private function addLogoToImageData($imageData)
    {
        // Create image resources from the PNG data
        $qrImage = imagecreatefromstring($imageData);
        $logoImage = imagecreatefrompng($this->logoPath);
        // Get dimensions of QR code and logo images
        $qrWidth = imagesx($qrImage);
        $qrHeight = imagesy($qrImage);
        $logoWidth = imagesx($logoImage);
        $logoHeight = imagesy($logoImage);
        // Calculate logo placement
        $logoQRWidth = $qrWidth / 5;
        $scale = $logoWidth / $logoQRWidth;
        $logoQRHeight = $logoHeight / $scale;
        // Calculate positions
        $logoX = ($qrWidth - $logoQRWidth) / 2;
        $logoY = ($qrHeight - $logoQRHeight) / 2;
        // Merge logo onto QR code
        imagecopyresampled($qrImage, $logoImage, $logoX, $logoY, 0, 0, $logoQRWidth, $logoQRHeight, $logoWidth, $logoHeight);
        // Output the QR code with logo to a buffer
        ob_start();
        imagepng($qrImage);
        $imageDataWithLogo = ob_get_contents();
        ob_end_clean();
        // Free up memory
        imagedestroy($qrImage);
        imagedestroy($logoImage);
        return $imageDataWithLogo;
    }
}
