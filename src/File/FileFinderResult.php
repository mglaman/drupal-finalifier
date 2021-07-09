<?php declare(strict_types=1);

namespace DrupalStaticFun\File;

final class FileFinderResult {

  private array $files;

  public function __construct(array $files) {
    $this->files = $files;
  }

  /**
   * @return array
   */
  public function getFiles(): array {
    return $this->files;
  }

}
