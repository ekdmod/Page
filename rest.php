<?php 
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
$HTTP_LINK = $_SERVER['HTTP_LINK'];
if (!isset($language)) 
{
    $language = $GLOBALS['language_content']->language;
}


function menu_page_execute_active_handler($path = NULL, $language) {
$path = drupal_get_normal_path($path);

$parts = parse_url('http://example.com/' . $path);
    // Strip the leading slash that was just added.
    $path_2['path'] = substr($parts['path'], 1);
    if (isset($parts['query'])) {
      parse_str($parts['query'], $path_2['query']);
    }
    if (isset($parts['fragment'])) {
    $path_2['fragment'] = $parts['fragment'];
    }

strchr($path_2['query']['q'],'?') == true ? $path = substr($path_2['query']['q'],3,strpos($path_2['query']['q'], '?') - 3) : $path = substr($path_2['query']['q'],3);
$_GET['it'] = $path_2['query']['it'] == true ? $path_2['query']['it'] : 0;
$_GET['id'] = strchr($path_2['query']['q'], 'id=') ?  substr($path_2['query']['q'] , strpos($path_2['query']['q'] , 'id=') + 3 ) : 0;
$_GET['q'] = $path;
// Check if site is offline.
  $page_callback_result = _menu_site_is_offline() ? MENU_SITE_OFFLINE : MENU_SITE_ONLINE;

  // Allow other modules to change the site status but not the path because that
  // would not change the global variable. hook_url_inbound_alter() can be used
  // to change the path. Code later will not use the $read_only_path variable.
  drupal_alter('menu_site_status', $page_callback_result, $path);

  // Only continue if the site status is not set.
  if ($page_callback_result == MENU_SITE_ONLINE) {
    if ($router_item = menu_get_item($path)) {
    
      if ($router_item['access']) {
        if ($router_item['include_file']) {
          require_once DRUPAL_ROOT . '/' . $router_item['include_file'];
        }
        $page_callback_result = call_user_func_array($router_item['page_callback'], $router_item['page_arguments']);
        is_array($page_callback_result) ? $page_callback_result = drupal_render($page_callback_result) : $page_callback_result;
$active_trail = &drupal_static(__FUNCTION__);
  if (isset($new_trail)) {
    $active_trail = $path;
  }
  elseif (!isset($active_trail)) {
    $active_trail = array();
    $active_trail[] = array(
      'title' => t('Home'), 
      'href' => '<front>', 
      'link_path' => '', 
      'localized_options' => array(), 
      'type' => 0,
    );

    // Try to retrieve a menu link corresponding to the current path. If more
    // than one exists, the link from the most preferred menu is returned.
  
  $selected_menu = NULL;
  if (empty($selected_menu)) {
    // Use an illegal menu name as the key for the preferred menu link.
    $selected_menu = MENU_PREFERRED_LINK;
  }
  static $preferred_links;
  if (!isset($preferred_links[$path])) {
    // Look for the correct menu link by building a list of candidate paths,
    // which are ordered by priority (translated hrefs are preferred over
    // untranslated paths). Afterwards, the most relevant path is picked from
    // the menus, ordered by menu preference.
//  $item = menu_get_item($path);
    $path_candidates = array();
    // 1. The current item href.
    $path_candidates[$router_item['href']] = $router_item['href'];
    // 2. The tab root href of the current item (if any).
    if ($router_item['tab_parent'] && ($tab_root = menu_get_item($router_item['tab_root_href']))) {
      $path_candidates[$tab_root['href']] = $tab_root['href'];
    }
    // 3. The current item path (with wildcards).
    $path_candidates[$router_item['path']] = $router_item['path'];
    // 4. The tab root path of the current item (if any).
    if (!empty($tab_root)) {
      $path_candidates[$tab_root['path']] = $tab_root['path'];
    }

    // Retrieve a list of menu names, ordered by preference.
    $menu_names = menu_get_active_menu_names();
    // Put the selected menu at the front of the list.
    array_unshift($menu_names, $selected_menu);

    $query = db_select('menu_links', 'ml', array('fetch' => PDO::FETCH_ASSOC));
    $query->leftJoin('menu_router', 'm', 'm.path = ml.router_path');
    $query->fields('ml');
    // Weight must be taken from {menu_links}, not {menu_router}.
    $query->addField('ml', 'weight', 'link_weight');
    $query->fields('m');
    $query->condition('ml.link_path', $path_candidates, 'IN');

    // Sort candidates by link path and menu name.
    $candidates = array();
    foreach ($query->execute() as $candidate) {
      $candidate['weight'] = $candidate['link_weight'];
      $candidates[$candidate['link_path']][$candidate['menu_name']] = $candidate;
      // Add any menus not already in the menu name search list.
      if (!in_array($candidate['menu_name'], $menu_names)) {
        $menu_names[] = $candidate['menu_name'];
      }
    }

    // Store the most specific link for each menu. Also save the most specific
    // link of the most preferred menu in $preferred_link.
    foreach ($path_candidates as $link_path) {
      if (isset($candidates[$link_path])) {
        foreach ($menu_names as $menu_name) {
          if (empty($preferred_links[$path][$menu_name]) && isset($candidates[$link_path][$menu_name])) {
            $candidate_item = $candidates[$link_path][$menu_name];
            $map = explode('/', $path);
            _menu_translate($candidate_item, $map);
            if ($candidate_item['access']) {
              $preferred_links[$path][$menu_name] = $candidate_item;
              if (empty($preferred_links[$path][MENU_PREFERRED_LINK])) {
                // Store the most specific link.
                $preferred_links[$path][MENU_PREFERRED_LINK] = $candidate_item;
              }
            }
          }
        }
      }
    }
  isset($preferred_links[$path][$selected_menu]) ? $preferred_link = $preferred_links[$path][$selected_menu] : $preferred_link = FALSE;
  }
//$current_item = menu_get_item($path);
    if ($preferred_link) {
      // Pass TRUE for $only_active_trail to make menu_tree_page_data() build
      // a stripped down menu tree containing the active trail only, in case
      // the given menu has not been built in this request yet.
      $tree = menu_tree_page_data($preferred_link['menu_name'], NULL, TRUE);
      list($key, $curr) = each($tree);
    }
    // There is no link for the current path.
    else {
      $preferred_link = $router_item;
      $curr = FALSE;
    }
    
    while ($curr) {
      $link = $curr['link'];
      if ($link['in_active_trail']) {
        // Add the link to the trail, unless it links to its parent.
        if (!($link['type'] & MENU_LINKS_TO_PARENT)) {
          // The menu tree for the active trail may contain additional links
          // that have not been translated yet, since they contain dynamic
          // argument placeholders (%). Such links are not contained in regular
          // menu trees, and have only been loaded for the additional
          // translation that happens here, so as to be able to display them in
          // the breadcumb for the current page.
          // @see _menu_tree_check_access()
          // @see _menu_link_translate()
          if (strpos($link['href'], '%') !== FALSE) {
            _menu_link_translate($link, TRUE);
          }
          if ($link['access']) {
            $active_trail[] = $link;
          }
        }
        $tree = $curr['below'] ? $curr['below'] : array();
      }
      list($key, $curr) = each($tree);
    }
  // FRONT PAGE 
  
  static $drupal_static_fast;
  if (!isset($drupal_static_fast)) {
    $drupal_static_fast['is_front_page'] = &drupal_static(__FUNCTION__);
  }
  $front_page = &$drupal_static_fast['is_front_page'];

  if (!isset($front_page)) {
    // As drupal_path_initialize updates $_GET['q'] with the 'site_frontpage' path,
    // we can check it against the 'site_frontpage' variable.
    $front_page = ($path == variable_get('site_frontpage', 'node'));
  }
  
  // FRONT PAGE
    // Make sure the current page is in the trail to build the page title, by
    // appending either the preferred link or the menu router item for the
    // current page. Exclude it if we are on the front page.
    
    $last = end($active_trail);
    if ($preferred_link && $last['href'] != $preferred_link['href']  && $front_page) {
      $active_trail[] = $preferred_link;
    }
  }
        
    // Allow modules to alter the breadcrumb, if possible, as that is much
    // faster than rebuilding an entirely new active trail.
  drupal_alter('menu_breadcrumb', $active_trail, $router_item);

    // Remove the tab root (parent) if the current path links to its parent.
    // Normally, the tab root link is included in the breadcrumb, as soon as we
    // are on a local task or any other child link. However, if we are on a
    // default local task (e.g., node/%/view), then we do not want the tab root
    // link (e.g., node/%) to appear, as it would be identical to the current
    // page. Since this behavior also needs to work recursively (i.e., on
    // default local tasks of default local tasks), and since the last non-task
    // link in the trail is used as page title (see menu_get_active_title()),
    // this condition cannot be cleanly integrated into menu_get_active_trail().
    // menu_get_active_trail() already skips all links that link to their parent
    // (commonly MENU_DEFAULT_LOCAL_TASK). In order to also hide the parent link
    // itself, we always remove the last link in the trail, if the current
    // router item links to its parent.
    if (($router_item['type'] & MENU_LINKS_TO_PARENT) == MENU_LINKS_TO_PARENT) {
      array_pop($active_trail);
    }
    print '"A"' . $page_callback_result . '"BA""C"';
/*    $count = count($active_trail) - 1;*/
    for ($i = 0, $count = count($active_trail)-1; $i < $count; $i++) {
//    $breadcrumb[] = l($parent['title'], $parent['href'], $parent['localized_options']);
      print l($active_trail[$i]['title'], $active_trail[$i]['href'], $active_trail[$i]['localized_options']) . ' » ';
    }
    print l($active_trail[$i]['title'], $active_trail[$i]['href'], $active_trail[$i]['localized_options']);
    print '"DD"';
//        is_array($page_callback_result) ? $page_callback_result = drupal_render($page_callback_result) : $page_callback_result;   
      }
      else {
        $page_callback_result = MENU_ACCESS_DENIED;
      }
    }
    else {
      $page_callback_result = MENU_NOT_FOUND;
    }
  } 
//  print $page_callback_result;
  $menu_name = 'navigation';
  $link = '/EDrupal/?q=lt/Gallery';

}

//$HTTP_LINK != 'node/add' ? menu_page_execute_active_handler($HTTP_LINK) : ajax_node_add_page();
menu_page_execute_active_handler($HTTP_LINK,$language);

