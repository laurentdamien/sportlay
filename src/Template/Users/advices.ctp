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

if($adviceDefault->published > 0){
    $text = "text".($adviceDefault->published);
    $advicePublished = $adviceDefault->$text;
}else{
    $text = "text".($adviceToday->published);
    $advicePublished = $adviceToday->$text;
}

?>

<body class="page advices">
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

        <?php echo $this->element('menu_admin'); ?>
        
       <section class="contentAdvices">
            <section class="container">
                <div class="row">
                    <h3 class="black">Conseils du jour</h3>
                    <p class="gray text-center"><?= $advicePublished; ?></p>
                </div>
            </section>
            
            <section class="container listAdvices">
                <div class="row">
                   <table class="table table-hover table-responsive">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-center mediumCase"><i class="fa fa-calendar-o"></i></th>
                                <th class="text-uppercase text-center"><i class="fa fa-file-text-o"></i></th>
                                <th class="text-uppercase text-center"><i class="fa fa-file-text-o"></i></th>
                                <th class="text-uppercase text-center littleCase"><i class="fa fa-calendar-check-o"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($advices as $advice){

                                switch($advice['day']){
                                    case '0':
                                        $day = "Défaut";
                                        break;
                                    case '1':
                                        $day = "Lundi";
                                        break;
                                    case '2':
                                        $day = "Mardi";
                                        break;
                                    case '3':
                                        $day = "Mercredi";
                                        break;
                                    case '4':
                                        $day = "Jeudi";
                                        break;
                                    case '5':
                                        $day = "Vendredi";
                                        break;
                                    case '6':
                                        $day = "Samedi";
                                        break;
                                    case '7':
                                        $day = "Dimanche";
                                        break;
                                }
                           ?>
                            <tr class="Advice<?= $advice['id']; ?>">
                                <td class="text-center">
                                    <p class="idAdvice"><?= $day; ?></p>
                                    <p data-id="<?= $advice['id']; ?>" class="editAdvice hide">Enregistrer</p>
                                </td>
                                <td class="text-center">
                                    <?= $advice['text1']; ?>
                                    <textarea class="editAdvice hide" name="editAdviceText1"><?= $advice['text1']; ?></textarea>
                                </td>
                                <td class="text-center">
                                    <?= $advice['text2']; ?>
                                    <textarea class="editAdvice hide" name="editAdviceText2"><?= $advice['text2']; ?></textarea>
                                </td>
                                <td class="text-center">
                                    <?= $advice['published']; ?>
                                    <input class="editAdvice text-center hide" name="editAdvicePublished" type="text" value="<?= $advice['published']; ?>" />
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <form id="formUpdateAdvice" class="hide" method="post" action="<?= $this->Url->build('/admin/updateAdvice', true); ?>">
                       <input type="hidden" name="editIdAdvice" />
                       <input type="hidden" name="editAdviceText1" />
                       <input type="hidden" name="editAdviceText2" />
                       <input type="hidden" name="editAdvicePublished" />
                       <input type="submit" />
                    </form>
                </div>
            </section>
        </section>
    </section>
<?php echo $this->element('footer'); ?>