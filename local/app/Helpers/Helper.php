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

    public function move_to_ftp($file){
        $ftp = new \FtpClient\FtpClient();
        $ftp->connect(config('ftp.ip'));
        $ftp->login(config('ftp.user'), config('ftp.password'));
        $file = public_path($file);
        $path = pathinfo($file);
        $path['dirname'] = 'pelck/'.$path['dirname'];
        $ftp->mkdir($path['dirname'],true);
        if($ftp->put($path['dirname'].'/'.$path['filename'],$file,FTP_BINARY)){
            unlink($file);
            return TRUE;
        }
        return FALSE;
    }

    public function encrypt_file($file , $remove_original = FALSE){
        $resfile = public_path($file);
        $desfile = $resfile.".enc";
        exec("openssl enc -aes-256-cbc -salt -in {$resfile} -out {$desfile} -pass file:/root/key.bin");
        if($remove_original == TRUE){
            unlink(public_path($file));
        }
        return $file.'.enc';
    }

    public function decrypt_file($file){
        $resfile = public_path($file);
        $desfile = substr($resfile,0,-4);
        exec("openssl enc -d -aes-256-cbc -in {$resfile} -out {$desfile} -pass file:/root/key.bin");
        return $desfile;
    }

    public function encrypt_for_user($file){
        $resfile = public_path($file);
        $desfile = substr($resfile,0,-4).'.plc';
        $bytes = openssl_random_pseudo_bytes(6);
        $key = bin2hex($bytes);
        exec("openssl enc -d -aes-256-cbc -in {$resfile} -out {$desfile} -K $key");
        $encryptData = $this->encrypt_key($key);
        $data = [
            'file'=>$desfile,
            'original_key'=>$key,
            'encrypt_data' => $encryptData
        ];
        return $data;
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