<?php

/**
 * Generate list related info based on filter and pagination
 */

namespace KrisRo\PhpRepublic;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Arrays;

class Listing {

  /**
   * List filter params
   * @var array
   */
  protected $listFilters = [];

  /**
   * List params (paging)
   * @var array
   */
  protected $listParams = [];
  private $params = null;

  private $sqlComponents = [];
  private $table = null;

  /**
   * Base URL for paging. Use this when url doesn't match <code>Congig::current_page()</code>
   * @var string
   */
  public $pageUrl;

  public function __construct(?array $params = [], ?array $filters = []) {
    $this->listParams = $params + Config::get('app/pagination/default');
    $this->listFilters = $filters + $this->listFilters;

    $this->prepParams();
    $this->getParams();
  }

  /**
   * Preps params from URL and populates <code>$listFilters</code> and <code>$listParams</code>
   *
   * @param array|null $params
   * @param array|null $filters
   */
  public function prepParams(): void {
    $filterKeys = array_keys($this->listFilters);

    $paramKey = null;
    $filterKey = null;

    $args = Config::get('url/components');

    while (null !== ($arg = array_shift($args))) {
      /* collect the param */
      if (null !== ($paramKey = $this->inArray(strtolower($arg)))) {
        $this->listParams[$paramKey] = $this->getParamValue($paramKey, $arg);
        $paramKey = null;
        continue;
      }

      /* colect the filter key */
      if (in_array(str_replace('-', '_', strtolower($arg)), $filterKeys)) {
        $filterKey = str_replace('-', '_', strtolower($arg));
        $paramKey = null;
        continue;
      }

      /* collect filter value */
      if ($filterKey && ($arg || $arg == 0)) {
        $this->listFilters[$filterKey][1][] = (string) urldecode($arg);
      }
    }
  }

  /**
   * Get $listFilter
   * @return array
   */
  public function getFilters(): array {
    return $this->listFilters;
  }

  /**
   * Paging parameters
   *
   * @return array
   */
  public function getParams(): array {
    if (!($this->listParams['page'] ?? null)) {
      $this->listParams['page'] = 1;
    }

    $this->listParams['start'] = ($this->listParams['page'] - 1) * $this->listParams['size'];
    $this->listParams['stop'] = $this->listParams['start'] + $this->listParams['size'];

    return $this->listParams;
  }

  /**
   * Builds page buttons info for table pagination
   *
   * @param array $params
   * @return array
   */
  public function getPagination(array $params): array {
    $this->listParams = $params;

    if ($this->listParams['start'] < 0) {
      $this->listParams['start'] = 0;
    }

    if ($this->listParams['stop'] > $this->listParams['total']) {
      $this->listParams['stop'] = $this->listParams['total'];
    }

    if ($this->listParams['total'] < 1) {
      return $this->listParams;
    }

    $pages = [];
    if ($this->listParams['page_count'] <= 10) {
      for ($i = 0; $i < $this->listParams['page_count']; $i++) {
        $pages[] = [
          'page' => ($i + 1),
          'class' => ((($i + 1) == $this->listParams['page']) ? 'active' : ''),
          'href' => $this->printParams(['page' => ($i + 1)]),
        ];
      }
    } else {
      /* get first page block */
      for ($i = 0; $i < min(3, $this->listParams['page'] - 2); $i++) {
        $pages[] = [
          'page' => ($i + 1),
          'class' => ((($i + 1) == $this->listParams['page']) ? 'active' : ''),
          'href' => $this->printParams(['page' => ($i + 1)]),
        ];
      }

      if (!empty($pages) && $this->listParams['page'] > 5) {
        $pages[]['page'] = '&hellip;';
      }

      /* get middle page block */
      for ($i = max(0, $this->listParams['page'] - 2); $i < min(max(3, $this->listParams['page'] + 1), $this->listParams['page_count'] - 3); $i++) {
        $pages[] = [
          'page' => ($i + 1),
          'class' => ((($i + 1) == $this->listParams['page']) ? 'active' : ''),
          'href' => $this->printParams(['page' => ($i + 1)]),
        ];
      }

      if ($this->listParams['page'] < $this->listParams['page_count'] - 4) {
        $pages[]['page'] = '&hellip;';
      }

      /* get last page block */
      for ($i = $this->listParams['page_count'] - 3; $i < $this->listParams['page_count']; $i++) {
        $pages[] = [
          'page' => ($i + 1),
          'class' => ((($i + 1) == $this->listParams['page']) ? 'active' : ''),
          'href' => $this->printParams(['page' => ($i + 1)]),
        ];
      }
    }

    $this->listParams['pages'] = $pages;
    $this->listParams['previous']['href'] = null;

    if ($this->listParams['page'] > 1) {
      $this->listParams['previous']['href'] = $this->printParams(['page' => $this->listParams['page'] - 1]);
    }

    $this->listParams['next']['href'] = null;

    if ($this->listParams['page'] < $this->listParams['page_count']) {
      $this->listParams['next']['href'] = $this->printParams(['page' => $this->listParams['page'] + 1]);
    }

    return $this->listParams;
  }

