# Project Saturn Studios - Laravel ES Utilities 🚀

[![Latest Version on Packagist](https://img.shields.io/packagist/v/projectsaturnstudios/pss-event-sourcing.svg?style=flat-square)](https://packagist.org/packages/projectsaturnstudios/pss-event-sourcing)
[![Total Downloads](https://img.shields.io/packagist/dt/projectsaturnstudios/pss-event-sourcing.svg?style=flat-square)](https://packagist.org/packages/projectsaturnstudios/laravel-es-utilities)
<!-- Add other badges here: e.g., Build Status, Code Coverage, License -->

Supercharge your Spatie Event Sourcing with the elegance of Spatie's Laravel-Data objects and the power of Loris Leiva's Laravel Actions. This package is all about making event sourcing in Laravel a smoother, more typed, and downright enjoyable ride.

# 📜 Table of Contents

- [🤔 Why This Package?](#-why-this-package)
- [✨ Features List](#-features-list)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [How to Rock It (Usage Guide)](#-how-to-rock-it-usage-guide)
- [Testing Your Masterpiece](#-testing-your-masterpiece)
- [Advanced Scenarios / Cookbook](#-advanced-scenarios--cookbook)
- [Contributing](#-contributing)
- [Support & Contact](#-support--contact)
- [License](#-license)
- [Credits & Thanks](#-credits--thanks)
- [README Change Log](#-readme-change-log)


# 🤔 Why This Package?

Event sourcing is a powerful pattern, and Spatie's `laravel-event-sourcing` package provides a rock-solid foundation for it in Laravel. So, why add another layer with `laravel-es-utilities`? Glad you asked, partner. This package is all about taking that solid foundation and making your developer experience (DX) even smoother, more robust, and, dare we say, more enjoyable.

Here's the lowdown on what this package brings to your shindig:

*   **🤠 Enhanced Type Safety & Clarity with `DataEvent`s**:
    *   **Problem**: Traditional event payloads can sometimes be loose arrays or objects, leading to uncertainty about their structure.
    *   **Solution**: We integrate `spatie/laravel-data` directly into your event sourcing workflow. By defining your events as strongly-typed `DataEvent` objects (which are essentially `Spatie\LaravelData\Data` objects tailored for event sourcing), you get auto-completion, compile-time(ish) checks via static analysis, and runtime validation. This means fewer bugs and clearer contracts for what your events represent.

*   **🎬 Structured & Testable Commands with `EventCommand`s**:
    *   **Problem**: Command handling logic can sometimes become scattered or less defined.
    *   **Solution**: Leveraging `lorisleiva/laravel-actions`, we promote a pattern of `EventCommand` classes. These are dedicated action classes that encapsulate the logic for validating a command, executing business rules, and ultimately producing a `DataEvent`. This makes your command logic more organized, easier to test in isolation, and reusable.

*   **⚙️ Streamlined Workflow**:
    *   **Problem**: Wiring up custom serializers or ensuring robust event persistence can require boilerplate.
    *   **Solution**: This package comes with:
        *   A pre-configured `EventSerializer` that understands how to handle `DataEvent` objects out-of-the-box.
        *   A `RetryPersistMiddleware` (applied by default via the `event_command()` helper) that automatically retries event persistence on common database issues, adding resilience to your system.
        *   The convenient `event_command()` helper function to dispatch your `EventCommand`s with sensible defaults.

*   **🚀 Improved Developer Experience (DX)**:
    *   **Overall Benefit**: By combining these elements, the goal is to reduce boilerplate, increase clarity, improve testability, and make the overall process of building event-sourced applications in Laravel more intuitive and less error-prone. You get to focus more on your business logic and less on the plumbing.

In short, if you love `spatie/laravel-event-sourcing` but wished for tighter integration with typed data objects and a more structured approach to commands, `laravel-es-utilities` is here to be your new best friend.

## ✨ Features List

This package is packed with goodies to make your event sourcing journey in Laravel smoother. Here are the highlights:

*   **Typed `DataEvent` Objects**:
    *   Leverages `spatie/laravel-data` to allow you to define your events as strongly-typed Data objects.
    *   Events should extend `ProjectSaturnStudios\EventSourcing\Events\DataEvent`.
    *   Benefits: Clear event contracts, auto-completion, validation, and easier refactoring.

*   **Structured `EventCommand` Actions**:
    *   Promotes the use of `lorisleiva/laravel-actions` for handling commands.
    *   Commands can extend the base `ProjectSaturnStudios\EventSourcing\EventCommands\EventCommand`.
    *   Benefits: Encapsulated command logic, improved testability, and reusability of commands.

*   **Convenient `event_command()` Helper**:
    *   A global helper function `event_command(YourEventCommand $command, array $middleware = [...])` to easily dispatch your `EventCommand` instances.
    *   Automatically applies `RetryPersistMiddleware` by default.

*   **Resilient `RetryPersistMiddleware`**:
    *   Spatie Command Bus middleware that automatically retries event persistence if common database issues (like `PDOException` or `CouldNotPersistAggregate`) occur.
    *   Enhances the reliability of your event stream.

*   **Tailored `EventSerializer`**:
    *   A custom event serializer (`ProjectSaturnStudios\EventSourcing\Serializers\EventSerializer`) that understands how to serialize and deserialize your `DataEvent` objects using `spatie/laravel-data`'s capabilities.
    *   Automatically configured by the package's service provider.

*   **`PSSEventSourcingServiceProvider`**:
    *   The package's service provider automatically merges necessary configurations, primarily setting up the custom `EventSerializer`.

*   **Flexible `AlternativeAggregateRoot`**:
    *   An optional base class (`ProjectSaturnStudios\EventSourcing\Aggregates\AlternativeAggregateRoot`) for your aggregates if you need to specify custom event or snapshot repositories.

*   **Helpful `app_queue()` Helper**:
    *   A utility function `app_queue('your-queue-name')` to easily prefix queue names based on the `QS_PREFIX` environment variable, useful for namespacing.

*   **Centralized `DispatchEventCommand` Action**:
    *   An underlying action (`ProjectSaturnStudios\EventSourcing\Actions\DispatchEventCommand`) used by the `event_command()` helper to integrate with Spatie's Command Bus and apply middleware.

## 📦 Installation

You can install the package via Composer:

```bash
composer require projectsaturnstudios/laravel-es-utilities
```

The package leverages Laravel's package auto-discovery, so you typically won't need to manually register the service provider (`ProjectSaturnStudios\EventSourcing\Providers\PSSEventSourcingServiceProvider`).

This service provider will automatically merge the necessary configuration to use the custom `ProjectSaturnStudios\EventSourcing\Serializers\EventSerializer` for handling `DataEvent` objects. This ensures your typed event data is correctly serialized and deserialized using `spatie/laravel-data` capabilities.

**Optional: Publishing Configuration**

If you wish to customize the event sourcing configuration further (beyond what this package provides by default), you can publish Spatie's main event sourcing configuration file. Note that this package already sets the recommended `event_serializer`. If you publish and modify Spatie's config, ensure you retain or re-configure the `event_serializer` to point to `ProjectSaturnStudios\EventSourcing\Serializers\EventSerializer::class` if you want to continue using this package's DataEvent serialization.

To publish Spatie's configuration (if you haven't already for `spatie/laravel-event-sourcing`):
```bash
php artisan vendor:publish --provider="Spatie\EventSourcing\EventSourcingServiceProvider" --tag="event-sourcing-config"
```
This will create a `config/event-sourcing.php` file in your application's config directory.
Again, for this package (`projectsaturnstudios/laravel-es-utilities`) to work as intended with `DataEvent` objects, the `event_serializer` in that published config should be:
```php
// config/event-sourcing.php
// ...
    'event_serializer' => \ProjectSaturnStudios\EventSourcing\Serializers\EventSerializer::class,
// ...
```
Our package attempts to set this for you by default through its own service provider's config merging. Publishing is only needed if you require deeper customization of other Spatie event sourcing settings.

## ⚙️ Configuration

This package is designed to work with minimal configuration out-of-the-box.

### Event Serializer

The most crucial piece of configuration is the `event_serializer`. Our `ProjectSaturnStudios\EventSourcing\Providers\PSSEventSourcingServiceProvider` automatically merges its configuration to set the `event_serializer` in your `config/event-sourcing.php` to use `ProjectSaturnStudios\EventSourcing\Serializers\EventSerializer::class`.

This ensures that your `DataEvent` objects are correctly handled. No manual configuration is typically needed for this.

If you publish the `spatie/laravel-event-sourcing` configuration file (`php artisan vendor:publish --provider="Spatie\EventSourcing\EventSourcingServiceProvider" --tag="event-sourcing-config"`), you'll see this setting:

```php
// config/event-sourcing.php
'event_serializer' => \ProjectSaturnStudios\EventSourcing\Serializers\EventSerializer::class,
```
If you override this, ensure you understand the implications for `DataEvent` serialization.

### Queue Prefixing (`app_queue()` helper)

The `app_queue(string $key): string` helper function allows you to prefix your queue names. It looks for an environment variable:

*   `QS_PREFIX`: If set, this prefix (e.g., `my-app-`) will be prepended to the queue key you provide.
    Example: `app_queue('events')` with `QS_PREFIX=prod` would result in `prod-events`.

This is useful for namespacing queues in different environments or applications sharing a Redis instance.

### RetryPersistMiddleware

The `ProjectSaturnStudios\EventSourcing\Middleware\RetryPersistMiddleware` used by the `event_command()` helper defaults to 3 retry attempts. If you were to use this middleware manually with Spatie's Command Bus, you could configure the number of retries via its constructor:

```php
use ProjectSaturnStudios\EventSourcing\Middleware\RetryPersistMiddleware;

// Example: Manually adding to a command bus
$bus->middleware(new RetryPersistMiddleware(maximumTries: 5));
```
However, when using the `event_command()` helper, it defaults to 3 retries, which is generally a sensible default.

### Other Spatie Event Sourcing Configuration

For all other event sourcing configurations (e.g., projectors, reactors, stored event repository, snapshot repository), please refer to the official `spatie/laravel-event-sourcing` package documentation and its `config/event-sourcing.php` file. This package primarily focuses on enhancing the event and command handling aspects.

## 🚀 How to Rock It (Usage Guide)

Alright, let's get down to brass tacks. Here's how you can use this package to make your event sourcing life a little more zen.

### Defining Your `DataEvent`

First up, you'll want to define your domain events as `DataEvent` objects. These are essentially `Spatie\LaravelData\Data` objects that also extend `ProjectSaturnStudios\EventSourcing\Events\DataEvent`. This base class provides the necessary integration with Spatie's event sourcing system and Laravel Data's features.

**Example:** Let's say we're building a simple "Todo" application and want an event for when a task is created.

```php
// app/Domain/Todo/Events/TodoTaskCreated.php

namespace App\Domain\Todo\Events;

use ProjectSaturnStudios\EventSourcing\Events\DataEvent;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper; // Optional: for snake_case mapping in DB
use Ramsey\Uuid\UuidInterface; // Or string if you prefer

#[MapName(SnakeCaseMapper::class)] // Optional, if you want stored event payload keys to be snake_case
class TodoTaskCreated extends DataEvent
{
    public function __construct(
        public readonly UuidInterface $taskUuid, // Or string for UUID
        public readonly string $description,
        public readonly string $status, // e.g., "pending", "completed"
        public readonly \DateTimeImmutable $createdAt
    ) {}

    // You typically don't need to define an eventType() method here.
    // Spatie's event sourcing often uses the fully qualified class name by default,
    // or you can configure aliases in config/event-sourcing.php if needed.
}
```

**Key Benefits Here:**
*   **Strong Typing**: `taskUuid`, `description`, `status`, and `createdAt` are all typed.
*   **Readonly Properties**: Good practice for immutable event data.
*   **Laravel Data Features**: You can use all of `spatie/laravel-data`'s features like casting, validation attributes (though validation is often done in commands), custom creators, etc., if needed.
*   **Serialization**: The configured `EventSerializer` will automatically use `laravel-data`'s `toJson()` and `from()` methods.

### Crafting Your `EventCommand`

Next, you'll create `EventCommand` classes. These are responsible for taking input, performing any necessary validation or business logic, and then creating the appropriate `DataEvent`. We recommend using `lorisleiva/laravel-actions` for these commands, and they can extend the base `ProjectSaturnStudios\EventSourcing\EventCommands\EventCommand` (though this base class is simple and mainly for type-hinting or namespacing).

**Example:** A command to create our `TodoTask`.

```php
// app/Domain/Todo/Commands/CreateTodoTask.php

namespace App\Domain\Todo\Commands;

use ProjectSaturnStudios\EventSourcing\EventCommands\EventCommand; // Optional base class
use Lorisleiva\LaravelActions\Concerns\AsAction;
use App\Domain\Todo\Events\TodoTaskCreated; // The DataEvent this command will produce
use App\Domain\Todo\Models\TodoTaskAggregate; // Assuming an aggregate for TodoTask
use Ramsey\Uuid\Uuid;

class CreateTodoTask extends EventCommand // Or just use AsAction without extending
{
    use AsAction;

    // Properties to hold the command data, matching the handle method parameters
    public string $description;

    public function __construct(string $description)
    {
        $this->description = $description;
    }

    // The handle method contains the core logic of your command
    public function handle(): void // Command handlers are typically void
    {
        $taskUuid = Uuid::uuid4();
        $initialStatus = 'pending'; // Or from config, etc.
        $currentTime = new \DateTimeImmutable();

        // Create the DataEvent
        $event = new TodoTaskCreated(
            taskUuid: $taskUuid,
            description: $this->description,
            status: $initialStatus,
            createdAt: $currentTime
        );

        // Here, you'd typically interact with an AggregateRoot
        // For a creation command, you might instantiate a new aggregate
        // or use a static factory method on the aggregate.

        /** @var TodoTaskAggregate $todoAggregate */
        // Option 1: Static constructor if your aggregate supports it
        // $todoAggregate = TodoTaskAggregate::createWithEvent($taskUuid, $event);

        // Option 2: Retrieve (if applicable, not for creation usually) then record
        // This pattern is more for updates, but showing the recordThat flow:
        // $todoAggregate = TodoTaskAggregate::retrieve($taskUuid); // Won't exist for creation
        // $todoAggregate->recordThat($event);

        // For creation, a common pattern with Spatie's package is to have
        // the aggregate handle its own instantiation via a command-like method
        // or by applying a creation event to a fresh instance.

        // Let's assume a simplified aggregate method for this example:
        // This is a conceptual example; your aggregate's API may differ.
        TodoTaskAggregate::new($taskUuid) // Gets a new aggregate instance
            ->recordAndApply($event)    // Custom method to record and apply (or just recordThat)
            ->persist();                // Persist the aggregate and its events
    }

    // Optional: Add Laravel Action features like validation
    public function rules(): array
    {
        return [
            'description' => 'required|string|min:3|max:255',
        ];
    }

    // You can also define authorization, middleware, etc., as per laravel-actions documentation.
}
```

**Key Points for `EventCommand`s:**
*   **Single Responsibility**: Each command handles one specific action.
*   **Data In, Event (indirectly) Out**: Takes input data, produces a `DataEvent` which is then recorded on an aggregate.
*   **Validation**: Use `laravel-actions`' validation features to ensure data integrity before processing.
*   **Interaction with Aggregates**: The command handler is where you'll typically retrieve an aggregate, call its methods (which in turn record events), and persist the aggregate.

### Dispatching with `event_command()`

Once you have your `EventCommand` defined, you'll want to dispatch it. This package provides a convenient global helper function `event_command()` for this purpose.

This helper function uses the `ProjectSaturnStudios\EventSourcing\Actions\DispatchEventCommand` action internally, which in turn uses Spatie's Command Bus. By default, if you don't specify any middleware, `event_command()` applies the `ProjectSaturnStudios\EventSourcing\Middleware\RetryPersistMiddleware`.

**Example:** Dispatching our `CreateTodoTask` command.

```php
// Typically in a Controller, another Action, or a Service class

use App\Domain\Todo\Commands\CreateTodoTask; // Your EventCommand

// ...

// Instantiate your command with the required data
$description = 'Finish writing this awesome README';
$command = new CreateTodoTask(description: $description);

// Dispatch it using the helper! This will use RetryPersistMiddleware by default.
event_command($command);

// The `handle()` method of your CreateTodoTask action will be executed.
// If it successfully creates and persists the event via an aggregate, you're golden!
// If a PDOException or CouldNotPersistAggregate occurs during the process,
// the RetryPersistMiddleware will automatically attempt to retry.
```

If you need to use custom middleware, or want to change the retry behavior:

```php
use App\Http\Middleware\SomeCustomMiddleware; // Example custom middleware
use ProjectSaturnStudios\EventSourcing\Middleware\RetryPersistMiddleware;

// Dispatch with only your custom middleware (no retry by default in this case):
event_command($command, [new SomeCustomMiddleware()]);

// Dispatch with your custom middleware AND the retry middleware:
event_command($command, [
    new SomeCustomMiddleware(),
    new RetryPersistMiddleware(maximumTries: 5) // Optionally configure retries
]);

// Dispatch with NO middleware at all (if you have a specific reason):
event_command($command, []);
```

### Working with Aggregates

Your `EventCommand` handlers will typically interact with Aggregate Roots (from `spatie/laravel-event-sourcing`). The aggregate is responsible for upholding business rules and recording events that result from command processing.

**Example Aggregate (`TodoTaskAggregate`):**

Let's sketch out what our `TodoTaskAggregate` might look like. It would extend `Spatie\EventSourcing\AggregateRoots\AggregateRoot` (or our `ProjectSaturnStudios\EventSourcing\Aggregates\AlternativeAggregateRoot` if you need its specific features).

```php
// app/Domain/Todo/Models/TodoTaskAggregate.php

namespace App\Domain\Todo\Models; // Adjust namespace as needed

use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use App\Domain\Todo\Events\TodoTaskCreated; // Our DataEvent
use App\Domain\Todo\Events\TodoTaskDescriptionChanged; // Another example event
use Ramsey\Uuid\UuidInterface;

class TodoTaskAggregate extends AggregateRoot
{
    protected string $description = '';
    protected string $status = '';

    // Static "named constructor" for creation, often called by a command
    public static function createTask(UuidInterface $uuid, string $description, string $initialStatus): self
    {
        $aggregateRoot = static::retrieve($uuid->toString()); // Get a new or existing instance mapped to UUID

        // Don't record if already created (idempotency for creation can be tricky,
        // often command validation should check if an entity with this ID/params already exists)
        // For simplicity here, we assume the command ensures this is a new task.

        $aggregateRoot->recordThat(new TodoTaskCreated(
            taskUuid: $uuid,
            description: $description,
            status: $initialStatus,
            createdAt: new \DateTimeImmutable()
        ));

        return $aggregateRoot; // Return for persistence in the command
    }

    // Public method to change the description
    public function changeDescription(string $newDescription): self
    {
        if ($this->description === $newDescription) {
            // No change needed, don't record an event
            return $this;
        }

        if (empty(trim($newDescription))) {
            // Or throw a domain exception
            throw new \InvalidArgumentException('Description cannot be empty.');
        }

        $this->recordThat(new TodoTaskDescriptionChanged(
            taskUuid: Uuid::fromString($this->uuid()), // Assumes $this->uuid() returns the string UUID
            newDescription: $newDescription,
            changedAt: new \DateTimeImmutable()
        ));

        return $this;
    }

    // Apply methods for each event type
    protected function applyTodoTaskCreated(TodoTaskCreated $event): void
    {
        $this->description = $event->description;
        $this->status = $event->status;
        // Other properties from the event can be set here
    }

    protected function applyTodoTaskDescriptionChanged(TodoTaskDescriptionChanged $event): void
    {
        $this->description = $event->newDescription;
    }
}
```

**Key Interactions:**
*   **Recording Events**: Inside your aggregate methods (like `createTask` or `changeDescription`), you call `$this->recordThat(new YourDataEvent(...))` to stage an event.
*   **Applying Events**: For each event type, you create a corresponding `apply<EventName>` method (e.g., `applyTodoTaskCreated`). This method is responsible for changing the state of the aggregate based on the event data. This is how the aggregate rebuilds its state when loaded from history.
*   **Persisting**: After calling methods on your aggregate in your `EventCommand`, you must call `$aggregate->persist()` to save all recorded events to the database.

**Revised `CreateTodoTask` Command (showing aggregate interaction more clearly):**

```php
// app/Domain/Todo/Commands/CreateTodoTask.php
// ... (namespace, uses etc.)
class CreateTodoTask extends EventCommand
{
    use AsAction;
    public string $description;

    public function __construct(string $description)
    {
        $this->description = $description;
    }

    public function handle(): void
    {
        $taskUuid = Uuid::uuid4();
        $initialStatus = 'pending';
        
        // Call the static method on the aggregate to handle event recording
        $aggregate = TodoTaskAggregate::createTask(
            uuid: $taskUuid,
            description: $this->description,
            initialStatus: $initialStatus
        );

        // Persist the aggregate (this saves the recorded TodoTaskCreated event)
        $aggregate->persist();
    }
    // ... (rules etc.)
}
```

### Implicit Benefits: `RetryPersistMiddleware` & `EventSerializer`

It's worth reiterating two key components that work quietly in the background to make your life easier when using the patterns described above:

*   **`EventSerializer`**:
    *   As configured by this package, the `ProjectSaturnStudios\EventSourcing\Serializers\EventSerializer` automatically handles the serialization of your `DataEvent` objects to JSON for storage and deserialization back into their strongly-typed `Spatie\LaravelData\Data` forms when events are retrieved.
    *   You generally don't need to think about this; it just works, ensuring your typed event data is preserved correctly.

*   **`RetryPersistMiddleware`**:
    *   When you dispatch commands using the `event_command()` helper, the `ProjectSaturnStudios\EventSourcing\Middleware\RetryPersistMiddleware` is active by default.
    *   If your command handler attempts to persist an aggregate (`$aggregate->persist()`) and a transient database error occurs (like a `PDOException` or Spatie's `CouldNotPersistAggregate`), this middleware will automatically retry the entire command execution a few times.
    *   This adds a significant layer of resilience against temporary network glitches or database deadlocks without you needing to write custom retry logic in every command.

These features are designed to provide a more robust and developer-friendly experience with minimal effort on your part.

## ✅ Testing Your Masterpiece

Testing is crucial for event-sourced applications. Here's how you can approach testing different parts of your system when using `laravel-es-utilities`.

### Testing `EventCommand` Actions

Since your `EventCommand` classes are built using `lorisleiva/laravel-actions`, you can test them like any other Laravel Action. You can unit test them by directly instantiating and calling the `handle` method, or test them as part of a feature test.

**Key things to assert:**
*   **Validation Rules**: Ensure your command's `rules()` method correctly validates input.
*   **Event Emission (via Aggregates)**: The primary goal is often to ensure the correct `DataEvent`(s) are recorded on an aggregate.
*   **State Changes on Aggregates**: Verify that the aggregate's state changes as expected after applying the events triggered by the command.

Spatie's `laravel-event-sourcing` package provides excellent testing tools for aggregates:

```php
use App\Domain\Todo\Commands\CreateTodoTask;
use App\Domain\Todo\Events\TodoTaskCreated;
use App\Domain\Todo\Models\TodoTaskAggregate;
use Illuminate\Foundation\Testing\RefreshDatabase; // Or your preferred DB setup
use Tests\TestCase; // Your base TestCase

class CreateTodoTaskTest extends TestCase
{
    use RefreshDatabase; // If your commands interact with the DB beyond event store

    /** @test */
    public function it_creates_a_todo_task_and_records_event()
    {
        $description = 'Write comprehensive tests';
        // $command = new CreateTodoTask(description: $description);

        // Execute the command
        // event_command($command);
        
        // Given our current EventCommand design (handle is void and no UUID is returned directly from event_command):
        // Testing the full flow initiated by event_command() and then asserting specific aggregate events
        // requires a strategy to retrieve or predict the aggregate UUID.
        // For a more direct test of the command's interaction with the aggregate:
        
        $taskUuid = \Ramsey\Uuid\Uuid::uuid4();
        // Simulate what the command's handle method does:
        $aggregate = TodoTaskAggregate::createTask($taskUuid, $description, 'pending');
        $aggregate->persist(); // Manually persist for this test setup

        $retrievedAggregate = TodoTaskAggregate::retrieve($taskUuid->toString());
        $retrievedAggregate->assertRecorded([
            TodoTaskCreated::class,
        ]);
        $retrievedAggregate->assertEventRecorded(TodoTaskCreated::class, function(TodoTaskCreated $event) use ($description) {
            return $event->description === $description;
        });
    }
}
```

### Testing Aggregates

Spatie's `laravel-event-sourcing` package provides powerful testing methods directly on your aggregate roots.

```php
use App\Domain\Todo\Models\TodoTaskAggregate;
use App\Domain\Todo\Events\TodoTaskCreated;
use App\Domain\Todo\Events\TodoTaskDescriptionChanged;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class TodoTaskAggregateTest extends TestCase
{
    /** @test */
    public function it_can_create_a_task()
    {
        $uuid = Uuid::uuid4();
        $aggregate = TodoTaskAggregate::createTask($uuid, 'My first task', 'pending');
        // $aggregate->persist(); // Persist if you want to check DB, not needed for assertRecorded on instance

        $aggregate->assertRecorded([
            TodoTaskCreated::class,
        ]);
    }

    /** @test */
    public function it_can_change_its_description()
    {
        $uuid = Uuid::uuid4();
        $aggregate = TodoTaskAggregate::createTask($uuid, 'Old description', 'pending');
        
        // Clear recorded events from creation if you only want to test the change
        // $aggregate->flushRecordedEvents(); 

        $aggregate->changeDescription('New shiny description');

        $aggregate->assertEventRecorded(TodoTaskDescriptionChanged::class, function (TodoTaskDescriptionChanged $event) {
            return $event->newDescription === 'New shiny description';
        });
    }
}
```
Key aggregate testing methods:
*   `$aggregateRoot->assertRecorded($expectedEvents)`
*   `$aggregateRoot->assertNotRecorded($unexpectedEvents)`
*   `$aggregateRoot->assertEventRecorded($eventClass, Closure $callback = null)`
*   `$aggregateRoot->assertNothingRecorded()`
*   `$aggregateRoot->flushRecordedEvents()`

### Testing Projectors and Reactors

Refer to the `spatie/laravel-event-sourcing` documentation for detailed guidance on testing projectors and reactors. They also have dedicated testing tools.

This package focuses on the command and event creation side, so ensure your `DataEvent` objects and `EventCommand` actions integrate well with your aggregates, and then leverage Spatie's tools for the rest.

## 🔮 Advanced Scenarios / Cookbook

Here are a couple of scenarios or tools within the package that go beyond the basic setup.

### Using `AlternativeAggregateRoot`

If you have specific needs for how your aggregate roots interact with event or snapshot repositories (for example, using different database connections or custom repository implementations for certain aggregates), this package provides an `AlternativeAggregateRoot`.

You can extend `ProjectSaturnStudios\EventSourcing\Aggregates\AlternativeAggregateRoot` instead of the default `Spatie\EventSourcing\AggregateRoots\AggregateRoot`. Then, within your aggregate, you can specify the classes for your custom repositories:

```php
namespace App\Domain\Special\Aggregates;

use ProjectSaturnStudios\EventSourcing\Aggregates\AlternativeAggregateRoot;
use App\Domain\Special\Repositories\MyCustomEventRepository; // Your custom repo class
use App\Domain\Special\Repositories\MyCustomSnapshotRepository; // Your custom repo class

class MySpecialAggregate extends AlternativeAggregateRoot
{
    // Point to your custom repository classes
    protected string $event_repo = MyCustomEventRepository::class;
    protected string $snapshot_repo = MyCustomSnapshotRepository::class;

    // ... rest of your aggregate logic ...

    public function doSomethingSpecial(): self
    {
        $this->recordThat(new SomethingSpecialHappened(/* ...data... */));
        return $this;
    }

    protected function applySomethingSpecialHappened(SomethingSpecialHappened $event): void
    {
        // ... apply state ...
    }
}
```

Your custom repository classes (`MyCustomEventRepository`, `MyCustomSnapshotRepository`) must implement the respective interfaces expected by Spatie's event sourcing package (e.g., `Spatie\EventSourcing\StoredEvents\Repositories\StoredEventRepository` and `Spatie\EventSourcing\Snapshots\SnapshotRepository`).

This gives you fine-grained control over persistence mechanisms for specific aggregates without altering the global defaults.

### Using the `app_queue()` Helper

The `app_queue(string $key): string` helper function is a simple utility for prefixing your queue names based on an environment variable. This is particularly useful if you're running multiple applications or environments that might share a queueing backend (like Redis) and you want to avoid queue name collisions.

**How it works:**
1.  Define an environment variable, for example, in your `.env` file:
    ```env
    QS_PREFIX=my_app_prod
    ```
2.  When you use the helper:
    ```php
    $eventsQueue = app_queue('events'); // Results in 'my_app_prod-events'
    $notificationsQueue = app_queue('notifications'); // Results in 'my_app_prod-notifications'
    ```
3.  If `QS_PREFIX` is not set or is empty, `app_queue('events')` will simply return `'events'`.

You can use this helper anywhere you define queue names, such as in your event listeners, jobs, or in the Spatie event sourcing configuration (`config/event-sourcing.php` for the main event queue).

**Example in `config/event-sourcing.php`:**
```php
// config/event-sourcing.php
    /*
     * A queue is used to guarantee that all events get passed to the projectors in
     * the right order. Here you can set of the name of the queue.
     */
    'queue' => env('EVENT_PROJECTOR_QUEUE_NAME', app_queue('event-sourcing')), // Using the helper
```

This helps keep your queue management clean and organized across different environments.

## 🙌 Contributing

We welcome contributions from the community! If you're interested in contributing to this package, please follow these steps:

1.  Fork the repository on GitHub.
2.  Create a new branch for your feature or bug fix.
3.  Make your changes in the new branch.
4.  Write tests for your changes.
5.  Submit a pull request.

## 🙌 Support & Contact

If you encounter any issues or have questions about this package, please feel free to open an issue on GitHub or contact us directly.

## 📄 License

This package is open-source and released under the MIT License. See the [LICENSE](LICENSE) file for more information.

## 📋 Credits & Thanks

This package was created by [Project Saturn Studios](https://projectsaturnstudios.com). We're grateful for the contributions of the community and the support of our users.

## 📜 README Change Log

See the [CHANGELOG](CHANGELOG.md) file for a detailed history of changes to this README.
