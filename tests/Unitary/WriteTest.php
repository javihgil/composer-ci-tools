<?php

namespace Jhg\ComposerCiTools\Tests\Unitary;

use Jhg\ComposerCiTools\Write;
use Mockery as m;

/**
 * Class WriteTest
 *
 * @coversDefaultClass Jhg\ComposerCiTools\Write
 */
class WriteTest extends ScriptTestCase
{
    /**
     * @covers ::blockStart
     * @covers ::getName
     * @covers ::configureOptions
     */
    public function testBlockStart()
    {
        Write::blockStart($this->createEvent());
        $this->assertOutputContains('Executing ...');
    }

    /**
     * @covers ::blockStart
     * @covers ::getName
     * @covers ::configureOptions
     */
    public function testBlockStartWithConfig()
    {
        $this->setMockExtra(['write' => [ 'block-start-format' => 'Lala' ] ]);
        Write::blockStart($this->createEvent());
        $this->assertOutputContains('Lala');
    }

    /**
     * @covers ::blockEnd
     * @covers ::getName
     * @covers ::configureOptions
     */
    public function testBlockEnd()
    {
        Write::blockEnd($this->createEvent());
        $this->assertOutputContains('Done!');
    }

    /**
     * @covers ::blockEnd
     * @covers ::getName
     * @covers ::configureOptions
     */
    public function testBlockEndWithConfig()
    {
        $this->setMockExtra(['write' => [ 'block-end-format' => 'Lala' ] ]);
        Write::blockEnd($this->createEvent());
        $this->assertOutputContains('Lala');
    }
}