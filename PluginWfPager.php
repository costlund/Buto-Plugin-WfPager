<?php
/**
<p>
Paginate an array
</p>
 */
class PluginWfPager{
  /*
   * Pagination of an array.
   */
  
  /*
   * Example of usage:
    $record = array();
    for($i=0; $i<110;$i++){
      $record[] = $i*1000;
    }
    $pager = new Pager();
    $pager->setRecord($record);
    $pager->setRowsEachPage(7);
    $pager->setPage(15);
    $pager->init();
    print_r($pager->getPager());
   */
  public $settings = array(
      'record' => array(), 
      'rows_each_page' => null, 
      'page' => null, 
      'pages' => null, 
      'rows' => null,
      'buttons' => array(
          'first' => array('disabled' => true, 'page' => null),
          'previous' => array('disabled' => true, 'page' => null),
          'next' => array('disabled' => true, 'page' => null),
          'last' => array('disabled' => true, 'page' => null)
          )
      );
  /**
   * Set the array record.
   * @param type $record
   */
  public function setRecord($record){
    $this->settings['record'] = $record;
  }
  /**
   * Set number of rows on each page.
   * @param type $rows_each_page
   */
  public function setRowsEachPage($rows_each_page){
    $this->settings['rows_each_page'] = $rows_each_page;
  }
  /**
   * Set current page by user interactive, if not set the page will be set as "1".
   * @param type $page
   */
  public function setPage($page){
    $this->settings['page'] = $page;
  }
  /**
   * Handling the settings array before call getPager();
   * 1. Handle if page is not set.
   * 2. Set rows.
   * 3. Set pages.
   * 4. 
   */
  public function init(){
    /**
     * 
     */
    if(!$this->settings['page']){$this->settings['page'] = 1;}
    $this->settings['rows'] = sizeof($this->settings['record']);
    $this->settings['pages'] = ceil(sizeof($this->settings['record'])/$this->settings['rows_each_page']);
    $start = ($this->settings['page']-1)*$this->settings['rows_each_page'];
    $end = $start + $this->settings['rows_each_page'];
    if(false){
      $temp = array();
      for($i=$start;$i<$end;$i++){
        if(array_key_exists($i, $this->settings['record'])){
          $temp[] = $this->settings['record'][$i];
        }
      }
    }else{
      $temp = array_slice($this->settings['record'], $start, $this->settings['rows_each_page']);
    }
    $this->settings['record'] = $temp;
    unset($temp);
    $page = $this->settings['page'];
    $pages = $this->settings['pages'];
    if($page>1){
      $this->settings['buttons']['first']['disabled'] = false;
      $this->settings['buttons']['first']['page'] = 1;
      $this->settings['buttons']['previous']['disabled'] = false;
      $this->settings['buttons']['previous']['page'] = $page-1;
    }
    if($page<$pages){
      $this->settings['buttons']['next']['disabled'] = false;
      $this->settings['buttons']['next']['page'] = $page+1;
      $this->settings['buttons']['last']['disabled'] = false;
      $this->settings['buttons']['last']['page'] = $pages;
    }
    if($this->settings['pages']==0){
      $this->settings['pages'] = 1;
    }
  }
  /**
   * Get the result after call init function.
   * @return type
   */
  public function getSettings(){
    return $this->settings;
  }
  public function getButtons($href){
    $temp = array();
    $operator = '?';
    if(strstr($href, '?')){$operator = '&';}
    $attribute = array('onclick' => "location.href='$href".$operator."page=".$this->settings['buttons']['first']['page']."';return false;");
    if($this->settings['buttons']['first']['disabled']){ $attribute['disabled'] = 'true'; }
    $temp[] = wfDocument::createHtmlElement('button', 'First', $attribute);
    $attribute = array('onclick' => "location.href='$href".$operator."page=".$this->settings['buttons']['previous']['page']."';return false;");
    if($this->settings['buttons']['previous']['disabled']){ $attribute['disabled'] = 'true'; }
    $temp[] = wfDocument::createHtmlElement('button', 'Previous', $attribute);
    $attribute = array('onclick' => "location.href='$href".$operator."page=".$this->settings['buttons']['next']['page']."';return false;");
    if($this->settings['buttons']['next']['disabled']){ $attribute['disabled'] = 'true'; }
    $temp[] = wfDocument::createHtmlElement('button', 'Next', $attribute);
    $attribute = array('onclick' => "location.href='$href".$operator."page=".$this->settings['buttons']['last']['page']."';return false;");
    if($this->settings['buttons']['last']['disabled']){ $attribute['disabled'] = 'true'; }
    $temp[] = wfDocument::createHtmlElement('button', 'Last', $attribute);
    return $temp;
  }
  public function getLinks($href){
    $temp = array();
    $operator = '?';
    if(strstr($href, '?')){$operator = '&';}
    if($this->settings['buttons']['first']['disabled']){
      $temp[] = wfDocument::createHtmlElement('span', 'First');
    }else{
      $attribute = array('href' => "$href".$operator."page=".$this->settings['buttons']['first']['page']."");
      $temp[] = wfDocument::createHtmlElement('a', 'First', $attribute);
    }
    if($this->settings['buttons']['previous']['disabled']){
      $temp[] = wfDocument::createHtmlElement('span', 'Previous');
    }else{
      $attribute = array('href' => "$href".$operator."page=".$this->settings['buttons']['previous']['page']."");
      $temp[] = wfDocument::createHtmlElement('a', 'Previous', $attribute);
    }
    if($this->settings['buttons']['next']['disabled']){
      $temp[] = wfDocument::createHtmlElement('span', 'Next');
    }else{
      $attribute = array('href' => "$href".$operator."page=".$this->settings['buttons']['next']['page']."");
      $temp[] = wfDocument::createHtmlElement('a', 'Next', $attribute);
    }
    if($this->settings['buttons']['last']['disabled']){
      $temp[] = wfDocument::createHtmlElement('span', 'Last');
    }else{
      $attribute = array('href' => "$href".$operator."page=".$this->settings['buttons']['last']['page']."");
      $temp[] = wfDocument::createHtmlElement('a', 'Last', $attribute);
    }
    return $temp;
  }
  public function getPageInfo(){
    if($this->settings['rows_each_page']==1){
      return wfDocument::createHtmlElement('div', 
              array(
                wfDocument::createHtmlElement('span', 'Page'),
                wfDocument::createHtmlElement('span', $this->settings['page']),
                wfDocument::createHtmlElement('span', 'of'),
                wfDocument::createHtmlElement('span', $this->settings['pages'])
                )
              );
    }else{
      return wfDocument::createHtmlElement('div', 
              array(
                wfDocument::createHtmlElement('span', 'Page'),
                wfDocument::createHtmlElement('span', $this->settings['page']),
                wfDocument::createHtmlElement('span', 'of'),
                wfDocument::createHtmlElement('span', $this->settings['pages']),
                wfDocument::createHtmlElement('span', 'contains'),
                wfDocument::createHtmlElement('span', $this->settings['rows']),
                wfDocument::createHtmlElement('span', 'records')
                )
              );
    }
  }
}
