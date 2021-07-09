<?php declare(strict_types=1);

use PhpParser\{Node, ParserFactory, NodeTraverser};

require __DIR__ . '/vendor/autoload.php';

$conflicts = new ArrayObject();

$candidates = json_decode(file_get_contents(__DIR__ . '/resources/candidates.json'));

$path = __DIR__ . '/vendor/drupal/commerce';
$fileFinder = new \DrupalStaticFun\File\FileFinder();
$result = $fileFinder->findFiles([
  __DIR__ . '/vendor/drupal/commerce',
  __DIR__ . '/vendor/drupal/address',
  __DIR__ . '/vendor/drupal/entity',
  __DIR__ . '/vendor/drupal/inline_entity_form',
  __DIR__ . '/vendor/drupal/profile',
  __DIR__ . '/vendor/drupal/state_machine',
  __DIR__ . '/vendor/drupal/token',

]);

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
$traverser = new NodeTraverser;
$traverser->addVisitor(new \PhpParser\NodeVisitor\NameResolver());
$traverser->addVisitor(new class($candidates, $conflicts) extends \PhpParser\NodeVisitorAbstract {

  public function __construct(
    private array $candidates,
    private ArrayObject $conflicts
  ) {}

  public function enterNode(\PhpParser\Node $node) {
    if (!$node instanceof Node\Stmt\Class_) {
      return null;
    }
    if ($node->extends === null) {
      return null;
    }
    $extends = $node->extends;
    $test = $extends->toString();

    $assert = array_search($test, $this->candidates, TRUE);
    if ($assert) {
      $this->conflicts->append($this->candidates[$assert]);
    }
  }

});

foreach ($result->getFiles() as $path) {
  $code = file_get_contents($path);
  $ast = $parser->parse($code);
  $traverser->traverse($ast);
}

file_put_contents(__DIR__ . '/resources/commerce.json',
  json_encode($conflicts->getArrayCopy(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
