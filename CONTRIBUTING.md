# Contributing to Eprofos Captcha

Thank you for considering contributing to Eprofos Captcha! This document outlines the process for contributing to this project.

## Code of Conduct

By participating in this project, you agree to maintain a respectful and inclusive environment for everyone.

## How to Contribute

### Reporting Bugs

If you find a bug, please create an issue with the following information:
- A clear, descriptive title
- Steps to reproduce the bug
- Expected behavior
- Actual behavior
- Screenshots if applicable
- Environment details (PHP version, browser, etc.)

### Suggesting Features

Feature suggestions are welcome! Please create an issue with:
- A clear, descriptive title
- Detailed description of the proposed feature
- Any relevant examples or mockups
- Explanation of why this feature would be useful

### Pull Requests

1. Fork the repository
2. Create a new branch (`git checkout -b feature/your-feature-name`)
3. Make your changes
4. Run tests to ensure they pass
5. Commit your changes (`git commit -m 'Add some feature'`)
6. Push to the branch (`git push origin feature/your-feature-name`)
7. Open a Pull Request

## Development Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Run tests: `composer test`

## Coding Standards

This project follows PSR-12 coding standards. Please ensure your code adheres to these standards.

- Run code style checks: `composer cs-check`
- Fix code style issues: `composer cs-fix`
- Run static analysis: `composer phpstan`

## Testing

All new features and bug fixes should include tests. Run the test suite with:

```
composer test
```

## Documentation

Please update the documentation when adding or modifying features. Clear and concise documentation is essential.

## License

By contributing to Eprofos Captcha, you agree that your contributions will be licensed under the project's MIT license.