  /**
   * Build URL for pagination buttons
   *
   * @param array|null $paramsData
   * @return string
   */
  public function printParams(?array $paramsData = []): string {
    $groupedParams = [];

    foreach ($this->listFilters as $key => $params) {
      if (!empty($params[1])) {
        $groupedParams[] = str_replace('_', '-', $key) . '/' . implode('/', array_unique($params[1]));
      }
    }

    if (!empty($paramsData['order-by'])) {
      $groupedParams[] = 'order-by-' . $paramsData['order-by'];
      $groupedParams[] = 'sort-' . ($this->listParams['sort'] != 'asc' ? 'asc' : 'desc');

    } elseif (!empty($this->listParams['order-by'])) {
      $groupedParams[] = 'order-by-' . $this->listParams['order-by'];
      $groupedParams[] = 'sort-' . ($this->listParams['sort'] == 'asc' ? 'asc' : 'desc');
    }

    if (!empty($this->listParams['size']) && $this->listParams['size'] != config::get('items-on-page-list')) {
      $groupedParams[] = 'size-' . $this->listParams['size'];
    }

    if (!empty($paramsData['page'])) {
      $groupedParams[] = 'page-' . $paramsData['page'];
    } elseif (!empty($this->listParams['page'])) {
      $groupedParams[] = 'page-' . $this->listParams['page'];
    }

    $getParams = [];
    if (Request::get('format')) {
      $getParams[] = '&format=' . Request::get('format'); // e.g. format=print (no side-bar)
    }

    if (Request::get('slim_table') !== null) {
      $getParams[] = 'slim_table=1';
      $getParams[] = 'target=' . Request::get('target');
    }

    return implode('/', $groupedParams) . ($getParams ? ('?' . implode('&', $getParams)) : '');
  }

  /**
   * Converts filters to string for URL
   *
   * @return string
   */
  public function printFilters(): string {
    $groupedFilters = [];

    foreach ($this->listFilters as $key => $filters) {
      if (!empty($filters[1])) {
        $groupedFilters[] = $key . '/' . implode('/', $filters[1]);
      }
    }

    return implode('/', $groupedFilters);
  }

  /**
   * Get sorting direction
   *
   * @param string|null $field
   * @return string
   */
  public function getSort(?string $field = ''): string {
    if ($field != $this->listParams['order-by']) {
      return '';
    }

    return $this->listParams['sort'];
  }

  /**
   * Add the sorting to the URL
   *
   * @param string $pageUrl
   * @return array
   */
  public function buildSortUrls(string $pageUrl): array {
    $baseUrl = $pageUrl . $this->printFilters();

    $urls = [];
    foreach (array_keys($this->listFilters) as $field) {
      $urls[$field] = trim($baseUrl, '/')
        . '/order-by-' . $field
        . '/sort-' . ($this->listParams['order-by'] == $field && $this->listParams['sort'] == 'desc' ? 'asc' : 'desc')
        . '/size-' . $this->listParams['size'];

      if (Request::get('slim_table') !== null) {
        $urls[$field] .= '?slim_table=1&target=' . Request::get('target');
      }
    }

    return $urls;
  }


