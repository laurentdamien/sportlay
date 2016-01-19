<!DOCTYPE html>
<html>
<head>
    <?php $cakeDescription = 'Sportlay - Pronostics et conseils en paris sportifs'; ?>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>
        <?= $cakeDescription ?>
    </title>
    <?= $this->Html->meta('favicon.ico', '/favicon.ico', ['type' => 'icon']); ?>
    <?= $this->Html->meta('keywords', 'Sportlay, paris, sportifs, sportif, paris sportifs, football, foot, compétition, pronostic, pronostics, pronostique, pronostiques, cote, cotes, vainqueur, parier, parieur, sport, aide, conseils, conseil, argent, gagner, perdu, match, nul, victoire, défaite, placer, ticket, combiné, simple, multiple'); ?>
    <?= $this->Html->meta('description', 'Sportlay est un site qui a pour but de conseiller et d\'aider les parieurs occasionnels ou réguliers dans leur choix de paris sportifs. Sportlay propose quotidiennement de multiple paris sur différents matchs sur les plus grandes compétitions européennes.'); ?>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <?= $this->Html->css(['styles']); ?>
</head>


<!-- Google Analytics -->
<?php if(CURRENT_PAGE != ADMIN && CURRENT_PAGE != LOGIN && DEBUG == 'false'){ ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-62371487-2', 'auto');
  ga('send', 'pageview');
</script>
<?php } ?> 