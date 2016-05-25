angular.module('ui.grid').run(['$templateCache', function($templateCache) {
  'use strict';

  $templateCache.put('ui-grid/ui-grid-filter',
    "<div class=\"ui-grid-filter-container\" ng-repeat=\"colFilter in col.filters\" ng-class=\"{'ui-grid-filter-cancel-button-hidden' : colFilter.disableCancelFilterButton === true }\"><div ng-if=\"colFilter.type !== 'select'\"><input type=\"text\" class=\"ui-grid-filter-input ui-grid-filter-input-{{$index}}\" ng-model=\"colFilter.term\" ng-attr-placeholder=\"{{colFilter.placeholder || ''}}\" aria-label=\"{{colFilter.ariaLabel || aria.defaultFilterLabel}}\"><div role=\"button\" class=\"ui-grid-filter-button\" ng-click=\"removeFilter(colFilter, $index)\" ng-if=\"!colFilter.disableCancelFilterButton\" ng-disabled=\"colFilter.term === undefined || colFilter.term === null || colFilter.term === ''\" ng-show=\"colFilter.term !== undefined && colFilter.term !== null && colFilter.term !== ''\"><i class=\"ui-grid-icon-cancel\" ui-grid-one-bind-aria-label=\"aria.removeFilter\">&nbsp;</i></div></div><div ng-if=\"colFilter.type === 'select'\"><select class=\"ui-grid-filter-select ui-grid-filter-input-{{$index}}\" ng-model=\"colFilter.term\" ng-attr-placeholder=\"{{colFilter.placeholder || aria.defaultFilterLabel}}\" aria-label=\"{{colFilter.ariaLabel || ''}}\" ng-options=\"option.value as option.label for option in colFilter.selectOptions\"><option value=\"\"></option></select><div role=\"button\" class=\"ui-grid-filter-button-select\" ng-click=\"removeFilter(colFilter, $index)\" ng-if=\"!colFilter.disableCancelFilterButton\" ng-disabled=\"colFilter.term === undefined || colFilter.term === null || colFilter.term === ''\" ng-show=\"colFilter.term !== undefined && colFilter.term != null\"><i class=\"ui-grid-icon-cancel\" ui-grid-one-bind-aria-label=\"aria.removeFilter\">&nbsp;</i></div></div></div>"
  );


  $templateCache.put('ui-grid/ui-grid-footer',
    "<div class=\"ui-grid-footer-panel ui-grid-footer-aggregates-row\"><!-- tfooter --><div class=\"ui-grid-footer ui-grid-footer-viewport\"><div class=\"ui-grid-footer-canvas\"><div class=\"ui-grid-footer-cell-wrapper\" ng-style=\"colContainer.headerCellWrapperStyle()\"><div role=\"row\" class=\"ui-grid-footer-cell-row\"><div ui-grid-footer-cell role=\"gridcell\" ng-repeat=\"col in colContainer.renderedColumns track by col.uid\" col=\"col\" render-index=\"$index\" class=\"ui-grid-footer-cell ui-grid-clearfix\"></div></div></div></div></div></div>"
  );


  $templateCache.put('ui-grid/ui-grid-grid-footer',
    "<div class=\"ui-grid-footer-info ui-grid-grid-footer\"><span>{{'search.totalItems' | t}} {{grid.rows.length}}</span> <span ng-if=\"grid.renderContainers.body.visibleRowCache.length !== grid.rows.length\" class=\"ngLabel\">({{\"search.showingItems\" | t}} {{grid.renderContainers.body.visibleRowCache.length}})</span></div>"
  );


  $templateCache.put('ui-grid/ui-grid-group-panel',
    "<div class=\"ui-grid-group-panel\"><div ui-t=\"groupPanel.description\" class=\"description\" ng-show=\"groupings.length == 0\"></div><ul ng-show=\"groupings.length > 0\" class=\"ngGroupList\"><li class=\"ngGroupItem\" ng-repeat=\"group in configGroups\"><span class=\"ngGroupElement\"><span class=\"ngGroupName\">{{group.displayName}} <span ng-click=\"removeGroup($index)\" class=\"ngRemoveGroup\">x</span></span> <span ng-hide=\"$last\" class=\"ngGroupArrow\"></span></span></li></ul></div>"
  );


  $templateCache.put('ui-grid/ui-grid-header',
    "<div role=\"rowgroup\" class=\"ui-grid-header\"><!-- theader --><div class=\"ui-grid-top-panel\"><div class=\"ui-grid-header-viewport\"><div class=\"ui-grid-header-canvas\"><div class=\"ui-grid-header-cell-wrapper\" ng-style=\"colContainer.headerCellWrapperStyle()\"><div role=\"row\" class=\"ui-grid-header-cell-row\"><div class=\"ui-grid-header-cell ui-grid-clearfix\" ng-repeat=\"col in colContainer.renderedColumns track by col.uid\" ui-grid-header-cell col=\"col\" render-index=\"$index\"></div></div></div></div></div></div></div>"
  );


  $templateCache.put('ui-grid/ui-grid-menu-button',
    "<div class=\"ui-grid-menu-button\"><div role=\"button\" ui-grid-one-bind-id-grid=\"'grid-menu'\" class=\"ui-grid-icon-container\" ng-click=\"toggleMenu()\" aria-haspopup=\"true\"><i class=\"ui-grid-icon-menu\" ui-grid-one-bind-aria-label=\"i18n.aria.buttonLabel\">&nbsp;</i></div><div ui-grid-menu menu-items=\"menuItems\"></div></div>"
  );


  $templateCache.put('ui-grid/ui-grid-no-header',
    "<div class=\"ui-grid-top-panel\"></div>"
  );


  $templateCache.put('ui-grid/ui-grid-row',
    "<div ng-repeat=\"(colRenderIndex, col) in colContainer.renderedColumns track by col.uid\" ui-grid-one-bind-id-grid=\"rowRenderIndex + '-' + col.uid + '-cell'\" class=\"ui-grid-cell\" ng-class=\"{ 'ui-grid-row-header-cell': col.isRowHeader }\" role=\"{{col.isRowHeader ? 'rowheader' : 'gridcell'}}\" ui-grid-cell></div>"
  );


  $templateCache.put('ui-grid/ui-grid',
    "<div ui-i18n=\"en\" class=\"ui-grid\"><!-- TODO (c0bra): add \"scoped\" attr here, eventually? --><style ui-grid-style>.grid{{ grid.id }} {\n" +
    "      /* Styles for the grid */\n" +
    "    }\n" +
    "\n" +
    "    .grid{{ grid.id }} .ui-grid-row, .grid{{ grid.id }} .ui-grid-cell, .grid{{ grid.id }} .ui-grid-cell .ui-grid-vertical-bar {\n" +
    "      height: {{ grid.options.rowHeight }}px;\n" +
    "    }\n" +
    "\n" +
    "    .grid{{ grid.id }} .ui-grid-row:last-child .ui-grid-cell {\n" +
    "      border-bottom-width: {{ ((grid.getTotalRowHeight() < grid.getViewportHeight()) && '1') || '0' }}px;\n" +
    "    }\n" +
    "\n" +
    "    {{ grid.verticalScrollbarStyles }}\n" +
    "    {{ grid.horizontalScrollbarStyles }}\n" +
    "\n" +
    "    /*\n" +
    "    .ui-grid[dir=rtl] .ui-grid-viewport {\n" +
    "      padding-left: {{ grid.verticalScrollbarWidth }}px;\n" +
    "    }\n" +
    "    */\n" +
    "\n" +
    "    {{ grid.customStyles }}</style><div class=\"ui-grid-contents-wrapper\"><div ui-grid-menu-button ng-if=\"grid.options.enableGridMenu\"></div><div ng-if=\"grid.hasLeftContainer()\" style=\"width: 0\" ui-grid-pinned-container=\"'left'\"></div><div ui-grid-render-container container-id=\"'body'\" col-container-name=\"'body'\" row-container-name=\"'body'\" bind-scroll-horizontal=\"true\" bind-scroll-vertical=\"true\" enable-horizontal-scrollbar=\"grid.options.enableHorizontalScrollbar\" enable-vertical-scrollbar=\"grid.options.enableVerticalScrollbar\"></div><div ng-if=\"grid.hasRightContainer()\" style=\"width: 0\" ui-grid-pinned-container=\"'right'\"></div><div ui-grid-grid-footer ng-if=\"grid.options.showGridFooter\"></div><div ui-grid-column-menu ng-if=\"grid.options.enableColumnMenus\"></div><div ng-transclude></div></div></div>"
  );


  $templateCache.put('ui-grid/uiGridCell',
    "<div class=\"ui-grid-cell-contents\" title=\"TOOLTIP\">{{COL_FIELD CUSTOM_FILTERS}}</div>"
  );


  $templateCache.put('ui-grid/uiGridColumnMenu',
    "<div class=\"ui-grid-column-menu\"><div ui-grid-menu menu-items=\"menuItems\"><!-- <div class=\"ui-grid-column-menu\">\n" +
    "    <div class=\"inner\" ng-show=\"menuShown\">\n" +
    "      <ul>\n" +
    "        <div ng-show=\"grid.options.enableSorting\">\n" +
    "          <li ng-click=\"sortColumn($event, asc)\" ng-class=\"{ 'selected' : col.sort.direction == asc }\"><i class=\"ui-grid-icon-sort-alt-up\"></i> Sort Ascending</li>\n" +
    "          <li ng-click=\"sortColumn($event, desc)\" ng-class=\"{ 'selected' : col.sort.direction == desc }\"><i class=\"ui-grid-icon-sort-alt-down\"></i> Sort Descending</li>\n" +
    "          <li ng-show=\"col.sort.direction\" ng-click=\"unsortColumn()\"><i class=\"ui-grid-icon-cancel\"></i> Remove Sort</li>\n" +
    "        </div>\n" +
    "      </ul>\n" +
    "    </div>\n" +
    "  </div> --></div></div>"
  );


  $templateCache.put('ui-grid/uiGridFooterCell',
    "<div class=\"ui-grid-cell-contents\" col-index=\"renderIndex\"><div>{{ col.getAggregationText() + ( col.getAggregationValue() CUSTOM_FILTERS ) }}</div></div>"
  );


  $templateCache.put('ui-grid/uiGridHeaderCell',
    "<div role=\"columnheader\" ng-class=\"{ 'sortable': sortable }\" ui-grid-one-bind-aria-labelledby-grid=\"col.uid + '-header-text ' + col.uid + '-sortdir-text'\" aria-sort=\"{{col.sort.direction == asc ? 'ascending' : ( col.sort.direction == desc ? 'descending' : (!col.sort.direction ? 'none' : 'other'))}}\"><div role=\"button\" tabindex=\"0\" class=\"ui-grid-cell-contents ui-grid-header-cell-primary-focus\" col-index=\"renderIndex\" title=\"TOOLTIP\"><span class=\"ui-grid-header-cell-label\" ui-grid-one-bind-id-grid=\"col.uid + '-header-text'\">{{ col.displayName CUSTOM_FILTERS }}</span> <span ui-grid-one-bind-id-grid=\"col.uid + '-sortdir-text'\" ui-grid-visible=\"col.sort.direction\" aria-label=\"{{getSortDirectionAriaLabel()}}\"><i ng-class=\"{ 'ui-grid-icon-up-dir': col.sort.direction == asc, 'ui-grid-icon-down-dir': col.sort.direction == desc, 'ui-grid-icon-blank': !col.sort.direction }\" title=\"{{col.sort.priority ? i18n.headerCell.priority + ' ' + col.sort.priority : null}}\" aria-hidden=\"true\"></i> <sub class=\"ui-grid-sort-priority-number\">{{col.sort.priority}}</sub></span></div><div role=\"button\" tabindex=\"0\" ui-grid-one-bind-id-grid=\"col.uid + '-menu-button'\" class=\"ui-grid-column-menu-button\" ng-if=\"grid.options.enableColumnMenus && !col.isRowHeader  && col.colDef.enableColumnMenu !== false\" ng-click=\"toggleMenu($event)\" ng-class=\"{'ui-grid-column-menu-button-last-col': isLastCol}\" ui-grid-one-bind-aria-label=\"i18n.headerCell.aria.columnMenuButtonLabel\" aria-haspopup=\"true\"><i class=\"ui-grid-icon-angle-down\" aria-hidden=\"true\">&nbsp;</i></div><div ui-grid-filter></div></div>"
  );


  $templateCache.put('ui-grid/uiGridMenu',
    "<div class=\"ui-grid-menu\" ng-if=\"shown\"><style ui-grid-style>{{dynamicStyles}}</style><div class=\"ui-grid-menu-mid\" ng-show=\"shownMid\"><div class=\"ui-grid-menu-inner\"><button type=\"button\" ng-focus=\"focus=true\" ng-blur=\"focus=false\" class=\"ui-grid-menu-close-button\" ng-class=\"{'ui-grid-sr-only': (!focus)}\"><i class=\"ui-grid-icon-cancel\" ui-grid-one-bind-aria-label=\"i18n.close\"></i></button><ul role=\"menu\" class=\"ui-grid-menu-items\"><li ng-repeat=\"item in menuItems\" role=\"menuitem\" ui-grid-menu-item ui-grid-one-bind-id=\"'menuitem-'+$index\" action=\"item.action\" name=\"item.title\" active=\"item.active\" icon=\"item.icon\" shown=\"item.shown\" context=\"item.context\" template-url=\"item.templateUrl\" leave-open=\"item.leaveOpen\" screen-reader-only=\"item.screenReaderOnly\"></li></ul></div></div></div>"
  );


  $templateCache.put('ui-grid/uiGridMenuItem',
    "<button type=\"button\" class=\"ui-grid-menu-item\" ng-click=\"itemAction($event, title)\" ng-show=\"itemShown()\" ng-class=\"{ 'ui-grid-menu-item-active': active(), 'ui-grid-sr-only': (!focus && screenReaderOnly) }\" aria-pressed=\"{{active()}}\" tabindex=\"0\" ng-focus=\"focus=true\" ng-blur=\"focus=false\"><i ng-class=\"icon\" aria-hidden=\"true\">&nbsp;</i> {{ name }}</button>"
  );


  $templateCache.put('ui-grid/uiGridRenderContainer',
    "<div role=\"grid\" ui-grid-one-bind-id-grid=\"'grid-container'\" class=\"ui-grid-render-container\" ng-style=\"{ 'margin-left': colContainer.getMargin('left') + 'px', 'margin-right': colContainer.getMargin('right') + 'px' }\"><!-- All of these dom elements are replaced in place --><div ui-grid-header></div><div ui-grid-viewport></div><div ng-if=\"colContainer.needsHScrollbarPlaceholder()\" class=\"ui-grid-scrollbar-placeholder\" ng-style=\"{height:colContainer.grid.scrollbarHeight + 'px'}\"></div><ui-grid-footer ng-if=\"grid.options.showColumnFooter\"></ui-grid-footer></div>"
  );


  $templateCache.put('ui-grid/uiGridViewport',
    "<div role=\"rowgroup\" class=\"ui-grid-viewport\" ng-style=\"colContainer.getViewportStyle()\"><!-- tbody --><div class=\"ui-grid-canvas\"><div ng-repeat=\"(rowRenderIndex, row) in rowContainer.renderedRows track by $index\" class=\"ui-grid-row\" ng-style=\"Viewport.rowStyle(rowRenderIndex)\"><div role=\"row\" ui-grid-row=\"row\" row-render-index=\"rowRenderIndex\"></div></div></div></div>"
  );


  $templateCache.put('ui-grid/cellEditor',
    "<div><form name=\"inputForm\"><input type=\"INPUT_TYPE\" ng-class=\"'colt' + col.uid\" ui-grid-editor ng-model=\"MODEL_COL_FIELD\"></form></div>"
  );


  $templateCache.put('ui-grid/dropdownEditor',
    "<div><form name=\"inputForm\"><select ng-class=\"'colt' + col.uid\" ui-grid-edit-dropdown ng-model=\"MODEL_COL_FIELD\" ng-options=\"field[editDropdownIdLabel] as field[editDropdownValueLabel] CUSTOM_FILTERS for field in editDropdownOptionsArray\"></select></form></div>"
  );


  $templateCache.put('ui-grid/fileChooserEditor',
    "<div><form name=\"inputForm\"><input ng-class=\"'colt' + col.uid\" ui-grid-edit-file-chooser type=\"file\" id=\"files\" name=\"files[]\" ng-model=\"MODEL_COL_FIELD\"></form></div>"
  );


  $templateCache.put('ui-grid/expandableRow',
    "<div ui-grid-expandable-row ng-if=\"expandableRow.shouldRenderExpand()\" class=\"expandableRow\" style=\"float:left; margin-top: 1px; margin-bottom: 1px\" ng-style=\"{width: (grid.renderContainers.body.getCanvasWidth()) + 'px', height: row.expandedRowHeight + 'px'}\"></div>"
  );


  $templateCache.put('ui-grid/expandableRowHeader',
    "<div class=\"ui-grid-row-header-cell ui-grid-expandable-buttons-cell\"><div class=\"ui-grid-cell-contents\"><i ng-class=\"{ 'ui-grid-icon-plus-squared' : !row.isExpanded, 'ui-grid-icon-minus-squared' : row.isExpanded }\" ng-click=\"grid.api.expandable.toggleRowExpansion(row.entity)\"></i></div></div>"
  );


  $templateCache.put('ui-grid/expandableScrollFiller',
    "<div ng-if=\"expandableRow.shouldRenderFiller()\" ng-class=\"{scrollFiller:true, scrollFillerClass:(colContainer.name === 'body')}\" ng-style=\"{ width: (grid.getViewportWidth()) + 'px', height: row.expandedRowHeight + 2 + 'px', 'margin-left': grid.options.rowHeader.rowHeaderWidth + 'px' }\"><i class=\"ui-grid-icon-spin5 ui-grid-animate-spin\" ng-style=\"{'margin-top': ( row.expandedRowHeight/2 - 5) + 'px', 'margin-left' : ((grid.getViewportWidth() - grid.options.rowHeader.rowHeaderWidth)/2 - 5) + 'px'}\"></i></div>"
  );


  $templateCache.put('ui-grid/expandableTopRowHeader',
    "<div class=\"ui-grid-row-header-cell ui-grid-expandable-buttons-cell\"><div class=\"ui-grid-cell-contents\"><i ng-class=\"{ 'ui-grid-icon-plus-squared' : !grid.expandable.expandedAll, 'ui-grid-icon-minus-squared' : grid.expandable.expandedAll }\" ng-click=\"grid.api.expandable.toggleAllRows()\"></i></div></div>"
  );


  $templateCache.put('ui-grid/csvLink',
    "<span class=\"ui-grid-exporter-csv-link-span\"><a href=\"data:text/csv;charset=UTF-8,CSV_CONTENT\" download=\"FILE_NAME\">LINK_LABEL</a></span>"
  );


  $templateCache.put('ui-grid/importerMenuItem',
    "<li class=\"ui-grid-menu-item\"><form><input class=\"ui-grid-importer-file-chooser\" type=\"file\" id=\"files\" name=\"files[]\"></form></li>"
  );


  $templateCache.put('ui-grid/importerMenuItemContainer',
    "<div ui-grid-importer-menu-item></div>"
  );


  $templateCache.put('ui-grid/pagination',
    "<div role=\"contentinfo\" class=\"ui-grid-pager-panel\" ui-grid-pager ng-show=\"grid.options.enablePaginationControls\"><div role=\"navigation\" class=\"ui-grid-pager-container\"><div role=\"menubar\" class=\"ui-grid-pager-control\"><button type=\"button\" role=\"menuitem\" class=\"ui-grid-pager-first\" ui-grid-one-bind-title=\"aria.pageToFirst\" ui-grid-one-bind-aria-label=\"aria.pageToFirst\" ng-click=\"pageFirstPageClick()\" ng-disabled=\"cantPageBackward()\"><div class=\"first-triangle\"><div class=\"first-bar\"></div></div></button> <button type=\"button\" role=\"menuitem\" class=\"ui-grid-pager-previous\" ui-grid-one-bind-title=\"aria.pageBack\" ui-grid-one-bind-aria-label=\"aria.pageBack\" ng-click=\"pagePreviousPageClick()\" ng-disabled=\"cantPageBackward()\"><div class=\"first-triangle prev-triangle\"></div></button> <input type=\"number\" ui-grid-one-bind-title=\"aria.pageSelected\" ui-grid-one-bind-aria-label=\"aria.pageSelected\" class=\"ui-grid-pager-control-input\" ng-model=\"grid.options.paginationCurrentPage\" min=\"1\" max=\"{{ paginationApi.getTotalPages() }}\" required> <span class=\"ui-grid-pager-max-pages-number\" ng-show=\"paginationApi.getTotalPages() > 0\"><abbr ui-grid-one-bind-title=\"paginationOf\">/</abbr> {{ paginationApi.getTotalPages() }}</span> <button type=\"button\" role=\"menuitem\" class=\"ui-grid-pager-next\" ui-grid-one-bind-title=\"aria.pageForward\" ui-grid-one-bind-aria-label=\"aria.pageForward\" ng-click=\"pageNextPageClick()\" ng-disabled=\"cantPageForward()\"><div class=\"last-triangle next-triangle\"></div></button> <button type=\"button\" role=\"menuitem\" class=\"ui-grid-pager-last\" ui-grid-one-bind-title=\"aria.pageToLast\" ui-grid-one-bind-aria-label=\"aria.pageToLast\" ng-click=\"pageLastPageClick()\" ng-disabled=\"cantPageToLast()\"><div class=\"last-triangle\"><div class=\"last-bar\"></div></div></button></div><div class=\"ui-grid-pager-row-count-picker\" ng-if=\"grid.options.paginationPageSizes.length > 1\"><select ui-grid-one-bind-aria-labelledby-grid=\"'items-per-page-label'\" ng-model=\"grid.options.paginationPageSize\" ng-options=\"o as o for o in grid.options.paginationPageSizes\"></select><span ui-grid-one-bind-id-grid=\"'items-per-page-label'\" class=\"ui-grid-pager-row-count-label\">&nbsp;{{sizesLabel}}</span></div><span ng-if=\"grid.options.paginationPageSizes.length <= 1\" class=\"ui-grid-pager-row-count-label\">{{grid.options.paginationPageSize}}&nbsp;{{sizesLabel}}</span></div><div class=\"ui-grid-pager-count-container\"><div class=\"ui-grid-pager-count\"><span ng-show=\"grid.options.totalItems > 0\">{{showingLow}} <abbr ui-grid-one-bind-title=\"paginationThrough\">-</abbr> {{showingHigh}} {{paginationOf}} {{grid.options.totalItems}} {{totalItemsLabel}}</span></div></div></div>"
  );


  $templateCache.put('ui-grid/columnResizer',
    "<div ui-grid-column-resizer ng-if=\"grid.options.enableColumnResizing\" class=\"ui-grid-column-resizer\" col=\"col\" position=\"right\" render-index=\"renderIndex\" unselectable=\"on\"></div>"
  );


  $templateCache.put('ui-grid/gridFooterSelectedItems',
    "<span ng-if=\"grid.selection.selectedCount !== 0 && grid.options.enableFooterTotalSelected\">({{\"search.selectedItems\" | t}} {{grid.selection.selectedCount}})</span>"
  );


  $templateCache.put('ui-grid/selectionHeaderCell',
    "<div><!-- <div class=\"ui-grid-vertical-bar\">&nbsp;</div> --><div class=\"ui-grid-cell-contents\" col-index=\"renderIndex\"><ui-grid-selection-select-all-buttons ng-if=\"grid.options.enableSelectAll\"></ui-grid-selection-select-all-buttons></div></div>"
  );


  $templateCache.put('ui-grid/selectionRowHeader',
    "<div class=\"ui-grid-disable-selection\"><div class=\"ui-grid-cell-contents\"><ui-grid-selection-row-header-buttons></ui-grid-selection-row-header-buttons></div></div>"
  );


  $templateCache.put('ui-grid/selectionRowHeaderButtons',
    "<div class=\"ui-grid-selection-row-header-buttons ui-grid-icon-ok\" ng-class=\"{'ui-grid-row-selected': row.isSelected}\" ng-click=\"selectButtonClick(row, $event)\">&nbsp;</div>"
  );


  $templateCache.put('ui-grid/selectionSelectAllButtons',
    "<div class=\"ui-grid-selection-row-header-buttons ui-grid-icon-ok\" ng-class=\"{'ui-grid-all-selected': grid.selection.selectAll}\" ng-click=\"headerButtonClick($event)\"></div>"
  );


  $templateCache.put('ui-grid/treeBaseExpandAllButtons',
    "<div class=\"ui-grid-tree-base-row-header-buttons\" ng-class=\"{'ui-grid-icon-minus-squared': grid.treeBase.numberLevels > 0 && grid.treeBase.expandAll, 'ui-grid-icon-plus-squared': grid.treeBase.numberLevels > 0 && !grid.treeBase.expandAll}\" ng-click=\"headerButtonClick($event)\"></div>"
  );


  $templateCache.put('ui-grid/treeBaseHeaderCell',
    "<div><div class=\"ui-grid-cell-contents\" col-index=\"renderIndex\"><ui-grid-tree-base-expand-all-buttons></ui-grid-tree-base-expand-all-buttons></div></div>"
  );


  $templateCache.put('ui-grid/treeBaseRowHeader',
    "<div class=\"ui-grid-cell-contents\"><ui-grid-tree-base-row-header-buttons></ui-grid-tree-base-row-header-buttons></div>"
  );


  $templateCache.put('ui-grid/treeBaseRowHeaderButtons',
    "<div class=\"ui-grid-tree-base-row-header-buttons\" ng-class=\"{'ui-grid-tree-base-header': row.treeLevel > -1 }\" ng-click=\"treeButtonClick(row, $event)\"><i ng-class=\"{'ui-grid-icon-minus-squared': ( ( grid.options.showTreeExpandNoChildren && row.treeLevel > -1 ) || ( row.treeNode.children && row.treeNode.children.length > 0 ) ) && row.treeNode.state === 'expanded', 'ui-grid-icon-plus-squared': ( ( grid.options.showTreeExpandNoChildren && row.treeLevel > -1 ) || ( row.treeNode.children && row.treeNode.children.length > 0 ) ) && row.treeNode.state === 'collapsed'}\" ng-style=\"{'padding-left': grid.options.treeIndent * row.treeLevel + 'px'}\"></i> &nbsp;</div>"
  );


  $templateCache.put('ui-grid/cellTitleValidator',
    "<div class=\"ui-grid-cell-contents\" ng-class=\"{invalid:grid.validate.isInvalid(row.entity,col.colDef)}\" title=\"{{grid.validate.getTitleFormattedErrors(row.entity,col.colDef)}}\">{{COL_FIELD CUSTOM_FILTERS}}</div>"
  );


  $templateCache.put('ui-grid/cellTooltipValidator',
    "<div class=\"ui-grid-cell-contents\" ng-class=\"{invalid:grid.validate.isInvalid(row.entity,col.colDef)}\" tooltip-html-unsafe=\"{{grid.validate.getFormattedErrors(row.entity,col.colDef)}}\" tooltip-enable=\"grid.validate.isInvalid(row.entity,col.colDef)\" tooltip-append-to-body=\"true\" tooltip-placement=\"top\" title=\"TOOLTIP\">{{COL_FIELD CUSTOM_FILTERS}}</div>"
  );

}]);
