<?php

	class Tool {

		public function summernote_ListFolder(){
			$resultReturn = $this->_summernote_ListFolder();
			return $resultReturn;
		}
		public function imageList(){
			$resultReturn = $this->_imgList();
			return $resultReturn;
		}
		public function createFolder(){
			$resultReturn = $this->_createFolder();
			return $resultReturn;
		}
		public function deleteImage(){
			$resultReturn = $this->_deleteImage();
			return $resultReturn;
		}
		public function deleteFolder(){
			$resultReturn = $this->_deleteFOLDER();
			return $resultReturn;
		}
		public function uploaImage(){
			$resultReturn = $this->_uploadImage();
			return $resultReturn;
		}


		private function _summernote_ListFolder(){


 			global $CONFIG;

 			$sub_path = $_GET['path'];
 			/*if($sub_path!=''){
				$sub_path = base64_encode($sub_path);
			}*/
			//echo base64_decode($sub_path);

			$path_dir = $CONFIG['path_folder'].base64_decode(urldecode($sub_path));
			//echo $path_dir;
			$files = glob($path_dir.'/*/',GLOB_ONLYDIR);

			$ret = array();
			$i = 0;
			foreach($files as $file){

			 	$fdname = explode("/",$file);
			  	$ct = (count($fdname))-2;

				$ret[$i]['folder'] = $fdname[$ct];
				$ret[$i]['path'] = base64_decode(urldecode($sub_path));

					$i++;

			}
			//GF::print_r($ret);
			return $ret;


		}
		private function _createFolder(){

			global $CONFIG;

			$sub_path = $_POST['folder_path'];
			$path_dir = $CONFIG['path_folder'].base64_decode(urldecode($sub_path));

			$fname = $_POST['folder_name'];
			//echo $fname;

			$fname = preg_replace('/\s+/', '', $fname);
			$create = mkdir($path_dir."/".$fname,0777,true);
			if($create){
				chmod($path_dir."/".$fname,0777);
				return true;
			}else{
				return false;
			}
		}
		private function _imgList_OLD(){

			global $CONFIG;

			$sub_path = $_GET['path'];
			$path_dir = $CONFIG['path_folder'].base64_decode(urldecode($sub_path));
			//echo $path_dir;exit();
			$fname = $_GET['foldername'];
			$ckname = $CONFIG['path_folder_url'].base64_decode(urldecode($sub_path))."/".$fname;
			//echo $path_dir.'/'.$fname;

			$files = glob($path_dir.'/'.$fname."/*.*");



			$ret = array();
			$i = 0;
			foreach($files as $file){
			  if(is_file($file))
			    $imgname = explode("/",$file);
				$ct = (count($imgname))-1;
				$ret[$i] = $ckname.$imgname[$ct];
				//echo $file."<br>";
				$i++;

			}

			return $ret;
		}
      private function _imgList(){

          global $CONFIG;

   		 $sub_path = $_GET['path'];
          $path_dir = $CONFIG['path_folder'].base64_decode(urldecode($sub_path));

          $fname = $_GET['foldername'];
          $ckname = $CONFIG['path_folder_url'].base64_decode(urldecode($sub_path))."/".$fname;

         //  $ckname = $fname."/";
         //  if($fname=="root"){
         //      $files = glob(PATHUPLOAD.'/*.*');
         //      $ckname = "";
         //  }else{
         //      $files = glob(PATHUPLOAD.'/'.$fname."/*.*");
         //  }
          $files = glob($path_dir.'/'.$fname."/*.*");

            $perpage = 20;
            $page = 1;
            if(isset($_GET['page'])){
                 if($_GET['page']!=''){
                    $page = intval($_GET['page']);

                 }
            }
            if($page==0){$page=1;}

            if(count($files)===0){
                 return array();
                 exit();
            }


            krsort($files);
            if(count($files)!==1){

                 $paginated = array();

                 $ttl = count($files);
                 $x = array_chunk($files,20,true);
                 $ggx = $page-1;
                 $paginated = $x[$ggx];
            }else{
                 $paginated = $files;
            }




          $ret = array();
          $i = 0;
          foreach($paginated as $file){
            if(is_file($file))
               $imgname = explode("/",$file);
              $ct = (count($imgname))-1;
              $ret[$i] = $ckname.$imgname[$ct];
              //echo $file."<br>";
              $i++;

          }
            $numpage = ceil(count($files)/$perpage);
            $arrreturn = array();
            $arrreturn['data'] = $ret;
            $arrreturn['num'] = $numpage;

          return $arrreturn;
      }
		private function _deleteImage(){


			global $CONFIG;
			$pure_path = $_GET['path'];
			$strip_path = str_replace($ckname = $CONFIG['path_folder_url'],'',$pure_path);
			$path_dir = $CONFIG['path_folder'].$strip_path;
			//$path = PATHUPLOAD.'/'.$iname;
			$ret = unlink($path_dir);
			echo $ret;

		}
		private function _deleteFOLDER(){

			global $CONFIG;

			$pure_path = $_GET['path'];
			$path_dir = $CONFIG['path_folder'].base64_decode(urldecode($pure_path));

			$files = glob($path_dir."/*");

			foreach($files as $file){
			  if(is_file($file))
			    @unlink($file);
			}
			//print_r($path_dir);
			$ret = @rmdir($path_dir);
			echo $ret;
		}

		private function _uploadImage(){

			global $CONFIG;
			$Str_file = explode(".",$_FILES['file_img']['name']);
			$file_type = end($Str_file);
			$tatal_name = count($Str_file);
			unset($Str_file[$tatal_name-1]);

			$pure_path = $_POST['folders_path'];
			$path_dir = $CONFIG['path_folder'].base64_decode(urldecode($pure_path));

			$new_name = $path_dir."/".time().implode('-',$Str_file).".".$file_type;
			//GF::print_r($_FILES['file_img']);
			/*if(copy($_FILES['file_img']['temp_name'],$new_name)){
				echo '1';
			}*/
			$move =  move_uploaded_file($_FILES['file_img']['tmp_name'], $new_name);
			if($move){
				return true;
			}else{
				return FALSE;
			}

		}


	}

?>
