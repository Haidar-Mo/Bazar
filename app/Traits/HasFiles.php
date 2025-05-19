<?php

namespace App\Traits;
use Illuminate\Http\UploadedFile;
use TCPDF;


/**
 * Files like : images , videos , ...etc
 */
trait HasFiles
{
    public function saveFile(UploadedFile|TCPDF $file, string $folder_name)
    {
        if ($file instanceof UploadedFile) {
            $extension = $file->getClientOriginalExtension();
        } else {
            // Handle case if you ever need to save raw content
            $extension = 'pdf';
        }
        $file_name = time() . '_' . substr(
            str_shuffle(
                'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
            ),
            0,
            50
        ) . '.' . $extension;

        $folderPath = public_path($folder_name);
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        if ($file instanceof UploadedFile) {
            $file->move($folderPath, $file_name);
        } else {
            // For raw content (though this case shouldn't happen with our current flow)
            file_put_contents($folderPath . '/' . $file_name, $file);
        }
        return "$folder_name/$file_name";
    }

    /**
     * Delete file from the public storage
     * @param string $path the path after app/public
     * @return bool
     */
    public static function deleteFile(string $path)
    {
        $filePath = public_path($path);
        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
            return true;
        }
        return false;
    }

    public static function convertImageToPdf(UploadedFile $imageFile)
    {
        $pdf = new TCPDF();
        $pdf->AddPage();

        $imagePath = $imageFile->getRealPath();
        list($imgWidth, $imgHeight) = getimagesize($imagePath);

        $pageWidth = $pdf->getPageWidth() - 10;
        $pageHeight = $pdf->getPageHeight() - 10;
        $margin = $pdf->getMargins();

        $availableWidth = $pageWidth - $margin['left'] - $margin['right'];
        $availableHeight = $pageHeight - $margin['top'] - $margin['bottom'];

        $imgWidthMM = $imgWidth * 0.264583;
        $imgHeightMM = $imgHeight * 0.264583;

        $scale = min($availableWidth / $imgWidthMM, $availableHeight / $imgHeightMM);

        $newWidth = $imgWidthMM * $scale;
        $newHeight = $imgHeightMM * $scale;

        $x = ($pageWidth - $newWidth) / 2;
        $y = ($pageHeight - $newHeight) / 2;

        $pdf->Image($imagePath, $x, $y, $newWidth, $newHeight);

        $tempPath = tempnam(sys_get_temp_dir(), 'pdf_');
        $pdf->Output($tempPath, 'F'); // Save to file

        return new UploadedFile(
            $tempPath,
            pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf',
            'application/pdf',
            null,
            true // Set test mode to true
        );
    }
}
