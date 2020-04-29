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

namespace Tygh\ElFinder;

use Tygh\Common\OperationResult;

class Core extends \elFinder
{
    public function __construct($opts)
    {
        if (session_id() == '') {
            session_start();
        }

        $this->time = $this->utime();
        $this->debug = (isset($opts['debug']) && $opts['debug'] ? true : false);
        $this->timeout = (isset($opts['timeout']) ? $opts['timeout'] : 0);

        setlocale(LC_ALL, !empty($opts['locale']) ? $opts['locale'] : 'en_US.UTF-8');

        // bind events listeners
        if (!empty($opts['bind']) && is_array($opts['bind'])) {
            foreach ($opts['bind'] as $cmd => $handler) {
                $this->bind($cmd, $handler);
            }
        }

        if (!isset($opts['roots']) || !is_array($opts['roots'])) {
            $opts['roots'] = array();
        }

        // check for net volumes stored in session
        foreach ($this->getNetVolumes() as $root) {
            $opts['roots'][] = $root;
        }

        // "mount" volumes
        foreach ($opts['roots'] as $i => $o) {
            if (!empty($o['driver']) && strpos($o['driver'], '\\') !== false) {
                $class = $o['driver'];
            } else {
                $class = 'elFinderVolume' . (isset($o['driver']) ? $o['driver'] : '');
            }

            if (class_exists($class)) {
                $volume = new $class();

                if ($volume->mount($o)) {
                    // unique volume id (ends on "_") - used as prefix to files hash
                    $id = $volume->id();

                    $this->volumes[$id] = $volume;
                    if (!$this->default && $volume->isReadable()) {
                        $this->default = $this->volumes[$id];
                    }
                } else {
                    $this->mountErrors[] = 'Driver "' . $class . '" : ' . implode(' ', $volume->error());
                }
            } else {
                $this->mountErrors[] = 'Driver "' . $class . '" does not exists';
            }
        }

        // if at least one redable volume - ii desu >_<
        $this->loaded = !empty($this->default);
    }

