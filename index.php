<?
	// http://www.reddit.com/dev/api#GET_top
	$limit = $_GET["limit"];
	$t = "week"; // [hour, day, week, month, year, all]
	if($top = simplexml_load_file("http://www.reddit.com/r/AlbumArtPorn/top.xml?t=$t&limit=$limit")){
		foreach($top->channel->item as $item){
			/* Regex
				Get a: 		http://regexr.com?33ogg
				Get link: 	http://regexr.com?33ogp
			*/
			preg_match('/(<a href="([^"]+)\.(jpe?g|png|gif)")[^>]*>/', $item->description, $match);
			preg_match('/(([^"]+)\.(jpe?g|png|gif))/', $match[1], $match);
			$urls[] = $match[1];
		}
		$urls = array_filter($urls);

		$coverArts = imageCreateTrueColor(count($urls)*200, 200);

		foreach($urls as $url){
			$cover = imageCreateFromJpeg($url);
			list($w, $h) = getImageSize($url);
			imageCopyResized($coverArts, $cover, 200*$i++, 0, 0, 0, 200, 200, $w, $h);
			imagedestroy($cover);
		}

		header ("Content-type: image/jpeg");
		imageJpeg($coverArts);
		imageDestroy($coverArts);
	}
?>