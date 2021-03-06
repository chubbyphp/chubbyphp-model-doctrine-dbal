### Model

#### MyModel

```php
<?php

declare(strict_types=1);

namespace MyProject\Model;

use Chubbyphp\Model\Collection\ModelCollection;
use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\Reference\ModelReference;
use Ramsey\Uuid\Uuid;

final class MyModel implements ModelInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $category;

    /**
     * @var ModelReference
     */
    private $oneToOne;

    /**
     * @var ModelCollection
     */
    private $oneToMany;

    /**
     * @param string|null $id
     * @return MyModel
     */
    public static function create(string $id = null): MyModel
    {
        $myModel = new self;
        $myModel->id = $id ?? (string) Uuid::uuid4();
        $myModel->oneToOne = new ModelReference();
        $myModel->oneToMany = new ModelCollection();

        return $myModel;
    }

    private function __construct() {}

    /**
     * @param array $data
     *
     * @return ModelInterface
     */
    public static function fromPersistence(array $data): ModelInterface
    {
        $model = new self;
        $model->id = $data['id'];
        $model->name = $data['name'];
        $model->category = $data['category'];
        $model->oneToOne = $data['oneToOne'];
        $model->oneToMany = $data['oneToMany'];

        return $model;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): MyModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $category
     * @return self
     */
    public function setCategory(string $category): MyModel
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param MyEmbeddedModel|null $oneToOne
     * @return self
     */
    public function setOneToOne(MyEmbeddedModel $oneToOne = null): MyModel
    {
        $this->oneToOne->setModel($oneToOne);

        return $this;
    }

    /**
     * @return MyEmbeddedModel|ModelInterface|null
     */
    public function getOneToOne()
    {
        return $this->oneToOne->getModel();
    }

    /**
     * @param MyEmbeddedModel[]|array $oneToMany
     * @return $this
     */
    public function setOneToMany(array $oneToMany)
    {
        $this->oneToMany->setModels($oneToMany);

        return $this;
    }

    /**
     * @return MyEmbeddedModel[]|ModelInterface[]|array
     */
    public function getOneToMany()
    {
        return $this->oneToMany->getModels();
    }

    /**
     * @return array
     */
    public function toPersistence(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'oneToOne' => $this->oneToOne,
            'oneToMany' => $this->oneToMany
        ];
    }
}
```

#### MyEmbeddedModel

```php
<?php

declare(strict_types=1);

namespace MyProject\Model;

use Chubbyphp\Model\ModelInterface;
use Ramsey\Uuid\Uuid;

final class MyEmbeddedModel implements ModelInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $modelId;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $modelId
     * @param string|null $id
     * @return MyEmbeddedModel
     */
    public function create(string $modelId, string $id = null): MyEmbeddedModel
    {
        $myEmbeddedModel = new self;
        $myEmbeddedModel->id = $id ?? (string) Uuid::uuid4();
        $myEmbeddedModel->modelId = $modelId;

        return $myEmbeddedModel;
    }

    private function __construct() {}

    /**
     * @param array $data
     *
     * @return MyEmbeddedModel|ModelInterface
     */
    public static function fromPersistence(array $data): ModelInterface
    {
        $model = new self;
        $model->id = $data['id'];
        $model->modelId = $data['modelId'];
        $model->name = $data['name'];

        return $model;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return MyEmbeddedModel
     */
    public function setName(string $name): MyEmbeddedModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function toPersistence(): array
    {
        return [
            'id' => $this->id,
            'modelId' => $this->modelId,
            'name' => $this->name
        ];
    }
}
```
