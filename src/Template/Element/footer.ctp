        <footer>
            <div>
                <p><a href="<?= CGU; ?>" class="gray">CGU</a></p>
                <p class="gray">contact@sportlay.com</p>
            </div>
            <p class="gray text-center">© Sportlay - 2015</p>
        </footer>
        <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <?php
            switch(CURRENT_PAGE){
                case HOME:
                    echo $this->Html->script('owl.carousel');
                    echo $this->Html->script('home');
                    break;
                
                case ADMIN:
                    echo $this->Html->script('classie');
                    echo $this->Html->script('selectFx');
                    echo $this->Html->script('admin');
                    break;
                
                case ADVICES:
                case STATS:
                    echo $this->Html->script('admin');
                    break;
    
            }

            if(strstr(CURRENT_PAGE, PROGNOSTIC) || strstr(CURRENT_PAGE, TICKET)){
                echo $this->Html->script('prognostic');
            }

            echo $this->Html->script('script');
        ?>
    </body>
</html>