<?php
$areacode='SE19';
$I = new TestGuy($scenario);
$I->wantTo('perform actions and see result');
$I->amInPath('/');
$I->fillField('areacode',$areacode);
$I->click('Search');
//lets test on the JSON result containing the ID as this will only exist for a valid postcode
$I->see('{"ShortResultText":"'.$areacode.'","Restaurants":[{"Id"');
