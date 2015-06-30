<?php $this->Combinator->add_libs('css', $this->Less->css('view-bdl-wskazniki', array('plugin' => 'Dane'))); ?>

<?php $this->Combinator->add_libs('js', '../plugins/highcharts/js/highcharts'); ?>
<?php $this->Combinator->add_libs('js', '../plugins/highcharts/locals'); ?>
<?php $this->Combinator->add_libs('js', 'Dane.view-bdl-wskazniki'); ?>

<?= $this->Element('dataobject/pageBegin', array('renderFile' => 'page-bdl_wskazniki')); ?>
<?= $this->Element('bdl_select', array('expand_dimension' => $expand_dimension, 'dims' => $dims)); ?>

    <div id="bdl-wskazniki" class="col-md-12">

        <? if (in_array('bdl_opis', $object_editable)) { ?>

            <?php $this->Combinator->add_libs('js', 'Dane.bdl_opis'); ?>
            <?php $this->Combinator->add_libs('css', $this->Less->css('bdl_opis', array('plugin' => 'Dane'))); ?>
            <?php $this->Combinator->add_libs('js', 'Dane.bdl_useritem'); ?>

            <?php $this->Combinator->add_libs('css', '../plugins/bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5'); ?>
            <?php echo $this->Html->script('../plugins/bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.all', array('block' => 'scriptBlock')); ?>
            <?php echo $this->Html->script('../plugins/bootstrap3-wysiwyg/dist/locales/bootstrap-wysihtml5.pl-NEW', array('block' => 'scriptBlock')); ?>

            <div id="bdl_opis_modal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Edycja nazwy i opisu wskaźnika:</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-sm-11">
                                <div class="hidden alert alert-success info"></div>
                                <div class="row "><label class="">Nazwa:</label></div>
                                <div class="row"><input class="form-control nazwa"
                                                        value="<?= $object->getData('bdl_podgrupa.nazwa') ? $object->getData('bdl_podgrupa.nazwa') : $object->getData('bdl_podgrupa.tytul'); ?>">
                                </div>
                                <br>

                                <div class="row"><label>Opis:</label></div>
                            </div>
                            <article id="editor">
                                <?= $object->getData('bdl_podgrupa.opis'); ?>
                            </article>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-md btn-primary btn-icon" id="bdl_savebtn"><i
                                    class="icon glyphicon glyphicon-ok"></i>Zapisz
                            </button>
                        </div>
                    </div>
                </div>
            </div>



            <div id="bdl_user_wskaznik_modal" class="modal fade">
                <div class="modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Dodaj do wskaźnika:</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-sm-12">
                                <div class="hidden alert alert-success info"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <form><label class="pull-right">Wybierz wskaźnik: </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select id="lista_wskaznikow" name="wskaznik" class="form-control">
                                        </select>
                                        </form>
                                    </div>
                                </div>
                                <div class="row nazwa_wskaznika">
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="text-center">Licznik</h4>
                                        <ul class="licznik_list list-group">

                                        </ul>
                                        <button type="button" class="btn btn-md btn-success btn-icon" id="bdl_temp_addbtn_l">
                                            <i
                                                class="icon glyphicon glyphicon-plus"></i>Dodaj
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <h4 class="text-center">Mianownik</h4>
                                        <ul class="mianownik_list list-group">

                                        </ul>
                                        <button type="button" class="btn btn-md btn-success btn-icon" id="bdl_temp_addbtn_m">
                                            <i
                                                class="icon glyphicon glyphicon-plus"></i>Dodaj
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <span class="text-center">
                                <button type="button" class="btn btn-md btn-primary btn-icon btn-inline"
                                        id="bdl_temp_savebtn"><i
                                        class="icon glyphicon glyphicon-ok"></i>Zapisz
                                </button>
                                <button type="button" class="btn btn-md btn-danger btn-icon  btn-inline"
                                        id="bdl_temp_cancelbtn"><i
                                        class="icon glyphicon glyphicon-remove"></i>Anuluj
                                </button>
                                </span>
                        </div>
                    </div>
                </div>
            </div>


        <? } ?>

        <div class="object">

            <?
            if (!empty($expanded_dimension)) {
                foreach ($expanded_dimension['options'] as $option) {
                    if (isset($option['data'])) {
                        ?>

                        <div class="wskaznik" data-dim_id="<?= $option['data']['id'] ?>">
                            <h2>
                                <a href="<?= $object->getUrl() ?>/kombinacje/<?= $option['data']['id'] ?>"><?= trim($option['value']) ?></a>
                                <? if (in_array('bdl_opis', $object_editable)) { ?>
                                    <button class="btn btn-sm btn-primary add_to_item pull-right"><span
                                            class="icon glyphicon glyphicon-plus"></span></button>
                                <? } ?>
                            </h2>

                            <div class="stats">
                                <div class="map call-md-2">
                                    <a href="<?= $object->getUrl() ?>/kombinacje/<?= $option['data']['id'] ?>">
                                        <img width="216" height="200"
                                             src="http://resources.sds.tiktalik.com/BDL_wymiary_kombinacje/<?= $option['data']['id'] ?>.png"
                                             class="imageInside" onerror="imgFixer(this)"/>
                                    </a>
                                </div>
                                <div class="charts col-md-9">
                                    <div class="head">
                                        <p class="vp">
                                            <span
                                                class="v"><?= number_format($option['data']['lv'], 2, ',', ' ') ?></span>
                                            <span class="u"><?= $option['data']['jednostka'] ?></span>
                                        <span
                                            class="y"><?= __d('dane', 'LC_BDL_WSKAZNIKI_LASTYEAR', array($option['data']['ly'])) ?></span>
                                        </p>

                                        <p class="fp">
                                            <?php if (isset($option['data']['dv']) && isset($option['data']['ply'])) { ?>
                                                <span class="factor <? if (intval($option['data']['dv']) < 0) {
                                                    echo "d";
                                                } else {
                                                    echo "u";
                                                } ?>">
                                                    <?= $option['data']['dv'] ?> %
                                                </span>
                                                <span class="i">
                                                    <?= __d('dane', 'LC_BDL_WSKAZNIKI_PREVLASTYEAR', array($option['data']['ply'])) ?>
                                                </span>
                                            <?php } ?>
                                        </p>
                                    </div>
                                    <div class="chart">
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="45"
                                                 aria-valuemin="0" aria-valuemax="100" style="width: 15%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?
                    }
                }
            }
            ?>

        </div>
    </div>

<?= $this->Element('dataobject/pageEnd'); ?>