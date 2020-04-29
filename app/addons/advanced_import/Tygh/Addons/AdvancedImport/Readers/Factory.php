<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

namespace Tygh\Addons\AdvancedImport\Readers;

use Tygh\Addons\AdvancedImport\Exceptions\DownloadException;
use Tygh\Addons\AdvancedImport\Exceptions\FileNotFoundException;
use Tygh\Addons\AdvancedImport\Exceptions\ReaderNotFoundException;
use Tygh\Enum\Addons\AdvancedImport\PresetFileTypes;
use Tygh\Exceptions\PermissionsException;
use Tygh\Registry;
use Tygh\Storage;
use Tygh\Tools\SecurityHelper;

class Factory
{
    /** @var array $file_dirs */
    protected $file_dirs;

    /** @var int|null $company_id */
    protected $company_id;

    /** @var string  */
    const UPLOADED_FILE_NAME = 'upload';

    /**
     * Factory constructor.
     *
     * @param int|null $company_id Current user company ID
     */
    public function __construct($company_id)
    {
        $this->company_id = (int) $company_id;

        $this->file_dirs = $this->initFilesDirectories($company_id);
    }

    /**
     * Gets file reader.
     *
     * @param array $preset Preset to read file for
     *
     * @return \Tygh\Addons\AdvancedImport\Readers\IReader Reader instance
     * @throws \Tygh\Exceptions\PermissionsException
     * @throws \Tygh\Addons\AdvancedImport\Exceptions\FileNotFoundException
     * @throws \Tygh\Addons\AdvancedImport\Exceptions\ReaderNotFoundException
     * @throws \Tygh\Addons\AdvancedImport\Exceptions\DownloadException
     */
    public function get(array $preset)
    {
        $file_to_load = $preset['file'];

        if (!$this->company_id && isset($preset['company_id'])) {
            $company_id = $preset['company_id'];
            if (preg_match('!^(?P<company_id_in_path>\d+)/(?P<file_to_load>.+)!', $file_to_load, $matches)
                && $matches['company_id_in_path'] != $company_id
            ) {
                throw new PermissionsException();
            }
            $file_to_load = preg_replace("!^{$company_id}/!", '', $file_to_load);
        } else {
            $company_id = $this->company_id;
        }

        if ($preset['file_type'] == PresetFileTypes::URL) {
            $file = $this->download($preset['file'], $company_id);
            if (!$file) {
                throw new DownloadException();
            }
            $file_path = $file['path'];
            $file_to_load = $file['name'];
        } else {
            $file_path = $this->getFilePath($file_to_load, $company_id);
        }

        if (!$file_path) {
            throw new FileNotFoundException();
        }

        $ext = fn_get_file_ext($file_to_load);
        if (!$this->readerExists($ext)) {
            throw new ReaderNotFoundException();
        }

        $reader_class = $this->getReaderClass($ext);

        $options = isset($preset['options'])
            ? $preset['options']
            : array();

        /** @var \Tygh\Addons\AdvancedImport\Readers\IReader $reader */
        $reader = new $reader_class($file_path, $options);

        return $reader;
    }

    /**
     * Downloads file.
     *
     * @param string   $url        Url
     * @param int|null $company_id Company to download file for
     *
     * @return array|null
     */
    public function download($url, $company_id = null)
    {
        $url = urldecode($url);

        $fileinfo = fn_get_url_data($url);
        if (!$fileinfo) {
            return null;
        }

        $ext = fn_get_file_ext($fileinfo['name']);

        if (!$this->readerExists($ext)) {
            $mime_type = $this->getRemoteFileMimeType($url);
            $ext = $this->getFileExtensionByMimeType($mime_type);
        }

        if (!$ext) {
            return null;
        }

        if ($fileinfo['name'] == '') {
            $fileinfo['name'] = md5($url);
        }

        if (substr($fileinfo['name'], -(fn_strlen($ext) + 1)) !== '.' . $ext) {
            $fileinfo['name'] .= '.' . $ext;
        }

        if (!fn_check_uploaded_data($fileinfo, array())) {
            return null;
        }

        return $fileinfo;
    }

    /**
     * Gets filepath to a file on server.
     *
     * @param string     $filename   Filename
     * @param int|null   $company_id Company to search file for
     * @param array|null $file_dirs  Directories to search in
     *
     * @return null|string
     */
    public function getFilePath($filename, $company_id = null, array $file_dirs = null)
    {
        $company_id = $this->getCompanyId($company_id);

        if ($file_dirs === null) {
            if ($company_id == $this->company_id) {
                $file_dirs = $this->file_dirs;
            } else {
                $file_dirs = $this->initFilesDirectories($company_id);
            }
        }

        foreach ($file_dirs as $dir) {
            if (file_exists($dir . $filename)) {
                return $dir . $filename;
            }
        }

        return null;
    }

    /**
     * Gets path to private files directory.
     * Creates missing private files directory.
     *
     * @param int|null $company_id Company to get path for
     *
     * @return string Private files directory path
     */
    protected function getPrivateFilesPath($company_id = null)
    {
        $company_id = $this->getCompanyId($company_id);

        $path = fn_get_files_dir_path($company_id);

        fn_mkdir($path);

        return $path;
    }

