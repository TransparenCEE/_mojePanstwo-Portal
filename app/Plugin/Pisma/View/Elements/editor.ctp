<?php echo $this->Html->css('/plugins/bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.min', array('block' => 'cssBlock')); ?>

<?php echo $this->Html->script('/plugins/bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.all', array('block' => 'scriptBlock')); ?>
<?php echo $this->Html->script('/plugins/bootstrap3-wysiwyg/dist/locales/bootstrap-wysihtml5.pl-PL', array('block' => 'scriptBlock')); ?>

<?php $this->Combinator->add_libs('css', $this->Less->css('pisma', array('plugin' => 'Pisma'))) ?>
<?php $this->Combinator->add_libs('js', 'Pisma.jquery.autosize.min.js') ?>
<?php $this->Combinator->add_libs('js', 'Pisma.jquery_steps.js') ?>
<?php $this->Combinator->add_libs('js', 'Pisma.pisma.js') ?>

<?php
if (!empty($pismo['szablon_id'])) $pismo_init['szablon_id'] = $pismo['szablon_id'];
if (!empty($pismo['adresat_id'])) $pismo_init['adresat_id'] = $pismo['adresat_id'];
?>


<div id="stepper" class="stepper"<? if (!empty($pismo_init)) echo ' data-pismo=' . json_encode($pismo_init); ?>>

    <h2>Wybierz szablon i adresata</h2>
    <section>
        <div class="container start">
            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <p class="hint-title">Wybierz szablon, aby ułatwić tworzenie pisma. Na podstawie wybranego szablonu,
                    umieścimy w Twoim piśmie odpowiednie formuły prawne i inne informacje. Jeśli nie chcesz
                    wybierać szablonu - przejdź dalej.</p>

                <form class="letter form-horizontal">
                    <?php echo $this->element('Pisma.start') ?>
                    <fieldset class="final">
                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-1 text-center">
                                <button type="submit" class="btn btn-md btn-primary">Wpisz treść pisma
                                    <span class="glyphicon glyphicon-play"></span>
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </section>

    <h2>Wpisz treść</h2>
    <section>
        <div class="container edit">
            <div class="editor-container row">
                <div class="col-xs-12 col-md-10">
                    <? echo $this->Element('Pisma.render'); ?>
                </div>
                <div class="col-xs-12 col-md-2 nopadding">
                    <div class="editor-tooltip">
                        <ul class="form-buttons">
                            <li class="inner-addon">
                                <i class="glyphicon glyphicon-send"></i>
                                <button class="btn btn-primary" name="send">Zapisz i wyślij</button>
                            </li>
                            <li class="inner-addon">
                                <i class="glyphicon glyphicon-saved"></i>
                                <button class="btn btn-primary" name="save">Zapisz</button>
                            </li>
                            <li class="inner-addon">
                                <i class="glyphicon glyphicon-print"></i>
                                <button class="btn btn-default" name="print">Drukuj</button>
                            </li>
                            <li class="inner-addon">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                                <button class="btn btn-default" name="delete">Skasuj</button>
                            </li>
                        </ul>
                    </div>
                    <div class="modal fade" id="pismoConfirm" tabindex="-1" role="dialog"
                         aria-labelledby="pismoConfirmLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Wprowadź nazwę pisma</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="pismoTitle">Podaj nazwę tego pisma:</label>
                                        <input type="text" class="form-control" id="pismoTitle" placeholder="Nowe pismo"
                                               maxlength="255">

                                        <p class="help-block errorMsg hide">Prosze wprowadzić nazwę pisma.</p>
                                    </div>
                                    <p class="help-block">Adresat Twojego pisma nie zobaczy tego pola. Będzie ono
                                        widoczne tylko dla Ciebie
                                        na stronie "Moje Pisma".</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                                    <button type="button" class="btn saveTemplate btn-primary">Zapisz</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
