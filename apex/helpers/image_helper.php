<?php

/**
 * @name		CodeIgniter Advanced Images
 * @author		Jens Segers
 * @link		http://www.jenssegers.be
 * @license		MIT License Copyright (c) 2012 Jens Segers
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Image
 *
 * Generates a modified image source to work with the advanced images controller
 *
 * @access    public
 * @param    mixed
 * @return    string
 * @throws ImagickException
 */

    function image_resize($image_path, $preset, $dowatermark = true){
        $CI =& get_instance();
        $image_path = urldecode($image_path);
        $pathinfo = pathinfo($image_path);
        $new_path = $image_path;
        if ($preset == 'resizebig'){
            $imgwidth = 822;
            $imgheight = 530;

        }
        if ($preset == 'resizepartners'){
            $imgwidth = 300;
            $imgheight = 90;

        }
        if ($preset == 'resizenews'){
            $imgwidth = 247;
            $imgheight = 185;

        }
        if ($preset == 'preview'){
            $imgwidth = 110;
            $imgheight = 110;

        }
        elseif ($preset == 'rpreview'){
            $imgwidth = 450;
            $imgheight = 275;
        }
        if ($preset == 'plan'){
            $imgwidth = 40;
            $imgheight = 40;

        }
        if (!file_exists('.'.$pathinfo["dirname"].'/'.$pathinfo["filename"].'.'.$pathinfo["extension"])) {
            return '/asset/img/logo-M16.png';
        }
        if (!is_dir(".".$pathinfo["dirname"]."/".$preset)){
            mkdir(".".$pathinfo["dirname"]."/".$preset, 0777);
        }
        if (!file_exists('.'.$pathinfo["dirname"].'/'.$preset.'/'.$pathinfo["filename"].'.'.$pathinfo["extension"])) {
            $image = new Imagick('.'.$pathinfo["dirname"].'/'.$pathinfo["filename"].'.'.$pathinfo["extension"]);
            $image->scaleImage($imgwidth, $imgheight, true);
            if ($dowatermark){
                $watermark = new Imagick();
                $watermark->readImage("./asset/assets/img/bigwatermark.png");
                $iWidth = $image->getImageWidth();
                $iHeight = $image->getImageHeight();
                $wWidth = $watermark->getImageWidth();
                $wHeight = $watermark->getImageHeight();

                if ($iHeight < $wHeight || $iWidth < $wWidth) {
                    // resize the watermark
                    $watermark->scaleImage($iWidth-80, $iHeight-40, true);

                    // get new size
                    $wWidth = $watermark->getImageWidth();
                    $wHeight = $watermark->getImageHeight();
                }

    // calculate the position
                $x = ($iWidth - $wWidth) / 2;
                $y = ($iHeight - $wHeight) - 30;

            $image->compositeImage($watermark, imagick::COMPOSITE_OVER, $x, $y);
            }
            if($f=fopen('.'.$pathinfo["dirname"].'/'.$preset.'/'.$pathinfo["filename"].'.'.$pathinfo["extension"], "w+")){
                $image->stripImage();
                $image->writeImageFile($f);
            }
        }
        return $pathinfo["dirname"].'/'.$preset.'/'.$pathinfo["filename"].'.'.$pathinfo["extension"];
    }


    function image($image_path, $preset, $dowatermark = true) {
        //return mb_detect_encoding($image_path);
        $image_path = urldecode($image_path);
        //$image_path = @iconv("ASCII", "UTF-8", $image_path);
        $pathinfo = pathinfo($image_path);
        $new_path = $image_path;

        // check if requested preset exists
        if (isset($sizes[$preset])) {
            $new_path = $pathinfo["dirname"] . $pathinfo["filename"] . "-" . implode("x", $sizes[$preset]) . "." . $pathinfo["extension"];
        }
        //$pathinfo["dirname"] = str_replace(" ","%20",$pathinfo["dirname"]);
        if ($preset == 'small'){
            $imgwidth = 305;
            $imgheight = 198;
        }
        elseif ($preset == 'medium'){
            $imgwidth = 400;
            $imgheight = 240;
        }
        elseif ($preset == 'pmedium'){
            $imgwidth = 632;
            $imgheight = 356;
        }
        elseif ($preset == 'large'){
            $imgwidth = 1280;
            $imgheight = 434;
        }
        elseif ($preset == 'sliderbig'){
            $imgwidth = 956;
            $imgheight = 570;
        }
        elseif ($preset == 'slidersmall'){
            $imgwidth = 305;
            $imgheight = 190;
        }
        elseif ($preset == 'sliderthumb'){
            $imgwidth = 70;
            $imgheight = 70;
        }
        elseif ($preset == 'resaleslider'){
            $imgwidth = 822;
            $imgheight = 530;
        }
        elseif ($preset == 'rpreview'){
            $imgwidth = 450;
            $imgheight = 275;
        }
        elseif ($preset == 'bigsliderthumb'){
            $imgwidth = 114;
            $imgheight = 114;
        }
        if (!file_exists('.'.$pathinfo["dirname"].'/'.$pathinfo["filename"].'.'.$pathinfo["extension"])) {
            return '/asset/img/logo-M16.png';
        }
        if (!is_dir(".".$pathinfo["dirname"]."/".$preset)){
            mkdir(".".$pathinfo["dirname"]."/".$preset, 0777);
            //return  $pathinfo["dirname"] ."/".$pathinfo["filename"] . "." . $pathinfo["extension"];
        }
        //mkdir("./asset/uploads/crm/new", 0777);
       if (!file_exists('.'.$pathinfo["dirname"].'/'.$preset.'/'.$pathinfo["filename"].'.jpg')){


        /*
         * Add file validation code here
         */

        //if (!file_exists('.'.$pathinfo["dirname"].'/'.$preset.'/'.$pathinfo["filename"].'.jpg')) {

        list($source_width, $source_height, $source_type) = getimagesize('.'.$pathinfo["dirname"].'/'.$pathinfo["filename"].'.'.$pathinfo["extension"]);

        switch ($source_type) {
            case IMAGETYPE_GIF:
                $source_gdim = imagecreatefromgif('.'.$pathinfo["dirname"].'/'.$pathinfo["filename"].'.'.$pathinfo["extension"]);
                break;
            case IMAGETYPE_JPEG:
                $source_gdim = imagecreatefromjpeg('.'.$pathinfo["dirname"].'/'.$pathinfo["filename"].'.'.$pathinfo["extension"]);
                break;
            case IMAGETYPE_PNG:
                $source_gdim = imagecreatefrompng('.'.$pathinfo["dirname"].'/'.$pathinfo["filename"].'.'.$pathinfo["extension"]);
                break;
        }

        $source_aspect_ratio = $source_width / $source_height;
        $desired_aspect_ratio = $imgwidth / $imgheight;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            /*
             * Triggered when source image is wider
             */
            $temp_height = $imgheight;
            $temp_width = ( int ) ($imgheight * $source_aspect_ratio);
        } else {
            /*
             * Triggered otherwise (i.e. source image is similar or taller)
             */
            $temp_width = $imgwidth;
            $temp_height = ( int ) ($imgwidth / $source_aspect_ratio);
        }

        /*
         * Resize the image into a temporary GD image
         */

        $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
        imagecopyresampled(
            $temp_gdim,
            $source_gdim,
            0, 0,
            0, 0,
            $temp_width, $temp_height,
            $source_width, $source_height
        );

        /*
         * Copy cropped region from temporary image into the desired GD image
         */

        $x0 = ($temp_width - $imgwidth) / 2;
        $y0 = ($temp_height - $imgheight) / 2;
        $desired_gdim = imagecreatetruecolor($imgwidth, $imgheight);
        imagecopy(
            $desired_gdim,
            $temp_gdim,
            0, 0,
            $x0, $y0,
            $imgwidth, $imgheight
        );

        imagejpeg($desired_gdim, '.'.$pathinfo["dirname"].'/'.$preset.'/'.$pathinfo["filename"].'.jpg');
            if ($preset == 'small' && $dowatermark){
            $image = new Imagick('.'.$pathinfo["dirname"].'/'.$preset.'/'.$pathinfo["filename"].'.jpg');
            $image->scaleImage($imgwidth, $imgheight, true);

            $watermark = new Imagick();
            $watermark->readImage("./asset/assets/img/smallwatermark.png");
            $iWidth = $image->getImageWidth();
            $iHeight = $image->getImageHeight();
            $wWidth = $watermark->getImageWidth();
            $wHeight = $watermark->getImageHeight();

            if ($iHeight < $wHeight || $iWidth < $wWidth) {
                // resize the watermark
                $watermark->scaleImage($iWidth, $iHeight, true);

                // get new size
                $wWidth = $watermark->getImageWidth();
                $wHeight = $watermark->getImageHeight();
            }

// calculate the position
            $x = ($iWidth - $wWidth) / 2;
            $y = ($iHeight - $wHeight) - 8;

            $image->compositeImage($watermark, imagick::COMPOSITE_OVER, $x, $y);
            if($f=fopen('.'.$pathinfo["dirname"].'/'.$preset.'/'.$pathinfo["filename"].'.jpg', "w+")){
                $image->writeImageFile($f);
            }
            }

            if (($preset == 'medium' || $preset == 'pmedium') && $dowatermark){
                $image = new Imagick('.'.$pathinfo["dirname"].'/'.$preset.'/'.$pathinfo["filename"].'.jpg');
                $image->scaleImage($imgwidth, $imgheight, true);
                $watermark = new Imagick();
                $watermark->readImage("./asset/assets/img/mediumwatermark.png");
                $iWidth = $image->getImageWidth();
                $iHeight = $image->getImageHeight();
                $wWidth = $watermark->getImageWidth();
                $wHeight = $watermark->getImageHeight();

                if ($iHeight < $wHeight || $iWidth < $wWidth) {
                    // resize the watermark
                    $watermark->scaleImage($iWidth, $iHeight, true);

                    // get new size
                    $wWidth = $watermark->getImageWidth();
                    $wHeight = $watermark->getImageHeight();
                }

// calculate the position
                $x = ($iWidth - $wWidth) / 2;
                $y = ($iHeight - $wHeight) - 4;

                $image->compositeImage($watermark, imagick::COMPOSITE_OVER, $x, $y);
                if($f=fopen('.'.$pathinfo["dirname"].'/'.$preset.'/'.$pathinfo["filename"].'.jpg', "w+")){
                    $image->writeImageFile($f);
                }
            }




      //  }



        //$this->load->library('image_lib');
        //$this->image_lib->fit();
        }
        
        return $pathinfo["dirname"].'/'.$preset.'/'.$pathinfo["filename"].'.jpg';
    }

    function getGray($image_path, $preset){
        return;
        $image_path = urldecode($image_path);
        $pathinfo = pathinfo($image_path);
        $new_path = $image_path;
        if (!file_exists('.'.$pathinfo["dirname"].'/'.$pathinfo["filename"].'.'.$pathinfo["extension"])) {
            return '/asset/img/logo-M16.png';
        }
        if (!is_dir(".".$pathinfo["dirname"]."/".$preset)){
            mkdir(".".$pathinfo["dirname"]."/".$preset, 0777);
        }

        try {
            $image = new Imagick('.' . $pathinfo["dirname"] . '/' . $pathinfo["filename"] . '.' . $pathinfo["extension"]);
        } catch (ImagickException $e) {
            die('Пиздец');
        }
        $image->setImageFormat("png");
        $image->transformimagecolorspace(Imagick::COLORSPACE_GRAY);
        $image->setImageOpacity(0.5);
        if($f=fopen('.'.$pathinfo["dirname"].'/'.$preset.'/'.$pathinfo["filename"].'.png', "w+")){
            $image->writeImageFile($f);
        }
        return $pathinfo["dirname"].'/'.$preset.'/'.$pathinfo["filename"].'.png';
    }


        function image2GrayColor( $img_path, $output_path ){

        $type_img = exif_imagetype( $img_path );
        $gd = gd_info();

        if( $type_img == 3 AND $gd['PNG Support'] == 1 ){

            $img_png = imagecreatefromPNG( $img_path );
            imagesavealpha( $img_png, TRUE );

            if( $img_png AND imagefilter( $img_png, IMG_FILTER_GRAYSCALE )) {
                @unlink( $output_path );
                imagepng( $img_png, $output_path );
            }
            imagedestroy( $img_png );
        }
        elseif( $type_img == 2 AND $gd['JPG Support'] == 1 ) {

            $img_jpg 	 = imagecreatefromJPEG( $img_path );
            if ( !$color_total = imagecolorstotal( $img_jpg )) {
                $color_total = 256;
            }
            imagetruecolortopalette( $img_jpg, FALSE, $color_total );

            for( $c = 0; $c < $color_total; $c++ ) {
                $col = imagecolorsforindex( $img_jpg, $c );
                $i   = ( $col['red']+$col['green']+$col['blue'] )/3;
                imagecolorset( $img_jpg, $c, $i, $i, $i );
            }
            @unlink( $output_path );
            imagejpeg( $img_jpg, $output_path );
            imagedestroy( $img_jpg );
        }
        elseif( $type_img == 1 AND $gd['GIF Create Support'] == 1  ) {

            $img_gif 	 = imagecreatefromGIF( $img_path );
            if ( !$color_total = imagecolorstotal( $img_gif )) {
                $color_total = 256;
            }
            imagetruecolortopalette( $img_gif, FALSE, $color_total );

            for( $c = 0; $c < $color_total; $c++ ) {
                $col = imagecolorsforindex( $img_gif, $c );
                $i   = ( $col['red']+$col['green']+$col['blue'] )/3;
                imagecolorset( $img_gif, $c, $i, $i, $i );
            }
            @unlink( $output_path );
            imagegif( $img_gif, $output_path );
            imagedestroy( $img_gif );
        }
        else{
            return 'Error: This format is not supported.';
        }
    }
