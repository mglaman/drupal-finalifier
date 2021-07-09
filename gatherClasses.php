<?php declare(strict_types=1);

use DrupalStaticFun\Visitors\NoExtendsOrAbstractTraverser;
use PhpParser\{ParserFactory, NodeTraverser};

require __DIR__ . '/vendor/autoload.php';

$collector = new \DrupalStaticFun\ClassCollector();

$visitors = [
  new \PhpParser\NodeVisitor\NameResolver(),
  new NoExtendsOrAbstractTraverser($collector),
];

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
$traverser = new NodeTraverser;
foreach ($visitors as $visitor) {
  $traverser->addVisitor($visitor);
}

try {
  $path = __DIR__ . '/vendor/drupal/core/lib/Drupal/Core';
  $fileFinder = new \DrupalStaticFun\File\FileFinder();
  $result = $fileFinder->findFiles([$path]);

  foreach ($result->getFiles() as $path) {
    $code = file_get_contents($path);
    $ast = $parser->parse($code);
    $traverser->traverse($ast);
  }

  $classes = $collector->getClasses();
  $prettyNames = array_map(static function (\PhpParser\Node\Stmt\Class_ $class_) {
    assert($class_->namespacedName instanceof \PhpParser\Node\Name);
    return $class_->namespacedName->toString();
  }, $classes);

  file_put_contents(__DIR__ . '/resources/candidates.json',
    json_encode($prettyNames, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));

} catch (Error $error) {
  echo "Parse error: {$error->getMessage()}\n";
  return;
}
