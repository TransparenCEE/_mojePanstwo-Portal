<style>
	.objectsPageWindow .container {display: none;}
	#connectionGraph {min-height: 700px;}
</style>

<?
if (isset($odpis) && $odpis) {
    $this->Html->meta(array(
        'http-equiv' => "refresh",
        'content' => "0;URL='$odpis'"
    ), null, array('inline' => false));
}

echo $this->Element('dataobject/pageBegin');

echo $this->Html->script('Dane.d3/d3', array('block' => 'scriptBlock'));

$this->Combinator->add_libs('css', $this->Less->css('dataobjectslider', array('plugin' => 'Dane')));
$this->Combinator->add_libs('css', $this->Less->css('view-krspodmioty', array('plugin' => 'Dane')));
$this->Combinator->add_libs('css', $this->Less->css('view-krs-graph', array('plugin' => 'Dane')));
$this->Combinator->add_libs('js', 'Dane.view-krspodmioty');
$this->Combinator->add_libs('js', 'graph-krs');
?>

</div>
</div>
</div>


<div id="connectionGraph" class="loading" data-id="<?php echo $object->getId() ?>"></div>


<div class="container">
<div class="row">
<div class="objectsPageContent main">

<?= $this->Element('dataobject/pageEnd'); ?>