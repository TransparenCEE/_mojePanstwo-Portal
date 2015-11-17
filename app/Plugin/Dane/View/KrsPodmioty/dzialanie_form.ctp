<?

$this->Combinator->add_libs('css', $this->Less->css('view-krspodmioty', array('plugin' => 'Dane')));
$this->Combinator->add_libs('css', $this->Less->css('view-krspodmioty-dzialania', array('plugin' => 'Dane')));
$this->Combinator->add_libs('css', $this->Less->css('view-dzialania', array('plugin' => 'Dane')));

echo $this->Html->script('../plugins/cropit/dist/jquery.cropit.js', array('block' => 'scriptBlock'));

/* tinymce */
echo $this->Html->script('../plugins/tinymce/js/tinymce/tinymce.min', array('block' => 'scriptBlock'));

/* tag-it */
echo $this->Html->script('../plugins/tag-it/js/tag-it.min', array('block' => 'scriptBlock'));
echo $this->Html->css('../plugins/tag-it/css/jquery.tagit.css');
echo $this->Html->css('../plugins/tag-it/css/tagit.ui-zendesk.css');

/* page script */
$this->Combinator->add_libs('js', 'Dane.view-krspodmioty-dzialania');

$edit = isset($dzialanie);

echo $this->Element('dataobject/pageBegin'); ?>

