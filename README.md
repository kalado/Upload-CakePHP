Upload-CakePHP
==============

Projeto de um Plugin para MultiUpload de imagens no CakePHP. Objetivo: Simples de implementar e Fácil de usar.



Instalação
==============

Crie uma pasta chama Upload na pasta "Plugin" de sua aplicação.
Adicione a linha: public $helpers = array('Upload.Upload'); em seu Controller.
Adicione no bootstrap.php a linha: CakePlugin::load('Upload');

Crie uma tabela no seu banco com a seguinte SQL :

CREATE  TABLE IF NOT EXISTS `imagens` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(255) NULL ,
  `url` VARCHAR(255) NULL ,
  `model` VARCHAR(255) NULL ,
  `idreferente` VARCHAR(10) NULL ,
  `utilidade` VARCHAR(255) NULL DEFAULT 'Logotipo,0;Destaque,0' ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB


Para chamar a galeria, adicione na sua View a seguinte linha:
<?php echo $this->Upload->send($id); ?>

Obs: O valor $id é referente a integração entre Tabelas e Registros. Este plugin foi criado para sempre fazer upload de imagens vinculado a um registro de suas Tabelas. Mas perceba que não é necessário nenhuma integração de SQL a não ser a citada acima.


