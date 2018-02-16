<?php
/**
 * $Id: ImageManagerPlugin.php 567 2008-11-06 16:49:12Z spocke $
 *
 * @package MCImageManager
 * @author Moxiecode
 * @copyright Copyright © 2007, Moxiecode Systems AB, All rights reserved.
 */

require_once($basepath . "ImageManager/Utils/MCImageToolsGD.php");

/**
 * This plugin class contans the core logic of the MCImageManager application.
 *
 * @package MCImageManager
 */
class Moxiecode_ImageManagerPlugin extends Moxiecode_ManagerPlugin {
	/**#@+
	 * @access public
	 */

	/**
	 * Constructs a new imagemanager instance.
	 */
	function Moxiecode_ImageManagerPlugin() {
	}

	function onPreInit(&$man, $prefix) {
		global $mcImageManagerConfig;

		if ($prefix == "im") {
			$man->setConfig($mcImageManagerConfig, false);
			$man->setLangPackPath("im");
			return false;
		}

		return true;
	}

	/**
	 * Gets executed when a RPC command is to be executed.
	 *
	 * @param MCManager $man MCManager reference that the plugin is assigned to.
	 * @param string $cmd RPC Command to be executed.
	 * @param object $input RPC input object data.
	 * @return object Result data from RPC call or null if it should be passed to the next handler in chain.
	 */
	function onRPC(&$man, $cmd, $input) {
		switch ($cmd) {
			case "getMediaInfo":
				return $this->_getMediaInfo($man, $input);

			case "resizeImage":
				$result = new Moxiecode_ResultSet("status,file,message");
				$file = $man->getFile($input["path"]);

				/*
				if ($man->verifyFile($file, "edit") < 0) {
					$result->add("FAILED", $man->encryptPath($file->getAbsolutePath()), $man->getInvalidFileMsg());
					return $result->toArray();
				}*/

				$filedata = array();
				$filedata["path"] = $man->encryptPath($file->getAbsolutePath());
				$filedata["width"] = isset($input["width"]) ? $input["width"] : 0;
				$filedata["height"] = isset($input["height"]) ? $input["height"] : 0;
				$filedata["target"] = isset($input["target"]) ? $input["target"] : "";
				$filedata["temp"] = isset($input["temp"]) ? $input["temp"] : "";

				$this->_resizeImage($man, $file, $filedata, $result);

				return $result->toArray();

			case "cropImage":
				$result = new Moxiecode_ResultSet("status,file,message");
				$file = $man->getFile($input["path"]);

				/*
				if ($man->verifyFile($file, "edit") < 0) {
					$result->add("FAILED", $man->encryptPath($file->getAbsolutePath()), $man->getInvalidFileMsg());
					return $result->toArray();
				}*/

				$filedata = array();
				$filedata["path"] = $man->encryptPath($file->getAbsolutePath());
				$filedata["width"] = $input["width"];
				$filedata["height"] = $input["height"];
				$filedata["top"] = $input["top"];
				$filedata["left"] = $input["left"];
				$filedata["target"] = isset($input["target"]) ? $input["target"] : "";
				$filedata["temp"] = isset($input["temp"]) ? $input["temp"] : "";

				$this->_cropImage($man, $file, $filedata, $result);

				return $result->toArray();

			case "rotateImage":
				$result = new Moxiecode_ResultSet("status,file,message");
				$file = $man->getFile($input["path"]);

				/*
				if ($man->verifyFile($file, "edit") < 0) {
					$result->add("FAILED", $man->encryptPath($file->getAbsolutePath()), $man->getInvalidFileMsg());
					return $result->toArray();
				}*/

				$filedata = array();
				$filedata["path"] = $file->getAbsolutePath();
				$filedata["angle"] = $input["angle"];
				$filedata["target"] = isset($input["target"]) ? $input["target"] : "";
				$filedata["temp"] = isset($input["temp"]) ? $input["temp"] : "";

				$this->_rotateImage($man, $file, $filedata, $result);

				return $result->toArray();

			case "flipImage":
				$result = new Moxiecode_ResultSet("status,file,message");
				$file = $man->getFile($input["path"]);

				/*
				if ($man->verifyFile($file, "edit") < 0) {
					$result->add("ACCESS_ERROR", $man->encryptPath($file->getAbsolutePath()), $man->getInvalidFileMsg());
					return $result->toArray();
				}*/

				$filedata = array();
				$filedata["path"] = $file->getAbsolutePath();
				$filedata["vertical"] = isset($input["vertical"]) ? $input["vertical"] : false;
				$filedata["horizontal"] = isset($input["horizontal"]) ? $input["horizontal"] : false;
				$filedata["target"] = isset($input["target"]) ? $input["target"] : "";
				$filedata["temp"] = isset($input["temp"]) ? $input["temp"] : "";

				$this->_flipImage($man, $file, $filedata, $result);

				return $result->toArray();

			case "saveImage":
				$config = $man->getConfig();
				$result = new Moxiecode_ResultSet("status,file,message");
				$file = $man->getFile($input["path"]);

				if (checkBool($config["general.demo"])) {
					$result->add("FAILED", $man->encryptPath($input["target"]), "{#error.demo}");
					$this->_cleanUp($man, $file->getParent());
					return $result->toArray();
				}

				/*if ($man->verifyFile($file, "edit") < 0) {
					$result->add("FAILED", $man->encryptPath($file->getAbsolutePath()), $man->getInvalidFileMsg());
					$this->_cleanUp($man, $file->getParent());
					return $result->toArray();
				}*/

				$filedata = array();
				$filedata["path"] = $file->getAbsolutePath();

				if (isset($input["target"]) && $input["target"] != "") {
					$targetFile = $man->getFile(utf8_encode($file->getParent()), $input["target"]);
					$filedata["target"] = utf8_encode($targetFile->getAbsolutePath());
				}

				$this->_saveImage($man, $file, $filedata, $result);
				$this->_cleanUp($man, $file->getParent());

				return $result->toArray();
		}

		return null;
	}

