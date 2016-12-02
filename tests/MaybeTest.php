<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use ThibaudDauce\Maybe\Exceptions\CannotCreateJustWithANullValue;
use ThibaudDauce\Maybe\Maybe;

class MaybeTest extends TestCase
{
    /** @test */
    public function can_create_a_maybe_nothing_with_a_null_value()
    {
        $maybe = new Maybe(null);

        $this->assertTrue($maybe->isNothing());
        $this->assertFalse($maybe->isJust());
    }

    /** @test */
    public function can_create_a_maybe_just_with_a_value()
    {
        $maybe = new Maybe(1);

        $this->assertTrue($maybe->isJust());
        $this->assertFalse($maybe->isNothing());
    }

    /** @test */
    public function can_create_a_maybe_nothing_from_static_nothing()
    {
        $maybe = Maybe::nothing();

        $this->assertTrue($maybe->isNothing());
        $this->assertFalse($maybe->isJust());
    }

    /** @test */
    public function can_create_a_maybe_just_from_static_just_and_a_value()
    {
        $maybe = Maybe::just("Frodo");

        $this->assertTrue($maybe->isJust());
        $this->assertFalse($maybe->isNothing());
    }

    /** @test */
    public function cannot_create_a_maybe_just_from_static_just_and_null()
    {
        try {
            Maybe::just(null);
        } catch (CannotCreateJustWithANullValue $e) {
            return;
        }

        $this->fail("Invalid Just created with a null value.");
    }

    /** @test */
    public function can_act_on_a_just_value()
    {
        $maybe = Maybe::just("Gandalf");
        $fetchedValue = null;

        $maybe->act(function() {
            $this->fail("Nothing action was called for a Just value.");
        }, function($value) use (&$fetchedValue) {
            $fetchedValue = $value;
        });

        $this->assertEquals('Gandalf', $fetchedValue);
    }

    /** @test */
    public function can_act_on_a_nothing()
    {
        $maybe = Maybe::nothing();
        $nothingCalled = false;

        $maybe->act(function() use (&$nothingCalled) {
            $nothingCalled = true;
        }, function($value) {
            $this->fail("Just action was called with {$value} for a Nothing value.");
        });

        $this->assertTrue($nothingCalled);
    }

    /** @test */
    public function can_get_the_default_value_from_a_nothing_with_fromDefault()
    {
        $maybe = Maybe::nothing();

        $value = $maybe->fromDefault('Aragorn');

        $this->assertEquals('Aragorn', $value);
    }

    /** @test */
    public function can_get_just_from_a_just_with_fromDefault()
    {
        $maybe = Maybe::just('Sam');

        $value = $maybe->fromDefault('Aragorn');

        $this->assertEquals('Sam', $value);
    }
}