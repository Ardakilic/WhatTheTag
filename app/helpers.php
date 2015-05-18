<?php

//gets file name (without extension) from a full path
function getFileName($fullName) {
	$pathinfo = pathinfo($fullName);
	return $pathinfo['filename'];
}