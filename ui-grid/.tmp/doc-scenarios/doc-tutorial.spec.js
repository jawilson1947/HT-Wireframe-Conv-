describe("ui-grid", function() {
  describe("tutorial/098_contributing_to_ui-grid", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/098_contributing_to_ui-grid");
    });
  
});

  describe("tutorial/099_upgrading_from_2", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/099_upgrading_from_2");
    });
  
});

  describe("tutorial/100_preReqs", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/100_preReqs");
    });
  
});

  describe("tutorial/101_intro", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/101_intro");
    });
  
    var GridObjectTest = require('../../test/e2e/gridObjectTestUtils.spec.js');
    var grid1 = new GridObjectTest('grid1');
    describe('101 tutorial', function() {
      it('grid should have three visible rows', function () {
        grid1.expectRowCount( 3 );
      });
    
      it('header values should be as expected', function () {
        grid1.expectHeaderColumns( ['First Name', 'Last Name', 'Company', 'Employed'] );
      });
    
      it('first row cell values should be as expected', function () {
        // checking individual cells usually gives a better stack trace when there are errors
        grid1.expectCellValueMatch( 0, 0, 'Cox' );
        grid1.expectCellValueMatch( 0, 1, 'Carney' );
        grid1.expectCellValueMatch( 0, 2, 'Enormo' );
        grid1.expectCellValueMatch( 0, 3, 'true' );
      });
    
      it('next two row cell values should be as expected', function () {
        // checking in bulk is convenient to write, but can be less informative with errors
        grid1.expectRowValuesMatch( 1, [ 'Lorraine', 'Wise', 'Comveyer', 'false' ] );
        grid1.expectRowValuesMatch( 2, [ 'Nancy', 'Waters', 'Fuelton', 'false' ] );
      });
    });

});

  describe("tutorial/102_sorting", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/102_sorting");
    });
  
      var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
      var GridObjectTest = require('../../test/e2e/gridObjectTestUtils.spec.js');
      var grid1 = new GridObjectTest('grid1');
      var grid2 = new GridObjectTest('grid2');
    
      describe('first grid on the page, no default sort', function() {
        // Reload the page before each test if on Firefox. Chrome does it automatically.
        gridTestUtils.firefoxReload();
    
        it('header values should be as expected', function () {
          grid1.expectHeaderColumns( [ 'Name', 'Gender', 'Company' ] );
        });
    
        it('grid should be unsorted by default', function () {
          grid1.expectCellValueMatch( 0, 0, 'Ethel Price' );
          grid1.expectCellValueMatch( 1, 0, 'Claudine Neal' );
        });
    
        it('sort by name by clicking header', function () {
          grid1.clickHeaderCell( 0 )
            .then(function () {
              grid1.expectCellValueMatch( 0, 0, 'Alexander Foley' );
              grid1.expectCellValueMatch( 1, 0, 'Alisha Myers' );
            });
        });
    
        it('reverse sort by name by clicking header', function () {
          grid1.clickHeaderCell( 0 )
            .then(function () {
              return grid1.clickHeaderCell( 0 );
            })
            .then(function () {
              grid1.expectCellValueMatch( 0, 0, 'Yvonne Parsons' );
              grid1.expectCellValueMatch( 1, 0, 'Woods Key' );
            });
        });
    
        it('return to original sort by name by clicking header', function () {
          grid1.clickHeaderCell( 0 )
            .then(function () {
              return grid1.clickHeaderCell( 0 );
            })
            .then(function () {
              return grid1.clickHeaderCell( 0 );
            })
            .then(function () {
              grid1.expectCellValueMatch( 0, 0, 'Ethel Price' );
              grid1.expectCellValueMatch( 1, 0, 'Claudine Neal' );
            });
        });
    
        it('sort asc by clicking menu', function() {
          grid1.clickColumnMenuSortAsc( 0 )
            .then(function () {
              grid1.expectCellValueMatch( 0, 0, 'Alexander Foley' );
              grid1.expectCellValueMatch( 1, 0, 'Alisha Myers' );
            });
        });
    
        it('sort desc by clicking menu, then remove sort', function() {
          grid1.clickColumnMenuSortDesc( 0 )
            .then(function () {
              grid1.expectCellValueMatch( 0, 0, 'Yvonne Parsons' );
              grid1.expectCellValueMatch( 1, 0, 'Woods Key' );
              return true;
            })
            .then(function () {
              return grid1.clickColumnMenuRemoveSort( 0 );
            })
            .then(function () {
              grid1.expectCellValueMatch( 0, 0, 'Ethel Price' );
              grid1.expectCellValueMatch( 1, 0, 'Claudine Neal' );
            });
        });
    
        it('sort two columns, gender then name, by shift clicking', function() {
          grid1.clickHeaderCell( 1 )
            .then(function () {
              return grid1.shiftClickHeaderCell( 0 );
            })
            .then(function () {
              grid1.expectCellValueMatch( 0, 0, 'Alisha Myers' );
              grid1.expectCellValueMatch( 1, 0, 'Beryl Rice' );
            });
        });
    
        it('sort disabled on last column', function() {
          grid1.clickHeaderCell( 2 )
            .then(function () {
              grid1.expectCellValueMatch( 0, 0, 'Ethel Price' );
              grid1.expectCellValueMatch( 1, 0, 'Claudine Neal' );
            });
        });
    
        it('click one menu, then click another menu, expect undisplay and redisplay on second click', function() {
          grid1.expectVisibleColumnMenuItems( 0, 4 );
          grid1.expectVisibleColumnMenuItems( 1, 4 );
        });
    
        it('toggle gender, expect Alexander Foley to move around', function() {
          // sort gender asc, then name
          grid1.clickHeaderCell( 1 )
            .then(function () {
              return grid1.clickHeaderCell( 1 );
            })
            .then(function () {
              return grid1.shiftClickHeaderCell( 0 );
            })
            .then(function () {
              grid1.expectCellValueMatch( 0, 0, 'Alexander Foley' );
            })
            .then(function () {
              return gridTestUtils.click(element(by.id('toggleGender')));
            })
            .then(function () {
              grid1.expectCellValueMatch( 0, 0, 'Anthony Joyner' );
            })
            .then(function () {
              return gridTestUtils.click(element(by.id('toggleGender')));
            })
            .then(function () {
              grid1.expectCellValueMatch( 0, 0, 'Alexander Foley' );
            });
        });
    
      });
    
    
      describe('second grid on the page, has default sort', function() {
        it('header values should be as expected', function () {
          grid2.expectHeaderColumns( [ 'Name', 'Gender', 'Company' ] );
        });
    
        it('grid should be sorted by default', function () {
          grid2.expectCellValueMatch( 0, 0, 'Yvonne Parsons' );
          grid2.expectCellValueMatch( 1, 0, 'Velma Fry' );
        });
    
        it('sort on second column can\'t be removed when cycle through header clicks', function () {
          grid2.clickHeaderCell( 0 )
            .then(function () {
              grid2.expectCellValueMatch( 0, 0, 'Ethel Price' );
            })
            .then(function () {
              return grid2.clickHeaderCell( 1 );
            })
            .then(function () {
              grid2.expectCellValueMatch( 0, 0, 'Wilder Gonzales' );
            })
            .then(function () {
              return grid2.clickHeaderCell( 1 );
            })
            .then(function () {
              grid2.expectCellValueMatch( 0, 0, 'Ethel Price' );
            });
        });
      });

});

  describe("tutorial/103_filtering", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/103_filtering");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    
    describe('first grid on the page, filtered by male by default', function() {
      gridTestUtils.firefoxReload();
    
      it('grid should have eight visible columns', function () {
        gridTestUtils.expectHeaderColumnCount( 'grid1', 8 );
      });
    
      it('filter on 4 columns, filter with greater than/less than on one, one with no filter, then one with one filter', function () {
        gridTestUtils.expectFilterBoxInColumn( 'grid1', 0, 1 );
        gridTestUtils.expectFilterSelectInColumn( 'grid1', 1, 1 );
        gridTestUtils.expectFilterBoxInColumn( 'grid1', 2, 0 );
        gridTestUtils.expectFilterBoxInColumn( 'grid1', 3, 1 );
        gridTestUtils.expectFilterBoxInColumn( 'grid1', 4, 1 );
        gridTestUtils.expectFilterBoxInColumn( 'grid1', 5, 2 );
        gridTestUtils.expectFilterBoxInColumn( 'grid1', 6, 1 );
      });
    
      it('third row should be Hatfield Hudson - will be Terry Clay if filtering broken', function () {
        gridTestUtils.expectCellValueMatch( 'grid1', 2, 0, 'Hatfield Hudson' );
      });
    
      it('cancel filter on gender column and on date column, should now see Bishop Carr in third row', function() {
        gridTestUtils.cancelFilterInColumn( 'grid1', 1 )
          .then(function () {
            return gridTestUtils.cancelFilterInColumn( 'grid1', 6 );
          })
          .then(function () {
            gridTestUtils.expectCellValueMatch( 'grid1', 2, 0, 'Bishop Carr' );
          });
      });
    
      it('filter on email column, should automatically do "ends with"', function() {
        gridTestUtils.cancelFilterInColumn( 'grid1', 1 )
          .then(function () {
            return gridTestUtils.cancelFilterInColumn( 'grid1', 6 );
          })
          .then(function () {
            return gridTestUtils.enterFilterInColumn( 'grid1', 3, 'digirang.com' );
          })
          .then(function () {
            gridTestUtils.expectRowCount( 'grid1', 2 );
          });
      });
    });

});

  describe("tutorial/104_i18n", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/104_i18n");
    });
  
});

  describe("tutorial/105_footer", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/105_footer");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    describe( '105 footer', function() {
      /*
      var $filter = angular.module('[app]').injector().get('$filter');
    
      function getMaxDateString() {
        // Get the bound value for the max date
        var maxFooterCell = gridTestUtils.footerCell( 'grid1', 6 );
        var maxDate =  maxFooterCell.evaluate('col.getAggregationValue()');
        var maxDateFormattedString = $filter('date')(maxDate, 'MMM d, yyyy');
    
        return maxDateFormattedString;
      }
      */
    
      it('grid should have six visible columns', function () {
        gridTestUtils.expectHeaderColumnCount( 'grid1', 7 );
      });
    
      it('grid should have six visible columns in footer', function () {
        gridTestUtils.expectFooterColumnCount( 'grid1', 7 );
      });
    
      it('grid should have footers with specific values', function () {
        gridTestUtils.expectFooterCellValueMatch( 'grid1', 0, '' );
        gridTestUtils.expectFooterCellValueMatch( 'grid1', 1, 'total: 281568' );
        gridTestUtils.expectFooterCellValueMatch( 'grid1', 2, '30.248' );
        gridTestUtils.expectFooterCellValueMatch( 'grid1', 3, 'min: 20' );
        gridTestUtils.expectFooterCellValueMatch( 'grid1', 4, 'max: 49' );
        gridTestUtils.expectFooterCellValueMatch( 'grid1', 5, 'custom template' );
        // gridTestUtils.expectFooterCellValueMatch( 'grid1', 6, 'max: ' + getMaxDateString() );
        // TODO: need to check item count, not done
      });
    
      it('filter and expect recalculate', function () {
        gridTestUtils.enterFilterInColumn( 'grid1', 1, '3' )
          .then(function () {
            gridTestUtils.expectFooterCellValueMatch( 'grid1', 0, '' );
            gridTestUtils.expectFooterCellValueMatch( 'grid1', 1, 'total: 68974' );
            gridTestUtils.expectFooterCellValueMatch( 'grid1', 2, '29.8978102189781' );
            gridTestUtils.expectFooterCellValueMatch( 'grid1', 3, 'min: 20' );
            gridTestUtils.expectFooterCellValueMatch( 'grid1', 4, 'max: 40' );
            gridTestUtils.expectFooterCellValueMatch( 'grid1', 5, 'custom template' );
            // gridTestUtils.expectFooterCellValueMatch( 'grid1', 6, 'max: ' + getMaxDateString() );
            // TODO: need to check item count, not done
          });
      });
    });

});

  describe("tutorial/106_binding", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/106_binding");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    it('grid should have one visible row and four columns', function () {
      gridTestUtils.expectRowCount( 'grid1', 1 );
      gridTestUtils.expectHeaderColumnCount( 'grid1', 4 );
    });
    
    it('headers as specified', function () {
      gridTestUtils.expectHeaderCellValueMatch( 'grid1', 0, 'First Name' );
      gridTestUtils.expectHeaderCellValueMatch( 'grid1', 1, '1st Friend' );
      gridTestUtils.expectHeaderCellValueMatch( 'grid1', 2, 'City' );
      gridTestUtils.expectHeaderCellValueMatch( 'grid1', 3, 'Get Zip' );
    });
    
    it('row values should be as expected', function () {
      gridTestUtils.expectRowValuesMatch( 'grid1', 0, [ 'Cox', 'friend0', 'Laurel', '39565' ]);
    });

});

  describe("tutorial/108_hidden_grids", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/108_hidden_grids");
    });
  
});

  describe("tutorial/109_multiple_grids", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/109_multiple_grids");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    it('grid1 should have three visible columns, grid2 has four', function () {
      gridTestUtils.expectHeaderColumnCount( 'grid1', 3 );
      gridTestUtils.expectHeaderColumnCount( 'grid2', 4 );
    });
    
    it('menus should show over correct grid', function () {
      // click on menu in grid 1
      var headerCell = gridTestUtils.headerCell( 'grid1', 0 );
      headerCell.element( by.css( '.ui-grid-column-menu-button' ) ).click();
      
      // check a menu list is showing somewhere in grid1, and has at least 2 items
      var columnMenu = element( by.id( 'grid1' ) ).element( by.css( '.ui-grid-column-menu' ));
      expect( columnMenu.all( by.repeater('item in menuItems') ).count() ).toBeGreaterThan(2);
    
      // click on menu in grid 2
      var headerCell = gridTestUtils.headerCell( 'grid2', 0 );
      headerCell.element( by.css( '.ui-grid-column-menu-button' ) ).click();
      
      // check a menu list is showing somewhere in grid2, and has at least 2 items
      var columnMenu = element( by.id( 'grid2' ) ).element( by.css( '.ui-grid-column-menu' ));
      expect( columnMenu.all( by.repeater('item in menuItems') ).count() ).toBeGreaterThan(2);
    });

});

  describe("tutorial/110_grid_in_modal", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/110_grid_in_modal");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    it('click modal button, grid should show with three columns and some data', function () {
      element( by.id ( 'showButton' ) ).click();
      gridTestUtils.expectHeaderColumnCount( 'grid1', 3 );
      gridTestUtils.expectRowValuesMatch( 'grid1', 0, [ 'Ethel Price', 'female', 'Enersol' ]);
      element( by.id ( 'buttonClose' ) ).click();
    });

});

  describe("tutorial/111_cellClass", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/111_cellClass");
    });
  
      var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
      describe( '111 cell class', function() {
        // Reload the page before each test if on Firefox. Chrome does it automatically.
        gridTestUtils.firefoxReload();
    
        it('grid should have two visible columns', function () {
          gridTestUtils.expectHeaderColumnCount( 'grid1', 2 );
        });
    
        it('column one formatted color red, background yellow', function () {
          // sort by company, 2,1 is no longer Velity so shouldn't be blue, check it's the same colour as row 1
          gridTestUtils.clickHeaderCell( 'grid1', 1 )
            .then(function () {
              gridTestUtils.expectCellValueMatch( 'grid1', 2, 1, 'Acusage' );
              expect( gridTestUtils.dataCell( 'grid1', 1, 1 ).getCssValue('color')).toEqual('rgba(44, 62, 80, 1)');
              expect( gridTestUtils.dataCell( 'grid1', 2, 1 ).getCssValue('color')).toEqual('rgba(44, 62, 80, 1)');
            });
        });
      });

});

  describe("tutorial/112_swapping_data", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/112_swapping_data");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    describe( '112 swapping data', function() {
      // Reload the page before each test if on Firefox. Chrome does it automatically.
      gridTestUtils.firefoxReload();
    
      it('grid should have four visible columns', function () {
        gridTestUtils.expectHeaderColumnCount( 'grid1', 4 );
      });
    
      it('swap data and data changes', function () {
        gridTestUtils.expectRowCount( 'grid1', 4 );
        gridTestUtils.expectRowValuesMatch( 'grid1', 0, [ 'Cox', 'Carney', 'Enormo', 'male' ] );
        gridTestUtils.expectRowValuesMatch( 'grid1', 1, [ 'Lorraine', 'Wise', 'Comveyer', 'female' ] );
    
        element( by.id( "swapData" ) ).click()
          .then(function () {
            return browser.waitForAngular();
          })
          .then(function () {
            gridTestUtils.expectRowCount( 'grid1', 6 );
            gridTestUtils.expectRowValuesMatch( 'grid1', 0, [ 'Waters', 'Shepherd', 'Kongene', 'true' ] );
            gridTestUtils.expectRowValuesMatch( 'grid1', 1, [ 'Hopper', 'Zamora', 'Acium', 'true' ] );
          })
          .then(function () {
            return element( by.id( "swapData" ) ).click();
          })
          .then(function () {
            return browser.waitForAngular();
          })
          .then(function () {
            gridTestUtils.expectRowCount( 'grid1', 4 );
            gridTestUtils.expectRowValuesMatch( 'grid1', 0, [ 'Cox', 'Carney', 'Enormo', 'male' ] );
            gridTestUtils.expectRowValuesMatch( 'grid1', 1, [ 'Lorraine', 'Wise', 'Comveyer', 'female' ] );
          });
      });
    
      it('add data and data changes', function () {
        gridTestUtils.expectRowCount( 'grid1', 4 );
    
        element( by.id( "addData" ) ).click()
          .then(function () {
            return browser.waitForAngular();
          })
          .then(function () {
            gridTestUtils.expectRowCount( 'grid1', 5 );
            gridTestUtils.expectRowValuesMatch( 'grid1', 4, [ 'New 5', 'Person 5', 'abc', 'male' ] );
          });
    
        element( by.id( "addData" ) ).click()
          .then(function () {
            return browser.waitForAngular();
          })
          .then(function () {
            gridTestUtils.expectRowCount( 'grid1', 6 );
            gridTestUtils.expectRowValuesMatch( 'grid1', 5, [ 'New 6', 'Person 6', 'abc', 'male' ] );
          });
      });
    
      it('remove data and data changes', function () {
        gridTestUtils.expectRowCount( 'grid1', 4 );
    
        element( by.id( "removeFirstRow" ) ).click()
          .then(function () {
            return browser.waitForAngular();
          })
          .then(function () {
            gridTestUtils.expectRowCount( 'grid1', 3 );
            gridTestUtils.expectRowValuesMatch( 'grid1', 0, [ 'Lorraine', 'Wise', 'Comveyer', 'female' ] );
          });
      });
    });

});

  describe("tutorial/113_adding_and_removing_columns", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/113_adding_and_removing_columns");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    describe( '113 dynamically changing columns', function() {
      it('grid should have two visible columns', function () {
        gridTestUtils.expectHeaderColumnCount( 'grid1', 2 );
      });
    
      it('add and remove columns from end, grid updates accordingly', function () {
        element(by.id('button_add')).click();
        gridTestUtils.expectHeaderColumnCount( 'grid1', 3 );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 1, 'Gender' );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 2, 'Company' );
    
        element(by.id('button_remove')).click();
        gridTestUtils.expectHeaderColumnCount( 'grid1', 2 );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 1, 'Gender' );
      });      
    
      it('add and remove columns in middle, grid updates accordingly', function () {
        element(by.id('button_splice')).click();
        gridTestUtils.expectHeaderColumnCount( 'grid1', 3 );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 1, 'Company' );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 2, 'Gender' );
    
        element(by.id('button_unsplice')).click();
        gridTestUtils.expectHeaderColumnCount( 'grid1', 2 );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 1, 'Gender' );
      }); 
      
      it('toggle column 0 visible should make column appear and disappear', function () {
        element(by.id('button_toggle_visible')).click();
        gridTestUtils.expectHeaderColumnCount( 'grid1', 1 );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 0, 'Gender' );
    
        element(by.id('button_toggle_visible')).click();
        gridTestUtils.expectHeaderColumnCount( 'grid1', 2 );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 0, 'Name' );
      });     
    
      it('toggle display name should change column header', function () {
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 1, 'Gender' );
        element(by.id('button_toggle_display_name')).click();
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 1, 'GENDER' );
        element(by.id('button_toggle_display_name')).click();
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 1, 'Gender' );
      });     
    });    

});

  describe("tutorial/114_row_header", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/114_row_header");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    describe( '114 add row header', function() {
      it('grid should have two visible columns, and one pinned column', function () {
        gridTestUtils.expectHeaderLeftColumnCount( 'grid1', 1 );
        gridTestUtils.expectHeaderColumnCount( 'grid1', 2 );
      });
    });    

});

  describe("tutorial/115_headerCellClass", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/115_headerCellClass");
    });
  
      var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    
      describe( '115 header cell class', function() {
        it('grid should have two visible columns', function () {
          gridTestUtils.expectHeaderColumnCount( 'grid1', 2 );
        });
    
        it('cell classes', function () {
          // blue for header 0
          expect( gridTestUtils.headerCell( 'grid1', 0 ).getCssValue('color')).toEqual('rgba(0, 0, 255, 1)');
    
          // header 2 starts with no coloring, but colors when sort is ASC
          expect( gridTestUtils.headerCell( 'grid1', 1 ).getCssValue('color')).toEqual('rgba(44, 62, 80, 1)', 'normal foreground');
    
          gridTestUtils.clickHeaderCell( 'grid1', 1 );
          expect( gridTestUtils.headerCell( 'grid1', 1 ).getCssValue('color')).toEqual('rgba(255, 0, 0, 1)', 'red highlight');
    
        });
      });

});

  describe("tutorial/116_fonts_and_installation", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/116_fonts_and_installation");
    });
  
});

  describe("tutorial/117_tooltips", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/117_tooltips");
    });
  
      var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');

});

  describe("tutorial/120_RTL", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/120_RTL");
    });
  
});

  describe("tutorial/121_grid_menu", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/121_grid_menu");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    
    describe('grid menu', function() {
      gridTestUtils.firefoxReload();
    
      it('grid1 should have three visible columns', function () {
        gridTestUtils.expectHeaderColumnCount( 'grid1', 3 );
      });
    
      it('grid1 grid menu should have 8 items', function () {
        gridTestUtils.expectVisibleGridMenuItems( 'grid1', 7 );
      });
    
      it('grid1 hide then show company column', function () {
        gridTestUtils.expectHeaderColumnCount( 'grid1', 3 );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 0, 'Name' );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 1, 'Gender' );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 2, 'Company' );
    
        gridTestUtils.clickGridMenuItem( 'grid1', 12 )   // there are some hidden menu items, this is company_hide
          .then(function () {
            gridTestUtils.expectHeaderColumnCount( 'grid1', 2 );
            gridTestUtils.expectHeaderCellValueMatch( 'grid1', 0, 'Name' );
            gridTestUtils.expectHeaderCellValueMatch( 'grid1', 1, 'Gender' );
    
            return gridTestUtils.unclickGridMenu( 'grid1');  // menu stays open if change columns
          })
          .then(function() {
            return gridTestUtils.clickGridMenuItem( 'grid1', 13 );  // there are some hidden menu items, this is company_show
          })
          .then(function() {
            gridTestUtils.expectHeaderColumnCount( 'grid1', 3 );
            gridTestUtils.expectHeaderCellValueMatch( 'grid1', 0, 'Name' );
            gridTestUtils.expectHeaderCellValueMatch( 'grid1', 1, 'Gender' );
            gridTestUtils.expectHeaderCellValueMatch( 'grid1', 2, 'Company' );
          });
      });
    });

});

  describe("tutorial/122_accessibility", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/122_accessibility");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    var GridObjectTest = require('../../test/e2e/gridObjectTestUtils.spec.js');
    var grid1 = new GridObjectTest('grid1');
    var grid2 = new GridObjectTest('grid2');
    var grid3 = new GridObjectTest('grid3');
    describe('accessibility tests', function(){
      // Reload the page before each test if on Firefox. Chrome does it automatically.
      gridTestUtils.firefoxReload();
    
      var expectToBeFocused = function(element){
        return expect(element.getInnerHtml()).
          toBe(browser.driver.switchTo().activeElement().getInnerHtml());
      };
    
      describe('first grid on page, no virtual data.', function(){
        describe('when column menu clicked', function(){
          it('should set focus to the first element in the menu', function(){
            grid1.clickColumnMenu(0).then(function(){
              var columnMenu = grid1.getGrid().element(by.css( '.ui-grid-column-menu' ));
              var closeButton = columnMenu.element( by.css( '.ui-grid-menu-close-button' ) );
              return expectToBeFocused(closeButton);
            });
          });
        });
        describe('when column menu is closed by sort', function(){
          it('should return the focus back to the menu button', function(){
            grid1.clickColumnMenuSortAsc(0).then(function(){
              var menuButton = grid1.headerCell(0).element( by.css( '.ui-grid-column-menu-button' ));
              return expectToBeFocused(menuButton);
            })
          });
        });
        describe('when column menu is closed by hiding the column', function(){
          it('should move the focus to the nearest header button unless there is no column then should move to the grid menu', function(){
            function hideColCheckFocusedCol(colNum, focusedNumb){
              return grid1.clickColumnMenuHide(colNum).then(function(){
                var gridMenuButton = grid1.headerCell(focusedNumb).element( by.css( '.ui-grid-header-cell-primary-focus:focus' ));
                return expectToBeFocused(gridMenuButton);
              });
            };
            hideColCheckFocusedCol(0, 0)
            .then(function(){
              hideColCheckFocusedCol(3, 2)
              .then(function(){
                hideColCheckFocusedCol(1, 0)
                .then(function(){
                  hideColCheckFocusedCol(0, 0)
                  .then(function(){
                    return grid1.clickColumnMenuHide(0).then(function(){
                      var gridMenuButton = grid1.getGrid().element( by.css( '.ui-grid-menu-button div[role="button"]' ));
                      return expectToBeFocused(gridMenuButton);
                    });
                  });
                });
              });
            });
          });
        });
        describe('when a filter is removed with the remove filter button', function(){
          it('should move the focus onto the filter input field', function(){
            var colNum = 0;
            return grid1.enterFilterInColumn(colNum, 'Cox').then(function(){
              return grid1.cancelFilterInColumn(colNum).then(function(){
                var filterInput = grid1.headerCell(colNum).element( by.css( '.ui-grid-filter-input' ) );
                expectToBeFocused(filterInput);
              });
            });
          });
        });
      });
    });

});

  describe("tutorial/190_large_dataset", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/190_large_dataset");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    describe( '190 large dataset', function() {
      it('grid should have five visible columns', function () {
        gridTestUtils.expectHeaderColumnCount( 'grid1', 5 );
      });
    
      it('column headers as expected', function () {
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 0, 'Id' );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 1, 'Name' );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 2, 'Age' );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 3, 'Address.City' );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 4, 'Age Again' );
      });
    
      it('first couple of rows as expected', function () {
        gridTestUtils.expectRowValuesMatch( 'grid1', 0, [ '0', 'Ramsey Cummings', '52', 'Glendale', '52' ] );
        gridTestUtils.expectRowValuesMatch( 'grid1', 1, [ '1', 'Stefanie Huff', '70', 'Beaverdale', '70' ] );
      });
    });

});

  describe("tutorial/191_horizontal_scrolling", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/191_horizontal_scrolling");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    describe( '191 horizontal scrolling', function() {
      gridTestUtils.firefoxReload();
      it('check first couple of headers and cells - make sure grid has rendered', function () {
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 0, 'Col0' );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 1, 'Col1' );
        gridTestUtils.expectHeaderCellValueMatch( 'grid1', 2, 'Col2' );
    
        gridTestUtils.expectCellValueMatch( 'grid1', 0, 0, 'r0c0' );
        gridTestUtils.expectCellValueMatch( 'grid1', 1, 0, 'r1c0' );
        gridTestUtils.expectCellValueMatch( 'grid1', 2, 0, 'r2c0' );
        gridTestUtils.expectCellValueMatch( 'grid1', 0, 1, 'r0c1' );
        gridTestUtils.expectCellValueMatch( 'grid1', 1, 1, 'r1c1' );
      });
    
    //      it('scroll right', function () {
        // still working out how to get protractor to scroll an element
    //      });
    });

});

  describe("tutorial/200_features", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/200_features");
    });
  
});

  describe("tutorial/201_editable", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/201_editable");
    });
  
});

  describe("tutorial/202_cellnav", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/202_cellnav");
    });
  
});

  describe("tutorial/203_pinning", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/203_pinning");
    });
  
});

  describe("tutorial/204_column_resizing", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/204_column_resizing");
    });
  
});

  describe("tutorial/205_row_editable", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/205_row_editable");
    });
  
});

  describe("tutorial/206_exporting_data", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/206_exporting_data");
    });
  
});

  describe("tutorial/207_importing_data", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/207_importing_data");
    });
  
});

  describe("tutorial/208_save_state", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/208_save_state");
    });
  
      var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
      describe( '208 save state', function() {
        gridTestUtils.firefoxReload();
        var gridId = 'gridSaveState';
        beforeEach(function(){
          // For some reason a second refresh is necessary for the correct number of columns to be returned in this test.
          browser.refresh();
        });
    
        it('Should start with three header columns', function(){
          gridTestUtils.expectHeaderColumnCount( gridId, 3 );
        });
    
        it('Should have the correct header names', function(){
          gridTestUtils.expectHeaderCellValueMatch( gridId, 0, 'Name' );
          gridTestUtils.expectHeaderCellValueMatch( gridId, 1, 'Gender' );
          gridTestUtils.expectHeaderCellValueMatch( gridId, 2, 'Company' );
        });
    
        it('Should have an expected value in the top left corner cell', function(){
          gridTestUtils.expectCellValueMatch( gridId, 0, 0, 'Ethel Price' );
        });
    
        it('Set a sort and a filter, save state, hide a column and remove sort and filter, set another sort and filter, restore the state', function () {
    
          gridTestUtils.clickColumnMenuSortAsc( gridId, 2 )
            .then(function () {
              return gridTestUtils.enterFilterInColumn( gridId, 1, 'female' );
            })
            .then(function () {
              gridTestUtils.expectCellValueMatch( gridId, 0, 0, 'Freda Mason' );
            })
            .then(function () {
              // return element(by.id('save')).click();
              return gridTestUtils.click(element(by.id('save')));
            })
            .then(function () {
              return gridTestUtils.clickColumnMenuRemoveSort( gridId, 2 );
            })
            .then(function () {
              return gridTestUtils.cancelFilterInColumn( gridId, 1 );
            })
            .then(function () {
              gridTestUtils.expectCellValueMatch( gridId, 0, 0, 'Ethel Price' );
            })
            .then(function () {
              return gridTestUtils.clickColumnMenuSortAsc( gridId, 0 );
            })
            .then(function () {
              return gridTestUtils.enterFilterInColumn( gridId, 2, 'Gee' );
            })
            .then(function () {
              return gridTestUtils.clickColumnMenuHide( gridId, 1 );
            })
            .then(function () {
              gridTestUtils.expectCellValueMatch( gridId, 0, 0, 'Alexander Foley' );
              gridTestUtils.expectHeaderColumnCount( gridId, 2 );
            })
    
            .then(function () {
              return element(by.id('restore')).click();
            })
            .then(function () {
              gridTestUtils.expectHeaderColumnCount( gridId, 3 );
              gridTestUtils.expectCellValueMatch( gridId, 0, 0, 'Freda Mason' );
            });
        });
    
      });

});

  describe("tutorial/209_grouping", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/209_grouping");
    });
  
      var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
      describe( '209 grouping', function() {
      });

});

  describe("tutorial/210_selection", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/210_selection");
    });
  
});

  describe("tutorial/212_infinite_scroll", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/212_infinite_scroll");
    });
  
});

  describe("tutorial/213_auto_resizing", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/213_auto_resizing");
    });
  
});

  describe("tutorial/214_pagination", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/214_pagination");
    });
  
});

  describe("tutorial/215_treeView", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/215_treeView");
    });
  
      var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
      describe( '215 tree view', function() {
      });

});

  describe("tutorial/216_expandable_grid", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/216_expandable_grid");
    });
  
});

  describe("tutorial/217_column_moving", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/217_column_moving");
    });
  
});

  describe("tutorial/299_third_party_features", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/299_third_party_features");
    });
  
});

  describe("tutorial/301_editableOnFocus", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/301_editableOnFocus");
    });
  
});

  describe("tutorial/302_custom_header", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/302_custom_header");
    });
  
});

  describe("tutorial/303_customizing_column_menu", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/303_customizing_column_menu");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    
    describe('column menus', function() {
      // Reload the page before each test if on Firefox. Chrome does it automatically.
      gridTestUtils.firefoxReload();
    
      it('grid1 should have three visible columns', function () {
        gridTestUtils.expectHeaderColumnCount( 'grid1', 3 );
      });
    
      it('no column menu on first column, including from long press', function () {
        var headerCell = gridTestUtils.headerCell( 'grid1', 0 );
        expect( headerCell.all( by.css( '.ui-grid-column-menu-button' ) ).count()).toEqual(0);
    
        browser.actions()
          .mouseDown(headerCell, protractor.Button.LEFT)
          .perform()
          .then(function () {
            return browser.sleep(550);  // 500ms for long press
          })
          .then(function () {
            return browser.actions().mouseUp(headerCell, protractor.Button.LEFT).perform()
          })
          .then(function () {
            var columnMenu = element( by.id( 'grid1' ) ).element( by.css( '.ui-grid-column-menu' ) ).all( by.css( '.ui-grid-menu-inner' ));
            expect(columnMenu.count()).toEqual(0);
          })
      });
    
      it('3 menu items in second column, implying no hide option and no remove sort option', function () {
        gridTestUtils.expectVisibleColumnMenuItems( 'grid1', 1, 3 );
      });
    
      it('Long press opens menu in second column', function () {
        var headerCell = gridTestUtils.headerCell( 'grid1', 1 );
    
        browser.actions()
          .mouseDown(headerCell, protractor.Button.LEFT)
          .perform()
          .then(function () {
            return browser.sleep(550);  // 500ms for long press
          })
          // Wait for element to be present b/c Firefox is kinda slow
          .then(function () {
            var el = element( by.id( 'grid1' ) ).element( by.css( '.ui-grid-column-menu' ) ).element( by.css( '.ui-grid-menu-inner' ));
    
            return browser.wait(function () {
              return el.isPresent();
            }, 2000);
          })
          .then(function () {
            return browser.actions().mouseUp(headerCell, protractor.Button.LEFT).perform()
          })
          .then(function () {
            var columnMenu = element( by.id( 'grid1' ) ).element( by.css( '.ui-grid-column-menu' ) ).element( by.css( '.ui-grid-menu-inner' ));
            expect(columnMenu.isDisplayed()).toEqual(true, 'column menu should be displayed');
          });
      });
    
      it('Column 2 rotates through sort ASC and sort DESC, but no sort null', function () {
        gridTestUtils.expectCellValueMatch( 'grid1', 0, 1, 'female' );
        gridTestUtils.expectCellValueMatch( 'grid1', 1, 1, 'female' );
        gridTestUtils.expectCellValueMatch( 'grid1', 2, 1, 'female' );
        gridTestUtils.expectCellValueMatch( 'grid1', 3, 1, 'female' );
    
        gridTestUtils.clickHeaderCell( 'grid1', 1 )
          .then(function () {
            gridTestUtils.expectCellValueMatch( 'grid1', 0, 1, 'male' );
            gridTestUtils.expectCellValueMatch( 'grid1', 1, 1, 'male' );
            gridTestUtils.expectCellValueMatch( 'grid1', 2, 1, 'male' );
            gridTestUtils.expectCellValueMatch( 'grid1', 3, 1, 'male' );
    
            return gridTestUtils.clickHeaderCell( 'grid1', 1 );
          })
          .then(function () {
            gridTestUtils.expectCellValueMatch( 'grid1', 0, 1, 'female' );
            gridTestUtils.expectCellValueMatch( 'grid1', 1, 1, 'female' );
            gridTestUtils.expectCellValueMatch( 'grid1', 2, 1, 'female' );
            gridTestUtils.expectCellValueMatch( 'grid1', 3, 1, 'female' );
          });
      });
    
      it('7 visible items in the third column, implying hide option', function () {
        gridTestUtils.expectVisibleColumnMenuItems( 'grid1', 2, 7 );
      });
    
      it('click header to sort third column, 8 visible items in the third column, implying remove sort option', function () {
        gridTestUtils.clickHeaderCell( 'grid1', 2 )
          .then(function () {
            gridTestUtils.expectVisibleColumnMenuItems( 'grid1', 2, 8 );
          });
      });
    });

});

  describe("tutorial/304_two_grids", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/304_two_grids");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    describe( '211 two grids', function() {
      it('check first grid has rendered', function () {
        gridTestUtils.expectHeaderColumnCount( 'firstGrid', 3 );
      });
    
      it('check second grid has rendered', function () {
        gridTestUtils.expectHeaderColumnCount( 'secondGrid', 3 );
      });
    
    //      it('check that menus display over the correct grid', function () {
        // still working out how to get protractor to scroll an element
    //      });
    });

});

  describe("tutorial/305_appScope", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/305_appScope");
    });
  
});

  describe("tutorial/306_custom_filters", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/306_custom_filters");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');

});

  describe("tutorial/307_external_sorting", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/307_external_sorting");
    });
  
      var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    
      describe('307 tutorial', function() {
        // Reload the page before each test if on Firefox. Chrome does it automatically.
        gridTestUtils.firefoxReload();
    
        it('grid should have three visible columns', function () {
          gridTestUtils.expectHeaderColumnCount( 'grid1', 3 );
        });
    
        it('header values should be as expected', function () {
          gridTestUtils.expectHeaderCellValueMatch( 'grid1', 0, 'Name' );
          gridTestUtils.expectHeaderCellValueMatch( 'grid1', 1, 'Gender' );
          gridTestUtils.expectHeaderCellValueMatch( 'grid1', 2, 'Company' );
        });
    
        it('grid should be unsorted by default', function () {
          gridTestUtils.expectCellValueMatch( 'grid1', 0, 0, 'Ethel Price' );
          gridTestUtils.expectCellValueMatch( 'grid1', 1, 0, 'Claudine Neal' );
        });
    
        it('sort by name by clicking header', function () {
          gridTestUtils.clickHeaderCell( 'grid1', 0 )
            .then(function () {
              gridTestUtils.expectCellValueMatch( 'grid1', 0, 0, 'Alexander Foley' );
              gridTestUtils.expectCellValueMatch( 'grid1', 1, 0, 'Alisha Myers' );
            });
        });
    
        it('reverse sort by name by clicking header', function () {
          gridTestUtils.clickHeaderCell( 'grid1', 0 )
            .then(function () {
              return gridTestUtils.clickHeaderCell( 'grid1', 0 );
            })
            .then(function () {
              gridTestUtils.expectCellValueMatch( 'grid1', 0, 0, 'Yvonne Parsons' );
              gridTestUtils.expectCellValueMatch( 'grid1', 1, 0, 'Woods Key' );
            });
        });
    
        it('return to original sort by name by clicking header', function () {
          gridTestUtils.clickHeaderCell( 'grid1', 0 )
            .then(function () {
              return gridTestUtils.clickHeaderCell( 'grid1', 0 );
            })
            .then(function () {
              return gridTestUtils.clickHeaderCell( 'grid1', 0 );
            })
            .then(function () {
              gridTestUtils.expectCellValueMatch( 'grid1', 0, 0, 'Ethel Price' );
              gridTestUtils.expectCellValueMatch( 'grid1', 1, 0, 'Claudine Neal' );
            });
        });
    
        it('sort ignored on second column', function() {
          gridTestUtils.clickHeaderCell( 'grid1', 1 )
            .then(function () {
              gridTestUtils.expectCellValueMatch( 'grid1', 0, 0, 'Ethel Price' );
              gridTestUtils.expectCellValueMatch( 'grid1', 1, 0, 'Claudine Neal' );
            });
        });
    
        it('sort disabled on last column', function() {
          gridTestUtils.clickHeaderCell( 'grid1', 2 )
            .then(function () {
              gridTestUtils.expectCellValueMatch( 'grid1', 0, 0, 'Ethel Price' );
              gridTestUtils.expectCellValueMatch( 'grid1', 1, 0, 'Claudine Neal' );
            });
        });
      });

});

  describe("tutorial/308_external_filtering", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/308_external_filtering");
    });
  
});

  describe("tutorial/309_editable_with_cellnav", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/309_editable_with_cellnav");
    });
  
});

  describe("tutorial/311_importing_data_with_rowedit", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/311_importing_data_with_rowedit");
    });
  
});

  describe("tutorial/312_exporting_data_complex", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/312_exporting_data_complex");
    });
  
});

  describe("tutorial/313_custom_interpolation_symbols", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/313_custom_interpolation_symbols");
    });
  
});

  describe("tutorial/314_external_pagination", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/314_external_pagination");
    });
  
});

  describe("tutorial/315_editable_cascading_dropdown", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/315_editable_cascading_dropdown");
    });
  
});

  describe("tutorial/316_dynamic_data_changes", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/316_dynamic_data_changes");
    });
  
});

  describe("tutorial/317_custom_templates", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/317_custom_templates");
    });
  
});

  describe("tutorial/319_complex_trees", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/319_complex_trees");
    });
  
      var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
      describe( '215 tree view', function() {
      });

});

  describe("tutorial/320_complex_grouping", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/320_complex_grouping");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    describe( '209 grouping', function() {
    });

});

  describe("tutorial/321_singleFilter", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/321_singleFilter");
    });
  
});

  describe("tutorial/322_validation", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/322_validation");
    });
  
});

  describe("tutorial/401_AllFeatures", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/401_AllFeatures");
    });
  
    var gridTestUtils = require('../../test/e2e/gridTestUtils.spec.js');
    
    describe('a grid with all features', function () {
      /*
       * This prevents protractor from throwing a Stale Element Reference errors when
       * the protractor accessibility tests run.
       */
      afterEach(function(){
        browser.refresh();
      });
    
      it('should not duplicate the menu options for pinning when resizing a column', function () {
        element( by.id('refreshButton') ).click();
        gridTestUtils.resizeHeaderCell( 'grid1', 1 );
        gridTestUtils.expectVisibleColumnMenuItems( 'grid1', 1, 12);
      });
    });

});

  describe("tutorial/402_GridIsScrolling", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/402_GridIsScrolling");
    });
  
});

  describe("tutorial/403_end_to_end_testing", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/403_end_to_end_testing");
    });
  
});

  describe("tutorial/404_large_data_sets_and_performance", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/404_large_data_sets_and_performance");
    });
  
    

});

  describe("tutorial/405_exporting_all_data_complex", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/405_exporting_all_data_complex");
    });
  
});

  describe("tutorial/499_FAQ", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/499_FAQ");
    });
  
});

  describe("tutorial/index", function() {
    beforeEach(function() {
      browser.driver.get("http://127.0.0.1:9999/docs/#/tutorial/index");
    });
  
});

});