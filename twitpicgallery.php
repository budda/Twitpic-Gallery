<?php
/*
 * Twitpic Gallery
 * Produces a list of Twitpic.com images for a given screenname
 *
 * @author Mike Carter <http://twitter.com/buddaboy>
 */
 
// Change this to grab a Twitter users photos
$screenname = 'buddaboy';


// Initialise Variables
$photo_ids = array();
$matches = array();

// Confirm there is a Twitpic account for the screenname
if ($page = @file_get_contents ('http://twitpic.com/photos/' . $screenname)) {

  // How many photos?  
  preg_match('/>Photos<\/a><\/div>\s*.*?>(\d*?)<\/div>/', $page, $matches);
  $photo_count = $matches[1];
  
  // Work out how many pages we ned to gather images from
  $total_pages = ceil($photo_count / 20);
  
  // Collect all the image ids into an array
  for($i = 1; $i <= $total_pages; $i++) {
    $photo_ids = array_merge($photo_ids, get_twitpic_photos($screenname, $i));
  }
}


// Render thumbnails of all images for the specified screenname
foreach($photo_ids as $id) {
  echo '<img src="http://twitpic.com/show/thumb/' . $id . '" />';
}


/*
 * Grab all Twitpic image Ids for a given screenname and page
 */
function get_twitpic_photos($screenname, $page = 1) {
  $page = file_get_contents ('http://twitpic.com/photos/' . $screenname . '?page=' . $page);
  
  $matches = array();
  preg_match_all('/profile\-photo\-img">\s*<a href\="\/(.*?)"><img/', $page, $matches);
  $photo_ids = $matches[1];
  
  return $photo_ids;
}
?>