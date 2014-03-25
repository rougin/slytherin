<?php

function _tag($number = NULL, $tag)
{
	$tag = $tag . "\n";
	for ($counter = 0; $counter < $number; $counter++)
	{
		$tag .= "\n";
	}
	return $tag;
}

function br($number = NULL)
{
	return $this->_tag($number, '<br/>');
}

function nbsp($number = NULL)
{
	return $this->_nbsp($number, '&nbsp');
}