	/**
	 * Gets called when data is streamed to client. This method should setup
	 * HTTP headers, content type etc and simply send out the binary data to the client and the return false
	 * ones that is done.
	 *
	 * @param MCManager $man MCManager reference that the plugin is assigned to.
	 * @param string $cmd Stream command that is to be performed.
	 * @param string $input Array of input arguments.
	 * @return bool true/false if the execution of the event chain should continue.
	 */
	function onStream(&$man, $cmd, $input) {
		switch ($cmd) {
			case "thumb":
				return $this->_streamThumb($man, $input);
		}

		return null;
	}

	/**
	 * Gets called before a file action occurs for example before a rename or copy.
	 *
	 * @param ManagerEngine $man ManagerEngine reference that the plugin is assigned to.
	 * @param int $action File action constant for example DELETE_ACTION.
	 * @param BaseFile $file1 File object 1 for example from in a copy operation.
	 * @param BaseFile $file2 File object 2 for example to in a copy operation. Might be null in for example a delete.
	 * @return bool true/false if the execution of the event chain should continue.
	 */
	function onBeforeFileAction(&$man, $action, $file1, $file2) {
		if ($action == DELETE_ACTION) {
			// Delete format images
			$config = $file1->getConfig();

			if (checkBool($config['filesystem.delete_format_images'])) {
				$imageutils = new $config['thumbnail'];
				$imageutils->deleteFormatImages($file1->getAbsolutePath(), $config["upload.format"]);
				$imageutils->deleteFormatImages($file1->getAbsolutePath(), $config["edit.format"]);
			}
		}

		return true;
	}

