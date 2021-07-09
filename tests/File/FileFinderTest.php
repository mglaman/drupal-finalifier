<?php

namespace DrupalStaticFun\Tests\File;

use DrupalStaticFun\File\FileFinder;
use PHPUnit\Framework\TestCase;

class FileFinderTest extends TestCase {

  /**
   * @dataProvider dataPaths
   */
  public function testFindFiles(string $path, array $expected) {
    $sut = new FileFinder();
    $result = $sut->findFiles([$path]);
    $this->assertEquals($expected, $result->getFiles());
  }

  public function dataPaths() {
    return [
      'single file' => [
        __DIR__ . '/../../src/File/FileFinder.php',
        [__DIR__ . '/../../src/File/FileFinder.php']
      ],
      'dir' => [
        __DIR__ . '/../../src/File',
        [
          __DIR__ . '/../../src/File/FileFinder.php',
          __DIR__ . '/../../src/File/FileFinderResult.php',
        ]
      ],
    ];
  }
}
