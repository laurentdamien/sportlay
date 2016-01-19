<?php
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;

$this->layout = false;

if(DEBUG != 'false'){
    if (!Configure::read('debug'))
        throw new NotFoundException();
}

echo $this->element('head');

?>

<body class="page userHome">
   <?php echo $this->element('header'); ?>
   <section class="content gray text-center">
       <a href="<?= ADMIN; ?>">
            <svg width="50" height="50">
              <circle id="circleArrowReturn" r="20" cx="25" cy="25" stroke="white" stroke-width="3" fill="none" />
            </svg>
            <?php echo $this->Html->image("arrow_left.png", array(
                "alt" => "Flèche qui permet le retour à la page précédente",
                "class" => "arrowReturn pointer"
            )); ?>
        </a>
        <section>
            <a href="./logout" class="logout">Déconnexion</a>
            <h2>Bonjour <?= $user['username']; ?></h2>
        </section>

   </section>
<?php echo $this->element('footer'); ?>