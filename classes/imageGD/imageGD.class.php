<?

// v2009.12.01 - no upload defendence
// v2009.12.22 - fixed crop - to fill white background
// v2011.07.20 - php5 + new method 'setSize' added

if(!defined('LANG')) define('LANG',"eng");
if(!defined('IMAGE_GD_PATH')) define('IMAGE_GD_PATH',dirname(__FILE__));

class ImageGD{

    const IMG_METHOD_RESIZE = 1;
    const IMG_METHOD_CROP   = 2;

    private $RootDIR   = '';
    private $imageExt  = array("jpg","jpeg","gif","png");
    private $ERRORS    = array();
    private $EXT       = '';
    private $FILENAME  = '';
    private $fillColor = '';
    private $maxSize = 2097152;

    public function ImageGD($dir){
        $this->RootDIR = $dir.'/';
    }


    public function setSize($method, $sourse, $width, $height,$destination='', $fill=false){
        switch($method){

            case self::IMG_METHOD_CROP :
                return $this->cropImage($sourse,$width,$height,$destination);
                break;

            case self::IMG_METHOD_RESIZE:
                return $this->resizeImage($sourse,$width,$height,$destination,$fill);
                break;

            default:
                return $this->resizeImage($sourse,$width,$height,$destination,$fill);
                break;

        }
    }

    //-----

    public function uploadImage($image,$newname=true,$name=''){
        if(!$this->_parseFilename($_FILES[$image]['name'])) {
            return $this->_fixError('BAD_FORMAT');
        }

        if($_FILES[$image]['size'] > $this->maxSize){
            return $this->_fixError('BIG_FILESIZE');
        }

        if($newname){
            $name =($name)?$this->_validName($name):$this->_randomName();
        }
        else{
            $name = $this->_validName($this->FILENAME);
        }

        if(!@move_uploaded_file($_FILES[$image]['tmp_name'], $this->RootDIR.$name.'.'.$this->EXT)){
            return $this->_fixError('SERVER_ERR');
        }
        return $name.'.'.$this->EXT;
    }


    //***************************************************************//
    // Resizes image                                                 //
    //***************************************************************//

    public function resizeImage($sourse,$width,$height,$destination='',$fill=false){
        if(!$this->EXT){
            if(!$this->_parseFilename($sourse)){
                return  $this->_fixError('BAD_FORMAT');
            }
        }

        if(!$original=$this->_createimage($this->RootDIR.$sourse)){
            return false;
        }


        $sourse_x=@imagesx($original);
        $sourse_y=@imagesy($original);

        $Rx = $width/$sourse_x;
        $Ry = $height/$sourse_y;


        if(($sourse_x>$width)||($sourse_y>$height)){
            if($Rx<$Ry){
                $w=$width;
                $h=@round($width*$sourse_y/$sourse_x);
            }
            else{
                $h=$height;
                $w=@round($height*$sourse_x/$sourse_y);
            }
        }
        else{
            $w =$sourse_x;
            $h =$sourse_y;
        }
        $resized = $this->_imagecreatetruecolor($w,$h);
        @imagecopyresampled($resized,$original,0,0,0,0,$w,$h,$sourse_x,$sourse_y);


        //----
        if($fill){
            $color_set = $this->hex2rgb($this->fillColor);

            $cropped = $this->_imagecreatetruecolor($width,$height);
            $color = imagecolorallocate($cropped, $color_set[0], $color_set[1], $color_set[2]);
            // imagefill($cropped, 0, 0, $color);

            $offsetx = ceil(($width-$w)/2);
            $offsety = ceil(($height-$h)/2);

            imagecopy($cropped,$resized,$offsetx,$offsety,0,0,$w,$h);
        }
        else{
            $cropped = $resized;
        }
        //---

        if(!$destination){
            $destination=$sourse;
        }
        $this->_renderImage($cropped,$this->RootDIR.$destination,95);
        @imagedestroy($resized);
        @imagedestroy($cropped);
        @imagedestroy($original);
    }


    //***************************************************************//
    // Crops image                                                   //
    //***************************************************************//
    public function cropImage($sourse,$th_width,$th_height,$destination=""){
        if(!$this->EXT){
            if(!$this->_parseFilename($sourse)){
                return  $this->_fixError('BAD_FORMAT');
            }
        }

        if(!$original=$this->_createimage($this->RootDIR.$sourse)){
            return false;
        }

        $sourse_x = imagesx($original);
        $sourse_y = imagesy($original);

        $Rx = $th_width/$sourse_x;
        $Ry = $th_height/$sourse_y;


        if($Rx>$Ry){
            $w  = $th_width;
            $h=round($w*$sourse_y/$sourse_x);
        }
        else{
            $h=$th_height;
            $w=round($h*$sourse_x/$sourse_y);
        }

        $resized = $this->_imagecreatetruecolor($w,$h);
        imagecopyresampled($resized,$original,0,0,0,0,$w,$h,$sourse_x,$sourse_y);

        $color_array = $this->hex2rgb($this->fillColor);
        $cropped = $this->_imagecreatetruecolor($th_width,$th_height);
        $red = imagecolorallocate($cropped, $color_array[0], $color_array[1], $color_array[2]);
        //imagefill($cropped, 0, 0, $red);

        $offsetx = ceil(($th_width-$w)/2);
        $offsety = ceil(($th_height-$h)/2);

        imagecopy($cropped,$resized,$offsetx,$offsety,0,0,$w,$h);
        if(!$destination){
            $destination=$sourse;
        }
        $this->_renderImage($cropped,$this->RootDIR.$destination,95);

        @imagedestroy($original);
        @imagedestroy($resized);
        @imagedestroy($cropped);
    }


