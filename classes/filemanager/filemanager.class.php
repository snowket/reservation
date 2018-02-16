<?
if(!defined('LANG')) define('LANG',"eng");
if(!defined('FILEMANAGER_PATH')) define('FILEMANAGER_PATH',dirname(__FILE__));

class FileManager {
	var $Messages=array();
	var $filetypes=array(
				"IMG_EXT"=>array("jpg","jpeg","gif","png","psd","bmp","tiff"),
				"ARCH_EXT"=>array("rar","zip","cab","arj","gzip","tar","gz"),
				"MEDIA_EXT"=>array("wav","mp3","mid","midi","avi","mpg","mpeg","asf","rm","wmv"),
				"TEXT_EXT"=>array("txt","htm","html","js","css","php")
			);

	var $PATH="";
	
	// reads contents of $folder into arrays
	function ReadDirectory($folder) {
		$contents = array();
		if($handle=@opendir($folder)) {
			while($f =readdir($handle)) {
				if(($f!=".")&&($f!="..")) {
					if(is_dir("$folder/$f"))
						$contents['FOLDERS'][]=$f;
					elseif(is_file("$folder/$f"))
						$contents['FILES'][]=$f;
				}
			}
			closedir($handle);
		}
		else {
			$this->_fixError($folder,'OPENDIR_FAILED');
			return false;
		}
		
		return $contents;
	}

	function filterFiles($filesArray,$types) {
		$filtered = array();
		for($i=0;$i<count($filesArray);$i++) {
			if(in_array($this->FileExtension($filesArray[$i],"LOWER"),$this->filetypes[$types]))
			$filtered[] = $filesArray[$i];
		}
		return $filtered;
	}

	// reads files of from $folder into array
	function ReadFiles($folder) {
		$files=array();
		if($handle=@opendir($folder)) {
			while($f =readdir($handle)) {
				if(is_file("{$folder}/{$f}"))
					$files[]=$f;
			}
		}
		else {
			$this->_fixError($folder,'OPENDIR_FAILED');
			return false;
		}
		return $files;
	}

	// removes folder and all it's contents.
	// example: remove_dir("path/folder");
	function RemoveDir($folder) {
		if($handle=@opendir($folder)) {
			while ($f =readdir($handle)) {
				$err=false;
				if(is_dir("$folder/$f")&&($f!=".")&&($f!=".."))
				$this->RemoveDir("$folder/$f");
				if(is_file("$folder/$f")) {
					if(!@unlink("$folder/$f")) {
						$this->_fixError("$folder/$f",'unlink_failed');
						return false;
					}
				}
			}
			closedir($handle);
		}
		else {
			$this->_fixError($folder,'OPENDIR_FAILED');
			return false;
		}
		
		if(!@rmdir($folder)) {
			$this->_fixError($folder,'rmdir_failed');
			return false;
		}
		return true;
	}

	function VerifyPath($sourse,$destination) {
		if(strstr(preg_replace("/\/|\\/","",$destination),preg_replace("/\/|\\/","",$sourse)))
			return false;
		return true;
	}

	// copies folder $sourse and all it's contents to folder $r_path.
	function CopyDir($sourse,$r_path) {
		if($handle=@opendir("$sourse")) {
			while($f =readdir($handle)) {
				if(is_dir("$sourse/$f")&&($f!=".")&&($f!=".."))
					$this->CopyDir("$sourse/$f","$r_path");
				elseif(is_file("$sourse/$f")) {
					if(!copy("$sourse/$f","$r_path/$f")) {
					$this->_fixError("$sourse/$f",'copy_failed');
					return false;
					}
				}
			}
			closedir($handle);
		}
		else {
			$this->_fixError($folder,'OPENDIR_FAILED');
			return false;
		}
		return true;
	}


	// moves folder $sourse and all it's contents to folder $r_path.
	function MoveDir($sourse,$r_path) {
		if(strstr($r_path,$sourse)) return false;
		if($handle=@opendir("$sourse")) {
			while($f =readdir($handle)) {
				if(is_dir("$sourse/$f")&&($f!=".")&&($f!=".."))
				$this->MoveDir("$sourse/$f","$r_path","");
				if(is_file("$sourse/$f")) {
					if(!@copy("$sourse/$f","$r_path/$f")) {
						$this->_fixError("$sourse/$f",'copy_failed');
						return false;
					}
					if(!@unlink("$sourse/$f")) {
						$this->_fixError("$sourse/$f",'unlink_failed');
						return false;
					}
				}
			}
			closedir($handle);
		}
		else {
			$this->_fixError($folder,'OPENDIR_FAILED');
			return false;
		}
		if(!rmdir("$sourse")) {
			$this->_fixError("$sourse/$f",'rmdir_failed');
			return false;
		}
		return true;
	}

	//moves file $filename to folder $r_path.
	function MoveFile($filename,$r_path) {
		if(is_file($filename)) {
			if(!@copy($filename,$r_path."/".basename($filename))) {
				$this->_fixError($filename,'copy_failed');
				return false;
			}
			if(!@unlink($filename)) {
				$this->_fixError($filename,'unlink_failed');
				return false;
			}
		}
		return true;
	}

