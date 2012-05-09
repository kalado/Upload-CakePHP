<?php

App::uses('AppController', 'Controller');

class ImagensController extends AppController {

    public $name = 'Imagens';
    public $components = array('RequestHandler');
    private $path = "";

    public function addimg($controller=null, $id=null, $tamanho = 200) {
        $this->setPath("galerias/" . $controller);

        if ($controller && $id) {
            $data['Imagem']['nome'] = $this->uploadimg($tamanho);
            $data['Imagem']['model'] = $controller;
            $data['Imagem']['idreferente'] = $id;
            $data['Imagem']['url'] = "/galerias/" . $controller . "/" . $data['Imagem']['nome'];
            $this->Imagem->save($data);
        }

        echo "1";

        $this->autoRender = false;
    }

    private function uploadimg($tamanho = 200) {
        if (!empty($_FILES)) {
            $img = $_FILES['Filedata']['name'];
            $ext = substr($img, -4);
            $img = md5($img . date("dmYHis")) . $ext;
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $targetPath = $this->path;
            $targetFile = $targetPath . "/" . $img;

            move_uploaded_file($tempFile, $targetFile);
        }

        $imgsize = getimagesize($targetFile);
        switch (strtolower(substr($targetFile, -3))) {
            case "jpg":
                $image = imagecreatefromjpeg($targetFile);
                break;
            case "png":
                $image = imagecreatefrompng($targetFile);
                break;
            case "gif":
                $image = imagecreatefromgif($targetFile);
                break;
            default:
                exit;
                break;
        }

        $width = $tamanho;
        $height = $imgsize[1] / $imgsize[0] * $width; //This maintains proportions

        $src_w = $imgsize[0];
        $src_h = $imgsize[1];

        $picture = imagecreatetruecolor($width, $height);
        imagealphablending($picture, false);
        imagesavealpha($picture, true);
        $bool = imagecopyresampled($picture, $image, 0, 0, 0, 0, $width, $height, $src_w, $src_h);

        if ($bool) {
            switch (strtolower(substr($targetFile, -3))) {
                case "jpg":
                    header("Content-Type: image/jpeg");
                    $bool2 = imagejpeg($picture, $targetPath . $img, 80);
                    break;
                case "png":
                    header("Content-Type: image/png");
                    imagepng($picture, $targetPath . $img);
                    break;
                case "gif":
                    header("Content-Type: image/gif");
                    imagegif($picture, $targetPath . $img);
                    break;
            }
        }

        imagedestroy($picture);
        imagedestroy($image);

        $nome = explode("//", $targetFile);
        return $nome[1];
    }

    private function setPath($p = "galerias") {
        $this->path = APP . WEBROOT_DIR . DS;
        $this->path = $this->path . $p . DS;
        $this->path = eregi_replace("/", DS, $this->path);
        $this->path = eregi_replace("\\\\", DS, $this->path);

        if (!is_dir($this->path)) {
            mkdir($this->path);
        }

        return true;
    }

    public function loadimg($controller=null, $id=null) {
        $this->layout = "ajax";
        $imgs = $this->Imagem->find("all", array("fields" => "id, nome, model, utilidade", "conditions" => array("Imagem.model = '" . $controller . "'", "Imagem.idreferente = '" . $id . "'")));

        $file = json_encode($imgs);

        $this->set("file", $file);
    }

    public function savedata() {
        if (isset($this->data['Imagem'])) {
            $data = "";
            $cont = count($this->data['Imagem']['utilidade']);

            $i = 0;
            foreach ($this->data['Imagem']['utilidade'] as $label => $var) {
                if ($cont == $i) {
                    $data .= $label . "," . $var;
                } else {
                    $data .= $label . "," . $var . ";";
                }
                $i++;
            }

            $dt['Imagem']['id'] = $this->data['Imagem']['id'];
            $dt['Imagem']['utilidade'] = $data;
            $this->Imagem->save($dt);
        }
    }

}
?>