	/**
	 * Gets called after a file action was perforem for example after a rename or copy.
	 *
	 * @param MCManager $man MCManager reference that the plugin is assigned to.
	 * @param int $action File action constant for example DELETE_ACTION.
	 * @param string $file1 File object 1 for example from in a copy operation.
	 * @param string $file2 File object 2 for example to in a copy operation. Might be null in for example a delete.
	 * @return bool true/false if the execution of the event chain should continue.
	 */
	function onFileAction(&$man, $action, $file1, $file2) {
		switch ($action) {
			case ADD_ACTION:
				$config = $file1->getConfig();

				if ($config["upload.format"]) {
					$imageutils = new $config['thumbnail'];
					$imageutils->formatImage($file1->getAbsolutePath(), $config["upload.format"], $config['upload.autoresize_jpeg_quality']);
				}

				if (checkBool($config["upload.create_thumbnail"]))
					$thumbnail = $this->_createThumb($man, $file1);

				if (checkBool($config['upload.autoresize'])) {
					$ext = getFileExt($file1->getName());

					if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png')))
						return true;

					$imageInfo = @getimagesize($file1->getAbsolutePath());
					$fileWidth = $imageInfo[0];
					$fileHeight = $imageInfo[1];

					$imageutils = new $config['thumbnail'];
					$percentage = min($config['upload.max_width'] / $fileWidth, $config['upload.max_height'] / $fileHeight);

					if ($percentage <= 1)
						$result = $imageutils->resizeImage($file1->getAbsolutePath(), $file1->getAbsolutePath(), round($fileWidth * $percentage), round($fileHeight * $percentage), $ext, $config['upload.autoresize_jpeg_quality']);
				}
				break;

			case DELETE_ACTION:
				$config = $file1->getConfig();

				if ($config['thumbnail.delete'] == true) {
					$thumbnailFolder = $man->getFile(dirname($file1->getAbsolutePath()) ."/". $config['thumbnail.folder']);
					$thumbnailPath = $thumbnailFolder->getAbsolutePath() . "/" . $config['thumbnail.prefix'] . basename($file1->getAbsolutePath());
					$thumbnail = $man->getFile($thumbnailPath);

					if ($thumbnail->exists())
						$thumbnail->delete();

					// Check if thumbnail directory should be deleted
					if ($thumbnailFolder->exists()) {
						$files = $thumbnailFolder->listFiles();

						if (count($files) == 0)
							$thumbnailFolder->delete();
					}
				}

				break;
		}

		return true; // Pass to next plugin
	}

	/**
	 * Gets called when custom data is to be added for a file custom data can for example be
	 * plugin specific name value items that should get added into a file listning.
	 *
	 * @param MCManager $man MCManager reference that the plugin is assigned to.
	 * @param BaseFile $file File reference to add custom info/data to.
	 * @param string $type Where is the info needed for example list or info.
	 * @param Array $custom Name/Value array to add custom items to.
	 * @return bool true/false if the execution of the event chain should continue.
	 */
	function onCustomInfo(&$man, &$file, $type, &$input) {
		// Is file and image
		$config = $file->getConfig();
		$input["editable"] = false;

		if ($file->isFile() && ($type == "list" || $type == "insert" || $type == "info")) {
			// Should we get config on each file here?
			//$config = $file->getConfig();
			$ext = getFileExt($file->getName());

			if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png', 'bmp')))
				return true;

			$imageutils = new $config['thumbnail'];
			$canEdit = $imageutils->canEdit($ext);

			$imageInfo = @getimagesize($file->getAbsolutePath());

			$fileWidth = $imageInfo[0];
			$fileHeight = $imageInfo[1];

			$targetWidth = $config['thumbnail.width'];
			$targetHeight = $config['thumbnail.height'];

			// Check thumnail size
			if ($config['thumbnail.scale_mode'] == "percentage") {
				$percentage = min($config['thumbnail.width'] / $fileWidth, $config['thumbnail.height'] / $fileHeight);

				if ($percentage <= 1) {
					$targetWidth = round($fileWidth * $percentage);
					$targetHeight = round($fileHeight * $percentage);
				} else {
					$targetWidth = $fileWidth;
					$targetHeight = $fileHeight;
				}
			}

			$input["thumbnail"] = true;

			// Check against config.
			if (($config["thumbnail.max_width"] != "" && $fileWidth > $config["thumbnail.max_width"]) || ($config["thumbnail.max_height"] != "" && $fileHeight > $config["thumbnail.max_height"]))
				$input["thumbnail"] = false;
			else {
				$input["twidth"] = $targetWidth;
				$input["theight"] = $targetHeight;
			}

			// Get thumbnail URL
			if ($type == "insert") {
				$thumbFile = $man->getFile($file->getParent() . "/" . $config['thumbnail.folder'] . "/" . $config['thumbnail.prefix'] . $file->getName());

				if ($thumbFile->exists())
					$input["thumbnail_url"] = $man->convertPathToURI($thumbFile->getAbsolutePath(), $config['preview.wwwroot']);
			}

			$input["width"] = $fileWidth;
			$input["height"] = $fileHeight;
			$input["editable"] = $canEdit;
		}

		return true;
	}

