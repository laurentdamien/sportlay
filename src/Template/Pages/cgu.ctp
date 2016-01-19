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

<body class="page cgu">
   <?php echo $this->element('header'); ?>
   <section class="content gray text-center">
       <a href="<?= HOME; ?>">
            <svg width="50" height="50">
              <circle id="circleArrowReturn" r="20" cx="25" cy="25" stroke="white" stroke-width="3" fill="none" />
            </svg>
            <?php echo $this->Html->image("arrow_left.png", array(
                "alt" => "Flèche qui permet le retour à la page précédente",
                "class" => "arrowReturn pointer"
            )); ?>
        </a>
        <section class="row introCGU">
            <h2>Conditions Générales d'Utilisations</h2>
            <h3>Objet des CGU</h3>
            <p>Les présentes Conditions Générales d'Utilisation ont pour objet de définir les modalités de mise à disposition du site www.sportlay.com, ci-après nommé « Sportlay - Pronostics et conseils en paris sportifs » et les conditions d'utilisation du Service par l'Utilisateur.</p>


            <h3>Définitions</h3>
            <p>Sportlay est un site qui apporte des conseils et des avis sur des rencontres sportives. L'objectif est d'essayer de pronostiquer le bon résultat en conseillant au mieux les utilisateurs. Ces conseils sont bâtis à partir de plusieurs années d'expérience.</p>
        </section>
        
        <section class="row detailsCGU">
            <h3>Propriété intellectuelle</h3>
            <p>Toutes analyses, univers graphiques, pronostics proposés, nom du site, logo et icônes appartiennent à Sportlay et nul ne peut les réutiliser à des fins commerciales ou de diffusions publiques et privées.</p>
            <p>Chaque pronostic et analyse sont uniquement l'avis personnel de Sportlay.</p>

            <h3>Responsabilité</h3>
            <p>Sportlay n'oblige en rien les utilisateurs à suivre les pronostics affichés sur le site. Chacun est libre et fait ce que bon lui semble des pronostics proposés. Sportlay rejette toutes responsabilités en cas de pertes de biens d'un utilisateur suite à un pronostics présent sur le site.</p>

            <h3>Force majeur</h3>
            <p>La responsabilité de www.sportlay.com ne pourra être engagée en cas de force majeure ou de faits indépendants de sa volonté.</p>

            <h3>Modification / Evolution / Mise-à-jour</h3>
            <p>Sportlay se réserve le droit de modifier à tout moment les Conditions Générales d'Utilisation.</p>
        </section>
   </section>
<?php echo $this->element('footer'); ?>