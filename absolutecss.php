<?php

// base variables
$grids = [1,2,3,4,5,6,7,8,9,12,24];
$sizes = [];

// prefix variables
$vertical = ["t" => "top", "b" => "bottom"];
$horizontal = ["r" => "right", "l" => "left"];
$measures = ["w" => "width", "h" => "height"];

$properties = ["p" => "padding", "m" => "margin", "f" => "font-size", "n" => "border-radius" ];
$psizes = [0.5,0.8,1.3,1.9,3,5,7,10,12,15];
$pstep = 0.3;

$talign = ["l" => "left", "c" => "center", "r" => "right", "j" => "justified" ];
$halign = ["t" => "top", "m" => "middle", "b" => "bottom" ];

$switches = [
	"grids",
	"corners",
	"sides",
	"alignment",
	"font-size",
	"padding",
	"margin",
	"border-radius",
];

$display = [
	"" => "",
	"sm-" => "screen and(max-width:35em)",
	"md-" => "screen and(min-width:35em;max-width:56em)",
	"xl-" => "screen and(min-width:80em)",
];

$directions = $vertical + $horizontal;
$alignments = $halign + $talign;
$all = $directions + $measures;

if (in_array("typography", $switches)) {
	echo "p{font-size:1.0em;}";
	echo "h1{font-size:1.8em;}";
	echo "h2{font-size:1.6em;}";
	echo "h3{font-size:1.4em;}";
	echo "h4{font-size:1.2em;}";
	echo "h5{font-size:0.9em;}";
	echo "h6{font-size:0.7em;}";
}

foreach ($display as $tick => $media) {
	if ($media) {
		echo "@media {$media}{";
	}

	echo ".{$tick}hide{display:none}";
	echo ".{$tick}show{display:none}";

	if (in_array("grids", $switches)) {
		$sizes = [];
		// setup sizes for each grid
		foreach($grids as $grid) {
			// percentage per unit
			$unit = 100 / $grid;
	
			// loop through x of y to get all sizes
			for ($i = 1; $i < $grid; ++$i) {
				// get calculated percentage based on unit
				$unitkey = (string) round($unit * $i, 3);
	
				// set unit key if it doesn't exist yet
				if (!array_key_exists($unitkey, $sizes)) {
					$sizes[$unitkey] = [];
				}
	
				// add key to list
				$sizes[$unitkey][] = "$i-$grid";
			}
		}
	
		// sort sizes
		ksort($sizes, SORT_NUMERIC);
	
		// add basic flex grid sizes
		foreach ($all as $prefix => $property) {
			// add nothing size
			echo ".{$tick}{$prefix}-0{{$property}:0}";
	
			// loop through sizes to generate class list
			foreach ($sizes as $percent => $marks) {
				$classes = [];
	
				// genrate class list
				foreach ($marks as $mark) {
					$classes[] = ".{$tick}{$prefix}-{$mark}";
				}
	
				// echo generated classes
				echo implode(",", $classes), "{{$property}:{$percent}%}";
			}
	
			// add everything size
			echo ".{$tick}{$prefix}-1{{$property}:100%}";
		}
	}
	
	if (in_array("corners", $switches)) {
		// loop through to create corner alignments
		foreach ($vertical as $vert_prefix => $vert_property) {
			foreach ($horizontal as $horiz_prefix => $horiz_property) {
				echo ".{$tick}{$vert_prefix}-{$horiz_prefix},.{$horiz_prefix}-{$vert_prefix}{{$vert_property}:0;{$horiz_property}:0}";
			}
		}
	}
	
	if (in_array("sides", $switches)) {
		// loop through directions to create full side alignments
		foreach ($horizontal as $prefix => $property) {
			echo ".{$tick}{$prefix}-x{{$property}:0;top:0;bottom:0}";
		}
		foreach ($vertical as $prefix => $property) {
			echo ".{$tick}{$prefix}-x{{$property}:0;left:0;right:0}";
		}
	}
	
	if (in_array("alignment", $switches)) {
		// loop through horizontal alignments to create internal alignments
		foreach ($halign as $prefix => $value) {
			echo ".{$tick}a-{$prefix}{vertical-align:{$value}}";
		}
	
		// loop through vertical alignments to create internal alignments
		foreach ($talign as $prefix => $value) {
			echo ".{$tick}a-{$prefix}{text-align:{$value}}";
		}
	}
	
	// loop through miscelaneous properties
	foreach ($properties as $prefix => $property) {
		if (in_array($property, $switches)) {
			foreach ($psizes as $count => $size) {
				echo ".{$tick}{$prefix}-{$count}{{$property}:" . ($size * $pstep) . "em}";
			}
		}
	}

	if ($media) {
		echo "}";
	}
}
