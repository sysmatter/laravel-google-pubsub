# Contributing to Laravel Google PubSub

Thank you for considering contributing to Laravel Google PubSub! This document outlines the process and guidelines for
contributing.

## Code of Conduct

This project adheres to a [Code of Conduct](CODE_OF_CONDUCT.md) that all contributors are expected to follow. Please be
respectful and constructive in all interactions.

## Getting Started

### Prerequisites

- PHP 8.2 or higher
- Composer
- Git
- A GitHub account

### Setting Up Your Development Environment

1. **Fork the repository** on GitHub

2. **Clone your fork** locally:
   ```bash
   git clone https://github.com/sysmatter/laravel-google-pubsub.git
   cd laravel-google-pubsub
   ```

3. **Install dependencies**:
   ```bash
   composer install
   ```

4. **Create a branch** for your changes:
   ```bash
   git checkout -b feature/your-feature-name
   # or
   git checkout -b bugfix/issue-number-description
   ```

## Development Workflow

### Running Tests

We use Pest for testing. All contributions must include tests.

```bash
# Run all tests
vendor/bin/pest

# Run tests with coverage
vendor/bin/pest --coverage

# Run specific test file
vendor/bin/pest tests/Unit/PubSubManagerTest.php

# Run tests in parallel
vendor/bin/pest --parallel
```

### Code Style

We use Laravel Pint for code styling. Please ensure your code follows our style guidelines:

```bash
# Check code style
vendor/bin/pint --test

# Fix code style automatically
vendor/bin/pint
```

### Static Analysis

We recommend running PHPStan before submitting:

```bash
composer require phpstan/phpstan --dev
vendor/bin/phpstan analyse src --level=5
```

## Contribution Guidelines

### Reporting Bugs

Before creating a bug report:

1. **Check existing issues** to avoid duplicates
2. **Update to the latest version** to see if the issue persists
3. **Collect information** about your environment

When creating a bug report, include:

- Clear, descriptive title
- Steps to reproduce
- Expected vs actual behavior
- PHP, Laravel, and package versions
- Relevant configuration
- Code samples or error messages

### Suggesting Features

Feature suggestions are welcome! Please:

1. **Check existing feature requests** first
2. **Describe the problem** you're trying to solve
3. **Propose a solution** with example usage
4. **Consider alternatives** you've thought about
5. **Be open to discussion** about implementation

### Submitting Pull Requests

#### Before You Start

- Discuss major changes in an issue first
- Keep changes focused and atomic
- Follow existing code patterns and conventions

#### Pull Request Process

1. **Update your branch** with the latest main:
   ```bash
   git checkout main
   git pull upstream main
   git checkout your-branch
   git rebase main
   ```

2. **Write or update tests** for your changes:
    - All new features must have tests
    - Bug fixes should include regression tests
    - Aim for high test coverage

3. **Update documentation** if needed:
    - Update README.md for user-facing changes
    - Add docblocks to new methods
    - Update configuration examples

4. **Run the test suite**:
   ```bash
   vendor/bin/pest --coverage
   vendor/bin/pint
   ```

5. **Commit your changes** with clear messages:
   ```bash
   git commit -m "Add feature X that does Y"
   ```

   Good commit messages:
    - Use present tense ("Add feature" not "Added feature")
    - Be descriptive but concise
    - Reference issues when applicable (#123)

6. **Push to your fork**:
   ```bash
   git push origin your-branch
   ```

7. **Create a Pull Request** on GitHub:
    - Use a clear, descriptive title
    - Fill out the PR template completely
    - Link related issues
    - Add screenshots for UI changes
    - Request review from maintainers

#### What to Expect

- Maintainers will review your PR as soon as possible
- You may receive feedback or change requests
- Be responsive to comments and questions
- Once approved, a maintainer will merge your PR

## Coding Standards

### General Guidelines

- Write clear, self-documenting code
- Keep methods short and focused
- Use type hints for all parameters and return types
- Add docblocks for complex methods
- Follow Laravel conventions and patterns

### Testing Standards

- Write tests using Pest syntax
- Use descriptive test names: `it('does something specific')`
- Test both happy paths and edge cases
- Mock external dependencies
- Avoid testing implementation details

Example test structure:

```php
it('throws exception when project id is missing', function () {
    $connector = new PubSubConnector();

    $config = [
        'driver' => 'pubsub',
        'queue' => 'default',
    ];

    // Clear the config value to ensure it's not set
    config(['pubsub.project_id' => null]);

    expect(fn () => $connector->connect($config))
        ->toThrow(PubSubException::class, 'Google Cloud project ID is required');
});
```

### Documentation Standards

- Update README.md for user-facing changes
- Include code examples for new features
- Document breaking changes clearly
- Add inline comments for complex logic

## Release Process

Maintainers handle releases following semantic versioning:

- **MAJOR** (1.0.0): Breaking changes
- **MINOR** (0.1.0): New features, backwards-compatible
- **PATCH** (0.0.1): Bug fixes, backwards-compatible

## Getting Help

- **Documentation**: Check the README first
- **Issues**: Search existing issues
- **Discussions**: Use GitHub Discussions for questions

## Recognition

Contributors will be:

- Listed in the README credits section
- Mentioned in release notes
- Acknowledged in the changelog

## License

By contributing, you agree that your contributions will be licensed under the [MIT License](LICENSE.md).

---

Thank you for contributing to Laravel Google PubSub! 🎉