<?

echo $this->Element('dataobject/pageBegin', array(
    'titleTag' => 'p',
));

echo $this->Element('Dane.dataobject/subobject', array(
    'object' => $prawo,
    'objectOptions' => array(
        'bigTitle' => true,
        'truncate' => 1024,
    )
));

?>
    <div class="prawo margin-sides-10">

        <div class="row">
			<div class="col-md-9">
                <?= $this->Document->place(
                    isset($dokument_id) && $dokument_id ?
                        $dokument_id : $prawo->getData('dokument_id')
                ) ?>
            </div>                
        </div>

    </div>
<?
echo $this->Element('dataobject/pageEnd');
