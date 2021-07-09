<?php

namespace DrupalStaticFun\Tests\File;

use DrupalStaticFun\File\FileFinderResult;
use PHPUnit\Framework\TestCase;

class FileFinderResultTest extends TestCase {

  public function testGetFiles() {
    $sut = new FileFinderResult(['foo.php']);
    $this->assertEquals(['foo.php'], $sut->getFiles());
  }

}
