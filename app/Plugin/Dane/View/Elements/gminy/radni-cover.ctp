<?
$this->Combinator->add_libs('css', $this->Less->css('zamowienia', array('plugin' => 'ZamowieniaPubliczne')));
$this->Combinator->add_libs('js', '../plugins/highcharts/js/highcharts');
$this->Combinator->add_libs('js', '../plugins/highcharts/locals');
$this->Combinator->add_libs('js', 'Dane.DataBrowser.js');

$options = array(
    'mode' => 'init',
);
?>
<div class="col-md-3 col-xs-12 dataAggsContainer">

    <? if(isset($_submenu) && isset($_submenu['items'])) {

        if (!isset($_submenu['base']))
            $_submenu['base'] = $object->getUrl();

        echo $this->Element('Dane.DataBrowser/browser-menu', array(
            'menu' => $_submenu,
        ));

    } ?>

    <?
    $this->Combinator->add_libs('css', $this->Less->css('banners-box', array('plugin' => 'Dane')));
    $this->Combinator->add_libs('css', $this->Less->css('pisma-button', array('plugin' => 'Pisma')));
    $this->Combinator->add_libs('js', 'Pisma.pisma-button');
    echo $this->element('tools/pismo', array(
        'label' => '<strong>Wyślij pismo</strong> do Rady Miasta',
        'adresat' => 'rada_gminy:' . $object->getId() .'',
    ));
    ?>

</div>
<div class="col-md-9">

        <div class="databrowser-panels">

            <? if ($object->getId() == 903) { ?>

                <div class="databrowser-panel">
                    <div class="aggs-init">

                        <div class="dataAggs">
                            <div class="agg agg-Dataobjects">
                                <? if ($dataBrowser['aggs']['radni']['top']['hits']['hits']) { ?>
                                    <ul class="dataobjects row radni_cover">
                                        <? foreach ($dataBrowser['aggs']['radni']['top']['hits']['hits'] as $doc) { ?>
                                            <li class="col-md-4">
                                                <?
                                                echo $this->Dataobject->render($doc, 'krakow_radni');
                                                ?>
                                            </li>
                                        <? } ?>
                                    </ul>
                                <? } ?>

                            </div>
                        </div>


                    </div>
                </div>

            <? } ?>

        </div>

</div>
