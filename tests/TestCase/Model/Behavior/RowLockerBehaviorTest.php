<?php
declare(strict_types=1);

namespace RowLocker\Test\TestCase\Model\Behavior;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use RowLocker\LockableInterface;
use RowLocker\LockableTrait;

/**
 * Class TestEntity
 *
 * @package RowLocker\Test\TestCase\Model\Behavior
 */
class TestEntity extends Entity implements LockableInterface
{
    use LockableTrait;
}

/**
 * RowLocker\Model\Behavior\RowLockerBehavior Test Case
 */
class RowLockerBehaviorTest extends TestCase
{
    protected $table;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.RowLocker.Articles'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp() : void
    {
        parent::setUp();
        $this->table = TableRegistry::getTableLocator()->get('Articles', [
                'entityClass' => TestEntity::class
            ]);
        $this->table->addBehavior('RowLocker.RowLocker');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown() : void
    {
        parent::tearDown();
    }

    /**
     * testUnlocked
     *
     * @return void
     */
    public function testUnlocked() : void
    {
        $results = $this->table->find('unlocked', ['lockingUser' => 'lorenzo'])->toArray();
        $this->assertCount(3, $results);

        $article = $results[0];
        $article->lock('someone-else');
        $this->table->save($article);

        $results = $this->table->find('unlocked', ['lockingUser' => 'lorenzo'])->toArray();
        $this->assertCount(2, $results);

        $results = $this->table->find('unlocked', ['lockingUser' => 'someone-else'])->toArray();
        $this->assertCount(3, $results);
    }
}
