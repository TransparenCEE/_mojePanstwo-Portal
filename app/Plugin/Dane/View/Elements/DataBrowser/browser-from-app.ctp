<?
echo $this->Html->css($this->Less->css('app'));

$displayAggs = isset($displayAggs) ? (boolean)$displayAggs : true;
$columns = isset($columns) ? $columns : array(9, 3);

echo $this->element('headers/main');
?>

<div class="app-sidebar">
    <div class="app-logo">
        <? if (!empty($_app)) { ?>
            <a href="#" target="_self">
                <img class="icon"
                     src="<?= $_app['href'] ?>/icon/icon_<?= $_app['id'] ?>.svg">
                <strong><?= $_app['name'] ?></strong>
            </a>
        <? } ?>
    </div>
    <ul class="app-list">
        <? array_shift($app_chapters['items']); ?>
        <? foreach ($app_chapters['items'] as $a) { ?>
            <li>
                <a href="<?= $a['href'] ?>" target="_self">
                    <span class="icon <?= $a['icon'] ?>"></span>
                    <strong><?= $a['label'] ?></strong>
                </a>
                <? /* <div class="sub-list"><li><li></ul> */ ?>
            </li>
        <? } ?>
    </ul>
</div>

<div class="app-content-wrap">
    <div class="objectsPage">
        <div class="dataBrowser margin-top-0<? if (isset($class)) echo " " . $class; ?>">
            <div class="container">
                <div class="dataBrowserContent">
                    <?
                    $options = array(
                        'displayAggs' => false,
                        'columns' => $columns,
                        'searcher' => true,
                    );

                    /*
                    if(isset($menu)) {
                        $options['menu'] = $menu;
                    }
                    */

                    echo $this->element('Dane.DataBrowser/browser-content', $options);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
