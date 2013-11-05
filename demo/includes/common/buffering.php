<?php
	//==================================================================
	// php script compressor
	//==================================================================
	ob_start("ob_gzhandler"); // Warning seems to involved php object serialization
	//ini_set("zlib.output_compression", "On"); // Best compressor method but behaviour change a little bit
	//==================================================================