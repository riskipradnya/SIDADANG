<?php
	class Validasi extends CI_Model
	{
		function validasiakun()
		{
			$Level=$this->session->userdata('Level');
			if($Level=="")
			{
				echo "<script>alert('Maaf anda tidak dapat akses halaman ini')</script>";
				redirect('halaman','refresh');	
			}	
		}	
	}
?>