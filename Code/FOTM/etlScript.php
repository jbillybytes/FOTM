<html> 
    <body> 
        <?php 
			$db = pg_connect('host=localhost dbname=FOTM user=postgres password=root');
			$folder_to_monitor= array('C:\FOTM\Charleston Courier FOTM','C:\FOTM\Mobile Register PDFs','C:\FOTM\Richmond Enquirer PDFs');
			$folder_ocr= array('C:\FOTM\fotm_ocr_txt_20140219\CharlestonCourierFOTM-txt\\','C:\FOTM\fotm_ocr_txt_20140219\Mobile Register PDFs-txt\\','C:\FOTM\fotm_ocr_txt_20140219\Richmond Enquirer PDFs-txt\\');

			for($j=0; $j< count($folder_to_monitor); $j++)
			{
				$dir = $folder_to_monitor[$j];
				$files = scandir($dir, 0);

				for($i = 2; $i < count($files); $i++) {
					$ftype = $userfile_extn = substr($files[$i], strrpos($files[$i], '.')+1);
					$fname = $files[$i];
					$fname_without_extn=substr($files[$i],0, strrpos($files[$i], '.'));
					#echo "fname: ".$fname_without_extn;

					#open the corresponding file in folder_ocr directory, read the file and put it in ocr_text variable
					$ocr_file_fd = @fopen($folder_ocr[$j].$fname_without_extn.".txt", "r");
					if ($ocr_file_fd != FALSE) {
						$ocr_file_data= fread($ocr_file_fd,filesize($folder_ocr[$j].$fname_without_extn.".txt"));
						fclose($ocr_file_fd);
						$ocr_file_data=preg_replace('/\'/', "''", $ocr_file_data);
						#echo $ocr_file_data;
						#$ocr_text= $dir
						$ocr_file_name=$fname_without_extn.".txt";
						$ocr_filepath=$folder_ocr[$j];
						$query = "select INSERT_INTO_ADS('". $fname. "','" . $dir. "','" . $ftype. "','".$ocr_file_data."')";
						#echo $query;
						$result = pg_query($query); 
						if (!$result) { 
						$errormessage = pg_last_error(); 
						echo "Error with database insertion: " . $errormessage; 
						exit(); 
					}
				}
			}
			}
        ?> 
    </body> 
</html>