  public function getSortingClasses(string $prefix): array {
    $classes = [];

    foreach (array_keys($this->listFilters) as $field) {
      if ($this->listParams['order-by'] == $field) {
        $classes[$field] = $prefix . $this->listParams['sort'];
      } else {
        $classes[$field] = '';
      }
    }

    return $classes;
  }

  /**
   * Builds the SELECT part of the query
   *
   * @param array $fields
   * @return self
   */
  public function select(array $fields): self {
    $sqlFields = [];
    foreach ($fields as $field) {
      list($selectTable, $selectField) = array_pad(explode('.', $field), 2, null);

      $selectTable = '`' . trim($selectTable, '`') . '`';
      
      if (!$selectField) {
        $sqlFields[] = $selectTable; // actually a field
        continue;
      }

      if ($selectField == '*') {
        $sqlFields[] = "{$selectTable}.*";
        continue;
      }

      $selectField = '`' . trim($selectField, '`') . '`';

      $sqlFields[] = "{$selectTable}.{$selectField}";
    }

    $this->sqlComponents['select'] = ' SELECT DISTINCT ' . implode(', ', $sqlFields) . ' ';

    return $this;
  }

  /**
   * Builds the FROM part of the query
   *
   * @param string $table
   * @return self
   */
  public function from(string $table): self {
    $this->table = $table;

    $this->sqlComponents['from'] = " FROM `{$table}` ";

    return $this;
  }

  /**
   * Builds the JOIN part of the query
   *
   * @param string $joinedTable
   * @param string $toTable
   * @param array $condition
   * 
   * @return self
   */
  public function join(string $joinedTable, string $toTable, ?array $condition = null): self {
    $join = ' JOIN ' .
                                     '`' . substr(trim($joinedTable, '`'), 0, strpos(trim($joinedTable, '`'), '.')) . '`' .
                                     ' ON ' .
                                     ((strpos($joinedTable, '`') ? $joinedTable : '`' . str_replace('.', '`.`', $joinedTable) . '`')) .
                                     ' = ' .
                                     (strpos($toTable, '`') ? $toTable : '`' . str_replace('.', '`.`', $toTable) . '`');

    if ($condition) {
      $field = str_replace('`', '', $condition[0]);
      $criteria = \KrisRo\PhpDatabaseModel\Model::simulateSqlIn($field, $condition[1], $condition[2] ?? '=', $condition[3] ?? null);

      $join .= ' AND ' . $criteria['condition'];

      if ($this->sqlComponents['params'] ?? null) {
        $this->sqlComponents['params'] += $criteria['params'];
      } else {
        $this->sqlComponents['params'] = $criteria['params'];
      }
    }

    $this->sqlComponents['join'][] = $join;

    return $this;
  }

  /**
   * Builds the LEFT JOIN part of the query
   *
   * @param string $joinedTable
   * @param string $toTable
   * @param array $condition
   * 
   * @return self
   */
  public function left(string $joinedTable, string $toTable, ?array $condition = null): self {
    $join = ' LEFT JOIN ' .
                '`' . substr(trim($joinedTable, '`'), 0, strpos(trim($joinedTable, '`'), '.')) . '`' .
            ' ON ' .
                ((strpos($joinedTable, '`') ? $joinedTable : '`' . str_replace('.', '`.`', $joinedTable) . '`')) .
                   ' = ' .
                (strpos($toTable, '`') ? $toTable : '`' . str_replace('.', '`.`', $toTable) . '`');

    if ($condition) {
      $field = str_replace('`', '', $condition[0]);
      $criteria = \KrisRo\PhpDatabaseModel\Model::simulateSqlIn($field, $condition[1], $condition[2] ?? '=', $condition[3] ?? null);

      $join .= ' AND ' . $criteria['condition'];

      if ($this->sqlComponents['params'] ?? null) {
        $this->sqlComponents['params'] += $criteria['params'];
      } else {
        $this->sqlComponents['params'] = $criteria['params'];
      }
    }

    $this->sqlComponents['join'][] = $join;

    return $this;
  }

