<?php

class Provider extends CI_Controller {

    public function __construct() {
        
        parent::__construct();
      //  $this->load->helper('file');
    }

    function componentCss() {

        $sOutput = '';
        $aFiels = get_filenames('./assets/admin/resources/css/');
        natcasesort($aFiels);
        foreach ($aFiels as $sFile) {
            $aFileData = pathinfo($sFile);

            if ($aFileData["extension"] == "css") {
                $sOutput .= '/* ' . $sFile . "*/\n";
                $sOutput .= read_file('./assets/admin/resources/css/' . $sFile) . "\n\n";
            }
        }
        header("Content-type: text/css");
        echo $sOutput;
        die();
    }

    function allowed_components() {
		
        $sOutput = '';
        $bSendPack = $this->config->item("send_pack");
        $sFileProd = $this->config->item("file_prod");
        $sFileDebug = $this->config->item("file_debug");
        $aFiels = get_filenames($sFileDebug);

        foreach ($aFiels as $sFile) {
            $aFileData = pathinfo($sFile);
            if (isset($aFileData["extension"]) &&($aFileData["extension"] != "js")) {
                continue;
            }

            if ($bSendPack) {
                $script = file_get_contents($sFileDebug . $sFile);
                $filemtime = @filemtime($sFileDebug . $sFile);
                $filemtimeCache = @filemtime($sFileProd . $sFile);
                $t1 = microtime(true);

                if ((!$filemtimeCache) || ($filemtime > $filemtimeCache)) {
                    $packer = new JavaScriptPacker($script, 'Normal', true, false);
                    $packed = $packer->pack();
                    file_put_contents($sFileProd . $sFile, $packed);
                }
                $t2 = microtime(true);
                $time = sprintf('%.4f', ($t2 - $t1));
                $sOutput .= '/* Script ' . $sFile . ' packed in ' . $time . " s. */\n";
                $sOutput .= read_file($sFileProd . $sFile) . "\n\n";
            }
            else {
                $sOutput .= read_file($sFileDebug . $sFile) . "\n\n";
            }
        }
        header("Content-type: application/javascript");
        echo $sOutput;
        die();
    }

    function extensions() {

        $sOutput = '';
        $aFiels = get_filenames('./assets/admin/resources/js/combine/');
        natcasesort($aFiels);
        foreach ($aFiels as $sFile) {
            $aFileData = pathinfo($sFile);

            if ($aFileData["extension"] == "js") {
                $sOutput .= '/* ' . $sFile . "*/\n";
                $sOutput .= read_file('./assets/admin/resources/js/combine/' . $sFile) . "\n\n";
            }
        }

        $aFiels = get_filenames('./assets/admin/resources/js/customs/');
        natcasesort($aFiels);
        foreach ($aFiels as $sFile) {
            $sOutput .= '/* ' . $sFile . "*/\n";
            $sOutput .= read_file('./assets/admin/resources/js/customs/' . $sFile) . "\n\n";
        }
        header("Content-type: application/javascript");
        echo $sOutput;
        die();
    }
	
}

/* End of file provider.php */
/* Location: ./application/controllers/provider.php */
