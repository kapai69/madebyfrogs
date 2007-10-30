<?php

/**
 * class FilesController
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class FileController extends Controller
{
    var $path;
    var $fullpath;
    
    public function __construct()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn()) {
            redirect(get_url('login'));
        }
    }
    
    public function index()
    {
        $this->browse();
    }
    
    public function browse()
    {
        $this->path = join('/', Dispatcher::getParams());
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
        $this->display('file/index', array(
            'dir' => $this->path,
            'files' => $this->_getListFiles()
        ));
    } // browse
    
    public function view()
    {
        $content = '';
        $filename = join('/', Dispatcher::getParams());
        $file = FILES_DIR.'/'.$filename;
        if ( ! $this->_isImage($file) && file_exists($file)) {
            $content = file_get_contents($file);
        }
        
        $this->setLayout('backend');
        $this->display('file/view', array(
            'is_image'  => $this->_isImage($file),
            'filename'  => $filename,
            'content'   => $content
        ));
    }
    
    public function save()
    {
        $data = $_POST['file'];
        
        // security (remove all ..)
        $data['name'] = str_replace('..', '', $data['name']);
        $file = FILES_DIR.'/'.$data['name'];
        if (file_exists($file)) {
            file_put_contents($file, $data['content']);
            //Flash::set('success', 'File has been saved with success!');
        } else {
            if (file_put_contents($file, $data['content']) !== false) {
                //Flash::set('success', __('File :name has been created with success!', array(':name'=>$data['name'])));
            } else {
                Flash::set('error', __('Directory is not writable! File has not been saved!'));
            }
        }
        
        redirect(get_url('file/browse/'.substr($data['name'], 0, strrpos($data['name'], '/'))));
    }
    
    public function create_file()
    {
        $data = $_POST['file'];
        
        $path = str_replace('..', '', $data['path']);
        $filename = str_replace('..', '', $data['name']);
        $file = FILES_DIR."/{$path}/{$filename}";
        
        if (file_put_contents($file, '') !== false) {
            chmod($file, 0644);
            //Flash::set('success', __('File :name has been created!', array(':name'=>$filename)));
        } else {
            Flash::set('error', __('File :name has not been created!', array(':name'=>$filename)));
        }
        redirect(get_url('file/browse/'.$path));
    }
    
    public function create_directory()
    {
        $data = $_POST['directory'];
        
        $path = str_replace('..', '', $data['path']);
        $dirname = str_replace('..', '', $data['name']);
        $dir = FILES_DIR."/{$path}/{$dirname}";
        
        if (mkdir($dir)) {
            chmod($dir, 0755);
            //Flash::set('success', __('Directory :name has been created!', array(':name'=>$dirname)));
        } else {
            Flash::set('error', __('Directory :name has not been created!', array(':name'=>$dirname)));
        }
        redirect(get_url('file/browse/'.$path));
    }
    
    public function delete()
    {
        $paths = Dispatcher::getParams();
        $file = join('/', $paths);
        $file = FILES_DIR.'/'.str_replace('..', '', $file);
        $filename = array_pop($paths);
        $paths = join('/', $paths);
        if (is_file($file)) {
            if (unlink($file)) {
                //Flash::set('success', __('File :name has been deleted with success!', array(':name'=>$filename)));
            } else {
                Flash::set('error', __('File :name has not been deleted!', array(':name'=>$filename)));
            }
        } else {
            if (rrmdir($file)) {
                //Flash::set('success', __('Directory :name has been deleted with success!', array(':name'=>$filename)));
            } else {
                Flash::set('error', __('Directory :name has not been deleted!', array(':name'=>$filename)));
            }
        }
        
        redirect(get_url('file/browse/'.$paths));
    }
    
    public function upload()
    {
        $data = $_POST['upload'];
        $path = str_replace('..', '', $data['path']);
        $overwrite = isset($data['overwrite']) ? true: false;
        
        if (isset($_FILES)) {
            $file = upload_file($_FILES['upload_file']['name'], FILES_DIR.'/'.$path.'/', $_FILES['upload_file']['tmp_name'], $overwrite);
            if ($file !== false) {
                //Flash::set('success', __('File :name has been uploaded with success!', array(':name'=>$file)));
            } else {
                Flash::set('error', __('File has not been uploaded!'));
            }
        }
        redirect(get_url('file/browse/'.$path));
    }
    
    public function chmod()
    {
        $data = $_POST['file'];
        $data['name'] = str_replace('..', '', $data['name']);
        $file = FILES_DIR.'/'.$data['name'];
        if (file_exists($file)) {
            if (chmod($file, octdec($data['mode']))) {
                //if (is_file($file)) {
                //    Flash::set('success', __('Permissions of file has been changed!'));
                //} else {
                //    Flash::set('success', __('Permissions of directory has been changed!'));
                //}
            } else {
                Flash::set('error', __('Change mode has not been done!'));
            }
        } else {
            Flash::set('error', __('File or directory not found!'));
        }
        $path = substr($data['name'], 0, strrpos($data['name'], '/'));
        redirect(get_url('file/browse/'.$path));
    }
    
    //
    // Privates
    //
    
    public function _getPath()
    {
        $path = join('/', get_params());
        return str_replace('..', '', $path);
    }
    
    public function _getListFiles()
    {
        $files = array();
        
        if (is_dir($this->fullpath) && $handle = opendir($this->fullpath)) {
            
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
                    $object->link = '<a href="'.get_url('file/browse/'.$this->path.$file).'">'.$file.'</a>';
                } else {
                    $object->is_dir = false;
                    $object->is_file = true;
                    $object->link = '<a href="'.get_url('file/view/'.$this->path.$file).'">'.$file.'</a>';
                }
                
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
        }
        
        return $files;
    } // _getListFiles

    public function _getPermissions($file)
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

    public function _isImage($file)
    {
        if (!@is_file($file)) return false;
        else if (!@exif_imagetype($file)) return false;
        else return true;
    }

} // end FileController class

// Usage: upload_file($_FILE['file']['name'],'temp/',$_FILE['file']['tmp_name'])
function upload_file($origin, $dest, $tmp_name, $overwrite=false)
{
    $origin = strtolower(basename($origin));
    $full_dest = $dest.$origin;
    $file_name = $origin;
    for ($i=1; file_exists($full_dest); $i++) {
        if ($overwrite) {
            unlink($full_dest);
            continue;
        }
        $file_ext = (strpos($origin, '.') === false ? '': '.'.substr(strrchr($origin, '.'), 1));
        $file_name = substr($origin, 0, strlen($origin) - strlen($file_ext)).'_'.$i.$file_ext;
        $full_dest = $dest.$file_name;
    }

    if (move_uploaded_file($tmp_name, $full_dest)) {
        // change mode of the dire to 0644 by default
        chmod($full_dest, 0644);
        return $file_name;
    }
    return false;
} // upload_file

// recursiv rmdir
function rrmdir($dirname)
{
    if (is_dir($dirname)) { // Operate on dirs only
        if (substr($dirname,-1)!='/') { $dirname.='/'; } // Append slash if necessary
        $handle = opendir($dirname);
        while (false !== ($file = readdir($handle))) {
            if ($file!='.' && $file!= '..') { // Ignore . and ..
                $path = $dirname.$file;
                if (is_dir($path)) { // Recurse if subdir, Delete if file
                    rrmdir($path);
                } else {
                    unlink($path);
                }
            }
        }
        closedir($handle);
        rmdir($dirname); // Remove dir
        return true; // Return array of deleted items
    } else {
        return false; // Return false if attempting to operate on a file
    }
} // rrmdir