  /**
   * Builds the RIGHT JOIN part of the query
   *
   * @param string $joinedTable
   * @param string $toTable
   * @param array $condition
   * 
   * @return self
   */
  public function right(string $joinedTable, string $toTable, ?array $condition = null): self {
    $join = ' RIGHT JOIN ' .
                '`' . substr(trim($joinedTable, '`'), 0, strpos(trim($joinedTable, '`'), '.')) . '`' .
            ' ON ' .
                ((strpos($joinedTable, '`') ? $joinedTable : '`' . str_replace('.', '`.`', $joinedTable) . '`')) .
                   ' = ' .
                (strpos($toTable, '`') ? $toTable : '`' . str_replace('.', '`.`', $toTable) . '`');

    if ($condition) {
      $field = str_replace('`', '', $condition[0]);
      $criteria = \KrisRo\PhpDatabaseModel\Model::simulateSqlIn($field, $condition[1], $condition[2] ?? '=', $condition[3] ?? null);

      $join .= ' AND ' . $criteria['condition'];

      if ($this->sqlComponents['params'] ?? null) {
        $this->sqlComponents['params'] += $criteria['params'];
      } else {
        $this->sqlComponents['params'] = $criteria['params'];
      }
    }

    $this->sqlComponents['join'][] = $join;

    return $this;
  }

  /**
   * Builds the WHERE part of the query
   *
   * @param string $field
   * @param string $filter
   * @param string|null $operator
   * @param string|null $type
   * @return self
   */
  public function filter(string $field, string $filter, ?string $operator = '=', ?string $type = null): self {
    $table = $this->table;

    if (strpos($field, '.') !== false) {
      list($table, $field) = explode('.', $field);
    }

    switch ($type) {
      case 'date':
        $this->filterDate($table, $field, $filter, $operator);
        break;

      default:
        $this->filterDefault($table, $field, $filter, $operator);
    }


    return $this;
  }

  /**
   * Builds the GROUP BY part of the query
   *
   * @param array $fields
   * @return self
   */
  public function group(array $fields): self {
    foreach ($fields as $field) {
      list($groupTable, $groupField) = array_pad(explode('.', $field), 2, null);

      $groupTable = '`' . trim($groupTable, '`') . '`';
      
      if (!$groupField) {
        $this->sqlComponents['group'][] = $groupTable; // actually a field
        continue;
      }

      $groupField = '`' . trim($groupField, '`') . '`';

      $this->sqlComponents['group'][] = "{$groupTable}.{$groupField}";
    }

    return $this;
  }

  /**
   * Build individual condition for the SQL query
   *
   * @param string $table
   * @param string $field
   * @param string $filter
   * @param string $operator
   * @return type
   */
  private function filterDefault(string $table, string $field, string $filter, string $operator) {
    $criteria = \KrisRo\PhpDatabaseModel\Model::simulateSqlIn($field, $this->listFilters[$filter][1], strtolower($operator), $table);
    if (!($criteria['condition'] ?? null)) {
      return;
    }

    $this->sqlComponents['where'][] = ' ' . $criteria['condition'] . ' ';
    if ($this->sqlComponents['params'] ?? null) {
      $this->sqlComponents['params'] += $criteria['params'];
    } else {
      $this->sqlComponents['params'] = $criteria['params'];
    }
  }

  private function filterDate(string $table, string $field, string $filter, string $operator) {
    if (!($date = current($this->listFilters[$filter][1] ?? []))) {
      return;
    }

    if (!Config::validator()->isValidDate($date)) {
      return;
    }

    $date = new \DateTimeImmutable("{$date} midnight");

    $start = $date->format('Y-m-d H:i:s');
    $stop = $date->modify('+1 day')->format('Y-m-d H:i:s');

    $field = '`' . str_replace('.', '`.`', str_replace('`', '', "{$table}.{$field}") . '`');

    $this->sqlComponents['where'][] = ' ' . "({$field} between :{$filter}_from and :{$filter}_until)" . ' ';

    $params = [
      ":{$filter}_from" => $start,
      ":{$filter}_until" => $stop,
    ];

    if ($this->sqlComponents['params'] ?? null) {
      $this->sqlComponents['params'] += $params;
    } else {
      $this->sqlComponents['params'] = $params;
    }
  }

