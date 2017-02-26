<?php
namespace App\Helpers;

class Helper
{
    function write_ini_file($assoc_arr, $path, $has_sections=FALSE) {
        $content = "";
        if ($has_sections) {
            foreach ($assoc_arr as $key=>$elem) {
                $content .= "[".$key."]\n";
                foreach ($elem as $key2=>$elem2) {
                    if(is_array($elem2))
                    {
                        for($i=0;$i<count($elem2);$i++)
                        {
                            $content .= $key2."[] = \"".$elem2[$i]."\"\n";
                        }
                    }
                    else if($elem2=="") $content .= $key2." = \n";
                    else $content .= $key2." = \"".$elem2."\"\n";
                }
            }
        }
        else {
            foreach ($assoc_arr as $key=>$elem) {
                if(is_array($elem))
                {
                    for($i=0;$i<count($elem);$i++)
                    {
                        $content .= $key."[] = \"".$elem[$i]."\"\n";
                    }
                }
                else if($elem=="") $content .= $key." = \n";
                else $content .= $key." = \"".$elem."\"\n";
            }
        }

        if (!$handle = fopen($path, 'w')) {
            return false;
        }

        $success = fwrite($handle, $content);
        fclose($handle);

        return $success;
    }

    public function download_from_ftp($file){
        $ftp = new \App\FTPClient\FTPClient();
        $ftp->connect(config('ftp.ip'),config('ftp.user'),config('ftp.password'));
        $path = pathinfo($file);
        $path['dirname'] = 'pelck/'.$path['dirname'];
        $file = public_path($file);
        if($ftp->downloadFile('/'.$path['dirname'].'/'.$path['filename'].'.'.$path['extension'],$file))
        return TRUE;
        else
        die("FTP DOWNLOAD FAIL");
    }

    public function move_to_ftp($files = []){
        $ftp = new \App\FTPClient\FTPClient();
        $ftp->connect(config('ftp.ip'),config('ftp.user'),config('ftp.password'));
        foreach($files as $file){
            $path = pathinfo($file);
            $path['dirname'] = 'pelck/'.$path['dirname'];
            $file = public_path($file);
            $ftp->makeDirRecursive($path['dirname']);
            if($ftp->uploadFile($file,'/'.$path['dirname'].'/'.$path['filename'].'.'.$path['extension'])){
                unlink($file);
            }
        }
        return TRUE;
    }

    public function encrypt_file($file , $remove_original = FALSE){
        $resfile = public_path($file);
        $desfile = $resfile.".enc";
        $key = config("app.safe_key");
        exec("openssl enc -aes-256-cbc -salt -in {$resfile} -out {$desfile} -k {$key}");
        if($remove_original == TRUE){
            unlink(public_path($file));
        }
        return $file.'.enc';
    }

    public function decrypt_file($file){
        $resfile = public_path($file);
        $desfile = substr($resfile,0,-4);
        $key = config("app.safe_key");
        exec("openssl enc -d -aes-256-cbc -in {$resfile} -out {$desfile} -k {$key}");
        return $desfile;
    }

    public function generate_user_key(){
        $bytes = openssl_random_pseudo_bytes(6);
        $key = bin2hex($bytes);
        $encryptData = $this->encrypt_key($key);
        $encryptData['original_key'] = $key;
        return $encryptData;
    }

    public function encrypt_for_user($file , $key){
        $resfile = $file;
        $desfile = substr($resfile,0,-4).'.plc';
        exec("openssl enc -d -aes-256-cbc -in {$resfile} -out {$desfile} -k {$key}");
        die("openssl enc -d -aes-256-cbc -in {$resfile} -out {$desfile} -k {$key}");
        return $desfile;
    }

    private function encrypt_key($key){
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privKey);
        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey["key"];
        openssl_public_encrypt($key, $encrypted, $pubKey);
        $data= [
            'private_key' => $privKey,
            'public_key' => $pubKey,
            'encrypt_key'=>$encrypted
        ];
        return $data;
    }
}