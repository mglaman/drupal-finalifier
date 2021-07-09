<?php declare(strict_types=1);

namespace DrupalStaticFun\Visitors;

use DrupalStaticFun\ClassCollector;
use PhpParser\{Node, NodeTraverser, NodeVisitorAbstract};

final class NoExtendsOrAbstractTraverser extends NodeVisitorAbstract {

  private ClassCollector $collector;

  public function __construct(ClassCollector $collector) {
    $this->collector = $collector;
  }

  public function enterNode(Node $node): null | int | Node {
    if (!$node instanceof Node\Stmt\Class_) {
      return null;
    }
    if ($node->isAbstract() || $node->isAnonymous() || $node->isFinal()) {
      return null;
    }
    if ($node->extends !== null) {
      return null;
    }
    $this->collector->addClass($node);
    return NodeTraverser::DONT_TRAVERSE_CHILDREN;
  }

}
