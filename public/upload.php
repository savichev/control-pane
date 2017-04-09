<?php
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$path=realpath('').'/media/';
	if(is_uploaded_file($_FILES['file']['tmp_name']))
	{
		$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
		$file = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_FILENAME));
		$uid = uniqid();
		$date = time();
		
		$returnJson[]=array('filepath'=>$path);
		
		//$filename=$path.$filename.'-'.$uid.".".$ext;
		$file=$file.'.'.$ext;
		$filename=$path.$file;	//.'.'.$ext;
		if (move_uploaded_file($_FILES['file']['tmp_name'], $filename))
		{
			$status = 'ok';	//'Successfully uploaded!';
		}else{
			$status = 'Upload Fail: Unknown error occurred!';
		}
	}
}
if($status!='ok') {echo json_encode(array('status' => $status));exit;}
return;

$valid_exts = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif'); // valid extensions
$max_size = 30000 * 1024; // max file size in bytes

$json = array();
	if ( $_SERVER['REQUEST_METHOD'] === 'POST' )
	{
		for($i=0;$i<count($_FILES['file']['tmp_name']);$i++)
		{
			$path="/media/";

			if(is_uploaded_file($_FILES['file']['tmp_name'][$i]) )
			{
				// get uploaded file extension
				$ext = strtolower(pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION));
				// looking for format and size validity
				if (in_array($ext, $valid_exts) AND $_FILES['file']['size'][$i] < $max_size)
				{
					// unique file path
					$uid = uniqid();
					$date = date('Y-m-d-H-i-s');
					$path = $path ."image_" .$date. '_' . $uid . "." .$ext;

					$returnJson[]= array("filepath"=>$path);

					$filename = "image_" . $date . "_" .$uid . "." . $ext;
					//$this->createthumb($i,$filename);

					// move uploaded file from temp to uploads directory
					if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $path))
					{
						$status = 'ok';	//'Successfully uploaded!';
						//perform sql updates here
					}else{
						$status = 'Upload Fail: Unknown error occurred!';
					}
				}else{
					$status = 'Upload Fail: Unsupported file format or It is too large to upload!';
				}
			}else{
				$status = 'Upload Fail: File not uploaded!';
			}
		}
	}else{
		$status = 'Bad request!';
	}

	echo json_encode(array('status' => $status));
	//echo json_encode($json);