<form class="dzialanie" action="<?= $object->getUrl(); ?>.json" method="post">
    <input type="hidden" name="_action" value="<?= $edit ? 'edit_activity' : 'add_activity'; ?>"/>

    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1">
            <? if ($edit) { ?>
                <header><h1><a href="<?= $dzialanie->getUrl() ?>"><?= $dzialanie->getData('tytul'); ?></a></h1></header>
                <input type="hidden" name="id" value="<?= $dzialanie->getId() ?>"/>
            <? } ?>
            <div class="row sub-header">
                <div class="col-sm-6">
                    <? if ($edit) { ?>
                        <span class="date">
                        <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                            <?= $this->Czas->dataSlownie(
                                $dzialanie->getData('data_utworzenia')
                            ); ?>
                    </span>
                    <? } ?>
                </div>
                <div class="col-sm-6">
                    <div class="share pull-right"></div>
                </div>
            </div>

        </div>

        <div class="col-xs-12 col-md-10 col-md-offset-1">
            <div class="well bs-component mp-form dzialanie">
                <? if (!$edit) { ?>
                    <legend>Dodaj nowe działanie organizacji!</legend>
                <? } ?>
                <div class="section col-xs-12 nopadding">
                    <? if (!$edit) { ?>
                        <p class="margin-top-10">Poinformuj innych o działaniach swojej organizacji. Informacje
                            o działaniach będą widoczne na stronie profilowej Twojej organizacji, a także
                            będą pojawiały się przy wynikach wyszukiwania na portalu mojePaństwo.</p>
                    <? } ?>

                    <div class="form-group margin-top-30">
                        <label for="dzialanieTitle">Tytuł działania:</label>
                        <input maxlength="195" type="text" class="form-control" id="dzialanieTitle"
                               name="tytul" <? if ($edit) echo 'value="' . $dzialanie->getData('tytul') . '"'; ?>/>
                    </div>
                    <div class="form-group margin-top-30">
                        <label for="dzialanieOpis">Krótkie podsumowanie:</label>
                                <textarea rows="7" maxlength="511" class="form-control"
                                          name="podsumowanie"><? if ($edit) echo $dzialanie->getData('podsumowanie'); ?></textarea>
                    </div>
                    <div class="form-group margin-top-30">
                        <label for="dzialanieOpis">Opis działania:</label>
		                        <textarea maxlength="16383" class="form-control tinymce" id="dzialanieOpis" name="opis">
		                            <? if ($edit) echo $dzialanie->getData('opis'); ?>
		                        </textarea>
                        <p class="help-block">Opis może się składać z maksymalnie 16383 znaków (w tym znaki HTML widoczne w Narzędzia - Kod źródłowy)</p>
                    </div>
                    <div class="form-group margin-top-30">
                        <label>Słowa kluczowe:</label>

                        <div class="row tags">
                            <input type="text" class="form-control tagit"
                                   name="tagi" <? if ($edit) printf('data-value="%s"', htmlspecialchars(json_encode(array_column($dzialanie_tags, 'label')), ENT_QUOTES, 'UTF-8')) ?> />
                        </div>
                    </div>
                    <div class="form-group margin-top-30" style="margin-bottom: 130px;">
                        <label>Zdjęcie:</label>

                        <div
                            class="image-editor" <? if ($edit && isset($dzialanie_photo_base64)) echo 'data-image="' . $dzialanie_photo_base64 . '"'; ?>>
                            <div class="cropit-image-preview"></div>
                            <div class="slider-wrapper">
                                <span class="icon icon-small glyphicon glyphicon-tree-conifer"></span>
                                <input type="range" class="cropit-image-zoom-input"/>
                                <span class="icon icon-large glyphicon glyphicon-tree-conifer"></span>
                            </div>
                            <p>Zalecany rozmiar: 810x320px</p>
                                    <span class="btn btn-default btn-file">Przeglądaj<input type="file"
                                                                                            class="cropit-image-input"/></span>
                        </div>

                    </div>
                    <div class="text-center">
                        <label class="text-normal">
                            <input type="checkbox" value="1"
                                   name="photo_disabled" <? if ($edit && $dzialanie->getData('photo_disabled') == '1') echo 'checked'; ?>/>
                            Nie pokazuj zdjęcia na stronie działania
                        </label>
                    </div>

                    <div class="form-group googleBlock margin-top-30">
                        <div class="row">
                            <div class="col-sm-12">
	                                    <span class="btn btn-link googleBtn" data-icon="&#xe607;">
	                                        <?= $edit ? 'Zmień' : 'Dodaj'; ?> lokalizację
	                                    </span>

                                <div class="col-xs-12 googleMapElement">
                                    <input id="pac-input" class="controls" type="text" placeholder="Szukaj...">

                                    <div id="loc" class="btn btn-sm"><i data-icon="&#xe607;"></i></div>

                                    <div id="googleMap"></div>
                                    <input
                                        type="hidden" <? if ($edit) echo 'value="' . $dzialanie->getData('geo_lat') . '"'; ?>
                                        type="text" name="geo_lat"/>
                                    <input
                                        type="hidden" <? if ($edit) echo 'value="' . $dzialanie->getData('geo_lng') . '"'; ?>
                                        type="text" name="geo_lng"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <? if (!$edit) { ?>
                        <div class="form-group mailBlock">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="btn btn-link btn-icon btn-auto-width mailBtn">
                                        <i class="icon glyphicon glyphicon-envelope"></i>
                                        Dodaj mailing
                                    </div>
                                    <div class="col-xs-12 mailElement">
                                        <div class="form-group margin-top-10">
                                            <label for="target">Do</label>
                                            <select id="target" name="mailing_target" class="form-control">
                                                <option>Posłowie</option>
                                                <option>Senatorowie</option>
                                            </select>
                                        </div>
                                        <div class="form-group margin-top-10">
                                            <label for="dzialanieOpis">Szablon</label>
                                                    <textarea style="height: 400px;" maxlength="16383"
                                                              class="form-control tinymce"
                                                              name="mail_template"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <? } ?>
                </div>

                <div class="section col-xs-12 nopadding">
                    <div class="form-group margin-top-20">
                        <label>Widoczność</label>

                        <div class="radio">
                            <input type="radio" name="status" id="widocznosc_opublikowane"
                                   value="1" <? if (!$edit || ($edit && $dzialanie->getData('status') == '1')) echo 'checked'; ?>>
                            <label for="widocznosc_opublikowane" class="checkbox-label">Opublikowane</label>
                        </div>
                        <div class="radio">
                            <input type="radio" name="status" id="widocznosc_brudnopis"
                                   value="0" <? if ($edit && $dzialanie->getData('status') == '0') echo 'checked'; ?>>
                            <label for="widocznosc_brudnopis" class="checkbox-label">Brudnopis</label>
                        </div>
                    </div>
                    <div class="form-group margin-top-20">
                        <label>Status</label><br/>

                        <div class="checkbox">
                            <input type="checkbox" value="1" id="status_zakonczone"
                                   name="zakonczone" <? if ($edit && $dzialanie->getData('zakonczone') == '1') echo 'checked'; ?>/>
                            <label for="status_zakonczone" class="text-normal">Zakończone</label>
                        </div>
                    </div>
                    <button class="btn auto-width btn-primary btn-icon submitBtn" type="submit">
                        <i class="icon glyphicon glyphicon-ok"></i>
                        Zapisz
                    </button>
                    <? if ($edit) { ?>
                        <div class="btn btn-link btn-icon btn-auto-width deleteBtn">
                            <i class="icon glyphicon glyphicon-remove"></i>
                            Usuń działanie
                        </div>
                    <? } ?>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->element('dataobject/pageEnd'); ?>