	// * * * * * * * Private methods

	/**
	 * SaveImage
	 * TODO: Check for PX or %
	 */
	function _saveImage(&$man, &$file, &$filedata, &$result) {
		$config =& $file->getConfig();

		// Find out if we have a temp file.
		$ext = getFileExt($file->getName());

		if (!$man->isToolEnabled("edit", $config)) {
			trigger_error("{#error.no_access}", FATAL);
			die();
		}

		// To file to save
		if (!$file->exists()) {
			$result->add("FAILED", $man->encryptPath($file->getAbsolutePath()), "{#error.file_not_exists}");
			return;
		}

		if (strpos($file->getName(), "mcic_") !== 0)
			$tmpImage = "mcic_". md5(session_id() . $file->getName()) . "." . $ext;
		else
			$tmpImage = $file->getName();

		$tempFile =& $man->getFile(utf8_encode(dirname($file->getAbsolutePath()) . "/" . $tmpImage));
		$tempFile->setTriggerEvents(false);

		/*
		Failed when mcic_ was found due to exclude in filesystem conf
		if ($man->verifyFile($tempFile, "edit") < 0) {
			$result->add("FAILED", $man->encryptPath($tempFile->getAbsolutePath()), $man->getInvalidFileMsg());
			return;
		}
		*/

		// NOTE: add check for R/W

		if ($tempFile->exists()) {
			if ($filedata["target"] != "") {
				$targetfile = $man->getFile($filedata["target"]);

				// Delete format images
				$config = $targetfile->getConfig();
				$imageutils = new $config['thumbnail'];
				$imageutils->deleteFormatImages($targetfile->getAbsolutePath(), $config["upload.format"]);

				// Just ignore it if it's the same file
				if ($tempFile->getAbsolutePath() != $targetfile->getAbsolutePath()) {
					if ($man->verifyFile($targetfile, "edit") < 0) {
						$result->add("FAILED", $man->encryptPath($targetfile->getAbsolutePath()), $man->getInvalidFileMsg());
						return;
					}

					if ($targetfile->exists())
						$targetfile->delete();

					$tempFile->renameTo($targetfile);
					$targetfile->importFile();

					// Reformat
					if ($config["edit.format"]) {
						$imageutils = new $config['thumbnail'];
						$imageutils->formatImage($targetfile->getAbsolutePath(), $config["edit.format"], $config['edit.jpeg_quality']);
					}
				}

				$result->add("OK", $man->encryptPath($targetfile->getAbsolutePath()), "{#message.save_success}");
			} else {
				$file->delete();
				$tempFile->renameTo($file);
				$file->importFile();

				$result->add("OK", $man->encryptPath($file->getAbsolutePath()), "{#message.save_success}");
			}
		} else {
			if ($filedata["target"] != "") {
				$targetfile = $man->getFile($filedata["target"]);

				// Just ignore it if it's the same file
				if ($file->getAbsolutePath() != $targetfile->getAbsolutePath()) {
					if ($man->verifyFile($targetfile, "edit") < 0) {
						$result->add("FAILED", $man->encryptPath($targetfile->getAbsolutePath()), $man->getInvalidFileMsg());
						return;
					}

					if ($targetfile->exists())
						$targetfile->delete();

					$file->copyTo($targetfile);
					$targetfile->importFile();
				}

				$result->add("OK", $man->encryptPath($targetfile->getAbsolutePath()), "{#message.save_success}");
			} else {
				// No temp, no target, abort!
				$result->add("FAILED", $man->encryptPath($file->getAbsolutePath()), "{#error.save_failed}");
			}
		}
	}

