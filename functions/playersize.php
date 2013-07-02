<?php

//heights and widths

	if ($format=='wide'){
		if($size == 'lg'){$width='960';$height='540';}
		if($size == 'med'){$width='640';$height='360';}
		if($size == 'sm'){$width='480';$height='270';}
	}
	
	if ($format=='standard'){
		if($size == 'lg'){$width='720';$height='540';}
		if($size == 'med'){$width='640';$height='480';}
		if($size == 'sm'){$width='480';$height='360';}
	}
	
	if ($type =='audio'){
		$width='300';
		$height='80';
	}
	


