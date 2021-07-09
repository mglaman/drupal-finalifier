<?php declare(strict_types=1);

namespace DrupalStaticFun\File;

use Symfony\Component\Finder\Finder;

final class FileFinder {

  private array $extensions = ['php'];

  /**
   * @param string[] $paths
   * @return \DrupalStaticFun\File\FileFinderResult
   */
  public function findFiles(array $paths): FileFinderResult {
    $files = [];
    foreach ($paths as $path) {
      if (!file_exists($path)) {
        throw new \InvalidArgumentException('Path does not exist');
      } elseif (is_file($path)) {
        $files[] = $path;
      } else {
        $finder = new Finder();
        $finder->followLinks();
        foreach ($finder->files()->name('*.{' . implode(',', $this->extensions) . '}')->in($path) as $fileInfo) {
          $files[] = $fileInfo->getPathname();
        }
      }
    }
    return new FileFinderResult($files);
  }

}