    /**
     * Gets path to public files directory.
     * Creates missing public files directory.
     *
     * @param int|null $company_id Company to get path for
     *
     * @return string Public files directory path
     */
    protected function getPublicFilesPath($company_id = null)
    {
        $company_id = $this->getCompanyId($company_id);

        $path = fn_get_public_files_path($company_id);

        fn_mkdir($path);

        return $path;
    }

    public function initFilesDirectories($company_id = null)
    {
        $company_id = $this->getCompanyId($company_id);

        return array(
            'private' => $this->getPrivateFilesPath($company_id),
            'public'  => $this->getPublicFilesPath($company_id),
        );
    }

    /**
     * Moves file to a private files directory of a company. Filename will be changed if a file
     * with the same name already located in the private directory.
     *
     * @param string        $filename        Filename in the target directory.
     * @param string        $source_path     Current file location.
     * @param int|null      $company_id      Owning company of the file.
     *
     * @return string       new filename
     */
    public function moveUpload($filename, $source_path, $company_id = null)
    {
        $info = fn_pathinfo($this->getPrivateFilePath($filename, $company_id));

        while (file_exists($info['dirname'] . '/' . $info['filename'] . '.' . $info['extension'])) {
            $info['filename'] = fn_strtolower(fn_generate_code($info['filename'], 8));
        }

        $new_filename = $info['filename'] . '.' . $info['extension'];
        $uploaded_file_location = $this->getPrivateFilePath($new_filename, $company_id);

        fn_rename($source_path, $uploaded_file_location);

        return $new_filename;
    }

    /**
     * Provides corrected company ID for assorted checks.
     *
     * @param int|null $company_id Company ID to check
     *
     * @return int|null
     */
    protected function getCompanyId($company_id = null)
    {
        return $this->company_id ?: $company_id;
    }

    /**
     * Handles preset file upload process.
     *
     * For files uploaded by URL, performs validation by mime type.
     * For local and server uploades uses core upload behaviour.
     *
     * @param array    $preset     Preset data
     * @param int|null $company_id Company to download file for
     *
     * @return array Upload info with preset ID as an array key and fileinfo as value
     */
    public function uploadPresetFile(array $preset, $company_id = null)
    {
        $preset = array_merge(array(
            'file'      => '',
            'file_type' => PresetFileTypes::LOCAL,
            'preset_id' => 0,
        ), $preset);

        $file = array();
        if ($preset['file_type'] === PresetFileTypes::URL) {
            $downloaded_file = $this->download($preset['file'], $company_id);
            if ($downloaded_file) {
                $file = array($preset['preset_id'] => $downloaded_file);
            }
        } else {
            $downloaded_file = fn_filter_uploaded_data(self::UPLOADED_FILE_NAME);
            if ($downloaded_file) {
                $file = array($preset['preset_id'] => reset($downloaded_file));
            }
        }

        return $file;
    }

    /**
     * Checks if the reader for a specific file format exists.
     *
     * @param string $extension File extension
     *
     * @return bool
     */
    protected function readerExists($extension)
    {
        $reader_class = $this->getReaderClass($extension);

        return class_exists($reader_class);
    }

    /**
     * Gets classname of the reader for a specific file format.
     *
     * @param string $extension File extension
     *
     * @return string
     */
    protected function getReaderClass($extension)
    {
        return '\Tygh\Addons\AdvancedImport\Readers\\' . fn_camelize(strtolower($extension));
    }

    /**
     * @param string $url
     *
     * @return string|null
     */
    protected function getRemoteFileMimeType($url)
    {
        $url_scheme = parse_url($url, PHP_URL_SCHEME);
        if (!$url_scheme) {
            $url = sprintf('http://%s', $url);
        }

        $headers = get_headers($url, 1);

        if (empty($headers['Content-Type'])) {
            return null;
        }

        if (is_array($headers['Content-Type'])) {
            $content_type = end($headers['Content-Type']);
        } else {
            $content_type = $headers['Content-Type'];
        }

        list($content_type) = explode(';', $content_type);

        return trim($content_type);
    }

    /**
     * @param string $mime_type
     *
     * @return string|null
     */
    protected function getFileExtensionByMimeType($mime_type)
    {
        $mime_to_ext = array_merge(fn_get_ext_mime_types('mime'), [
            'text/xml' => 'xml'
        ]);

        return isset($mime_to_ext[$mime_type]) ? $mime_to_ext[$mime_type] : null;
    }

    /**
     * Removes file from a company private directory.
     *
     * @param string   $filename    File that must be removed
     * @param int|null $company_id  Owning company of the file
     */
    public function removeFile($filename, $company_id = null)
    {
        $path = $this->getPrivateFilePath($filename, $company_id);
        fn_rm($path);
    }

    /**
     * Returns a filepath for file that locate in company private directory.
     *
     * @param string   $filename    Filename that located in private directory
     * @param int|null $company_id  Owning company of the private directory
     *
     * @return string filepath
     */
    protected function getPrivateFilePath($filename, $company_id = null)
    {
        $company_id = $this->getCompanyId($company_id);

        return $this->getPrivateFilesPath($company_id) . $filename;
    }
}
