<?php declare(strict_types=1);

namespace DavidBadura\XsdBuilder;

class Attribute
{
    private string $name;
    private string $type;
    private ?string $default = null;

    private function __construct()
    {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function setDefault($default): void
    {
        $this->default = $default;
    }

    public function default(): ?string
    {
        return $this->default;
    }

    public static function string(string $name): self
    {
        return self::create($name, Type::STRING);
    }

    public static function decimal(string $name): self
    {
        return self::create($name, Type::DECIMAL);
    }

    public static function integer(string $name): self
    {
        return self::create($name, Type::INTEGER);
    }

    public static function boolean(string $name): self
    {
        return self::create($name, Type::BOOLEAN);
    }

    public static function date(string $name): self
    {
        return self::create($name, Type::DATE);
    }

    public static function time(string $name): self
    {
        return self::create($name, Type::TIME);
    }

    public static function create(string $name, string $type): self
    {
        $self = new self();
        $self->name = $name;
        $self->type = $type;

        return $self;
    }

    public function createDomElement(\DOMDocument $dom): \DOMElement
    {
        $el = $dom->createElement('xs:attribute');
        $el->setAttribute('name', $this->name);
        $el->setAttribute('type', $this->type);

        if ($this->default) {
            $el->setAttribute('default', $this->default);
        }

        return $el;
    }
}