	// returns size of directory $folder in bytes ,kilobytes or megabytes
	//$unit="" || "mb" || "kb"
	function DirSize($folder,$unit="",$accuracy=2) {
		$dirsize=0;
		if($handle=@opendir($folder)) {
			while($f =readdir($handle)) {
				if(is_dir($folder."/".$f)&&($f!=".")&&($f!=".."))
					$dirsize += $this->DirSize($folder."/".$f,$unit,$accuracy);
				elseif(is_file($folder."/".$f))
					$dirsize += @filesize($folder."/".$f);
			}
			closedir($handle);
		}
		else {
			$this->_fixError($folder,'OPENDIR_FAILED');
			return false;
		}
		
		if($unit=="kb")		return round($dirsize/1024,$accuracy);
		elseif($unit=="mb")	return round($dirsize/(1024*1024),$accuracy);
		else				return $dirsize;
	}

	// returns size of a file in bytes ,kilobytes or megabytes
	// $unit="" || "mb" || "kb"
	function FilesSize($filename,$unit="",$accuracy=2) {
		if(@is_file($filename)) {
			if($unit=="kb")		return round(@filesize($filename)/1024,$accuracy);
			elseif($unit=="mb")	return round(@filesize($filename)/(1024*1024),$accuracy);
			else				return @filesize($filename);
		}
		return false;
	}

	// uploads file $filename to directory $folder;
	// $allowed- array of allowed extensions
	function uploadFile($folder,$filename,$maxsize,$allowed=array(),$newname="") {
		if(!$_FILES[$filename]["name"]||!$_FILES[$filename]["size"])
		return false;

		if(!list($name,$ext)= $this->_parseFilename($_FILES[$filename]["name"])) {
			$this->_fixError($_FILES[$filename]["name"],'INVALID_FILENAME');
			return false;
		}
		if(!empty($allowed)) {
			if(!in_array($ext,$allowed)) {
				$this->_fixError($_FILES[$filename]["name"],'INVALID_TYPE');
				return false;
			}
		}
		if($_FILES[$filename]["size"]>$maxsize) {
			$this->_fixError($_FILES[$filename]["name"],'BIG_FILESIZE');
			return false;
		}
		$newname =($newname)?$newname.".".$ext:(StringConvertor::trimWhitespaces(preg_replace('/[^a-zA-Z0-9_\-\.\/]/','_',$_FILES[$filename]["name"]),true));
		if(!@move_uploaded_file($_FILES[$filename]['tmp_name'],$folder."/".$newname)) {
			$this->_fixError('','UPLOAD_FAILED');
			return false;
		}
		return $newname;
	}

	//$display= ALL||FOLDERS||FILES
	//$HTMLcode=none||radio||checkbox
	function PrintAsTree($folder,$display="ALL",$style="",$HTMLcode="radio",$FoldName="",$FileName="",$forbidden=array()) {
		$tree="";
		if($handle=@opendir($folder)) {
			$tree="<ul class=\"{$style}\">";
			while($f =readdir($handle)) {
				if(is_dir("$folder/$f")&&($f!=".")&&($f!="..")) {
					if($this->PATH=="") $this->PATH=$folder;
					$path=str_replace($this->PATH,"",$folder);
					if($display!="FILES") {
						$tree .="<li>";
						if($HTMLcode!="none")
						$tree .="<input type=\"{$HTMLcode}\" name=\"{$FoldName}\" value=\"{$path}/{$f}\">";
						$tree .=$f;
					}
					$tree .=$this->PrintAsTree("$folder/$f",$display,$style,$HTMLcode,$FoldName,$FileName);
				}
				if(is_file("$folder/$f")&&$display!="FOLDERS") {
					$tree .="<li>";
					if($HTMLcode!="none")
						$tree .="<input type=\"{$HTMLcode}\" name=\"{$FileName}\" value=\"{$path}/{$f}\">";
					$tree .=$f;
				}
			}
			closedir($handle);
			$tree .="</ul>";
		}
		return $tree;
	}

	function FileExtension($filename,$lowercase=true) {
		if($lowercase)
			return strtolower(@substr($filename,1+strrpos($filename,".")));
		return @substr($filename,1+strrpos($filename,"."));
	}

	function LastModified($filename,$format="d.m.Y") {
		return date($format,@filemtime($filename));
	}

	//**************************************************************//
	//	Returns error messages or FALSE								//
	//	if $asArray=true -as array, else as string					//
	//**************************************************************//
	function passErrors($lang=""){
		if($lang&&file_exists(FILEMANAGER_PATH."/lang/filemanager_".$lang.".php"))
			include_once(FILEMANAGER_PATH."/lang/filemanager_".$lang.".php");
		elseif(file_exists(FILEMANAGER_PATH."/lang/filemanager_".LANG.".php"))
			include_once(FILEMANAGER_PATH."/lang/filemanager_".LANG.".php");
		else
			include_once(FILEMANAGER_PATH."/lang/filemanager_eng.php");
		if(count($this->ERRORS)>0) {
			for($i=0;$i<count($this->ERRORS);$i++)
			$err[]= $this->ERRORS[$i][0].$FILEMANAGER_ERR[$this->ERRORS[$i][1]];
			return implode("<br>",$err);
		}
		return false;
	}


	//**************************************************************//
	//	FOR INTERNAL USE ONLY										//
	//**************************************************************//
	function _parseFilename($filename) {
		$extpos = @strrpos($filename,".");
		if($extpos>0&&$extpos<strlen($filename)-2)
			return array(substr($filename,0,$extpos),strtolower(substr($filename,$extpos+1)));
		else
			return false;
	}

	function _fixError($title,$err_id) {
		$this->ERRORS[]= array($title,$err_id);
	}
}
?>