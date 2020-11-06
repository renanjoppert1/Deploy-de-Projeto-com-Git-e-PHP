<?php
include('Net/SSH2.php');

/**
 *  Configuração de login SSH 
 *  Substitua os seguintes dados:
 * 
 *  dominio.com.br:22    =>    domínio do site ou IP (hostname):porta SSH
 *  user              =>    usuário SSH
 *  password          =>    senha SSH
 * 
 */
$ssh = new Net_SSH2('dominio.com.br:22');
if (!$ssh->login('usuer', 'password')) {
  exit('Falha no Login');
}


/**
 *  $path_directory   =>    você irá colocar o diretório base da sua aplicação. Você poderá navegar via SSH até a pasta destino e executar o comando pwd para pegar este valor
 *  $folder           =>    nome da pasta que você irá colcoar o seu projeto
 */

$path_directory = '';
$folder = 'api';

/**
 * 
 * Verifica se já existe uma pasta com o nome do projeto, se existir ele excluirá a pasta 
 *  
 */
$folders = $ssh->exec('cd '. $path_directory .' ; ls');
if (strpos($folders, $folder) != false) {
  echo $ssh->exec('rm -fr ' . $folder);
}

/**
 * 
 * Clona o repositório e renomeia a pasta do repositório para o nome definido em $folder
 *  
 */
echo $ssh->exec('git clone https://github.com/PokeAPI/pokeapi.git ' . $folder);
echo '<br>';


/**
 * 
 * Se você tiver algum arquivo de htaccess padrão, .env, ou qualquer outro tipo que deseja colar de outro diretório e colocar dentro, você poderá reaproveitar o código abaixo, se não, pode comentar
 *  
 */
$ssh->exec('cp /home/config-deploy/env/.env-example '. $path_directory .'/' . $folder . '/.env');
$ssh->exec('cp /home/config-deploy/htaccess/.htaccess-example '. $path_directory .'/' . $folder . '/.htaccess');



/**
 * 
 *  Se você utiliza o Laravel, poderá reaproveitar os comandos abaixo também!
 *  
 */
$ssh->exec('cd ' . $path_directory . '/'. $folder . '; composer install ; php artisan migrate:fresh --seed');
