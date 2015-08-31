<?
$this->Combinator->add_libs('js', '../plugins/highstock/js/highstock');
$this->Combinator->add_libs('js', '../plugins/highstock/js/modules/drilldown');
$this->Combinator->add_libs('js', '../plugins/highstock/locals');

$this->Combinator->add_libs('js', 'Dane.budzet-view');
?>

<? echo $this->Element('dataobject/pageBegin'); ?>

<div class="row">
    <div class="col-md-9">

        <div class="block block-simple col-xs-12">
            <header>Główne parametry budżetu:</header>
            <section class="aggs-init margin-sides-20">
                <div class="dataAggs">
                    <div class="agg agg-Dataobjects">

                        <div class="col-xs-12 col-sm-11 row">
                            <table class="table table-strict">
                                <thead>
                                <tr>
                                    <th>Dochody</th>
                                    <th>Wydatki</th>
                                    <th>Deficyt</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= number_format_h($object->getData('liczba_dochody') * 1000) ?></td>
                                    <td><?= number_format_h($object->getData('liczba_wydatki') * 1000) ?></td>
                                    <td><?= number_format_h($object->getData('liczba_deficyt') * 1000) ?></td>
                                </tr>
                                </tbody>
                            </table>

                            <? if ($object->getData('rok') > 2006 && $object->getData('rok') < 2010) { ?>
                                <table class="table table-strict">
                                    <caption>W tym ze środków Unii Europejskiej</caption>
                                    <tbody>
                                    <tr>
                                        <td><?= number_format_h($object->getData('liczba_dochody_eu') * 1000) ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            <? } ?>
                            <? if ($object->getData('rok') > 2009) { ?>
                                <table class="table table-strict">
                                    <caption>W tym ze środków Unii Europejskiej</caption>
                                    <thead>
                                    <tr>
                                        <th>Dochody</th>
                                        <th>Wydatki</th>
                                        <th>Deficyt</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><?= number_format_h($object->getData('liczba_dochody_eu') * 1000) ?></td>
                                        <td><?= number_format_h($object->getData('liczba_wydatki_eu') * 1000) ?></td>
                                        <td><?= number_format_h($object->getData('liczba_deficyt_eu') * 1000) ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            <? } ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="col-md-3">

        <ul class="dataHighlights rightColumn margin-top-15">

            <?
            $data_wydania = $object->getData('prawo.data_wydania');
            if (isset($data_wydania) && !empty($data_wydania) && ($data_wydania != '0000-00-00')) { ?>
                <li class="dataHighlight col-xs-12">
                    <p class="_label">Data wydania</p>

                    <p class="_value"><?= $this->Czas->dataSlownie($data_wydania); ?></p>
                </li>
            <? } ?>

            <?
            $data_publikacji = $object->getData('prawo.data_publikacji');
            if (isset($data_publikacji) && !empty($data_publikacji) && ($data_publikacji != '0000-00-00')) { ?>
                <li class="dataHighlight col-xs-12">
                    <p class="_label">Data publikacji</p>

                    <p class="_value"><?= $this->Czas->dataSlownie($data_publikacji); ?></p>
                </li>
            <? } ?>


            <?
            $data_wejscia_w_zycie = $object->getData('prawo.data_wejscia_w_zycie');
            if (isset($data_wejscia_w_zycie) && !empty($data_wejscia_w_zycie) && ($data_wejscia_w_zycie != '0000-00-00')) { ?>
                <li class="dataHighlight col-xs-12">
                    <p class="_label">Data wejścia w życie</p>

                    <p class="_value"><?= $this->Czas->dataSlownie($data_wejscia_w_zycie); ?></p>
                </li>
            <? } ?>

            <?
            if ($sygnatura = $object->getData('prawo.sygnatura')) { ?>
                <li class="dataHighlight col-xs-12">
                    <p class="_label">Sygnatura</p>

                    <p class="_value"><?= $sygnatura ?></p>
                </li>
            <? } ?>
        </ul>

    </div>
</div>
<div class="row">

    <div class="block block-simple col-xs-12">

        <? $dane = array(
            'dzialy' => array(),
            'rozdzialy' => array()
        );
        $temp = array();

        $source = $object->getLayers('dzialy');
        if ($source['dzialy']) {
            $i = 0;
            $inne = array(
                'name' => 'Pozostałe',
                'y' => 0,
                'drilldown' => 'Inne'
            );
            foreach ($source['dzialy'] as $czesc) {
                $ret = array();
                $ret['name'] = $czesc['pl_budzety_wydatki']['tresc'];
                $ret['y'] = $czesc[0]['plan'];
                $ret['drilldown'] = $czesc['pl_budzety_wydatki']['dzial_str'];
                if ($i > 13) {
                    $inne['y'] += (int)$czesc[0]['plan'];
                    $temp[] = $ret;
                } else {
                    $dane['dzialy'][] = $ret;
                }
                $i++;
            }
            $dane['dzialy'][] = $inne;
            $dane['rozdzialy'][] = array(
                'name' => 'Pozostałe',
                'id' => 'Inne',
                'data' => $temp
            );
            $rozdzialy = array();
            foreach ($source['rozdzialy'] as $rozdzial) {
                $ret = array();
                $src = $rozdzial['pl_budzety_wydatki_dzialy']['src'];
                if (isset($rozdzialy[$src])) {

                    $rozdzialy[$src]['data'][] = array(
                        'name' => $rozdzial['pl_budzety_wydatki']['tresc'],
                        'y' => $rozdzial[0]['plan']
                    );

                } else {

                    $ret['name'] = $rozdzial['pl_budzety_wydatki_dzialy']['tresc'];
                    $ret['id'] = $src;
                    $ret['data'] = array();
                    $ret['data'][] = array(
                        'name' => $rozdzial['pl_budzety_wydatki']['tresc'],
                        'y' => $rozdzial[0]['plan']
                    );
                    $rozdzialy[$src] = $ret;

                }
            }

            foreach ($rozdzialy as $rozdzial) {
                $dane['rozdzialy'][] = $rozdzial;
            }
            ?>
            <div class="hidden highchart_datasource" data-highchart='<? echo json_encode($dane) ?>'></div>

            <header>Wydatki według działów:</header>
            <section class="aggs-init margin-sides-20">
                <div class="dataAggs">
                    <div class="agg agg-Dataobjects">
                        <div id="wydatki_budzetu_wg_czesci"></div>
                        <div id="wydatki_budzetu_wg_czesci2" class="hidden"></div>
                        <small>Kliknij w interesujący wycinek wykresu, aby uzyskać więcej danych</small>
                    </div>
                </div>
            </section>
        <? } ?>
    </div>

</div>


<?= $this->Element('dataobject/pageEnd'); ?>