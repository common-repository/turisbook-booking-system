<div class="bootstrap-iso">
	<div class="fluid-container">
		<div class="row">
			<div class="col-12">
				<?php

				$sep = '';
				foreach($amenities as $amenity){
					$alink = get_term_link($amenity->term_id);
					?><?php echo $sep;?><a href="<?php echo $alink;?>"><?php echo $amenity->name; ?></a><?php
					$sep = ', ';
				}
				?>	
			</div>
		</div>
	</div>
</div>