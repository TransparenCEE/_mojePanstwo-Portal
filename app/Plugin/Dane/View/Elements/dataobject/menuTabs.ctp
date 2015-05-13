<? /*

<ul class="nav nav-pills nav-stacked">
  <li class="active"><a href="#">Treść</a></li>
  <li><a href="#">Akty powiązane</a></li>
  <li class="disabled"><a href="#">Disabled</a></li>
  <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
      Tematy <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="#">Action</a></li>
      <li><a href="#">Another action</a></li>
      <li><a href="#">Something else here</a></li>
      <li class="divider"></li>
      <li><a href="#">Separated link</a></li>
    </ul>
  </li>
</ul>
*/ ?>

<? if (isset($menu) && isset($menu['items'])) { ?>
    <ul class="nav nav-tabs">
        <li class="mobileMenu active visible-xs">
            <a href="#mobileMenu">
                <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>
            </a>
        </li>
        <?
        foreach ($menu['items'] as $m) {
            unset($m['icon']);
            $classes = array();

            if (isset($m['class']) && !empty($m['class'])) {
                $classes = explode(' ', $m['class']);
            }

            if (isset($menu['selected']) && ($m['id'] == $menu['selected'])) {
                $classes[] = 'active';
            }

            if (isset($m['dropdown']) && !empty($m['dropdown']['items'])) {
                foreach ($m['dropdown']['items'] as &$item) {
                    if ($item['id'] == $menu['selected']) {

                        $classes[] = 'active';
                        $item['selected'] = true;
                        break;

                    }
                }
            }

            $dropdown = false;
            if (isset($m['dropdown']) && $m['dropdown']) {
                $dropdown = true;
                $classes[] = 'dropdown';
            }

            $href = $menu['base'];
            if ($m['id'])
                $href .= '/' . $m['id'];

            ?>
            <li class="<?= implode(' ', $classes) ?>">
                <a <? if ($dropdown) {
                    echo 'class="dropdown-toggle" data-toggle="dropdown"';
                } ?>href="<?= $href ?>">
                    <? if (isset($m['icon'])) { ?><span class="<?= $m['icon'] ?>"></span> <? } ?>
                    <?= $m['label'] ?><? if (isset($m['count']) && $m['count']) { ?>
                        <span class="badge"><?= $m['count'] ?></span>
                    <? } ?>
                    <? if ($dropdown) { ?>
                        <span class="caret"></span>
                    <? } ?>
                </a>
                <? if ($dropdown) { ?>
                    <ul class="dropdown-menu">
                        <?
                        if (!empty($m['dropdown']['items'])) {
                            foreach ($m['dropdown']['items'] as $n) {
                                if (isset($n['topborder']) && $n['topborder']) { ?>
                                    <li class="divider"></li>
                                <? } ?>
                                <li<? if (isset($n['selected']) && $n['selected']) { ?> class="active"<? } ?>>
                                    <a href="<?= isset($n['href']) ? $n['href'] : '#' ?>">
                                        <?= $n['label'] ?><? if (isset($n['count']) && $n['count']) { ?>
                                            <span class="badge"><?= $n['count'] ?></span><? } ?>
                                    </a>
                                </li>
                            <? }
                        } ?>
                    </ul>
                <? } ?>
            </li>
        <? } ?>
    </ul>
<? } ?>
