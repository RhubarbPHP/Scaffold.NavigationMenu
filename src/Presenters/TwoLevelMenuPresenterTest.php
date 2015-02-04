<?php

namespace Rhubarb\Scaffolds\NavigationMenu\Presenters;

use Rhubarb\Crown\Context;
use Rhubarb\Crown\Layout\LayoutModule;
use Rhubarb\Stem\Models\Model;
use Rhubarb\Stem\Repositories\Repository;
use Rhubarb\Stem\Schema\SolutionSchema;
use Rhubarb\Crown\Module;
use Rhubarb\Leaf\Views\UnitTestView;
use Rhubarb\Scaffolds\NavigationMenu\MenuItem;
use Rhubarb\Scaffolds\NavigationMenu\MenuItemTest;

include_once(__DIR__ . "/../MenuItemTest.php");

class TwoLevelMenuPresenterTest extends MenuItemTest
{
	public function testMenuViewGetsCorrectMenus()
	{
		$request = Context::CurrentRequest();
		$request->UrlPath = "/";

		$view = new UnitTestView();

		$menu = new TwoLevelMenuPresenter();
		$menu->AttachMockView( $view );

		$menu->Test();

		$this->assertCount( 5, $view->primaryMenuItems );
		$this->assertCount( 0, $view->secondaryMenuItems );

		$this->assertEquals( "/companies/", $view->primaryMenuItems[1]->Url );
		$this->assertEquals( "/setup/", $view->primaryMenuItems[2]->Url );

		$request->UrlPath = "/companies/";

		$menu->Test();

		$this->assertCount( 5, $view->primaryMenuItems );
		$this->assertEquals( "History", $view->secondaryMenuItems[1]->MenuName );

		$request->UrlPath = "/companies/history/";

		$menu->Test();

		$this->assertCount( 5, $view->primaryMenuItems );
		$this->assertEquals( "History", $view->secondaryMenuItems[1]->MenuName );

		$request->UrlPath = "/companies/history/ancient/";

		$menu->Test();

		$this->assertEquals( "Ancient History", $view->secondaryMenuItems[0]->MenuName );
		$this->assertEquals( 5, $view->activePrimaryMenuItemId );
		$this->assertEquals( 8, $view->activeSecondaryMenuItemId );

		$request->UrlPath = "/setup/help/closing/";

		$menu->Test();

		$this->assertEquals( 6, $view->activeSecondaryMenuItemId );
	}
}