  private function orderBy() {
    if (!($this->listParams['order-by'] ?? null) || !($this->listFilters[$this->listParams['order-by']][0] ?? null)) {
      return '';
    }

    return ' ORDER BY '
           . '`' . str_replace('.', '`.`', str_replace('`', '', $this->listFilters[$this->listParams['order-by']][0]) . '`')
           . ($this->listParams['sort'] == 'desc' ? ' DESC ' : ' ASC ');
  }

  /**
   * Executes the query
   *
   * @return array
   */
  public function getData(): array {
    $sql = $this->sqlComponents['from'] . PHP_EOL;
    $sql .= ($this->sqlComponents['join'] ?? null) ? implode(PHP_EOL, $this->sqlComponents['join']) . PHP_EOL : '';
    $sql .= ($this->sqlComponents['where'] ?? null) ? ' WHERE ' . implode(' AND ', $this->sqlComponents['where']) . ' ' : '';
    $sql .= ($this->sqlComponents['group'] ?? null) ? ' GROUP BY ' . implode(', ', $this->sqlComponents['group']) . ' ' : '';

    $countSql = "SELECT COUNT(*) AS `total` " . $sql;
    $dataSql = (($this->sqlComponents['select'] ?? null) ?: ' SELECT * ') . PHP_EOL . $sql;

    $dataSql .= $this->orderBy();

    if (!isset($this->listParams['start'])) {
      $this->listParams['start'] = 0;
    }

    if (!isset($this->listParams['size'])) {
      $this->listParams['size'] = intval(Config::get('pagination/default/size'));
    }

    $dataSql .= ' LIMIT ' . intval($this->listParams['start']) . ',' . intval($this->listParams['size']);

    $list = [
      'data' => [],
      'total' => 0,
    ];
//    echo '<pre>';
//    var_dump($dataSql);
//    var_dump($countSql);
//    var_dump($this->sqlComponents['params']);
//    die(__LINE__ . ' :: ' . __FILE__);
    $list['data'] = Config::dbModel()->query($dataSql)->execute($this->sqlComponents['params'] ?? [])->fetchAllAssoc();

    $result = Config::dbModel()->query($countSql)->execute($this->sqlComponents['params'] ?? [])->fetch(\PDO::FETCH_UNIQUE);

    $list['total'] = (int) ($result['total'] ?? 0);

    $this->paging($list);

    return $list;
  }

  /**
   * Add pagination data to Template class
   *
   * @param array $list
   */
  private function paging(array $list) {
    $this->listParams['total'] = $list['total'];
		$this->listParams['page_count'] = ceil($this->listParams['total'] / $this->listParams['size']);

    $this->listParams = $this->getPagination($this->listParams);

    $section = SECTION == 'front' ? '' : SECTION . '/';

    $pageUrl = $this->pageUrl ? trim($this->pageUrl, '/') . '/' : Request::buildUrl($section . Config::current_page()) . '/';

    Template::filter($this->listFilters);
    Template::pagination($this->listParams);
    Template::sort_urls($this->buildSortUrls($pageUrl));
    Template::sort_classes($this->getSortingClasses('sorting_'));
    Template::list_size_option_template(preg_replace('/size-\d+/', '_SIZE_', Arrays::getValueByPath($this->listParams, 'pages/0/href', '')));
    Template::list_address($pageUrl);
  }

  /**
   * Get a pagination parameter
   *
   * @param type $item
   * @return string|null
   */
  private function inArray($item): string|null {
    if (null === $this->params) {
      $this->params = (array) array_keys($this->listParams);
    }

    foreach ($this->params as $param) {
      if (false !== strpos($item . '-', $param)) {
        return $param;
      }
    }

    return null;
  }

  /**
   * Extract value for a URL parameter
   *
   * @param string $key
   * @param string $arg
   * @return string|null
   */
  private function getParamValue(string $key, string $arg): string|null {
    if (!preg_match('/^' . $key . '-(.*)/', strtolower($arg), $match)) {
      return null;
    }

    return $match[1];
  }

  public static function getListSizeOptions(): array {
    return [10, 25, 50, 100];
  }
}
