<?php declare(strict_types=1);

namespace DrupalStaticFun;

use PhpParser\Node;

final class ClassCollector {

  /**
   * @var Node\Stmt\Class_[]
   */
  private array $classes = [];

  public function addClass(Node\Stmt\Class_ $class): void {
    // @todo make sure we don't duplicate.
    $this->classes[] = $class;
  }

  /**
   * @return array
   */
  public function getClasses(): array {
    return $this->classes;
  }

}
