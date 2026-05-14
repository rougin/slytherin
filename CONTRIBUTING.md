# Contributing

To ensure a smooth and effective collaboration, kindly follow the guidelines below.

## Raising issues

* **Open an issue first** - before working on a change, create an issue to discuss it.
* **Example issue title** - `Error when running the route`

> [!NOTE]
> This prevents duplicated work and ensures the change aligns with the project's goals.

## Pull requests

- **Reference the issue** - create a pull request (PR) once the issue is approved.
- **Keep it focused** - each PR should address only one issue.

> [!NOTE]
> A PR title must reference the issue number (e.g., `#1 - Fix for running the route`).

## Workflow

Code must adhere to the project's standards before submitting.

* **Automated tests** - new features and bug fixes must include `phpunit` tests.
```bash
$ composer test
```

* **Code quality** - check for potential bugs and errors with `phpstan`.
```bash
$ composer analyze
```

* **Coding style** - automatically format code with `php-cs-fixer`.
```bash
$ composer restyle
```

> [!NOTE]
> Bug fixes should add a test that fails without the fix.