	/**
	 * CropImage
	 * TODO: Check for PX or %
	 */
	function _cropImage(&$man, &$file, &$filedata, &$result) {
		$ext = getFileExt($file->getName());
		$config = $file->getConfig();
		$imageutils = new $config['thumbnail'];

		if (!$man->isToolEnabled("edit", $config)) {
			trigger_error("{#error.no_access}", FATAL);
			die();
		}

		// To file to crop
		if (!$file->exists()) {
			$result->add("FAILED", $man->encryptPath($file->getAbsolutePath()), "{#error.file_not_exists}");
			return;
		}

		if ($filedata["temp"]) {
			if (strpos($file->getName(), "mcic_") !== 0)
				$tmpImage = "mcic_". md5(session_id() . $file->getName()) . "." . $ext;
			else
				$tmpImage = $file->getName();

			$tempFile =& $man->getFile(dirname($file->getAbsolutePath()) . "/" . $tmpImage);
			$tempFile->setTriggerEvents(false);

			$status = $imageutils->cropImage($file->getAbsolutePath(), $tempFile->getAbsolutePath(), $filedata["top"], $filedata["left"], $filedata["width"], $filedata["height"], $ext, $config["edit.jpeg_quality"]);
			if ($status) {
				$tempFile->importFile();
				$result->add("OK", $man->encryptPath($tempFile->getAbsolutePath()), "{#message.crop_success}");
			} else {
				$result->add("FAILED", $man->encryptPath($file->getAbsolutePath()), "{#error.crop_failed}");
			}
		} else {
			if (checkBool($config["general.demo"])) {
				$result->add("FAILED", $man->encryptPath($dir->getAbsolutePath()), "{#error.demo}");
				return $result->toArray();
			}

			if ($filedata["target"] != "") {
				$targetfile = $man->getFile($filedata["target"]);

				if ($targetfile->isDirectory()) {
					$targetfile = $man->getFile($man->addTrailingSlash($targetfile->getAbsolutePath()) . $file->getName());
				}

				if ($man->verifyFile($targetfile, "edit") < 0) {
					$result->add("FAILED", $man->encryptPath($targetfile->getAbsolutePath()), $man->getInvalidFileMsg());
					return;
				}
			} else
				$targetfile = $file;

			$status = $imageutils->cropImage($file->getAbsolutePath(), $targetfile->getAbsolutePath(), $filedata["top"], $filedata["left"], $filedata["width"], $filedata["height"], $ext, $config["edit.jpeg_quality"]);

			if ($status) {
				$targetfile->importFile();
				$result->add("OK", $man->encryptPath($targetfile->getAbsolutePath()), "{#message.crop_success}");
			} else {
				$result->add("FAILED", $man->encryptPath($targetfile->getAbsolutePath()), "{#error.no_access}");
			}
		}
	}

	/**
	 * ResizeImage
	 */
	function _resizeImage(&$man, &$file, &$filedata, &$result) {
		$ext = getFileExt($file->getName());
		$config = $file->getConfig();
		$imageutils = new $config['thumbnail'];

		if (!$man->isToolEnabled("edit", $config)) {
			trigger_error("{#error.no_access}", FATAL);
			die();
		}
		
		// To file to resize
		if (!$file->exists()) {
			$result->add("FAILED", $man->encryptPath($file->getAbsolutePath()), "{#error.file_not_exists}");
			return;
		}

		if ($filedata["temp"]) {
			if (strpos($file->getName(), "mcic_") !== 0)
				$tmpImage = "mcic_". md5(session_id() . $file->getName()) . "." . $ext;
			else
				$tmpImage = $file->getName();

			$tempFile =& $man->getFile(dirname($file->getAbsolutePath()) . "/" . $tmpImage);
			$tempFile->setTriggerEvents(false);
			
			$status = $imageutils->resizeImage($file->getAbsolutePath(), $tempFile->getAbsolutePath(), $filedata["width"], $filedata["height"], $ext, $config["edit.jpeg_quality"]);
			if ($status) {
				$tempFile->importFile();
				$result->add("OK", $man->encryptPath($tempFile->getAbsolutePath()), "{#message.resize_success}");
			} else {
				$result->add("FAILED", $man->encryptPath($file->getAbsolutePath()), "{#error.resize_failed}");
			}
		} else {
			if (checkBool($config["general.demo"