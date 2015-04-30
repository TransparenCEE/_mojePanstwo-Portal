<?php $this->Combinator->add_libs('css', $this->Less->css('pisma', array('plugin' => 'MojePisma'))) ?>
<?php $this->Combinator->add_libs('css', $this->Less->css('pisma-moje', array('plugin' => 'MojePisma'))) ?>

<?php $this->Combinator->add_libs('js', 'MojePisma.pisma-my.js') ?>

<?= $this->Element('appheader'); ?>

<div class="search-container">
    <? if ($pagination['total']) { ?>
    <div class="container">
	    <div class="row">
		    <div class="col-sm-10 col-sm-offset-1">
			    <div class="form-group">
			        <form method="GET" action="/moje-pisma">
			            <input name="q" class="form-control input-md" placeholder="Szukaj w moim pismach..." type="text"
			                   value="<?= $q ?>">
			            <input type="submit" value="Szukaj" style="display: none;"/>
			        </form>
			    </div>
		    </div>
	    </div>
    </div>
    <? } ?>
</div>

<div class="container" id="myPismaBrowser" data-query='<?= json_encode($query); ?>'>
    <div class="col-md-10 col-sm-offset-1">
	
		<? if( !$this->Session->read('Auth.User.id') ) { ?>
		<div class="alert alert-dismissable alert-success">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<h4>Uwaga!</h4>
			<p>Nie jesteś zalogowany. Twoje pisma będą przechowywane na tym urządzeniu przez 24 godziny. <a class="_specialCaseLoginButton" href="/login">Zaloguj się</a>, aby trwale przechowywać pisma na swoim koncie.</p>
		</div>
		<? } ?>
		
        <div class="letters">
						
            <? if ($pagination['total']) { ?>

                <div class="row actionbar">
                    <div class="col-md-1 text-center">
                        <input type="checkbox" class="checkAll margin-top-10"/>
                    </div>
                    <div class="col-md-4 desc text-muted">
                        <div class="selectedCount margin-top-7"></div>
                        <div class="paginationList margin-top-7">
                            <? echo ($pagination['page'] * $pagination['perPage']) - ($pagination['perPage'] - 1) ?>
                            <span
                                class="small">-</span> <?= $pagination['total'] > ($pagination['page'] * $pagination['perPage']) ? ($pagination['page'] * $pagination['perPage']) : $pagination['total'] ?>
                            <span class="small">z</span> <?= $pagination['total'] ?>
                        </div>
                    </div>
                    <div class="col-md-7 text-right">
                        <div class="optionsChecked">
	                        <form action="/moje-pisma/moje/delete">
		                        <div class="inputs">
		                        </div>
	                            <button class="btn btn-default deleteButton" type="submit">
	                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
	                            </button>
	                        </form>
                        </div>
                        <div class="optionsUnChecked">
                            
                            <? if( isset($aggs['template']) ) echo  $this->element('MojePisma.aggs', array(
                            	'data' => $aggs['template'],
                            	'label' => 'Szablon',
                            	'allLabel' => 'Wszystkie szablony',
                            	'var' => 'template',
                            	'selected' => isset( $filters_selected['template'] ),
                            )); ?>
                            
                            <? if( isset($aggs['to']) ) echo $this->element('MojePisma.aggs', array(
                            	'data' => $aggs['to'],
                            	'label' => 'Adresat',
                            	'allLabel' => 'Wszyscy adresaci',
                            	'var' => 'to',
                            	'selected' => isset( $filters_selected['to'] ),
                            )); ?>
                            
                            <? if( isset($aggs['sent']) ) echo  $this->element('MojePisma.aggs', array(
                            	'data' => $aggs['sent'],
                            	'label' => 'Status',
                            	'allLabel' => 'Wszystkie statusy',
                            	'var' => 'sent',
                            	'selected' => isset( $filters_selected['sent'] ),
                            )); ?>
                            
                            <? if( isset($aggs['access']) ) echo  $this->element('MojePisma.aggs', array(
                            	'data' => $aggs['access'],
                            	'label' => 'Dostęp',
                            	'allLabel' => 'Wszystko',
                            	'var' => 'access',
                            	'selected' => isset( $filters_selected['access'] ),
                            )); ?>

                        </div>
                    </div>
                </div>
				
				<? if( !empty($filters_selected) ) { ?>
				<div class="row">
					<div class="col-md-12">
						<p class="remove-filters"><a href="/moje-pisma/moje"><span class="glyphicon glyphicon-remove"></span> Usuń wszystkie filtry</a></p>
					</div>
				</div>
				<? } ?>
				
				<div class="items">
                <? foreach ($items as $item) { ?>
                    <div class="row item-list" data-id="<?= $item['id']; ?>">
                        <div class="col-sm-1 text-center haveCheckbox">
                            <input type="checkbox" class="itemCheckbox"/>
                        </div>
                        <div class="col-sm-9">
                            <div class="thumb">
                                <a href="/moje-pisma/<?= $item['alphaid'] ?>,<?= $item['slug'] ?>">
                                    <img src="http://pisma.sds.tiktalik.com/thumbs/<?= $item['hash'] ?>.png"/>
                                </a>
                            </div>
                            <div class="cont">

                                <p class="title">
                                    <a href="/moje-pisma/<?= $item['alphaid'] ?>,<?= $item['slug'] ?>"><?= ( isset($item['name']) && $item['name'] ) ? $item['name'] : 'Pismo' ?></a>
                                </p>

                                <? if( isset($item['sent']) && $item['sent'] ) {?>
                                    <p class="meta">
                                        Wysłano: <?= date('Y-m-d H:i:s', strtotime($item['sent_at'])) ?>
                                    </p>
                                <? } ?>

                                <? if (isset($item['to_name'])) { ?>
                                    <p class="fields">
                                        <small>Do:</small> <span class="val"><?= $item['to_name'] ?></span>
                                    </p>
                                <? } ?>

                            </div>
                        </div>
                        <div class="col-sm-2 text-right">
                            <span class="date">
                                <?= dataSlownie($item['date']); ?>
                            </span>
                        </div>
                    </div>
                <? } ?>
				</div>
				
                <?php if (1 < $pagination['total'] / $pagination['perPage']) { ?>
                    <div class="paginationListNumber">
                        <div class="btn-group" role="group">
                            <?php for ($x = 0; $x < $pagination['total'] / $pagination['perPage']; $x++) { ?>
                                <a href="/moje-pisma/moje?page=<?php echo $x + 1; ?>" type="button"
                                   class="btn btn-default<?php if (($x + 1) == $pagination['page']) echo ' active' ?>"><?php echo $x + 1; ?></a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

            <? } else {
                if ($q) {
                    ?>
                    <p class="letters-msg">Brak pism</p>
                <?
                } else {
                    ?>
                    <p class="letters-msg">Nie stworzyłeś jeszcze żadnych pism</p>
                <?
                }
            } ?>

        </div>
    </div>
</div>