# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial project setup.

## [1.0.0] - YYYY-MM-DD

### Added
- Initial creation of the `laravel-es-utilities` package.
- Core features:
    - Typed `DataEvent` objects using `spatie/laravel-data`.
    - Structured `EventCommand` actions using `lorisleiva/laravel-actions`.
    - `event_command()` helper for easy command dispatch.
    - `RetryPersistMiddleware` for resilient event persistence.
    - Custom `EventSerializer` for `DataEvent` objects.
    - `PSSEventSourcingServiceProvider` for auto-configuration.
    - `AlternativeAggregateRoot` for custom repository needs.
    - `app_queue()` helper for prefixed queue names.
    - `DispatchEventCommand` action for underlying command bus integration.
- Comprehensive `README.md` including:
    - Introduction and "Why Bother?" section.
    - Core Features list.
    - Installation and Configuration guides.
    - Detailed Usage Guide with examples for `DataEvent`, `EventCommand`, `event_command()`, and Aggregates.
    - Testing guidelines.
    - Advanced Scenarios for `AlternativeAggregateRoot` and `app_queue()`.
    - Sections for Contributing, Support, License, Credits, and Changelog.
- Basic `CHANGELOG.md` file.