    public function addMask($sourse,$destination,$mask1=false,$mask2=false,$x=0,$y=0){
        if(!$original=$this->_createimage($this->RootDIR.$sourse)){
            return false;
        }
        $sourse_x = imagesx($original);
        $sourse_y = imagesy($original);

        if($mask1){
            $trans_mask = $this->_createimage($mask1);
            imagecopy($original,$trans_mask,0,0,0,0,imagesx($trans_mask),imagesy($trans_mask));
            // print p(imagecolorsforindex($original,imagecolorallocate($original,0,255,42)));
            imagecolortransparent($original,imagecolorexact($original,0,255,42)/*imagecolorat($trans_mask,0,0)*/);
        }
        if($mask2){
            $overlay_mask = $this->_createimage($mask2);
            imagecopy($original,$overlay_mask,0,0,$x,$y,imagesx($overlay_mask),imagesy($overlay_mask));
        }

        $this->_renderImage($original,$this->RootDIR.$destination);
        @imagedestroy($original);
    }


    public function addWatermark($sourse,$Watermark,$offsetX,$offsetY){
        if(!$original  = $this->_createimage($this->RootDIR.$sourse)){
            return false;
        }

        $this->_parseFilename($Watermark);
        if(!$watermark = $this->_createimage($Watermark)){
            return false;
        }

        $watermark_w  =  @imagesx($watermark);
        $watermark_y  =  @imagesy($watermark);

        @imagecopy($original,$watermark,$offsetX,$offsetY,0,0,$watermark_w,$watermark_y);
        $this->_renderImage($original,$this->RootDIR.$sourse,$quality);

        @imagedestroy($original);
        @imagedestroy($watermark);
    }

    public function hex2rgb($hexcolor){
        $hexcolor = str_replace('#','',$hexcolor);
        sscanf($hexcolor, '%2x%2x%2x', $red, $green, $blue);
        return array($red, $green, $blue);
    }

    //***************************************************************//
    // Returns error messages or FALSE                               //
    // if $asArray=true -as array, else as string                    //
    //***************************************************************//
    public function passErrors(){
        if(file_exists(IMAGE_GD_PATH."/lang/imageGD_".LANG.".php")){
            include_once(IMAGE_GD_PATH."/lang/imageGD_".LANG.".php");
        }
        else{
            include_once(IMAGE_GD_PATH."/lang/imageGD_eng.php");
        }

        if(count($this->ERRORS)>0){
            for($i=0;$i<count($this->ERRORS);$i++){
                $err[]= $IMAGEGD_ERR[$this->ERRORS[$i]];
            }
            return implode("<br>",$err);
        }
        return false;
    }

    //***************************************************************//
    //  for internal use only                                        //
    //                                                               //
    //***************************************************************//


    function _imagecreatetruecolor($width, $height){
        $image = imagecreatetruecolor($width, $height);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        return $image;
    }




    private function _createimage($image){
        switch($this->EXT){
            case("jpg"):  $img=@imagecreatefromjpeg($image); break;
            case("jpeg"): $img=@imagecreatefromjpeg($image); break;
            case("gif"):  $img=@imagecreatefromgif($image);  break;
            case("png"):  $img=@imagecreatefrompng($image);  break;
        }

        imagealphablending($img, false);
        imagesavealpha($img, true);

        if(!$img){
            return $this->_fixError('READING_FAILED');
        }
        return $img;
    }


    private function _renderImage($resourse,$name,$quality=85){
        $render = false;
        switch($this->EXT){
            case("jpg"):  $render = imagejpeg($resourse,$name,$quality);  break;
            case("jpeg"): $render = imagejpeg($resourse,$name,$quality);  break;
            case("gif"):  $render = @imagegif($resourse, $name);          break;
            case("png"):  $render = @imagepng($resourse, $name);          break;
        }

        if(!$render) {
            $this->_fixError("RENDERING_FAILED");
            return false;
        }
        return true;
    }



    private function _getExtension($filename){
        return strtolower(substr($filename,1+strrpos($filename,".")));
    }


    private function _parseFilename($filename){
        $filename = basename($filename);
        $extpos = strrpos($filename,".");
        if($extpos>0&&$extpos<strlen($filename)-2){
            $ext = strtolower(substr($filename,$extpos+1));
            if(!in_array($ext,$this->imageExt)){
                return false;
            }
            $this->FILENAME = substr($filename,0,$extpos);
            $this->EXT = $ext;
            return true;
        }
        return false;
    }

    private function _randomName($length=15){
        $characters = "0123456789qwertyuiopasdfghjklzxcvbnm";
        $n = strlen($characters)-1;
        $code = '';
        srand((double)microtime() * 1000000);
        for($i=0; $i<$length; $i++){
            $randname .= $characters[rand(0, $n)];
        }
        return $randname;
    }

    private function _validName($filename){
        return preg_replace("/\s+/","_",$filename);
    }

    private function _fixError($err_id){
        $this->ERRORS[]= $err_id;
        return false;
    }
}

?>