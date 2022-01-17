# Buto-Plugin-WfPager
Pagination.

## Usage
Init.
```
$pager = new PluginWfPager();
$pager->setRecord($rs);
$pager->setRowsEachPage(20);
$pager->setPage(wfRequest::get('page'));
$pager->init();
```
Render elements.
```
foreach ($pager->settings['record'] as $key => $value) {
  // set data...
}
```
Page info.
```
$pager->getPageInfo()
```
Links.
```
$pager->getLinks($url);
```
