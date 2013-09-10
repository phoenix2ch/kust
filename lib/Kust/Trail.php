<?php
/**
 * System class - Trail
 *
 * Author:	Quentin Zervaas
 * Date:	  16 March 2005
 *
 * Modification:
 *	+ Author:	Benoît Zuckschwerdt
 *	+ Date:		27 November 2012
 *
 * See -> http://www.phpriot.com/articles/breadcrumbs
 *
 */

class Kust_Trail {
    var $path = array();

    public function __construct($includeHome = true, $homeLabel = 'Home', $homeLink = '/home') {
        if ($includeHome)
            $this->addStep($homeLabel, $homeLink);
    }

    public function addStep($title, $link = '') {
        $item = array('title' => $title);
        if (strlen($link) > 0)
            $item['link'] = $link;
        $this->path[] = $item;
    }
}
?>
