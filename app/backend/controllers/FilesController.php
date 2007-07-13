<?php

/**
 * class FilesController
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class FilesController extends Controller
{
    var $path;
    var $fullpath;

    function index()
    {
        $this->browse();
    }

    function browse()
    {
        include_javascript('pages');
        
        $this->path = join('/',get_params());
        // make sure there's a / at the end
        if (substr($this->path, -1, 1) != '/') $this->path .= '/';

        //security

        // we dont allow back link
        $this->path = preg_replace('/\./', '', $this->path);

        // clean up nicely
        $this->path = preg_replace('/\/\//', '', $this->path);

        // we dont allow leading slashes
        $this->path = preg_replace('/^\//', '', $this->path); 

        $this->fullpath = FILES_DIR.'/'.$this->path;

        // clean up nicely
        $this->fullpath = preg_replace('/\/\//', '/', $this->fullpath);

        $this->setLayout('backend');
        $this->setView('files/index');
        $this->assignToView('dir', $this->path);
        $this->assignToView('files', $this->_getListFiles());
        $this->render();
    } // index

    function view()
    {
        $content = '';
        $filename = join('/',get_params());
        $file = FILES_DIR.'/'.$filename;
        if (!$this->_isImage($file)) {
            if (file_exists($file)) {
                // get content and make some changes to display
                $content = file_get_contents($file);
                $content = preg_replace("/&shy;|&amp;shy;/","\\xad", $content);
                $content = str_replace('<','&lt;', $content);
                $content = str_replace('>','&gt;', $content);
            }
        } // if

        $this->setLayout('backend');
        $this->setView('files/view');
        $this->assignToView('is_image', $this->_isImage($file));
        $this->assignToView('filename', $filename);
        $this->assignToView('content', $content);
        $this->render();
    } // index

    function save()
    {
        $data = array_var($_POST, 'file');
        
        // security (remove all ..)
        $data['name'] = str_replace('..', '', $data['name']);
        $file = FILES_DIR.'/'.$data['name'];
        if (file_exists($file)) {
            file_put_contents($file, $data['content']);
            flash_success('File has been saved with success!');
        } else {
            if (file_put_contents($file, $data['content']) !== false) {
                flash_success('File '.$data['name'].' has been created with success!');
            } else {
                flash_error('Directory is not writable! File has not been saved!');
            }
        }
        
        redirect_to(get_url('files', 'browse', substr($data['name'], 0, strrpos($data['name'], '/'))));
    } // save
    
    function create_file()
    {
        $data = array_var($_POST, 'file');
        
        $path = str_replace('..', '', $data['path']);
        $filename = str_replace('..', '', $data['name']);
        $file = FILES_DIR."/{$path}/{$filename}";
        
        if (file_put_contents($file, '') !== false) {
            chmod($file, 0644);
            flash_success("File {$filename} has been created!");
        } else {
            flash_error("File {$filename} has not been created!");
        }
        redirect_to(get_url('files', 'browse', $path));
    }
    
    function create_directory()
    {
        $data = array_var($_POST, 'directory');
        
        $path = str_replace('..', '', $data['path']);
        $dirname = str_replace('..', '', $data['name']);
        $dir = FILES_DIR."/{$path}/{$dirname}";
        
        if (mkdir($dir)) {
            chmod($dir, 0755);
            flash_success("Directory {$dirname} has been created!");
        } else {
            flash_error("Directory {$dirname} has not been created!");
        }
        redirect_to(get_url('files', 'browse', $path));
    }
    
    function delete()
    {
        $paths = get_params();
        $file = join('/', $paths);
        $file = FILES_DIR.'/'.str_replace('..', '', $file);
        $filename = array_pop($paths);
        $paths = join('/', $paths);
        if (is_file($file)) {
            if (unlink($file)) {
                flash_success('File '.$filename.' has been deleted with success!');
            } else {
                flash_error('File '.$filename.' has not been deleted!');
            }
        } else {
            if (rrmdir($file)) {
                flash_success('Directory '.$filename.' has been deleted with success!');
            } else {
                flash_error('Directory '.$filename.' has not been deleted!');
            }
        }
        
        redirect_to(get_url('files', 'browse', $paths));
    }
    
    function upload()
    {
        $data = array_var($_POST, 'upload');
        $path = str_replace('..', '', $data['path']);
        $overwrite = isset($data['overwrite']) ? true: false;
        
        if (isset($_FILES)) {
            $file = upload_file($_FILES['upload_file']['name'], FILES_DIR.'/'.$path.'/', $_FILES['upload_file']['tmp_name'], $overwrite);
            if ($file !== false) {
                flash_success('File '.$file.' has been uploaded with success!');
            } else {
                flash_error('File has not been uploaded!');
            }
        }
        redirect_to(get_url('files', 'browse', $path));
    } // upload

    function chmod()
    {
        $data = array_var($_POST, 'file');
        $data['name'] = str_replace('..', '', $data['name']);
        $file = FILES_DIR.'/'.$data['name'];
        if (file_exists($file)) {
            if (chmod($file, octdec($data['mode']))) {
                if (is_file($file)) {
                    flash_success('Permissions of file has been changed!');
                } else {
                    flash_success('Permissions of directory has been changed!');
                }
            } else {
                flash_error('Change mode has not been done sorry!');
            }
        } else {
            flash_error('File or directory not found!');
        }
        $path = substr($data['name'], 0, strrpos($data['name'], '/'));
        redirect_to(get_url('files', 'browse', $path));
    }
    
    //
    // Privates
    //

    function _getPath()
    {
        $path = join('/', get_params());
        return str_replace('..', '', $path);
    } // _getPath
    
    function _getListFiles()
    { 
        if ($handle = opendir($this->fullpath)) {
            $files = array();

            // check each files ...
            while (false !== ($file = readdir($handle))) {
                // do not display . and the root ..
                if ($file == '.' || $file == '..') continue;

                $object = new stdClass;
                $file_stat = stat($this->fullpath.$file);

                // make the link depending on if it's a file or a dir
                if (is_dir($this->fullpath.$file)) {
                    $object->is_dir = true;
                    $object->is_file = false;
                    $object->link = '<a href="'.get_url('files', 'browse', $this->path.$file).'">'.$file.'</a>';
                } else {
                    $object->is_dir = false;
                    $object->is_file = true;
                    $object->link = '<a href="'.get_url('files', 'view', $this->path.$file).'">'.$file.'</a>';
                } // if

                $object->name = $file;
                // humain size
                $object->size = convert_size($file_stat['size']);
                // permission
                $object->perms = $this->_getPermissions($this->fullpath.$file);
                // date modification
                $object->mtime = date('D, j M, Y', $file_stat['mtime']);
                $files[] = $object;
            } // while
            closedir($handle);
        } // if

        return $files;
    } // _getListFiles

    function _getPermissions($file)
    {
        $perms = fileperms($file);

        if (($perms & 0xC000) == 0xC000) {
            // Socket
            $info = 's';
        } elseif (($perms & 0xA000) == 0xA000) {
            // Symbolic Link
            $info = 'l';
        } elseif (($perms & 0x8000) == 0x8000) {
            // Regular
            $info = '-';
        } elseif (($perms & 0x6000) == 0x6000) {
            // Block special
            $info = 'b';
        } elseif (($perms & 0x4000) == 0x4000) {
            // Directory
            $info = 'd';
        } elseif (($perms & 0x2000) == 0x2000) {
            // Character special
            $info = 'c';
        } elseif (($perms & 0x1000) == 0x1000) {
            // FIFO pipe
            $info = 'p';
        } else {
            // Unknown
            $info = 'u';
        }

        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
                 (($perms & 0x0800) ? 's' : 'x' ) :
                 (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
                 (($perms & 0x0400) ? 's' : 'x' ) :
                 (($perms & 0x0400) ? 'S' : '-'));

        // World
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
                 (($perms & 0x0200) ? 't' : 'x' ) :
                 (($perms & 0x0200) ? 'T' : '-'));

        $info .= ' ('.substr(sprintf('%o', $perms), -4, 4).')';
        return $info;
    } // _getPermissions

    function _isImage($file)
    {
        if (!@is_file($file)) return false;
        else if (!@exif_imagetype($file)) return false;
        else return true;
    } // _isImage

} // FilesController