    /**
     * @inheritdoc
     */
    public function bind($cmd, $handler)
    {
        $allCmds = array_keys($this->commands);
        $cmds = array();
        foreach (explode(' ', $cmd) as $_cmd) {
            if ($_cmd !== '') {
                if ($all = strpos($_cmd, '*') !== false) {
                    list(, $sub) = array_pad(explode('.', $_cmd), 2, '');
                    if ($sub) {
                        $sub = str_replace('\'', '\\\'', $sub);
                        $cmds = array_merge($cmds, array_map(function ($cmd) use ($sub) {
                            return $cmd . '.' . trim($sub);
                        }, $allCmds));
                    } else {
                        $cmds = array_merge($cmds, $allCmds);
                    }
                } else {
                    $cmds[] = $_cmd;
                }
            }
        }
        $cmds = array_unique($cmds);

        foreach ($cmds as $cmd) {
            if (!isset($this->listeners[$cmd])) {
                $this->listeners[$cmd] = array();
            }

            if (is_callable($handler)) {
                $this->listeners[$cmd][] = $handler;
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function upload($args)
    {
        $target = $args['target'];
        $volume = $this->volume($target);
        $files = isset($args['FILES']['upload']) && is_array($args['FILES']['upload']) ? $args['FILES']['upload'] : array();
        $result = array('added' => array(), 'header' => empty($args['html']) ? false : 'Content-Type: text/html; charset=utf-8');
        $paths = $args['upload_path'] ? $args['upload_path'] : array();
        $chunk = $args['chunk'] ? $args['chunk'] : '';
        $cid = $args['cid'] ? (int)$args['cid'] : '';

        if (!$volume) {
            return array('error' => $this->error(self::ERROR_UPLOAD, self::ERROR_TRGDIR_NOT_FOUND, '#' . $target));
        }

        // regist Shutdown function
        $GLOBALS['elFinderTempFiles'] = array();

        register_shutdown_function(function () {
            foreach (array_keys($GLOBALS['elFinderTempFiles']) as $f) {
                @unlink($f);
            }
        });

        // file extentions table by MIME
        $extTable = array_flip(array_unique($volume->getMimeTable()));

        if (empty($files)) {
            if (isset($args['upload']) && is_array($args['upload']) && ($tempDir = $this->getTempDir($volume->getTempPath()))) {
                $names = array();
                foreach ($args['upload'] as $i => $url) {
                    // check chunked file upload commit
                    if ($args['chunk']) {
                        if ($url === 'chunkfail' && $args['mimes'] === 'chunkfail') {
                            $this->checkChunkedFile(null, $chunk, $cid, $tempDir);
                            if (preg_match('/^(.+)(\.\d+_(\d+))\.part$/s', $chunk, $m)) {
                                $result['warning'] = $this->error(self::ERROR_UPLOAD_FILE, $m[1], self::ERROR_UPLOAD_TRANSFER);
                            }
                            return $result;
                        } else {
                            $tmpfname = $tempDir . '/' . $args['chunk'];
                            $files['tmp_name'][$i] = $tmpfname;
                            $files['name'][$i] = $url;
                            $files['error'][$i] = 0;
                            $GLOBALS['elFinderTempFiles'][$tmpfname] = true;
                            break;
                        }
                    }

                    $tmpfname = $tempDir . DIRECTORY_SEPARATOR . 'ELF_FATCH_' . md5($url . microtime(true));

                    // check is data:
                    if (substr($url, 0, 5) === 'data:') {
                        list($data, $args['name'][$i]) = $this->parse_data_scheme($url, $extTable);
                    } else {
                        $fp = fopen($tmpfname, 'wb');
                        $data = $this->get_remote_contents($url, 30, 5, 'Mozilla/5.0', $fp);
                    }
                    if ($data) {
                        $_name = isset($args['name'][$i]) ? $args['name'][$i] : preg_replace('~^.*?([^/#?]+)(?:\?.*)?(?:#.*)?$~', '$1', rawurldecode($url));
                        if ($_name) {
                            $_ext = '';
                            if (preg_match('/(\.[a-z0-9]{1,7})$/', $_name, $_match)) {
                                $_ext = $_match[1];
                            }
                            if ((is_resource($data) && fclose($data)) || file_put_contents($tmpfname, $data)) {
                                $GLOBALS['elFinderTempFiles'][$tmpfname] = true;
                                $_name = preg_replace('/[\/\\?*:|"<>]/', '_', $_name);
                                list($_a, $_b) = array_pad(explode('.', $_name, 2), 2, '');
                                if ($_b === '') {
                                    if ($_ext) {
                                        rename($tmpfname, $tmpfname . $_ext);
                                        $tmpfname = $tmpfname . $_ext;
                                    }
                                    $_b = $this->detectFileExtension($tmpfname);
                                    $_name = $_a . $_b;
                                } else {
                                    $_b = '.' . $_b;
                                }
                                if (isset($names[$_name])) {
                                    $_name = $_a . '_' . $names[$_name]++ . $_b;
                                } else {
                                    $names[$_name] = 1;
                                }
                                $files['tmp_name'][$i] = $tmpfname;
                                $files['name'][$i] = $_name;
                                $files['error'][$i] = 0;
                            } else {
                                @ unlink($tmpfname);
                            }
                        }
                    }
                }
            }
            if (empty($files)) {
                return array('error' => $this->error(self::ERROR_UPLOAD, self::ERROR_UPLOAD_NO_FILES));
            }
        }

        foreach ($files['name'] as $i => $name) {
            if (($error = $files['error'][$i]) > 0) {
                $result['warning'] = $this->error(self::ERROR_UPLOAD_FILE, $name, $error == UPLOAD_ERR_INI_SIZE || $error == UPLOAD_ERR_FORM_SIZE ? self::ERROR_UPLOAD_FILE_SIZE : self::ERROR_UPLOAD_TRANSFER);
                $this->uploadDebug = 'Upload error code: ' . $error;
                break;
            }

            $tmpname = $files['tmp_name'][$i];
            $path = ($paths && !empty($paths[$i])) ? $paths[$i] : '';
            if ($name === 'blob') {
                if ($chunk) {
                    if ($tempDir = $this->getTempDir($volume->getTempPath())) {
                        list($tmpname, $name) = $this->checkChunkedFile($tmpname, $chunk, $cid, $tempDir);
                        if ($name) {
                            $result['_chunkmerged'] = basename($tmpname);
                            $result['_name'] = $name;
                        }
                    } else {
                        $result['warning'] = $this->error(self::ERROR_UPLOAD_FILE, $chunk, self::ERROR_UPLOAD_TRANSFER);
                        $this->uploadDebug = 'Upload error: unable open tmp file';
                    }
                    return $result;
                } else {
                    // for form clipboard with Google Chrome
                    $type = $files['type'][$i];
                    $ext = isset($extTable[$type]) ? '.' . $extTable[$type] : '';
                    $name = substr(md5(basename($tmpname)), 0, 8) . $ext;
                }
            }

            // do hook function 'upload.presave'
            if (!empty($this->listeners['upload.presave'])) {
                foreach ($this->listeners['upload.presave'] as $handler) {
                    call_user_func_array($handler, array(&$path, &$name, $tmpname, $this, $volume));
                }
            }

            if (($fp = fopen($tmpname, 'rb')) == false) {
                $result['warning'] = $this->error(self::ERROR_UPLOAD_FILE, $name, self::ERROR_UPLOAD_TRANSFER);
                $this->uploadDebug = 'Upload error: unable open tmp file';
                if (!is_uploaded_file($tmpname)) {
                    if (@ unlink($tmpname)) unset($GLOBALS['elFinderTempFiles'][$tmpfname]);
                    continue;
                }
                break;
            }
            if ($path) {
                $_target = $volume->getUploadTaget($target, $path, $result);
            } else {
                $_target = $target;
            }
            if (!$_target || ($file = $volume->upload($fp, $_target, $name, $tmpname)) === false) {
                $result['warning'] = $this->error(self::ERROR_UPLOAD_FILE, $name, $volume->error());
                fclose($fp);
                if (!is_uploaded_file($tmpname)) {
                    if (@ unlink($tmpname)) unset($GLOBALS['elFinderTempFiles'][$tmpname]);;
                    continue;
                }
                break;
            }

            fclose($fp);
            if (!is_uploaded_file($tmpname) && @ unlink($tmpname)) unset($GLOBALS['elFinderTempFiles'][$tmpname]);
            $result['added'][] = $file;
        }
        if ($GLOBALS['elFinderTempFiles']) {
            foreach (array_keys($GLOBALS['elFinderTempFiles']) as $_temp) {
                @ unlink($_temp);
            }
        }
        $result['removed'] = $volume->removed();
        return $result;
    }

    /**
     * Get temporary dirctroy path
     *
     * @param  string $volumeTempPath
     * @return string
     * @author Naoki Sawada
     */
    private function getTempDir($volumeTempPath = null)
    {
        $testDirs = array();
        if (function_exists('sys_get_temp_dir')) {
            $testDirs[] = sys_get_temp_dir();
        }
        if ($volumeTempPath) {
            $testDirs[] = rtrim(realpath($volumeTempPath), DIRECTORY_SEPARATOR);
        }
        $tempDir = '';
        $test = DIRECTORY_SEPARATOR . microtime(true);
        foreach ($testDirs as $testDir) {
            if (!$testDir) continue;
            $testFile = $testDir . $test;
            if (touch($testFile)) {
                unlink($testFile);
                $tempDir = $testDir;
                $gc = time() - 3600;
                foreach (glob($tempDir . '/ELF*') as $cf) {
                    if (filemtime($cf) < $gc) {
                        @unlink($cf);
                    }
                }
                break;
            }
        }
        return $tempDir;
    }

    /**
     * Check chunked upload files
     *
     * @param string $tmpname uploaded temporary file path
     * @param string $chunk uploaded chunk file name
     * @param string $cid uploaded chunked file id
     * @param string $tempDir temporary dirctroy path
     * @return array (string JoinedTemporaryFilePath, string FileName) or (empty, empty)
     * @author Naoki Sawada
     */
    private function checkChunkedFile($tmpname, $chunk, $cid, $tempDir)
    {
        if (preg_match('/^(.+)(\.\d+_(\d+))\.part$/s', $chunk, $m)) {
            $encname = md5($cid . '_' . $m[1]);
            $part = $tempDir . '/ELF' . $encname . $m[2];
            if (is_null($tmpname)) {
                // chunked file upload fail
                foreach (glob($tempDir . '/ELF' . $encname . '*') as $cf) {
                    @unlink($cf);
                }
                return;
            }
            if (move_uploaded_file($tmpname, $part)) {
                @chmod($part, 0600);
                $total = $m[3];
                $parts = array();
                for ($i = 0; $i <= $total; $i++) {
                    $name = $tempDir . '/ELF' . $encname . '.' . $i . '_' . $total;
                    if (is_readable($name)) {
                        $parts[] = $name;
                    } else {
                        $parts = null;
                        break;
                    }
                }
                if ($parts) {
                    $check = $tempDir . '/ELF' . $encname;
                    if (!is_file($check)) {
                        touch($check);
                        if ($resfile = tempnam($tempDir, 'ELF')) {
                            $target = fopen($resfile, 'wb');
                            foreach ($parts as $f) {
                                $fp = fopen($f, 'rb');
                                while (!feof($fp)) {
                                    fwrite($target, fread($fp, 8192));
                                }
                                fclose($fp);
                                unlink($f);
                            }
                            fclose($target);
                            unlink($check);
                            return array($resfile, $m[1]);
                        }
                        unlink($check);
                    }
                }
            }
        }
        return array('', '');
    }

    /**
     * Checks whether file with the specified extension can be created, renamed or uploaded in the volume.
     *
     * @param \Tygh\ElFinder\Volume $volume   Target file volume
     * @param string                $filename Filename
     *
     * @return \Tygh\Common\OperationResult
     */
    protected function tyghIsFileExtensionAllowed($volume, $filename)
    {
        $result = new OperationResult(true);

        $file_extension = fn_strtolower(fn_get_file_ext($filename));

        $forbidden_extensions = array_intersect(
            $volume->getMimeTable(),
            $volume->tyghGetDeniedMimeTypes()
        );

        if (isset($forbidden_extensions[$file_extension])) {
            $result->setSuccess(false);
            $result->addError(
                0,
                strip_tags(
                    __('text_forbidden_file_extension', array('[ext]' => $file_extension))
                )
            );
        }

        return $result;
    }

    /**
     * Checks whether the file extension is allowed and then renames the file.
     *
     * @see \elFinder::rename()
     *
     * @param array $args Renamed file
     *
     * @return array Operation result
     **/
    protected function rename($args)
    {
        /** @var \Tygh\ElFinder\Volume $volume */
        $volume = $this->getVolume($args['target']);

        $ext_check = $this->tyghIsFileExtensionAllowed($volume, $args['name']);

        if ($ext_check->isSuccess()) {
            return parent::rename($args);
        } else {
            return array('error' => $this->error($ext_check->getFirstError()));
        }
    }

    /**
     * Checks whether the file extension is allowed and then creates the file.
     *
     * @see \elFinder::mkfile()
     *
     * @param array $args Created file
     *
     * @return array Operation result
     **/
    protected function mkfile($args)
    {
        /** @var \Tygh\ElFinder\Volume $volume */
        $volume = $this->getVolume($args['target']);

        $ext_check = $this->tyghIsFileExtensionAllowed($volume, $args['name']);

        if ($ext_check->isSuccess()) {
            return parent::mkfile($args);
        } else {
            return array('error' => $this->error($ext_check->getFirstError()));
        }
    }
}
