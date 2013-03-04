<table border="1">
	<tr>
	<th>function name</th>
	<?php
		foreach ($languages as $language){
			echo '<th>'.$language.'</th>';
		}
	?>
	</tr>
	<?php

			
			foreach ($variables as $variable){
				echo '<tr>';
				echo '<td>'.$variable.'</td>';
				foreach ($languages as $language){
					if (isset($translate[$language][$variable]))
						echo '<td><div id="'.$language.'-'.$variable.'" onclick="edit(\''.$language.'\',\''.$variable.'\')">'.@$translate[$language][$variable].'</div></td>';
					else 
						echo '<td><div id="'.$language.'-'.$variable.'" onclick="edit(\''.$language.'\',\''.$variable.'\')">&nbsp; </div></td>';
				}
				echo '</tr>';
			}
			
		
	?>
	
</table> 

