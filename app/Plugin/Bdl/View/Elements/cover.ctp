<?
	$this->Combinator->add_libs('css', $this->Less->css('bdl', array('plugin' => 'Bdl')));
	$this->Combinator->add_libs('js', 'Bdl.bdl.js');
	
	$colors = array(
		'begin' => array(42, 112, 157),
		'end' => array(65, 151, 78),
	);
	
	$colors['diff'] = array(
		$colors['end'][0] - $colors['begin'][0],
		$colors['end'][1] - $colors['begin'][1],
		$colors['end'][2] - $colors['begin'][2],
	);
		
?>

<div class="col-xs-12">
	<div class="appBanner">
	    <h1 class="appTitle">Bank danych lokalnych</h1>
	
	    <p class="appSubtitle">Przeglądaj wskaźniki dotyczące sytuacji społecznej i ekonomicznej Polski.</p>
	</div>
</div>

<div class="col-xs-12">
	<div id="bdl">
	    <div class="container">
	        <div class="content col-xs-12 row">
	            <?
		            if( $count = count($tree) ) {
			            
			            $count--;
			            $i = 0;
			            
	            ?>
		            <div class="row items">
		                <? foreach ($tree as $kategoria) {
			                
			                if( $count ) {                
				                $color = array(
					                (int) ( $colors['begin'][0] + ( $colors['diff'][0] * $i / $count ) ),
					                (int) ( $colors['begin'][1] + ( $colors['diff'][1] * $i / $count ) ),
					                (int) ( $colors['begin'][2] + ( $colors['diff'][2] * $i / $count ) ),
				                );
			                } else {
				                $color = $colors['begin'];
			                }
			                			                
			                $id = $kategoria['kategoria']['id'];
			                $label = $kategoria['kategoria']['nazwa'];
			                $slug = $kategoria['kategoria']['slug'];
		                ?>
		                    <div class="block col-md-3 ?>" data-color="<? echo $color[0] . ', ' .$color[1] . ', ' . $color[2] ?>">
		                        <div class="item" name="<?= $slug ?>" data-id="<?= $id ?>">
		
		                            <a href="/bdl#<?= $slug ?>" class="inner"
		                               data-title="<?= $label ?>"
		                               data-info="">
		
		                                <div class="logo">
		                                    <span class="icon-<?= $id ?>"></i>
		                                </div>
		
		                                <div class="title" style="background-color: rgb(<?= $color[0] ?>, <?= $color[1] ?>, <?= $color[2] ?>)">
		                                    <div class="nazwa"><?= $label ?></div>
		                                </div>
		
		                                <div class="text">
			                                <?
				                                foreach( $kategoria['grupy'] as $id => $grupa ) {
					                                
					                                $id = $grupa['grupa']['id'];
					                                $label = $grupa['grupa']['nazwa'];
					                                
					                        ?>
					                        	<h3 name="<?=$slug?>,<?= $grupa['grupa']['slug'] ?>"><?= $label ?></h3>
					                        	<ul class="wskazniki">
						                        <?
							                        foreach( $grupa['wskazniki'] as $id => $wskaznik ) {
								                ?>
								                	<li>
								                		<div class="row">
									                		<div class="col-xs-9">
										                		<span class="href" href="/dane/bdl_wskazniki/<?= $wskaznik['bdl_wskazniki.id'] ?>"><?= $wskaznik['bdl_wskazniki.tytul'] ?></span>
									                		</div><div class="col-xs-1">
										                		<?= @$wskaznik['bdl_wskazniki.liczba_ostatni_rok'] ?>
									                		</div><div class="col-xs-2">
										                		<?= @$wskaznik['bdl_wskazniki.poziom_str'] ?>
									                		</div>
								                		</div>
								                	</li>
								                <?
									                }
									            ?>
					                        	</ul>
					                        <?
				                                }
				                            ?>
		                                </div>
		
		                            </a>
		
		                        </div>
		                    </div>
		                <? 
			                $i++;
		                }
			            ?>
		            </div>
	            <? } ?>
	        </div>
	    </div>
	</div>
</div>